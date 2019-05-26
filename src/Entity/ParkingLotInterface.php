<?php
namespace App\Entity;


use Symfony\Component\HttpFoundation\Request;

interface ParkingLotInterface
{
    public function getParkingLotsRequest(Request $request);
    public function insertParkingLotRequest($label, $lat, $lng, $capacity, $pricePerDay, $airportId);
}
