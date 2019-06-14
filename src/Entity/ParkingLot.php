<?php


namespace App\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParkingLot extends RequestBuilder implements ParkingLotInterface
{

    private $test_tolerant_reader = false;

    public function getParkingLotsRequest(Request $request)
    {
        $this->query = "SELECT * FROM `parking_lot`";

        $this->selectByCoords(
            $request->query->get('center_lat'),
            $request->query->get('center_lng'),
            $request->query->get('radius')
        );

        $this->selectByDate([
            "start" => $request->query->get('start_date'),
            "end" => $request->query->get('end_date')
        ]);

        $this->selectByPrice([
            "min" => $request->query->get('min_price'),
            "max" => $request->query->get('max_price')
        ]);

        $this->selectByCapacity($request->query->get("min_capacity"));

        $this->selectById($request->query->get("airport_id"));

        $this->selectByAirportName($request->get("airport_name"));

        if ($this->test_tolerant_reader) {
            $request->headers->set("Accept", "application/yaml");
        }

        if ($this->valid_request) {
            if ($this->test_tolerant_reader) {
                $parking_lots = $this->fetchIdDataUpdated($this->execQuery(), ["airport"]);
            } else {
                $parking_lots = $this->fetchIdData($this->execQuery(), ["airport"]);
            }
            return ['parking_lots' => $parking_lots];
        }

        return [
            "msg" => 'incorrect parameters',
            "status" => Response::HTTP_BAD_REQUEST
        ];
    }

    public function insertParkingLotRequest($label, $lat, $lng, $capacity, $pricePerDay, $airportId)
    {
        $db = SModel::getInstance();
        $query = "INSERT INTO parking_lot (label,lat,lng,capacity,price_per_day,airport_id)
            VALUES (:label, :lat, :lng, :capacity, :price_per_day,:airport_id)";
        $prep = $db->prepare($query);
        $prep->bindValue("label", $label);
        $prep->bindValue("lat", $lat);
        $prep->bindValue("lng", $lng);
        $prep->bindValue("capacity", $capacity);
        $prep->bindValue("price_per_day", $pricePerDay);
        $prep->bindValue("airport_id", $airportId);

        $prep->execute();

        switch ($prep->errorCode()) {
            case "23000":
                return [
                    "msg" => "a parking lot with the same label already exists",
                    "status" => Response::HTTP_CONFLICT
                ];
            case "00000":
                return [
                    "msg" => "parking lot created",
                    "status" => Response::HTTP_CREATED
                ];
            default:
                return [
                    "msg" => "invalid input",
                    "status" => Response::HTTP_BAD_REQUEST
                ];
        }
    }

    private function selectByCoords($lat, $lng, $radius)
    {
        if (
            isset($lat) && isset($lng) && isset($radius) &&
            is_numeric($lat) && is_numeric($lng) && is_numeric($radius)
        ) {
            $this->valid_request = true;
            $this->addWhereCondition("(1.852 * 60 * SQRT(POW((:lng - parking_lot.lng) *
                COS((parking_lot.lat + :lat) / 2), 2) + POW((parking_lot.lat - :lat), 2)) < :radius) ");
            $this->query_parameters[':lng'] = (float)$lng;
            $this->query_parameters[':radius'] = (float)$radius;
            $this->query_parameters[':lat'] = (float)$lat;
        }
    }

    private function selectByPrice($price)
    {
        if (isset($price["min"]) && is_numeric($price["min"])) {
            $this->valid_request = true;
            $this->addWhereCondition("price_per_day >= :min_price ");
            $this->query_parameters['min_price'] = $price["min"];
        }

        if (isset($price["max"]) && is_numeric($price["max"])) {
            $this->valid_request = true;
            $this->addWhereCondition("price_per_day<= :max_price ");
            $this->query_parameters["max_price"] = $price["max"];
        }
    }

    private function selectByDate($date)
    {
        $condition = "parking_lot.id IN (SELECT parking_spot_id FROM rent_car WHERE ";
        $start = false;
        $has_changed = false;

        if (isset($date["start"])) {
            try {
                $Date2 = new \DateTime($date["start"]);
                $this->query_parameters["start_date"] = $Date2->format("y-m-d");
                $condition .= "start_date >= :start_date";
                $has_changed = true;
                $start = true;
            } catch (\Exception $e) { }
        }

        if (isset($date["end"])) {
            try {
                $date = new \DateTime($date["end"]);
                if ($start) {
                    $condition .= " AND ";
                }
                $condition .= "end_date <= :end_date";
                $this->query_parameters["end_date"] = $date->format("y-m-d");
                $has_changed = true;
            } catch (\Exception $e) { }
        }
        if ($has_changed) {
            $this->valid_request = true;
            $condition .= ") ";
            $this->addWhereCondition($condition);
        }
    }

    private function selectByCapacity($min_capacity)
    {
        if (isset($min_capacity) && is_numeric($min_capacity)) {
            $this->valid_request = true;
            $this->addWhereCondition("capacity >= :min_capacity");
            $this->query_parameters["min_capacity"] = $min_capacity;
        }
    }

    private function selectById($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition("airport_id = :id");
            $this->query_parameters["id"] = $id;
        }
    }

    private function selectByAirportName($name)
    {
        if (isset($name) && is_string($name)) {
            $this->valid_request = true;
            $this->addWhereCondition("airport_id IN (SELECT id FROM airport WHERE LOWER(name)
                LIKE '%" . strtolower($name) . "%')");
            // $this->query_parameters["airport_name"] = $name;
        }
    }
}
