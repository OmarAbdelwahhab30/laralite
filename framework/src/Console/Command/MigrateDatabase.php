<?php

namespace Laralite\Framework\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class MigrateDatabase implements CommandInterface
{

    public string $name = "migrate";

    public function __construct(private readonly Connection $connection, private string $migrationsPath)
    {

    }

    public function execute(array $params = []): int
    {

        try {
            $this->connection->beginTransaction();

            $this->createMigrationsTable();

            $applied = $this->getAppliedMigrations();

            $all = $this->getMigrationsToApply();

            $toApply = array_diff($all, $applied);

            $schema = new Schema();
            foreach ($toApply as $migration) {
                $migrationObject = require $this->migrationsPath . DIRECTORY_SEPARATOR . $migration;

                $migrationObject->up($schema);

                $this->insertMigration($migration);
            }

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
            }

            $this->connection->commit();
            return 0;
        } catch (\Throwable $throwable) {

            $this->connection->rollBack();

            throw $throwable;
        }
    }

    /**
     * @throws Exception
     */
    private function createMigrationsTable()
    {

        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist("migrations")) {
            $schema = new Schema();
            $table = $schema->createTable("migrations");
            $table->addColumn('id', Types::INTEGER, ['unsigned' => true, 'autoincrement' => true]);
            $table->addColumn('migration', Types::STRING);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
            $table->setPrimaryKey(['id']);

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sqlArray[0]);

            echo 'migrations table created';
        }

    }

    /**
     * @throws \Exception
     */
    private function getAppliedMigrations(): array
    {

        $sql = "SELECT migration FROM migrations";
        return $this->connection->executeQuery($sql)->fetchFirstColumn();
    }

    private function getMigrationsToApply(): false|array
    {
        $files = scandir($this->migrationsPath);
        return array_filter($files, function ($file) {
            return !in_array($file, [".", ".."]);
        });
    }

    /**
     * @throws Exception
     */
    private function insertMigration($migration)
    {
        $sql = "INSERT INTO migrations (migration) VALUES (?)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(1, $migration);

        $stmt->executeStatement();
    }
}