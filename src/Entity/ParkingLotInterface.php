<?php
namespace App\Entity;


use Symfony\Component\HttpFoundation\Request;

interface ParkingLotInterface
{
    public function getParkingLotsRequest($lng, $radius, $lat, Request $request);
    public function insertParkingLotRequest($label, $lat, $lng, $nbPlaces, $pricePerDay, $airportId);
}
