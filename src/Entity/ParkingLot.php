<?php


namespace App\Entity;

class ParkingLot implements ParkingLotInterface
{

    public function getParkingLotsRequest($lng, $radius, $lat, \Symfony\Component\HttpFoundation\Request $request)
    {
        $db = SModel::getInstance();
        $query = "SELECT * FROM `parking_lot` ";
        $query .= "LEFT JOIN ( (SELECT (count(parking_lot_id))";
        $query .= "AS countPlaceTaken,parking_lot_id FROM rent_parking_spot ";
        $query .= "GROUP BY parking_lot_id))";
        $query .= "AS countTable on countTable.parking_lot_id=parking_lot.id ";
        $query .= "WHERE (1.852 * 60 * SQRT(POW((:lng - parking_lot.lng) * COS((parking_lot.lat + :lat) / 2), 2) + POW((parking_lot.lat - :lat), 2)) < :radius) ";
        $queryParameter[':lng'] = (float)$lng;
        $queryParameter[':radius'] = (float)$radius;
        $queryParameter[':lat'] = (float)$lat;
        $this->selectByDate([
            "start" => $request->query->get('start_date'),
            "end" => $request->query->get('end_date')
        ]);
        $this->selectByPrice([
            "min" => $request->query->get('min_price'),
            "max" => $request->query->get('max_price')
        ]);
        $this->selectByNbplace($request->query->get("number_places"));
        $prep = $db->prepare($query);
        foreach ($queryParameter as $key => $value) {
            $prep->bindValue($key, $value);
        }
        $prep->execute();
        $parkingLot = $prep->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($parkingLot as $key => $parking) {
            if (is_null($parking["countPlaceTaken"])) {
                $parkingLot[$key]["nb_places"] = (string)($parking["nb_places"]);
            } else {
                $parkingLot[$key]["nb_places"] = (string)($parking["nb_places"] - $parking["countPlaceTaken"]);
            }
        }
        return  $parkingLot;
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

    private function selectByPrice($price)
    {
        if (isset($price["min"]) && is_numeric($price["min"])) {
            $this->query = $this->query . "AND price_per_day >= :min_price ";
            $this->queryParameter['min_price'] = $price["min"];
        }
        if (isset($price["max"]) && is_numeric($price["max"])) {
            $this->query = $this->query . "AND price_per_day<= :max_price ";
            $this->queryParameter["max_price"] = $price["max"];
        }
    }

    private function selectByDate($date)
    {
        $query = "AND parking_lot.id IN (SELECT parking_spot_id FROM rent_car WHERE ";
        $start = false;
        $has_change = false;
        if (isset($date["start"])) {
            try {
                $Date2 = new \DateTime($date["start"]);
                $this->queryParameter["start_date"] = $Date2->format("y-m-d");
                $query = $query . "start_date >= :start_date";
                $has_change = true;
                $start = true;
            } catch (\Exception $e) { }
        }
        if (isset($date["end"])) {
            try {
                $date = new \DateTime($date["end"]);
                if ($start) {
                    $query .= " AND ";
                }
                $query .= "end_date <= :end_date";
                $this->queryParameter["end_date"] = $date->format("y-m-d");
                $has_change = true;
            } catch (\Exception $e) { }
        }
        if ($has_change) {
            $query .= ") ";
            $this->query .= $query;
        }
    }

    private function selectByNbplace($nbPlace)
    {
        if (isset($nbPlace) && is_numeric($nbPlace)) {
            $this->query .= " AND nb_places >= :number_places";
            $this->queryParameter["number_places"] = $nbPlace;
        }
    }
}
