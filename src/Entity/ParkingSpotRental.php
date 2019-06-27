<?php


namespace App\Entity;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ParkingSpotRental extends RequestBuilder
{

    public function insertParkingSpotRentalRequest(
        $start_date,
        $end_date,
        $car_id,
        $parking_lot_id
    ) {

        if (
            isset($start_date) && is_string($start_date) &&
            isset($end_date) && is_string($end_date) &&
            isset($car_id) && is_numeric($car_id) &&
            isset($parking_lot_id) && is_numeric($parking_lot_id)
        ) {
            $this->query = "INSERT INTO rent_parking_spot (start_date, end_date, car_id, parking_lot_id)
            VALUES (:start_date, :end_date, :car_id, :parking_lot_id)";

            $this->query_parameters = [
                "start_date" => $start_date,
                "end_date" => $end_date,
                "car_id" => $car_id,
                "parking_lot_id" => $parking_lot_id
            ];

            $res = $this->execQuery();
            if (array_key_exists("error_msg", $res)) {
                return $res;
            }

            // return the id of the newly created parking spot rental
            $this->query = "SELECT id FROM rent_parking_spot WHERE
                start_date = :start_date AND
                end_date = :end_date AND
                car_id = :car_id AND
                parking_lot_id = :parking_lot_id";

            return $this->execQuery()[0];
        } else {
            return [
                "msg" => "incorrect parameters",
                "status" => Response::HTTP_BAD_REQUEST
            ];
        }
    }

    public function getParkingSpotRentalsRequest(Request $request)
    {
        $this->query = "SELECT * FROM rent_parking_spot as s";

        $this->selectByDate(
            $request->query->get("start_date"),
            $request->query->get("end_date")
        );
        $this->selectById($request->query->get("id"));
        $this->selectByCarId($request->query->get("car_id"));
        $this->selectByOwnerId($request->query->get("owner_id"));
        $this->selectByAirportId($request->query->get("airport_id"));
        $this->selectByAirportName($request->query->get("airport_name"));

        if ($this->valid_request) {
            $parking_spot_rentals = $this->fetchIdData($this->execQuery(), [
                "parking_lot",
                "airport",
                "car",
                "gearbox",
                "fuel"
            ], 2);

            return ["parking_spot_rentals" => $parking_spot_rentals];
        }
        return [
            "msg" => "incorrect parameters",
            "status" => Response::HTTP_BAD_REQUEST
        ];
    }

    private function selectByDate($start, $end)
    {

        if (isset($start) && is_string($start) && isset($end) && is_string($end)) {
            try {
                $Date2 = new \DateTime($start);
                $this->query_parameters["start_date"] = $Date2->format("y-m-d H:i");
                $this->addWhereCondition("start_date <= :start_date AND end_date >= :end_date AND NOT EXISTS (SELECT id FROM rent_car as r WHERE r.parking_spot_id = s.id AND :start_date <= r.start_date AND :end_date >= r.end_date)");
                $date = new \DateTime($end);
                $this->query_parameters["end_date"] = $date->format("y-m-d H:i");
                $this->valid_request = true;
            } catch (\Exception $e) { }
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

    private function selectByCarId($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition("car_id = :car_id");
            $this->query_parameters["car_id"] = $id;
        }
    }

    private function selectByOwnerId($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition(":owner_id IN (SELECT c.owner_id FROM car as c WHERE car_id = c.id)");
            $this->query_parameters["owner_id"] = $id;
        }
    }

    private function selectByAirportId($id)
    {
        if (isset($id) && is_numeric($id)) {
            $this->valid_request = true;
            $this->addWhereCondition(":airport_id IN (SELECT p.airport_id FROM parking_lot as p WHERE parking_lot_id = p.id)");
            $this->query_parameters["airport_id"] = $id;
        }
    }

    private function selectByAirportName($name)
    {
        if (isset($name) && is_string($name)) {
            $this->valid_request = true;
            $this->addWhereCondition("id IN (SELECT s.id FROM rent_parking_spot as s, parking_lot as p, airport as a WHERE s.parking_lot_id = p.id AND p.airport_id = a.id AND LOWER(a.name) LIKE '%" . strtolower($name) . "%')");
        }
    }

    public function deleteParkingSpotRentalRequest($id)
    {
        $db = SModel::getInstance();
        $this->query = "DELETE FROM rent_parking_spot WHERE id = :id";
        $prep = $db->prepare($this->query);
        $prep->bindValue("id", $id);
        $prep->execute();
    }
}
