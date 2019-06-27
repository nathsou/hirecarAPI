<?php


namespace App\Controller;


use App\Entity\CarRental;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarRentalController extends MediaTypeController
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $endpoint = "car_rentals";

    /**
     * car_rentals
     * @Route("/car_rentals",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getCarRentals(Request $request)
    {
        $car_rental = new CarRental();
        return $this->handleResponse($request, $car_rental->getCarRentalsRequest($request));
    }

    /**
     * car_rentals
     * @Route("/car_rentals/{id}",methods={"DELETE"})
     * condition="context.getMethod() in ['DELETE']
     */
    public function deleteCarRental(Request $request)
    {

        $id = $request->get('id');
        if (
            isset($id) && is_numeric($id)
        ) {
            $car_rental = new CarRental();
            $car_rental->deleteCarRentalRequest($id);
            return $this->mediaTypeConverter($request);
        }

        return $this->handleResponse($request, [
            "msg" => "",
            "status" => Response::HTTP_BAD_REQUEST
        ]);
    }

    /**
     * car_rentals
     * @Route("/car_rentals",methods={"POST"})
     *  condition="context.getMethod() in ['POST']
     */
    public function insertCarRental(Request $request)
    {

        $data = $this->inputMediaTypeConverter($request);

        if (
            array_key_exists("start_date", $data)
            && array_key_exists("end_date", $data)
            && array_key_exists("user_id", $data)
            && array_key_exists("parking_spot_id", $data)
        ) {
            $start_date = $data["start_date"];
            $end_date = $data["end_date"];
            $user_id = $data["user_id"];
            $parking_spot_id = $data["parking_spot_id"];

            if (
                isset($start_date) && is_string($start_date) &&
                isset($end_date) && is_string($end_date) &&
                isset($user_id) && is_numeric($user_id) &&
                isset($parking_spot_id) && is_numeric($parking_spot_id)
            ) {
                $requestDB = new CarRental();
                return $this->handleResponse(
                    $request,
                    $requestDB->insertCarRentalRequest($start_date, $end_date, $user_id, $parking_spot_id)
                );
            }
        }

        return $this->handleResponse($request, [
            "msg" => 'invalid input',
            "status" => Response::HTTP_BAD_REQUEST
        ]);
    }
}
