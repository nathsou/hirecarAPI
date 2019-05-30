<?php


namespace App\Controller;

use App\Entity\ParkingSpotRental;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParkingSpotRentalController extends MediaTypeController
{
    public function __construct()
    {
        parent::__construct();
    }

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
}
