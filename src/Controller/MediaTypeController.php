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
    public function __construct()
    { }

    protected function mediaTypeConverter(
        Request $request,
        $data = NULL
    ) {
        $mimes =  explode(',',  explode(";", $request->headers->get("accept"))[0]);

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
                        $encoder = new XmlEncoder();
                        return $encoder->decode($request->getContent(), 'xml');
                    } catch (\Exception $e) {
                        return ["error" => $e->getMessage()];
                    }
                }
        }
    }
}
