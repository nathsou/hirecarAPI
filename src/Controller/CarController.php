<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 2019-04-27
 * Time: 01:24
 */

namespace App\Controller;


use App\Entity\Car;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends RequestDBController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * cars
     * @Route("/cars",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getCar(Request $request)
    {
        $center_lat = $request->query->get("center_lat");
        $center_lng = $request->query->get("center_lng");
        $radius = $request->query->get("radius");
        $airport = $request->query->get("airportId");
        $requestDB = new Car();
        return $this->mediaTypeConverter($request, $requestDB->getCarsRequest($center_lat, $center_lng, $radius, $airport, $request));
    }

    /**
     * cars
     * @Route("/cars",methods={"POST"})
     * condition="context.getMethod() in ['POST']
     */
    public function insertCar(Request $request)
    {
        $data = $this->mediatypeConvertiesseurInput($request);
        if (
            array_key_exists("model", $data)
            && array_key_exists("nb_places", $data)
            && array_key_exists("nb_doors", $data)
            && array_key_exists("owner_id", $data)
            && array_key_exists("gearbox_id", $data)
            && array_key_exists("fuel_id", $data)
            && array_key_exists("price_per_day", $data)
        ) {
            $model = $data["model"];
            $nb_places = $data["nb_places"];
            $nb_doors = $data["nb_doors"];
            $owner_id = $data["owner_id"];
            $gearbox_id = $data["gearbox_id"];
            $fuel_id = $data["fuel_id"];
            $price_per_day = $data["price_per_day"];
        }
        if (
            isset($model)
            && isset($nb_places) && is_numeric($nb_places)
            && isset($nb_doors) && is_numeric($nb_doors)
            && isset($owner_id) && is_numeric($owner_id)
            && isset($gearbox_id) && is_numeric($gearbox_id)
            && isset($fuel_id) && is_numeric($fuel_id)
            && isset($price_per_day) && is_numeric($price_per_day)
        ) {
            $requestDB = new Car();
            $requestDB->insertCarRequest($model, $nb_places, $nb_doors, $owner_id, $gearbox_id, $fuel_id, $price_per_day);
            return $this->mediaTypeConverter($request, ["etat" => "ok"]);
        }
        return $this->mediaTypeConverter($request, ["etat" => "error"]);
    }

    /**
     * cars
     * @Route("/cars/{id}",methods={"PUT"})
     * condition="context.getMethod() in ['PUT']
     */
    public function updateCar(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (
            array_key_exists("model", $data)
            && array_key_exists("nb_places", $data)
            && array_key_exists("nb_doors", $data)
            && array_key_exists("gearbox_id", $data)
            && array_key_exists("fuel_id", $data)
            && array_key_exists("price_per_day", $data)
        ) {
            $model = $data["model"];
            $nb_places = $data["nb_places"];
            $nb_doors = $data["nb_doors"];
            $gearbox_id = $data["gearbox_id"];
            $fuel_id = $data["fuel_id"];
            $price_per_day = $data["price_per_day"];
        }
        $id = $request->get('id');
        if (
            isset($model) &&
            isset($nb_places) && is_numeric($nb_places) &&
            isset($nb_places) && is_numeric($nb_places) &&
            isset($nb_doors) && is_numeric($nb_doors) &&
            isset($gearbox_id) && is_numeric($gearbox_id) &&
            isset($fuel_id) && is_numeric($fuel_id) &&
            isset($price_per_day) && is_numeric($price_per_day) &&
            isset($id) && is_numeric($id)
        ) {
            $requestData = new Car();
            $requestData->updateCarRequest($model, $nb_places, $nb_doors, $gearbox_id, $fuel_id, $price_per_day, $id);
            return $this->mediaTypeConverter($request, ["etat" => "ok"]);
        }
        return $this->mediaTypeConverter($request, ["etat" => "error"]);
    }

    /**
     * cars
     * @Route("/cars/{id}",methods={"DELETE"})
     * condition="context.getMethod() in ['DELETE']
     */
    public function deleteCar(Request $request)
    {

        $id = $request->get('id');
        if (
            isset($id) && is_numeric($id)
        ) {
            $requestDB = new Car();
            $requestDB->deleteCarRequest($id);
            return $this->mediaTypeConverter($request, ["etat" => "ok"]);
        }
        return $this->mediaTypeConverter($request, ["etat" => "error"]);
    }
}
