<?php


namespace App\Entity;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ParkingLot extends RequestBuilder implements ParkingLotInterface
{

    private $valid_request = false;

    public function getParkingLotsRequest(Request $request)
    {
        $db = SModel::getInstance();
        $this->query = "SELECT * FROM `parking_lot` ";
        $this->query .= "LEFT JOIN ( (SELECT (count(parking_lot_id)) ";
        $this->query .= "AS countPlaceTaken, parking_lot_id FROM rent_parking_spot ";
        $this->query .= "GROUP BY parking_lot_id)) ";
        $this->query .= "AS countTable on countTable.parking_lot_id=parking_lot.id ";

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

        $this->selectByNbplace($request->query->get("number_places"));

        $this->selectById($request->query->get("airport_id"));

        if ($this->valid_request) {
            $prep = $db->prepare($this->query);

            foreach ($this->query_parameters as $key => $value) {
                $prep->bindValue($key, $value);
            }

            $prep->execute();
            $parkingLots = $prep->fetchAll(\PDO::FETCH_ASSOC);

            return new JsonResponse(['airports' => $parkingLots], Response::HTTP_OK);
        }

       return new Response('', Response::HTTP_PARTIAL_CONTENT);
    }

    public function insertParkingLotRequest($label, $lat, $lng, $nbPlaces, $pricePerDay, $airportId)
    {
        $db = SModel::getInstance();
        $query = "INSERT INTO parking_lot (label,lat,lng,nb_places,price_per_day,airport_id) VALUES  (:label, :lat, :lng, :nb_places, :price_per_day,:airport_id)";
        $prep = $db->prepare($query);
        $prep->bindValue("label", $label);
        $prep->bindValue("lat", $lat);
        $prep->bindValue("lng", $lng);
        $prep->bindValue("nb_places", $nbPlaces);
        $prep->bindValue("price_per_day", $pricePerDay);
        $prep->bindValue("airport_id", $airportId);
        $prep->execute();
    }


    private function selectByCoords($lat, $lng, $radius)
    {
        if (
            isset($lat) && isset($lng) && isset($radius) &&
            is_numeric($lat) && is_numeric($lng) && is_numeric($radius)
        ) {
            $this->valid_request = true;
            $this->addWhereCondition("(1.852 * 60 * SQRT(POW((:lng - parking_lot.lng) * COS((parking_lot.lat + :lat) / 2), 2) + POW((parking_lot.lat - :lat), 2)) < :radius) ");
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

    private function selectByNbplace($nbPlace)
    {
        if (isset($nbPlace) && is_numeric($nbPlace)) {
            $this->valid_request = true;
            $this->addWhereCondition("nb_places >= :number_places");
            $this->query_parameters["number_places"] = $nbPlace;
        }
    }

    private function selectById($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition( "airport_id = :id");
            $this->query_parameters["id"] = $id;
        }
    }
}
