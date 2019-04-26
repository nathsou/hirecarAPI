<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 2019-04-26
 * Time: 19:44
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class get_parking_lots extends AbstractController
{
    private $bdd;
    private $query;
    private $queryParameter;

    public function __construct(){
        $this->bdd=new \PDO('mysql:host=mysql-lo07.alwaysdata.net;dbname=lo07_hirecar','lo07','4RO35sq!wAw9QruS$');
        $this->query="SELECT * FROM `parking_lot` WHERE (SQRT(POW((`lat`-:lat),2)+POW((`lng`-:long),2))) < :radius ";
        $this->queryParameter=[];
    }
    /**
     * get_parking_lots
     * @Route("/get_parking_lots")
     *  condition="context.getMethod() in ['GET']
     */
    public function get_parking_lots(Request $request){
        $center_lat=$request->query->get('center_lat');
        $center_lng=$request->query->get('center_lng');
        $radius=$request->query->get('radius');
        if (is_numeric($center_lat) && is_numeric($center_lng) && is_numeric($radius)){
            $this->queryParameter[':long'] = (float)$center_lng;
            $this->queryParameter[':radius']= (float)$radius;
            $this->queryParameter[':lat']=(float)$center_lat;
            $this->selectByPrice(["min"=>$request->query->get('min_price'),
                                    "max"=>$request->query->get('max_price')]);
            $this->selecteByDate([
                "start"=>$request->query->get('start_date'),
                "end"=>$request->query->get('end_date')
            ]);
            $this->selectByNbplace($request->query->get("number_places"));
            $prep= $this->bdd->prepare($this->query);
            foreach ($this->queryParameter as $key=>$value){
                $prep->bindValue($key,$value);
            }
            $prep->execute();
             return $this->json(['airports'=>$prep->fetchAll(),
                                   'queryParameter' =>$this->queryParameter,"query"=>$this->query]);
        }
        else{
            return $this->json(['error'=>'les données fournies ne sont pas des nombres']);
        }
    }
    private function selectByPrice($price){
        if (isset($price["min"]) && is_numeric($price["min"]) ){
            $this->query=$this->query."AND price_per_day > :min_price ";
            $this->queryParameter['min_price']=$price["min"];
        }
        if(isset($price["max"]) && is_numeric($price["max"])){
            $this->query=$this->query."AND price_per_day< :max_price ";
            $this->queryParameter["max_price"]=$price["max"];
        }
    }
    private function selecteByDate($date){
        $query= "AND parking_lot.id IN (SELECT parking_spot_id FROM rent_car WHERE ";
        $start =false;
        $has_change=false;
        if (isset($date["start"])){
            try{
                 $Date2= new \DateTime($date["start"]);
                $this->queryParameter["start_date"]=$Date2->format("y-m-d");
                $query = $query."start_date > :start_date";
                $has_change=true;
                $start=true;
            }catch (\Exception $e){

            }
        }
        if (isset($date["end"])){
            try{
                $date =new \DateTime($date["end"]);
                if ($start){
                    $query.=" AND ";
                }
                $query.="end_date < :end_date";
                $date2=\DateTime($date['end']);
                $this->queryParameter["end_date"]=$date2;
                $has_change=true;
            }catch (\Exception $e){

            }
        }
        if($has_change){
            $query.=") ";
            $this->query.=$query;
        }
    }
    private function selectByNbplace($nbPlace){
        if (isset($nbPlace) && is_numeric($nbPlace)){
            $this->query.=" AND nb_places > :number_places";
            $this->queryParameter["number_places"]=$nbPlace;
        }
    }
}