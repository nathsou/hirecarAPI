<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 2019-04-27
 * Time: 01:28
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

class MediaTypeController extends AbstractController
{
    public function __construct()
    { }

    protected function mediaTypeConverter(
        Request $request,
        $data = NULL
    ) {
        foreach (explode(";", $request->headers->get("accept")) as $mime) {
            switch ($mime) {
                case "application/XML":
                    $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><user_info></user_info>");
                    convertArrayToXML($data, $xml);
                    $response = new Response($xml->asXML());
                    $response->headers->set('Content-Type', 'application/XML');
                    return $response;

                case "application/yaml":
                    $response = new Response(YAML::dump($data));
                    $response->headers->set('Content-Type', 'application/yaml');
                    return $response;

                case "application/json":
                    return $this->json($data);
            }
        }
        // return json if nothing matches
        return $this->json($data);
    }

    protected function handleResponse($request, array $data) {
        if (
            array_key_exists("error_msg", $data) &&
            array_key_exists( "error_status", $data)
        ) {
            return new Response($data["error_msg"], $data["error_status"]);
        }

        return $this->mediaTypeConverter($request, $data);
    }

    protected function inputMediaTypeConverter(Request $request)
    {
        // ignore ;charset=UTF-8 etc..
        $mime = explode(";", $request->headers->get("content-type"))[0];

        switch ($mime) {
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
