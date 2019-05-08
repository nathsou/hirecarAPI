<?php


namespace App\Entity;


use Symfony\Component\Dotenv\Dotenv;

class ConnectionDB
{
    protected $bdd;
    protected $query;
    protected $queryParameter;

    public function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . '/../../.env');
        $this->bdd = new \PDO($_ENV["DB_URI"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $this->queryParameter = [];
    }
}
