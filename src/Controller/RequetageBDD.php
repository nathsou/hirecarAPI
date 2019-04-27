<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 2019-04-27
 * Time: 01:28
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Dotenv\Dotenv;

class RequetageBDD extends AbstractController
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
