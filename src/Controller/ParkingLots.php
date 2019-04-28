<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 2019-04-26
 * Time: 19:44
 */
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class ParkingLots extends RequetageBDD
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * parking_lots
     * @Route("/parking_lots")
     *  condition="context.getMethod() in ['GET']
     */
    public function get_parking_lots(Request $request)
    {
        $this->query = "SELECT * FROM `parking_lot` ";
        $this->query .= "LEFT JOIN ( (SELECT (count(parking_lot_id))";
        $this->query .= "AS countPlaceTaken,parking_lot_id FROM rent_parking_spot ";
        $this->query .= "GROUP BY parking_lot_id))";
        $this->query .= "AS countTable on countTable.parking_lot_id=parking_lot.id ";
        $this->query .= "WHERE (  1.852*60* SQRT(POW( (:long - parking_lot.lng)* COS ((parking_lot.lat + :lat)/2) ,2)+POW((parking_lot.lat - :lat),2))  < :radius) ";
        $center_lat = $request->query->get('center_lat');
        $center_lng = $request->query->get('center_lng');
        $radius = $request->query->get('radius');
        if (is_numeric($center_lat) && is_numeric($center_lng) && is_numeric($radius)) {
            $this->queryParameter[':long'] = (float)$center_lng;
            $this->queryParameter[':radius'] = (float)$radius;
            $this->queryParameter[':lat'] = (float)$center_lat;
            $this->selecteByDate([
                "start" => $request->query->get('start_date'),
                "end" => $request->query->get('end_date')
            ]);
            $this->selectByPrice([
                "min" => $request->query->get('min_price'),
                "max" => $request->query->get('max_price')
            ]);

            $this->selectByNbplace($request->query->get("number_places"));
            $queryTemp = "SELECT id, label, lat, lng, price_per_day, airport_id, parking_lot_id, (finalTable.nb_places-finalTable.countPlaceTaken) AS nb_places FROM ( ";
            $queryTemp2 = " )AS finalTable ";
            $this->query = $queryTemp . $this->query . $queryTemp2;
            $prep = $this->bdd->prepare($this->query);
            foreach ($this->queryParameter as $key => $value) {
                $prep->bindValue($key, $value);
            }
            $prep->execute();
            return $this->json(['airports' => $prep->fetchAll(\PDO::FETCH_ASSOC),'query'=>$this->query]);
        } else {
            return $this->json(['error' => 'les données fournies ne sont pas des nombres', "query" => $this->query, "data" => $this->queryParameter]);
        }
    }
    /**
     * parking_lots
     * @Route("/parking_lots",methods={"POST"})
     *  condition="context.getMethod() in ['POST']
     */
    public function insertParkingLot(Request $request){
        $this->query ="INSERT INTO parking_lot (label,lat,lng,nb_places,price_per_day,airport_id) VALUES  (:label, :lat, :lng, :nb_places, :price_per_day,:airport_id)";
        $data= json_decode($request->getContent(),true);
        $label=$data["label"];
        $lat=$data["lat"];
        $long=$data["long"];
        $nb_places=$data["nb_places"];
        $price_per_day=$data["price_per_day"];
        $airport_id=$data["airport_id"];
        if(isset($label)&&
            isset($lat)&& is_numeric($lat)&&
            isset($long)&&is_numeric($long)&&
            isset($nb_places)&&is_numeric($nb_places)&&
            isset($price_per_day)&&is_numeric($price_per_day)&&
            isset($airport_id)&&is_numeric($airport_id)
        ){
            $prep=$this->bdd->prepare($this->query);
            $prep->bindValue("label",$label);
            $prep->bindValue("lat",$lat);
            $prep->bindValue("lng",$long);
            $prep->bindValue("nb_places",$nb_places);
            $prep->bindValue("price_per_day",$price_per_day);
            $prep->bindValue("airport_id",$airport_id);
            $prep->execute();
            return $this->json(["etat"=>"ok"]);
        }
        return $this->json(["etat"=>"error"]);
    }

    private function selectByPrice($price)
    {
        if (isset($price["min"]) && is_numeric($price["min"])) {
            $this->query = $this->query . "AND price_per_day >= :min_price ";
            $this->queryParameter['min_price'] = $price["min"];
        }
        if (isset($price["max"]) && is_numeric($price["max"])) {
            $this->query = $this->query . "AND price_per_day<= :max_price ";
            $this->queryParameter["max_price"] = $price["max"];
        }
    }
    private function selecteByDate($date)
    {
        $query = "AND parking_lot.id IN (SELECT parking_spot_id FROM rent_car WHERE ";
        $start = false;
        $has_change = false;
        if (isset($date["start"])) {
            try {
                $Date2 = new \DateTime($date["start"]);
                $this->queryParameter["start_date"] = $Date2->format("y-m-d");
                $query = $query . "start_date >= :start_date";
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
                $query .= "end_date <= :end_date";
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
    private function selectByNbplace($nbPlace)
    {
        if (isset($nbPlace) && is_numeric($nbPlace)) {
            $this->query .= " AND nb_places >= :number_places";
            $this->queryParameter["number_places"] = $nbPlace;
        }
    }

}
