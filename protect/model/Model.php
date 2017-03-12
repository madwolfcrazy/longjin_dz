<?php

use \Interop\Container\ContainerInterface as ContainerInterface;

class Model 
{
    protected $ci;
    protected static $pdo;

    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
        $dbconfig = $this->ci->get('settings')['db'];
        $dsn  =  "mysql:host={$dbconfig['host']};dbname={$dbconfig['database']};charset={$dbconfig['charset']}";
        $this->pdo  =  new \Slim\PDO\Database($dsn, $dbconfig['user'], $dbconfig['pwd']);
    }

    public function __get($name) 
    {
        if( $name == 'pdo') {
            exit('hhher');
        }

    }
}
