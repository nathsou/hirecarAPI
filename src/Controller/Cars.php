<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 2019-04-27
 * Time: 01:24
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Cars extends RequetageBDD
{
    public function __construct()
    {
        parent::__construct();
        $this->query = "SELECT * FROM `car` WHERE car.id IN (SELECT car_id FROM rent_parking_spot WHERE parking_lot_id IN (SELECT id FROM parking_lot WHERE ";
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
        if ((isset($center_lat) && isset($center_lng) && isset($radius)) xor isset($airport)) {
            if (isset($center_lat) && isset($center_lng) && isset($radius) && is_numeric($center_lat) && is_numeric($center_lng) && is_numeric($radius)) {
                $this->query .= "(1.852 * 60 * SQRT(POW((:lng - `lng`) * COS((`lat` + :lat) / 2), 2) + POW((`lat` - :lat), 2)) < :radius)";
                $this->queryParameter["lat"] = $center_lat;
                $this->queryParameter["lng"] = $center_lng;
                $this->queryParameter["radius"] = $radius;
            } elseif (is_numeric($airport)) {
                $this->query .= "id = :airportID ";
                $this->queryParameter["airportID"] = $airport;
            } else {
                return $this->json(["error" => "data parameters are unavailable"]);
            }
            $this->selectByPrice([
                "min" => $request->query->get('min_price'),
                "max" => $request->query->get('max_price')
            ]);
            $this->query .= "))";
            $this->selecteByDate([
                "start" => $request->query->get('start_date'),
                "end" => $request->query->get('end_date')
            ]);
            $this->selectGearbox($request->query->get("gearBox"));
            $this->selectBynbplaces($request->query->get("nb_seats"));
            $this->selectBynbporte($request->query->get("nb_doors"));
            $this->selectByFuel($request->query->get("fuel"));
            $this->selectByModel($request->query->get("model"));
            $prep = $this->bdd->prepare($this->query);
            foreach ($this->queryParameter as $key => $value) {
                $prep->bindValue($key, $value);
            }
            $prep->execute();
            return $this->mediatypeConverteur($request, ["cars" => $prep->fetchAll(\PDO::FETCH_ASSOC)]);
        } else {
            return $this->mediatypeConverteur($request, ["error" => "data parameters are unavailable"]);
        }
    }
    private function selecteByDate($date)
    {
        $query = "AND car.id IN (SELECT car_id FROM rent_parking_spot WHERE ";
        $start = false;
        $has_change = false;
        if (isset($date["start"])) {
            try {
                $Date2 = new \DateTime($date["start"]);
                $this->queryParameter["start_date"] = $Date2->format("y-m-d");
                $query = $query . "start_date > :start_date";
                $has_change = true;
                $start = true;
            } catch (\Exception $e) { }
        }
        if (isset($date["end"])) {
            try {
                $date = new \DateTime($date["end"]);
                if ($start) {
                    $query .= " AND ";
                }
                $query .= "end_date < :end_date";
                $date2 = \DateTime($date['end']);
                $this->queryParameter["end_date"] = $date2;
                $has_change = true;
            } catch (\Exception $e) { }
        }
        if ($has_change) {
            $query .= ") ";
            $this->query .= $query;
        }
    }
    private function selectGearbox($gearBoxType)
    {
        if (isset($gearBoxType) && preg_match("/[a-z]*/", $gearBoxType)) {
            $this->query .= "AND gearbox_id IN ( SELECT id FROM gearbox WHERE type = :gearBox) ";
            $this->queryParameter["gearBox"] = $gearBoxType;
        }
    }
    private function selectBynbplaces($nbPlaces)
    {
        if (isset($nbPlaces) && is_numeric($nbPlaces)) {
            $this->query .= "AND nb_places >= :nb_seats ";
            $this->queryParameter["nb_seats"] = $nbPlaces;
        }
    }
    private function selectBynbporte($nbPorte)
    {
        if (isset($nbPorte) && is_numeric($nbPorte)) {
            $this->query .= "AND nb_places >= :nb_doors ";
            $this->queryParameter["nb_doors"] = $nbPorte;
        }
    }
    private function selectByFuel($fuel)
    {
        if (isset($fuel) && preg_match("/[a-z]*/", $fuel)) {
            $this->query .= "AND fuel_id IN ( SELECT id FROM fuel WHERE type = :fuel) ";
            $this->queryParameter["fuel"] = $fuel;
        }
    }
    private function selectByModel($model)
    {
        if (isset($model) && preg_match("/([a-z]|_)*/", $model)) {
            $model = str_replace("_", " ", $model);
            $this->query .= 'AND model = :model ';
            $this->queryParameter["model"] = $model;
        }
    }
    private function selectByPrice($price)
    {
        if (isset($price["min"]) && is_numeric($price["min"])) {
            $this->query = $this->query . "AND price_per_day > :min_price ";
            $this->queryParameter['min_price'] = $price["min"];
        }
        if (isset($price["max"]) && is_numeric($price["max"])) {
            $this->query = $this->query . "AND price_per_day< :max_price ";
            $this->queryParameter["max_price"] = $price["max"];
        }
    }

    /**
     * cars
     * @Route("/cars",methods={"POST"})
     * condition="context.getMethod() in ['POST']
     */
    public function insertCar(Request $request)
    {
        $this->query = "INSERT INTO car (model, nb_places, nb_doors, owner_id, gearbox_id, fuel_id, price_per_day) VALUES (:model, :nb_places, :nb_doors, :owner_id, :gearbox_id, :fuel_id, :price_per_day)";

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
            $prep = $this->bdd->prepare($this->query);
            $prep->bindValue("model", $model);
            $prep->bindValue("nb_places", $nb_places);
            $prep->bindValue("nb_doors", $nb_doors);
            $prep->bindValue("owner_id", $owner_id);
            $prep->bindValue("gearbox_id", $gearbox_id);
            $prep->bindValue("fuel_id", $fuel_id);
            $prep->bindValue("price_per_day", $price_per_day);
            $prep->execute();
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
        $this->query = "UPDATE car SET model = :model, nb_places = :nb_places, nb_doors = :nb_doors, gearbox_id = :gearbox_id, fuel_id = :fuel_id, price_per_day = :price_per_day WHERE id = :id";

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
            $prep = $this->bdd->prepare($this->query);
            $prep->bindValue("model", $model);
            $prep->bindValue("nb_places", $nb_places);
            $prep->bindValue("nb_doors", $nb_doors);
            $prep->bindValue("gearbox_id", $gearbox_id);
            $prep->bindValue("fuel_id", $fuel_id);
            $prep->bindValue("price_per_day", $price_per_day);
            $prep->bindValue("id", $id);
            $prep->execute();
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
        $this->query = "DELETE FROM car WHERE id = :id";
        $id = $request->get('id');
        if (
            isset($id) && is_numeric($id)
        ) {
            $prep = $this->bdd->prepare($this->query);
            $prep->bindValue("id", $id);
            $prep->execute();
            return $this->mediatypeConverteur($request, ["etat" => "ok"]);
        }
        return $this->mediatypeConverteur($request, ["etat" => "error"]);
    }
}
