<?php


namespace App\Entity;

class ParkingLot extends ConnectionDB implements ParkingLotInterface
{

    public function getParkingLotsRequest($lng, $radius, $lat, \Symfony\Component\HttpFoundation\Request $request)
    {
        $this->query = "SELECT * FROM `parking_lot` ";
        $this->query .= "LEFT JOIN ( (SELECT (count(parking_lot_id))";
        $this->query .= "AS countPlaceTaken,parking_lot_id FROM rent_parking_spot ";
        $this->query .= "GROUP BY parking_lot_id))";
        $this->query .= "AS countTable on countTable.parking_lot_id=parking_lot.id ";
        $this->query .= "WHERE (1.852 * 60 * SQRT(POW((:lng - parking_lot.lng) * COS((parking_lot.lat + :lat) / 2), 2) + POW((parking_lot.lat - :lat), 2)) < :radius) ";
        $this->queryParameter[':lng'] = (float)$lng;
        $this->queryParameter[':radius'] = (float)$radius;
        $this->queryParameter[':lat'] = (float)$lat;
        $this->selectByDate([
            "start" => $request->query->get('start_date'),
            "end" => $request->query->get('end_date')
        ]);
        $this->selectByPrice([
            "min" => $request->query->get('min_price'),
            "max" => $request->query->get('max_price')
        ]);
        $this->selectByNbplace($request->query->get("number_places"));
        $queryTemp = "SELECT id, label, lat, lng, price_per_day, airport_id, parking_lot_id, (finalTable.nb_places-finalTable.countPlaceTaken) AS nb_places FROM ( ";
        $queryTemp2 = " )AS finalTable ";
        $this->query = $queryTemp . $this->query . $queryTemp2;
        $prep = $this->bdd->prepare($this->query);
        foreach ($this->queryParameter as $key => $value) {
            $prep->bindValue($key, $value);
        }
        $prep->execute();
        return  $prep->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insertParkingLotRequest($label, $lat, $lng, $nbPlaces, $pricePerDay, $airportId)
    {
        $this->query = "INSERT INTO parking_lot (label,lat,lng,nb_places,price_per_day,airport_id) VALUES  (:label, :lat, :lng, :nb_places, :price_per_day,:airport_id)";
        $prep = $this->bdd->prepare($this->query);
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
