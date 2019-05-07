<?php
    namespace App\Entity;
    interface ParkingLotsRequeteInterface
    {
        public function getParkingLots($long,$radius,$lat,\Symfony\Component\HttpFoundation\Request $request);
        public function getInsertParkingLots($label,$lat,$long,$nbPlaces,$pricePerDay,$airportId);
    }