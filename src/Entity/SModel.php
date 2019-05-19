<?php


namespace App\Entity;


use Symfony\Component\Dotenv\Dotenv;
use Doctrine\DBAL\Driver\PDOException;

class SModel extends \PDO
{
    private static $instance;

    public function __construct()
    { }

    public static function getInstance()
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . '/../../.env');
        $options = [];
        if (!isset(self::$instance)) {
            try {
                self::$instance = new \PDO($_ENV["DB_URI"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"], $options);
            } catch (PDOException $e) {
                print_r($e->getCode() . ' : ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
