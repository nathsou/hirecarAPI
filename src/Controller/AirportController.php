<?php


namespace App\Controller;


use App\Entity\Airport;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AirportController extends MediaTypeController
{

    /**
     * airports
     * @Route("/airports",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getAirports(Request $request)
    {
        $airport = new Airport();
        $data = $airport->getAirportsRequest($request);

        return $this->handleResponse($request, $data);
    }
}