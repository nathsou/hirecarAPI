<?php


namespace App\Entity;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Car extends RequestBuilder implements CarInterface
{

    public function insertCarRequest($model, $seats, $doors, $owner_id, $gearbox_id, $fuel_id, $price_per_day)
    {
        $db = SModel::getInstance();
        $this->query = "INSERT INTO car (model, seats, doors, owner_id, gearbox_id, fuel_id, price_per_day)
            VALUES (:model, :seats, :doors, :owner_id, :gearbox_id, :fuel_id, :price_per_day)";
        $prep = $db->prepare($this->query);
        $prep->bindValue("model", $model);
        $prep->bindValue("seats", $seats);
        $prep->bindValue("doors", $doors);
        $prep->bindValue("owner_id", $owner_id);
        $prep->bindValue("gearbox_id", $gearbox_id);
        $prep->bindValue("fuel_id", $fuel_id);
        $prep->bindValue("price_per_day", $price_per_day);
        $prep->execute();
    }
    public function updateCarRequest($model, $seats, $doors, $gearbox_id, $fuel_id, $price_per_day, $id)
    {
        $db = SModel::getInstance();
        $this->query = "UPDATE car SET model = :model, seats = :seats, doors = :doors, gearbox_id = :gearbox_id,
            fuel_id = :fuel_id, price_per_day = :price_per_day WHERE id = :id";
        $prep = $db->prepare($this->query);
        $prep->bindValue("model", $model);
        $prep->bindValue("seats", $seats);
        $prep->bindValue("doors", $doors);
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
    public function getCarFeaturesRequest()
    {
        $db = SModel::getInstance();

        $this->query_gearbox = "SELECT * FROM gearbox";
        $prep = $db->prepare($this->query_gearbox);
        $prep->execute();
        $gearbox = $prep->fetchAll(\PDO::FETCH_ASSOC);

        $this->query_fuel = "SELECT * FROM fuel";
        $prep = $db->prepare($this->query_fuel);
        $prep->execute();
        $fuel = $prep->fetchAll(\PDO::FETCH_ASSOC);
        return ["gearbox" => $gearbox, "fuel" => $fuel];
    }
    public function getUserCarsRequest($id)
    {
        $this->query = "SELECT car.id, model, seats, doors, gearbox_id, fuel_id, price_per_day FROM car, gearbox, fuel WHERE owner_id = :id AND car.gearbox_id = gearbox.id AND car.fuel_id = fuel.id";

        $this->query_parameters["id"] = $id;
        $cars = $this->fetchIdData($this->execQuery(), ["fuel", "gearbox"]);
        return ["cars" => $cars];
    }
    public function getCarsRequest(Request $request)
    {
        $this->query = "SELECT * FROM `car`";

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

        $this->selectById($request->query->get("id"));
        $this->selectByGearbox($request->query->get("gearbox"));
        $this->selectBySeatsCount($request->query->get("min_seats"));
        $this->selectByDoorsCount($request->query->get("min_doors"));
        $this->selectByFuel($request->query->get("fuel"));
        $this->selectByModel($request->query->get("model"));
        $this->selectByOwnerId($request->query->get("owner_id"));
        $this->selectByAirportName($request->get("airport_name"));
        $this->selectByAirporId($request->get("airport_id"));

        if ($this->valid_request) {
            $cars = $this->fetchIdData($this->execQuery(), ["fuel", "gearbox"]);

            return ["cars" => $cars];
        }

        return [
            "error_msg" => "incorrect parameters",
            "error_status" => Response::HTTP_BAD_REQUEST
        ];
    }
    private function selectByDate($date)
    {
        $condition = "car.id IN (SELECT car_id FROM rent_parking_spot WHERE ";
        $start = false;
        $has_changed = false;
        if (isset($date["start"])) {
            try {
                $Date2 = new \DateTime($date["start"]);
                $this->query_parameters["start_date"] = $Date2->format("y-m-d");
                $condition = $condition . "start_date > :start_date";
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
                $condition .= "end_date < :end_date";
                $date2 = \DateTime($date['end']);
                $this->query_parameters["end_date"] = $date2;
                $has_changed = true;
            } catch (\Exception $e) { }
        }
        if ($has_changed) {
            $this->valid_request = true;
            $condition .= ")";
            $this->addWhereCondition($condition);
        }
    }

    private function selectById($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition("id = :id");
            $this->query_parameters["id"] = $id;
        }
    }

