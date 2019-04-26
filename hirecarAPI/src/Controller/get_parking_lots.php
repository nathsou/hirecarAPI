<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 2019-04-26
 * Time: 19:44
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class get_parking_lots extends Controller
{
    /**
     * get_parking_lots
     * @Route("/get_parking_lots")
     */
    public function get_parking_lots(Request $request){
        $center_lat=$request->query->get('center_lat');
        $center_lng=$request->query->get('center_lng');
        $radius=$request->query->get('radius');
        if (is_numeric($center_lat) && is_numeric($center_lng) && is_numeric($radius)){
            $BDD= new \PDO('mysql:host=mysql-lo07.alwaysdata.net;dbname=lo07_hirecar','lo07','4RO35sq!wAw9QruS$');
            $prep= $BDD->prepare("SELECT * FROM `parking_lot` WHERE (SQRT(POW((`lat`-:lat),2)+POW((`lng`-:long),2))) < :radius ");
            $center_lng = (float)$center_lng;
            $radius= (float)$radius;
            $center_lat=(float)$center_lat;
            $prep->bindValue(':lat',$center_lat);
            $prep->bindValue(':long',$center_lng);
            $prep->bindValue('radius',$radius);
            $prep->execute();
             return $this->json(['airports'=>$prep->fetchAll()]);
        }
        else{
            return $this->json(['error'=>'les données fournies ne sont pas des nombres']);
        }
    }
}