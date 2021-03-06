<?php


namespace App\Entity;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CarRental extends RequestBuilder
{

    public function getCarRentalsRequest(Request $request)
    {
        $this->query = "SELECT * FROM rent_car";

        $this->selectByDate(
            $request->query->get("start_date"),
            $request->query->get("end_date")
        );

        $this->selectById($request->query->get("id"));
        $this->selectByCarId($request->query->get("car_id"));
        $this->selectByAirportId($request->query->get("airport_id"));
        $this->selectByUserId($request->query->get("user_id"));
        $this->selectByOwnerId($request->query->get("owner_id"));
        $this->selectByAirportName($request->query->get("airport_name"));

        if ($this->valid_request) {
            $car_rentals = $this->fetchIdData($this->execQuery(), [
                "parking_spot" => "rent_parking_spot",
                "parking_lot",
                "airport",
                "car",
                "gearbox",
                "fuel"
            ], 3);

            return ["car_rentals" => $car_rentals];
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

    private function selectByCarId($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition(":car_id IN (SELECT car_id FROM rent_parking_spot as s WHERE s.id = parking_spot_id)");
            $this->query_parameters["car_id"] = $id;
        }
    }

    private function selectByAirportId($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition(":airport_id IN (SELECT p.airport_id FROM rent_parking_spot as s,
            parking_lot as p WHERE parking_spot_id = s.id AND s.parking_lot_id = p.id)");
            $this->query_parameters["airport_id"] = $id;
        }
    }

    private function selectByUserId($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition("user_id = :user_id");
            $this->query_parameters["user_id"] = $id;
        }
    }

    private function selectByOwnerId($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition(":owner_id IN (SELECT c.owner_id FROM rent_parking_spot as s,
            car as c WHERE s.id = parking_spot_id and c.id = s.car_id)");
            $this->query_parameters["owner_id"] = $id;
        }
    }


    private function selectByAirportName($name)
    {
        if (isset($name) && is_string($name)) {
            $this->valid_request = true;
            $this->addWhereCondition("id IN (SELECT rc.id FROM rent_car as rc, rent_parking_spot as s,
                parking_lot as p, airport as a WHERE s.parking_lot_id = rc.parking_spot_id AND s.parking_lot_id = p.id
                AND p.airport_id = a.id AND LOWER(a.name) LIKE '%" . strtolower($name) . "%')");
        }
    }

    private function selectByDate($start, $end)
    {

        if (isset($start) && is_string($start)) {
            try {
                $Date2 = new \DateTime($start);
                $this->query_parameters["start_date"] = $Date2->format("y-m-d H:i");
                $this->addWhereCondition("start_date >= :start_date");
                $this->valid_request = true;
            } catch (\Exception $e) { }
        }

        if (isset($end) && is_string($end)) {
            try {
                $date = new \DateTime($end);
                $this->addWhereCondition("end_date <= :end_date");
                $this->query_parameters["end_date"] = $date->format("y-m-d H:i");
                $this->valid_request = true;
            } catch (\Exception $e) { }
        }
    }

    public function deleteCarRentalRequest($id)
    {
        $db = SModel::getInstance();
        $this->query = "DELETE FROM rent_car WHERE id = :id";
        $prep = $db->prepare($this->query);
        $prep->bindValue("id", $id);
        $prep->execute();
    }

    public function insertCarRentalRequest($start_date, $end_date, $user_id, $parking_spot_id)
    {
        $db = SModel::getInstance();
        $query = "INSERT INTO rent_car (start_date, end_date, user_id, parking_spot_id)
            VALUES (:start_date, :end_date, :user_id, :parking_spot_id)";

        $prep = $db->prepare($query);
        $prep->bindValue("start_date", $start_date);
        $prep->bindValue("end_date", $end_date);
        $prep->bindValue("user_id", $user_id);
        $prep->bindValue("parking_spot_id", $parking_spot_id);

        $prep->execute();

        switch ($prep->errorCode()) {
            case "23000":
                return [
                    "msg" => "duplicate car rentals",
                    "status" => Response::HTTP_CONFLICT
                ];
            case "00000":
                // return the id of the newly created car rental
                $this->query = "SELECT id FROM rent_car WHERE
                                start_date = '" . $start_date . "' AND
                                end_date = '" . $end_date . "' AND
                                user_id = " . $user_id . " AND
                                parking_spot_id = " . $parking_spot_id;

                return $this->execQuery()[0];
            default:
                return [
                    "msg" => "invalid input",
                    "status" => Response::HTTP_BAD_REQUEST
                ];
        }
    }
}
