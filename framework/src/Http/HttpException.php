<?php

namespace Laralite\Framework\Http;

class HttpException extends \Exception
{

    private int $statusCode;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

}