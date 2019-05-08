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

class ParkingLotController extends RequestDBController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * parking_lots
     * @Route("/parking_lots",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getParkingLots(Request $request)
    {
        $center_lat = $request->query->get('center_lat');
        $center_lng = $request->query->get('center_lng');
        $radius = $request->query->get('radius');
        if (is_numeric($center_lat) && is_numeric($center_lng) && is_numeric($radius)) {
            $requestDB = new ParkingLot();
            return $this->mediatypeConverteur($request, ['airports' => $requestDB->getParkingLotsRequest($center_lng, $radius, $center_lat, $request)]);
        } else {
            return $this->mediatypeConverteur($request, ['error' => 'les données fournies ne sont pas des nombres']);
        }
    }
    /**
     * parking_lots
     * @Route("/parking_lots",methods={"POST"})
     *  condition="context.getMethod() in ['POST']
     */
    public function insertParkingLot(Request $request)
    {

        $data = json_decode($request->getContent(), true);
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
            return $this->mediatypeConverteur($request, ["etat" => "ok"]);
        }
        return $this->mediatypeConverteur($request, ["etat" => "error"]);
    }
}
