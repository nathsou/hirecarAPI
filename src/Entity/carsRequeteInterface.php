<?php


namespace App\Entity;


use Symfony\Component\HttpFoundation\Request;

interface carsRequeteInterface
{
    public function insertCarRequete($model, $nbPlaces, $nb_doors, $ownerId, $gearboxId, $fuel_id, $pricePerDay);
    public function updateCarRequete($model,$nb_places,$nb_doors,$gearbox_id,$fuel_id,$price_per_day,$id);
    public function deleteCarRequete($id);
    public function getParkingLotsRequete($center_lat,$center_lng,$radius,$airport,Request $request);
}