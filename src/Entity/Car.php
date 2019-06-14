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
        return true;
    }
    public function selectInsertedCarIdRequest()
    {
        $this->query = "SELECT id FROM car WHERE id = (SELECT MAX(id) FROM car)";
        $result = $this->execQuery();
        return $result[0]["id"];
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
        $this->query = "SELECT car.id, model, seats, doors, gearbox_id, fuel_id, owner_id, price_per_day FROM car, gearbox, fuel WHERE owner_id = :id AND car.gearbox_id = gearbox.id AND car.fuel_id = fuel.id";

        $this->query_parameters["id"] = $id;
        $cars = $this->fetchIdData($this->execQuery(), ["fuel", "gearbox"]);
        return ["cars" => $cars];
    }
    public function getCarsRequest(Request $request)
    {
        $this->query = "SELECT * FROM `car`";
        $this->selectByPrice([
            "min" => $request->query->get('min_price'),
            "max" => $request->query->get('max_price')
        ]);
        $this->selectById($request->query->get("id"));
        $this->selectByMinSeats($request->query->get("min_seats"));
        $this->selectByMinDoors($request->query->get("min_doors"));
        $this->selectByGearbox($request->query->get("gearbox"));
        $this->selectByFuel($request->query->get("fuel"));
        $this->selectByModel($request->query->get("model"));
        $this->selectByOwnerId($request->query->get("owner_id"));

        if ($this->valid_request) {
            $cars = $this->fetchIdData($this->execQuery(), ["fuel", "gearbox"]);

            return ["cars" => $cars];
        }

        return [
            "msg" => "incorrect parameters",
            "status" => Response::HTTP_BAD_REQUEST
        ];
    }

    private function selectById($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition("id = :id");
            $this->query_parameters["id"] = $id;
        }
    }

    private function selectByMinSeats($seats)
    {
        if (isset($seats) && is_numeric($seats)) {
            $this->valid_request = true;
            $this->addWhereCondition("seats >= :seats ");
            $this->query_parameters["seats"] = $seats;
        }
    }

    private function selectByMinDoors($doors)
    {
        if (isset($doors) && is_numeric($doors)) {
            $this->valid_request = true;
            $this->addWhereCondition("doors >= :doors");
            $this->query_parameters["doors"] = $doors;
        }
    }

    private function selectByGearbox($name)
    {
        if (isset($name) && is_string($name)) {
            $this->valid_request = true;
            $this->addWhereCondition("id IN (SELECT c.id FROM car as c, gearbox as g WHERE c.gearbox_id = g.id AND LOWER(g.type) LIKE '%" . strtolower($name) . "%')");
        }
    }

    private function selectByFuel($name)
    {
        if (isset($name) && is_string($name)) {
            $this->valid_request = true;
            $this->addWhereCondition("id IN (SELECT c.id FROM car as c, fuel as f WHERE c.fuel_id = f.id AND LOWER(f.type) LIKE '%" . strtolower($name) . "%')");
        }
    }

    private function selectByModel($name)
    {
        if (isset($name) && is_string($name)) {
            $this->valid_request = true;
            $this->addWhereCondition("id IN (SELECT c.id FROM car as c WHERE LOWER(c.model) LIKE '%" . strtolower($name) . "%')");
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
}
