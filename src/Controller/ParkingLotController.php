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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParkingLotController extends MediaTypeController
{

    protected $endpoint = "parking_lots";
    /**
     * parking_lots
     * @Route("/parking_lots",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getParkingLots(Request $request)
    {
        $pl = new ParkingLot();
        return $this->handleResponse($request, $pl->getParkingLotsRequest($request));
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
            && array_key_exists("capacity", $data)
            && array_key_exists("price_per_day", $data)
            && array_key_exists("airport", $data)
        ) {
            $label = $data["label"];
            $lat = $data["lat"];
            $lng = $data["lng"];
            $capacity = $data["capacity"];
            $price_per_day = $data["price_per_day"];
            $airport_id = $data["airport"]["id"];
            if (
                isset($label) &&
                isset($lat) && is_numeric($lat) &&
                isset($lng) && is_numeric($lng) &&
                isset($capacity) && is_numeric($capacity) &&
                isset($price_per_day) && is_numeric($price_per_day) &&
                isset($airport_id) && is_numeric($airport_id)
            ) {
                $pl = new ParkingLot();
                // return $this->handleResponse(
                //     $request,
                //     $pl->insertParkingLotRequest($label, $lat, $lng, $capacity, $price_per_day, $airport_id)
                // );
                $pl->insertParkingLotRequest($label, $lat, $lng, $capacity, $price_per_day, $airport_id);
                $id = $pl->selectInsertedParkingLotIdRequest();
                return new Response('{"id": "' . $id . '"}', Response::HTTP_OK);
            }
        }

        return $this->handleResponse($request, [
            "msg" => 'invalid input',
            "status" => Response::HTTP_BAD_REQUEST
        ]);
    }

    /**
     * parking_lots
     * @Route("/parking_lots/{id}",methods={"DELETE"})
     * condition="context.getMethod() in ['DELETE']
     */
    public function deleteParkingLot(Request $request)
    {

        $id = $request->get('id');
        if (
            isset($id) && is_numeric($id)
        ) {
            $pl = new ParkingLot();
            if ($pl->checkParkingSpotRental($id) === 0) {
                $pl->deleteParkingLotRequest($id);
                return $this->mediaTypeConverter($request, [
                    "msg" => "parking lot removed",
                    "status" => Response::HTTP_OK
                ]);
            } else {
                return $this->handleResponse($request, [
                    "msg" => "rent parking spot on parking lot",
                    "status" => Response::HTTP_CONFLICT
                ]);
            }
        }
        return new Response('', Response::HTTP_BAD_REQUEST);
    }
}
