<?php
namespace App\Entity;


use Symfony\Component\HttpFoundation\Request;

interface ParkingLotInterface
{
    public function getParkingLotsRequest(Request $request);
    public function insertParkingLotRequest($label, $lat, $lng, $capacity, $pricePerDay, $airportId);
    public function updateParkingLotRequest($label, $lat, $lng, $capacity, $pricePerDay, $airportId, $id);
    public function deleteParkingLotRequest($id);
}
