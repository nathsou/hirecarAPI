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

class CarController extends MediaTypeController
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
        $car = new Car();
        return $this->handleResponse($request, $car->getCarsRequest($request));
    }

    /**
     * cars
     * @Route("/cars/features",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getCarFeatures(Request $request)
    {
        $car = new Car();
        return $this->handleResponse($request, $car->getCarFeaturesRequest($request));
    }

    /**
     * cars
     * @Route("/cars/{id}",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */

    public function getUserCars(Request $request)
    {
        $id = $request->get('id');
        if (
            isset($id) && is_numeric($id)
        ) {
            $car = new Car();
            $data = $car->getUserCarsRequest($id);
            return $this->mediaTypeConverter($request, $data);
        }
        return new Response('', Response::HTTP_BAD_REQUEST);
    }


    /**
     * cars
     * @Route("/cars",methods={"POST"})
     * condition="context.getMethod() in ['POST']
     */
    public function insertCar(Request $request)
    {
        $data = $this->inputMediaTypeConverter($request);

        if (
            array_key_exists("model", $data)
            && array_key_exists("seats", $data)
            && array_key_exists("doors", $data)
            && array_key_exists("owner_id", $data)
            && array_key_exists("gearbox", $data)
            && array_key_exists("fuel", $data)
            && array_key_exists("price_per_day", $data)
        ) {
            $model = $data["model"];
            $seats = $data["seats"];
            $doors = $data["doors"];
            $owner_id = $data["owner_id"];
            $gearbox_id = $data["gearbox"]["id"];
            $fuel_id = $data["fuel"]["id"];
            $price_per_day = $data["price_per_day"];
            if (
                isset($model)
                && isset($seats) && is_numeric($seats)
                && isset($doors) && is_numeric($doors)
                && isset($owner_id) && is_numeric($owner_id)
                && isset($gearbox_id) && is_numeric($gearbox_id)
                && isset($fuel_id) && is_numeric($fuel_id)
                && isset($price_per_day) && is_numeric($price_per_day)
            ) {
                $car = new Car();
                $car->insertCarRequest($model, $seats, $doors, $owner_id, $gearbox_id, $fuel_id, $price_per_day);
            }
            return $this->mediaTypeConverter($request);
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
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
            && array_key_exists("seats", $data)
            && array_key_exists("doors", $data)
            && array_key_exists("gearbox", $data)
            && array_key_exists("fuel", $data)
            && array_key_exists("price_per_day", $data)
        ) {
            $model = $data["model"];
            $seats = $data["seats"];
            $doors = $data["doors"];
            $gearbox_id = $data["gearbox"]["id"];
            $fuel_id = $data["fuel"]["id"];
            $price_per_day = $data["price_per_day"];
        }
        $id = $request->get('id');
        if (
            isset($model) &&
            isset($seats) && is_numeric($seats) &&
            isset($doors) && is_numeric($doors) &&
            isset($gearbox_id) && is_numeric($gearbox_id) &&
            isset($fuel_id) && is_numeric($fuel_id) &&
            isset($price_per_day) && is_numeric($price_per_day) &&
            isset($id) && is_numeric($id)
        ) {
            $car = new Car();
            $car->updateCarRequest($model, $seats, $doors, $gearbox_id, $fuel_id, $price_per_day, $id);
            return $this->mediaTypeConverter($request, ["etat" => "ok"]);
        }
        return new Response('', Response::HTTP_BAD_REQUEST);
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
            $car = new Car();
            $car->deleteCarRequest($id);
            return $this->mediaTypeConverter($request, ["etat" => "ok"]);
        }
        return new Response('', Response::HTTP_BAD_REQUEST);
    }
}
