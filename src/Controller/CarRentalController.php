<?php


namespace App\Controller;


use App\Entity\CarRental;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarRentalController extends MediaTypeController
{
    public function __construct()
    {
        parent::__construct();
    }

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
}