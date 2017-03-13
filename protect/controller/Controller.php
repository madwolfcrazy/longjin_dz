<?php

use \Interop\Container\ContainerInterface as ContainerInterface;

class Controller
{
    protected $ci;
    protected $pdo;

    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
    }
}
