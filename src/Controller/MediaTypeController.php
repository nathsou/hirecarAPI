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
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Yaml\Yaml;


class MediaTypeController extends AbstractController
{
    protected $spec_name = null;

    public function __construct()
    { }

    protected function getMimes(Request $request) {
        return  explode(',',  explode(";", trim($request->headers->get("accept")))[0]);
    }

    protected function mediaTypeConverter(
        Request $request,
        $data = NULL
    ) {
        $mimes = $this->getMimes($request);

        foreach ($mimes as $mime) {
            switch ($mime) {
                case "application/xml":
                    $root_name = array_keys($data)[0];

                    // if the array's name is in plural form
                    if ($root_name[strlen($root_name) - 1] == 's') {
                        $encoder = new XmlEncoder($root_name);
                        $elems_name = substr($root_name, 0, strlen($root_name) - 1);
                        $response = new Response($encoder->encode([$elems_name => $data[$root_name]], "xml"));
                    } else {
                        $encoder = new XmlEncoder(); // the root node is named 'response'
                        $response = new Response($encoder->encode($data, "xml"));
                    }

                    $response->headers->set('Content-Type', 'application/xml');
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
        $res = null;
        if (
            array_key_exists("msg", $data) &&
            array_key_exists( "status", $data)
        ) {
            $res = new Response($data["msg"], $data["status"]);
        } else {
            $res = $this->mediaTypeConverter($request, $data);
        }

        // provide a service descriptor in the LINK header if available
        if ($this->spec_name != null) {
            $path = $_SERVER['HTTP_HOST']."/spec/".$this->spec_name;
            $res->headers->set("Link", "<" . $path . ">; rel=describedby");
        }

        return $res;
    }

    protected function inputMediaTypeConverter(Request $request)
    {
        // ignore ;charset=UTF-8 etc..
        $mime = explode(";", $request->headers->get("Content-Type"))[0];

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
                        $encoder = new XmlEncoder();
                        return $encoder->decode($request->getContent(), 'xml');
                    } catch (\Exception $e) {
                        return ["error" => $e->getMessage()];
                    }
                }
        }
    }
}
