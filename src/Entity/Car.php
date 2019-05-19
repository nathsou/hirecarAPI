<?php


namespace App\Entity;


use Symfony\Component\HttpFoundation\Request;

class Car extends RequestBuilder implements CarInterface
{

    public function insertCarRequest($model, $nbPlaces, $nbDoors, $ownerId, $gearboxId, $fuelId, $pricePerDay)
    {
        $db = SModel::getInstance();
        $this->query = "INSERT INTO car (model, nb_places, nb_doors, owner_id, gearbox_id, fuel_id, price_per_day) VALUES (:model, :nb_places, :nb_doors, :owner_id, :gearbox_id, :fuel_id, :price_per_day)";
        $prep = $db->prepare($this->query);
        $prep->bindValue("model", $model);
        $prep->bindValue("nb_places", $nbPlaces);
        $prep->bindValue("nb_doors", $nbDoors);
        $prep->bindValue("owner_id", $ownerId);
        $prep->bindValue("gearbox_id", $gearboxId);
        $prep->bindValue("fuel_id", $fuelId);
        $prep->bindValue("price_per_day", $pricePerDay);
        $prep->execute();
    }
    public function updateCarRequest($model, $nb_places, $nb_doors, $gearbox_id, $fuel_id, $price_per_day, $id)
    {
        $db = SModel::getInstance();
        $this->query = "UPDATE car SET model = :model, nb_places = :nb_places, nb_doors = :nb_doors, gearbox_id = :gearbox_id, fuel_id = :fuel_id, price_per_day = :price_per_day WHERE id = :id";
        $prep = $db->prepare($this->query);
        $prep->bindValue("model", $model);
        $prep->bindValue("nb_places", $nb_places);
        $prep->bindValue("nb_doors", $nb_doors);
        $prep->bindValue("gearbox_id", $gearbox_id);
        $prep->bindValue("fuel_id", $fuel_id);
        $prep->bindValue("price_per_day", $price_per_day);
        $prep->bindValue("id", $id);
        $prep->execute();
    }
    public function deleteCarRequest($id)
    {
        $db = SModel::getInstance();
        $this->query = "DELETE FROM car WHERE id = :id";
        $prep = $db->prepare($this->query);
        $prep->bindValue("id", $id);
        $prep->execute();
    }
    public function getCarsRequest($center_lat, $center_lng, $radius, $airport, Request $request)
    {
        $db = SModel::getInstance();
        $this->query = "SELECT * FROM `car` WHERE car.id IN (SELECT car_id FROM rent_parking_spot WHERE parking_lot_id IN (SELECT id FROM parking_lot WHERE ";
        if ((isset($center_lat) && isset($center_lng) && isset($radius)) xor isset($airport)) {
            if (isset($center_lat) && isset($center_lng) && isset($radius) && is_numeric($center_lat) && is_numeric($center_lng) && is_numeric($radius)) {
                $this->query .= "(1.852 * 60 * SQRT(POW((:lng - `lng`) * COS((`lat` + :lat) / 2), 2) + POW((`lat` - :lat), 2)) < :radius)";
                $this->query_parameters["lat"] = $center_lat;
                $this->query_parameters["lng"] = $center_lng;
                $this->query_parameters["radius"] = $radius;
            } elseif (is_numeric($airport)) {
                $this->query .= "id = :airportID ";
                $this->query_parameters["airportID"] = $airport;
            } else {
                return ["error" => "data parameters are unavailable"];
            }
            $this->selectByPrice([
                "min" => $request->query->get('min_price'),
                "max" => $request->query->get('max_price')
            ]);
            $this->query .= "))";
            $this->selectByDate([
                "start" => $request->query->get('start_date'),
                "end" => $request->query->get('end_date')
            ]);
            $this->selectGearbox($request->query->get("gearBox"));
            $this->selectBynbplaces($request->query->get("nb_seats"));
            $this->selectBynbporte($request->query->get("nb_doors"));
            $this->selectByFuel($request->query->get("fuel"));
            $this->selectByModel($request->query->get("model"));
            $prep = $db->prepare($this->query);
            foreach ($this->query_parameters as $key => $value) {
                $prep->bindValue($key, $value);
            }
            $prep->execute();

            return ["cars" => $prep->fetchAll(\PDO::FETCH_ASSOC)];
        } else {
            return [["error" => "data parameters are unavailable"]];
        }
    }
    private function selectByDate($date)
    {
        $query = "AND car.id IN (SELECT car_id FROM rent_parking_spot WHERE ";
        $start = false;
        $has_change = false;
        if (isset($date["start"])) {
            try {
                $Date2 = new \DateTime($date["start"]);
                $this->queryParameter["start_date"] = $Date2->format("y-m-d");
                $query = $query . "start_date > :start_date";
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
                $query .= "end_date < :end_date";
                $date2 = \DateTime($date['end']);
                $this->queryParameter["end_date"] = $date2;
                $has_change = true;
            } catch (\Exception $e) { }
        }
        if ($has_change) {
            $query .= ") ";
            $this->query .= $query;
        }
    }
    private function selectGearbox($gearBoxType)
    {
        if (isset($gearBoxType) && preg_match("/[a-z]*/", $gearBoxType)) {
            $this->query .= "AND gearbox_id IN ( SELECT id FROM gearbox WHERE type = :gearBox) ";
            $this->queryParameter["gearBox"] = $gearBoxType;
        }
    }
    private function selectBynbplaces($nbPlaces)
    {
        if (isset($nbPlaces) && is_numeric($nbPlaces)) {
            $this->query .= "AND nb_places >= :nb_seats ";
            $this->queryParameter["nb_seats"] = $nbPlaces;
        }
    }
    private function selectBynbporte($nbPorte)
    {
        if (isset($nbPorte) && is_numeric($nbPorte)) {
            $this->query .= "AND nb_places >= :nb_doors ";
            $this->queryParameter["nb_doors"] = $nbPorte;
        }
    }
    private function selectByFuel($fuel)
    {
        if (isset($fuel) && preg_match("/[a-z]*/", $fuel)) {
            $this->query .= "AND fuel_id IN ( SELECT id FROM fuel WHERE type = :fuel) ";
            $this->queryParameter["fuel"] = $fuel;
        }
    }
    private function selectByModel($model)
    {
        if (isset($model) && preg_match("/([a-z]|_)*/", $model)) {
            $model = str_replace("_", " ", $model);
            $this->query .= 'AND model = :model ';
            $this->queryParameter["model"] = $model;
        }
    }
    private function selectByPrice($price)
    {
        if (isset($price["min"]) && is_numeric($price["min"])) {
            $this->query = $this->query . "AND price_per_day > :min_price ";
            $this->queryParameter['min_price'] = $price["min"];
        }
        if (isset($price["max"]) && is_numeric($price["max"])) {
            $this->query = $this->query . "AND price_per_day< :max_price ";
            $this->queryParameter["max_price"] = $price["max"];
        }
    }
}
