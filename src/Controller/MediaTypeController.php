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
    protected $endpoint = null;
    protected $linked = [];
    protected $actions = [];
    protected $host;

    public function __construct()
    {
        $this->host = $_SERVER['HTTP_HOST'];

        $this->linked = [
            "cars" => [
                "car_rentals",
                "parking_lots"
            ]
        ];

        $this->actions = [
            "get_cars" => [
                "method" => "GET",
                "uri" => "/cars"
            ],
            "delete_car" => [
                "method" => "DELETE",
                "uri" => "/cars/:id",
                "params" => ["id"]
            ],
            "update_car" => [
                "method" => "PUT",
                "uri" => "/cars/:id",
                "params" => ["id"]
            ],
            "get_car_rentals" => [
                "method" => "GET",
                "uri" => "/car_rentals?car_id=:id",
                "params" => ["id"]
            ]
        ];
    }

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

    protected function generateActions(array $actions_keys, array $params = [])
    {
        $actions = [];

        foreach ($actions_keys as $key) {
            $action = $this->actions[$key];
            $actions[$key] = $action;
            //$endpoint = "/" . explode("/", $action["uri"])[1] . "/";
            $uri = $action["uri"];
            if (array_key_exists("params", $action)) {
                foreach ($action["params"] as $param) {
                    $uri = str_replace(":" . $param, $params[$param], $uri);
                }
                unset($actions[$key]["params"]);
            }
            // $actions[$key]["description"] = $this->host . "/spec". $endpoint;
            $actions[$key]["uri"] = $this->host . $uri;
        }

        return $actions;
    }

    protected function includeLinkedServices(array $data)
    {
        if (array_key_exists($this->endpoint, $this->linked)) {
            $data["linked"] = [];

            foreach ($this->linked[$this->endpoint] as $endpoint) {
                $service = [];
                $service["description"] = $this->host . "/spec/" . $endpoint;
                $service["uri"] = $this->host . "/" . $endpoint;
                $data["linked"][$endpoint] = $service;
            }
        }

        return $data;
    }

    protected function handleResponse($request, array $data) {
        $res = null;
        if (
            array_key_exists("msg", $data) &&
            array_key_exists( "status", $data)
        ) {
            $res = new Response($data["msg"], $data["status"]);
        } else {
            $res = $this->mediaTypeConverter($request, $this->includeLinkedServices($data));
        }

        // provide a service descriptor in the LINK header if available
        if ($this->endpoint != null) {
            $path = $this->host."/spec/".$this->endpoint;
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
