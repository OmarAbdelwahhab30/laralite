<?php

namespace Laralite\Framework\Tests;

class DependencyClass
{

    public SubDependencyClass $dependency;

    public function __construct(SubDependencyClass $sub)
    {
        $this->dependency = $sub;
    }

    public function getDependency(): SubDependencyClass
    {
        return $this->dependency;
    }
}