<?php


namespace App\Controller;


use App\Entity\Airport;
use Symfony\Component\HttpFoundation\Response;
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

        if (
            array_key_exists("error_msg", $data) &&
            array_key_exists( "error_status", $data)
        ) {
            return new Response($data["error_msg"], $data["error_status"]);
        }

        return $this->mediaTypeConverter($request, $data);
    }
}