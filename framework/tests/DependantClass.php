<?php

namespace Laralite\Framework\Tests;

class DependantClass
{

    public DependencyClass $dependency;
    public function __construct(DependencyClass $dependency,int $id)
    {
        $this->dependency = $dependency;
    }

    public function getDependency(): DependencyClass
    {
        return $this->dependency;
    }
}