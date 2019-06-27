<?php


namespace App\Entity;


use Symfony\Component\HttpFoundation\Request;

class Airport extends RequestBuilder
{
    public function getAirportsRequest(Request $request)
    {
        $this->query = "SELECT * FROM `airport`";

        $name = $request->query->get("name");

        if (isset($name) && is_string($name)) {
            //TODO: Use %:name%
            $this->addWhereCondition("LOWER(name) LIKE '%" . strtolower($name) . "%'");
            // $this->query_parameters["name"] = strtolower($name);

            $this->query .= " ORDER BY name"; // LIMIT 10

        }
        $airports = $this->execQuery();
        return ["airports" => $airports];
    }
}
