<?php
namespace App\Entity;


use Symfony\Component\HttpFoundation\Request;

interface CarInterface
{
    public function insertCarRequest($model, $nbPlaces, $nb_doors, $ownerId, $gearboxId, $fuel_id, $pricePerDay);
    public function updateCarRequest($model, $nb_places, $nb_doors, $gearbox_id, $fuel_id, $price_per_day, $id);
    public function deleteCarRequest($id);
    public function getCarsRequest($center_lat, $center_lng, $radius, $airport, Request $request);
}
