<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class SpecController extends MediaTypeController
{

    protected function sendSpec(Request $request, $file) {
        $mimes = $this->getMimes($request);
        $ext = 'json';

        foreach ($mimes as $mime) {
            switch ($mime) {
                case 'application/json':
                    break;
                case 'application/yaml':
                    $ext = 'yaml';
                    break;
            }
        }

        $response = $this->file(
            '../service_descriptor/resolved/'.$file.'.'.$ext,
            $file.'.'.$ext,
            ResponseHeaderBag::DISPOSITION_INLINE
        );

        $response->headers->set("Content-Type", "application/".$ext);

        return $response;
    }
    /**
     * parking_lots
     * @Route("/spec/parking_lots",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getParkingLotsSpec(Request $request)
    {
        return $this->sendSpec($request,"parking_lots");
    }

    /**
     * users
     * @Route("/spec/users",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getUsersSpec(Request $request)
    {
        return $this->sendSpec($request,"users");
    }

    /**
     * users_id
     * @Route("/spec/users_id",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getUsersIDSpec(Request $request)
    {
        return $this->sendSpec($request,"users_id");
    }

    /**
     * parking_spot_rentals
     * @Route("/spec/parking_spot_rentals",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getParkingSpotRentalsSpec(Request $request)
    {
        return $this->sendSpec($request,"parking_spot_rentals");
    }

    /**
     * parking_spot_rentals_id
     * @Route("/spec/parking_spot_rentals_id",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getParkingSpotRentalsIDSpec(Request $request)
    {
        return $this->sendSpec($request,"parking_spot_rentals_id");
    }

    /**
     * cars
     * @Route("/spec/cars",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getCarsSpec(Request $request)
    {
        return $this->sendSpec($request,"cars");
    }

    /**
     * cars_id
     * @Route("/spec/cars_id",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getCarsIDSpec(Request $request)
    {
        return $this->sendSpec($request,"cars_id");
    }

    /**
     * cars_id
     * @Route("/spec/car_rentals",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getCarRentalsSpec(Request $request)
    {
        return $this->sendSpec($request,"car_rentals");
    }

    /**
     * cars_id
     * @Route("/spec/verify_payment",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function verifyPaymentSpec(Request $request)
    {
        return $this->sendSpec($request,"verify_payment");
    }
}