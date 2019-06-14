<?php


namespace App\Controller;


use App\Entity\Mail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class descriptorService extends MediaTypeController
{
    /**
     * cars
     * @Route("/",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function descriptionService(Request $request){
        if(in_array("text/html",$this->getMimes($request))){
            return $this->render('swaggerAPI.twig',["serveurName"=>$_SERVER['HTTP_HOST']]);
        }
        else{
            $path = __DIR__.'/../../public/resolved/index.json';
            $file= fopen($path,'r');
            return $this->mediaTypeConverter($request,json_decode(fread($file,filesize($path)),true));
        }

    }

}