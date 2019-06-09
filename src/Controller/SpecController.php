<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class SpecController extends MediaTypeController
{

    /**
     * parking_lots
     * @Route("/spec/parking_lots",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getParkingLotsSpec(Request $request)
    {
        $response = $this->file(
            '../service_descriptor/resolved/parking_lots.json',
            'parking_lots.json',
            ResponseHeaderBag::DISPOSITION_INLINE
        );

        $response->headers->set("Content-Type", "application/json");

        return $response;
    }
}