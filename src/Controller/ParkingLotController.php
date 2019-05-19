<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 2019-04-26
 * Time: 19:44
 */

namespace App\Controller;

use App\Entity\ParkingLot;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParkingLotController extends MediaTypeController
{

    /**
     * parking_lots
     * @Route("/parking_lots",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getParkingLots(Request $request)
    {
            $pl = new ParkingLot();
            return $pl->getParkingLotsRequest($request);
    }
    /**
     * parking_lots
     * @Route("/parking_lots",methods={"POST"})
     *  condition="context.getMethod() in ['POST']
     */
    public function insertParkingLot(Request $request)
    {

        $data = $this->inputMediaTypeConverter($request);
        if (
            array_key_exists("label", $data)
            && array_key_exists("lat", $data)
            && array_key_exists("lng", $data)
            && array_key_exists("nb_places", $data)
            && array_key_exists("price_per_day", $data)
            && array_key_exists("airport_id", $data)
        ) {
            $label = $data["label"];
            $lat = $data["lat"];
            $lng = $data["lng"];
            $nb_places = $data["nb_places"];
            $price_per_day = $data["price_per_day"];
            $airport_id = $data["airport_id"];
            if (
                isset($label) &&
                isset($lat) && is_numeric($lat) &&
                isset($lng) && is_numeric($lng) &&
                isset($nb_places) && is_numeric($nb_places) &&
                isset($price_per_day) && is_numeric($price_per_day) &&
                isset($airport_id) && is_numeric($airport_id)
            ) {
                $requestDB = new ParkingLot();
                $requestDB->insertParkingLotRequest($label, $lat, $lng, $nb_places, $price_per_day, $airport_id);
                return $this->mediaTypeConverter($request, ["etat" => "ok"]);
            }
        }

        return $this->mediaTypeConverter($request, ["etat" => "error"]);
    }
}
