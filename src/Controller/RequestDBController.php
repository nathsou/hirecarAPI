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


function convertArrayToXML($array, $xml)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            if (!is_numeric($key)) {
                $xmlNode = $xml->addChild("$key");
                convertArrayToXML($value, $xmlNode);
            } else {
                $xmlNode = $xml->addChild("element:$key");
                convertArrayToXML($value, $xmlNode);
            }
        } else {
            $xml->addChild("$key", htmlspecialchars("$value"));
        }
    }
}

function xmlToArray(SimpleXMLElement $xml): array
{
    $parser = function (SimpleXMLElement $xml, array $collection = []) use (&$parser) {
        $nodes = $xml->children();
        if (0 === $nodes->count()) {
            $collection['value'] = strval($xml);
            return strval($xml);
        }

        foreach ($nodes as $nodeName => $nodeValue) {
            if (count($nodeValue->xpath('../' . $nodeName)) < 2) {
                $collection[$nodeName] = $parser($nodeValue);
                continue;
            }

            $collection[$nodeName][] = $parser($nodeValue);
        }

        return $collection;
    };

    return [
        $xml->getName() => $parser($xml)
    ];
}

class RequestDBController extends AbstractController
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

    protected function mediaTypeConverter(
        Request $request,
        $data = NULL
    ) {
        //TODO: Handle multiple accept values (and choose the first one that matches)
        $returnType = $request->headers->get("accept");
        switch ($returnType) {
            case "application/XML":
                $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><user_info></user_info>");
                convertArrayToXML($data, $xml);
                $response = new Response($xml->asXML());
                $response->headers->set('Content-Type', 'application/XML');
                return  $response;

            case "application/yaml":
                $response = new Response(YAML::dump($data));
                $response->headers->set('Content-Type', 'application/yaml');
                return  $response;

            case "application/json":
            default:
                return $this->json($data);
        }
    }

    protected function inputMediaTypeConverter(Request $request)
    {
        // ignore ;charset=UTF-8 etc..
        $typeInput = explode(";", $request->headers->get("content-type"))[0];
        switch ($typeInput) {
            case "application/json": {
                    try {
                        return json_decode($request->getContent(), true);
                    } catch (\Exception $e) {
                        return ["error" => $e->getMessage()];
                    }
                }
            case "application/yaml": {
                    try {
                        return Yaml::parse($request->getContent());
                    } catch (\Exception $e) {
                        return ["error" => $e->getMessage()];
                    }
                }
            case "application/xml": {
                    try {
                        $xml = simplexml_load_string($request->getContent());
                        return  xmlToArray($xml)['document'];
                    } catch (\Exception $e) {
                        return ["error" => $e->getMessage()];
                    }
                }
        }
    }
}
