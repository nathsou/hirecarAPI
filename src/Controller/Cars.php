<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 2019-04-27
 * Time: 01:24
 */

namespace App\Controller;


use App\Entity\carsRequeteMYSQL;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Cars extends RequetageBDD
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
    public function get_parking_lots(Request $request)
    {
        $center_lat = $request->query->get("center_lat");
        $center_lng = $request->query->get("center_lng");
        $radius = $request->query->get("radius");
        $airport = $request->query->get("airportId");
        $requeteData= new carsRequeteMYSQL();
        return $this->mediatypeConverteur($request,$requeteData->getParkingLotsRequete($center_lat,$center_lng,$radius,$airport,$request));
    }
    /**
     * cars
     * @Route("/cars",methods={"POST"})
     * condition="context.getMethod() in ['POST']
     */
    public function insertCar(Request $request)
    {

        $data = json_decode($request->getContent(), true);
        $model = $data["model"];
        $nb_places = $data["nb_places"];
        $nb_doors = $data["nb_doors"];
        $owner_id = $data["owner_id"];
        $gearbox_id = $data["gearbox_id"];
        $fuel_id = $data["fuel_id"];
        $price_per_day = $data["price_per_day"];
        if (
            isset($model)
            && isset($nb_places) && is_numeric($nb_places)
            && isset($nb_doors) && is_numeric($nb_doors)
            && isset($owner_id) && is_numeric($owner_id)
            && isset($gearbox_id) && is_numeric($gearbox_id)
            && isset($fuel_id) && is_numeric($fuel_id)
            && isset($price_per_day) && is_numeric($price_per_day)
        ) {
            $requeteData = new carsRequeteMYSQL();
            $requeteData->insertCarRequete($model,$nb_places,$nb_doors,$owner_id,$gearbox_id,$fuel_id,$price_per_day);
            return $this->mediatypeConverteur($request, ["etat" => "ok"]);
        }
        return $this->mediatypeConverteur($request, ["etat" => "error"]);
    }

    /**
     * cars
     * @Route("/cars/{id}",methods={"PUT"})
     * condition="context.getMethod() in ['PUT']
     */
    public function updateCar(Request $request)
    {

        $data = json_decode($request->getContent(), true);
        $model = $data["model"];
        $nb_places = $data["nb_places"];
        $nb_doors = $data["nb_doors"];
        $gearbox_id = $data["gearbox_id"];
        $fuel_id = $data["fuel_id"];
        $price_per_day = $data["price_per_day"];
        $id = $request->get('id');
        if (
            isset($model)
            && isset($nb_places) && is_numeric($nb_places)
            && isset($nb_places) && is_numeric($nb_places)
            && isset($nb_doors) && is_numeric($nb_doors)
            && isset($gearbox_id) && is_numeric($gearbox_id)
            && isset($fuel_id) && is_numeric($fuel_id)
            && isset($price_per_day) && is_numeric($price_per_day)
            && isset($id) && is_numeric($id)
        ) {
            $requestData= new carsRequeteMYSQL();
            $requestData->updateCarRequete($model,$nb_places,$nb_doors,$gearbox_id,$fuel_id,$price_per_day,$id);
            return $this->mediatypeConverteur($request, ["etat" => "ok"]);
        }
        return $this->mediatypeConverteur($request, ["etat" => "error"]);
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
            $requeteData=new carsRequeteMYSQL();
            $requeteData->deleteCarRequet($id);
            return $this->mediatypeConverteur($request, ["etat" => "ok"]);
        }
        return $this->mediatypeConverteur($request, ["etat" => "error"]);
    }
}
