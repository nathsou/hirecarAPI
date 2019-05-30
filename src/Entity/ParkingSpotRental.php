<?php


namespace App\Entity;


use Symfony\Component\HttpFoundation\Response;

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
                "error_msg" => "incorrect parameters",
                "error_status" => Response::HTTP_BAD_REQUEST
            ];
        }

    }
}