    private function selectByCoords($lat, $lng, $radius)
    {
        if (
            isset($lat) && isset($lng) && isset($radius) &&
            is_numeric($lat) && is_numeric($lng) && is_numeric($radius)
        ) {
            $condition = 'car.id IN (SELECT car_id FROM rent_parking_spot WHERE parking_lot_id IN
                (SELECT id FROM parking_lot WHERE';

            $this->valid_request = true;
            $condition .= '(1.852 * 60 * SQRT(POW((:lng - parking_lot.lng) * COS((parking_lot.lat + :lat) / 2), 2) +
                POW((parking_lot.lat - :lat), 2)) < :radius))) ';
            $this->query_parameters[':lng'] = (float)$lng;
            $this->query_parameters[':radius'] = (float)$radius;
            $this->query_parameters[':lat'] = (float)$lat;

            $this->addWhereCondition($condition);
        }
    }

    private function selectByGearbox($gearbox_id)
    {
        if (isset($gearbox_id) && preg_match("/[a-z]*/", $gearbox_id)) {
            $this->valid_request = true;
            $this->addWhereCondition("gearbox_id IN (SELECT id FROM gearbox WHERE type = :gearbox_id)");
            $this->query_parameters["gearbox_id"] = $gearbox_id;
        }
    }

    private function selectBySeatsCount($seats)
    {
        if (isset($seats) && is_numeric($seats)) {
            $this->valid_request = true;
            $this->addWhereCondition("seats >= :seats ");
            $this->query_parameters["seats"] = $seats;
        }
    }

    private function selectByDoorsCount($doors)
    {
        if (isset($doors) && is_numeric($doors)) {
            $this->valid_request = true;
            $this->addWhereCondition("doors >= :doors");
            $this->query_parameters["doors"] = $doors;
        }
    }

    private function selectByFuel($fuel_id)
    {
        if (isset($fuel_id) && preg_match("/[a-z]*/", $fuel_id)) {
            $this->valid_request = true;
            $this->addWhereCondition("fuel_id IN (SELECT id FROM fuel WHERE type = :fuel_id) ");
            $this->query_parameters["fuel_id"] = $fuel_id;
        }
    }

    private function selectByModel($model)
    {
        if (isset($model) && preg_match("/([a-z]|_)*/", $model)) {
            $this->valid_request = true;
            $model = str_replace("_", " ", $model);
            $this->addWhereCondition('model = :model');
            $this->query_parameters["model"] = $model;
        }
    }

    private function selectByPrice($price)
    {
        if (isset($price["min"]) && is_numeric($price["min"])) {
            $this->valid_request = true;
            $this->addWhereCondition("price_per_day > :min_price ");
            $this->query_parameters['min_price'] = $price["min"];
        }

        if (isset($price["max"]) && is_numeric($price["max"])) {
            $this->valid_request = true;
            $this->addWhereCondition("price_per_day < :max_price ");
            $this->query_parameters["max_price"] = $price["max"];
        }
    }

    private function selectByOwnerId($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition("owner_id = :owner_id");
            $this->query_parameters['owner_id'] = $id;
        }
    }

    private function selectByAirportName($name)
    {
        if (isset($name) && is_string($name)) {
            $this->valid_request = true;
            $query = "car.id IN (SELECT s.car_id FROM rent_parking_spot as s WHERE s.parking_lot_id IN ";
            $query .= "(SELECT p.id FROM parking_lot as p WHERE p.airport_id IN ";
            $query .= "(SELECT a.id FROM airport as a WHERE LOWER(a.name) LIKE '%" . strtolower($name) . "%' )))";

            $this->addWhereCondition($query);
            // $this->query_parameters["airport_name"] = $name;
        }
    }

    private function selectByAirporId($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $query = "car.id IN (SELECT s.car_id FROM rent_parking_spot as s WHERE s.parking_lot_id IN ";
            $query .= "(SELECT p.id FROM parking_lot as p WHERE p.airport_id IN ";
            $query .= "(SELECT a.id FROM airport as a WHERE a.id = :airport_id)))";

            $this->addWhereCondition($query);
            $this->query_parameters["airport_id"] = $id;
        }
    }
}
