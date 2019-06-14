<?php


namespace App\Controller;

use App\Entity\ParkingSpotRental;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParkingSpotRentalController extends MediaTypeController
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $endpoint = "parking_spot_rentals";

    /**
     * parking_spot_rentals
     * @Route("/parking_spot_rentals",methods={"POST"})
     *  condition="context.getMethod() in ['POST']
     */
    public function insertParkingSpotRental(Request $request)
    {
        $data = $this->inputMediaTypeConverter($request);
        $spot_rental = new ParkingSpotRental();
        $res = $spot_rental->insertParkingSpotRentalRequest(
            $data["start_date"],
            $data["end_date"],
            $data["car_id"],
            $data["parking_lot_id"]
        );

        return $this->handleResponse($request, $res);
    }

    /**
     * parking_spot_rentals
     * @Route("/parking_spot_rentals",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getParkingSpotRentals(Request $request)
    {
        $spot_rental = new ParkingSpotRental();
        return $this->handleResponse($request, $spot_rental->getParkingSpotRentalsRequest($request));
    }

    /**
     * parking_spot_rentals
     * @Route("/parking_spot_rentals/{id}",methods={"DELETE"})
     * condition="context.getMethod() in ['DELETE']
     */
    public function deleteParkingSpotRental(Request $request)
    {
        $id = $request->get('id');
        if (
            isset($id) && is_numeric($id)
        ) {
            $spot_rental = new ParkingSpotRental();
            $spot_rental->deleteParkingSpotRentalRequest($id);
            return $this->mediaTypeConverter($request);
        }

        return $this->handleResponse($request, [
            "msg" => "",
            "status" => Response::HTTP_BAD_REQUEST
        ]);
    }
}
