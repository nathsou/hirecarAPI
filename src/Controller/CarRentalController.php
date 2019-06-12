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

    protected $spec_name = "car_rentals";

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
}
