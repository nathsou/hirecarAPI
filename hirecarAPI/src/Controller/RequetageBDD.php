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

    public function __construct(){
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env');
        $this->bdd=new \PDO('mysql:host=mysql-lo07.alwaysdata.net;dbname=lo07_hirecar','lo07',$_ENV["DB_PASSWORD"]);
        $this->queryParameter=[];
    }

}