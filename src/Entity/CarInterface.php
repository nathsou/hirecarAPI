<?php
namespace App\Entity;


use Symfony\Component\HttpFoundation\Request;

interface CarInterface
{
    public function insertCarRequest($model, $nbPlaces, $nb_doors, $owner_id, $gearbox_id, $fuel_id, $price_per_day);
    public function updateCarRequest($model, $seats, $doors, $gearbox_id, $fuel_id, $price_per_day, $id);
    public function deleteCarRequest($id);
    public function getCarsRequest(Request $request);
}
