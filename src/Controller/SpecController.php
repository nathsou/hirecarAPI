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
            '../service_descriptor/resolved/parking_lots.'.$ext,
            'parking_lots.'.$ext,
            ResponseHeaderBag::DISPOSITION_INLINE
        );

        $response->headers->set("Content-Type", "application/".$ext);

        return $response;
    }
}