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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use SimpleXMLElement;

function convertArrayToXML($array,$xml){
    foreach ($array as $key=>$value){
        if(is_array($value)){
            if (!is_numeric($key)){
                $xmlNode=$xml->addChild("$key");
                convertArrayToXML($value,$xmlNode);
            }else{
                $xmlNode=$xml->addChild("element:$key");
                convertArrayToXML($value,$xmlNode);
            }
        }else{
            $xml->addChild("$key",htmlspecialchars("$value"));
        }
    }
}

class RequetageBDD extends AbstractController
{
    //TODO doit disparaitre
    protected $bdd;
    protected $query;
    protected $queryParameter;
    //TODO doit disparaite
    public function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . '/../../.env');
        $this->bdd = new \PDO($_ENV["DB_URI"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        $this->queryParameter = [];
    }

    protected function mediatypeConverteur(Request $request, $data){
        $returnType=$request->query->get("type");
        switch ($returnType){
            case "xml":
                $xml= new SimpleXMLElement("<?xml version=\"1.0\"?><user_info></user_info>");
                convertArrayToXML($data,$xml);
                $response = new Response($xml->asXML());
                $response->headers->set('Content-Type', 'application/XML');
                return  $response;
            case "yalm":
                $response = new Response(YAML::dump($data));
                $response->headers->set('Content-Type', 'application/yaml');
                return  $response;
            default:
                return $this->json($data);
        }
    }
}
