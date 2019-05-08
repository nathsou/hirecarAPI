<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends RequestDBController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * users
     * @Route("/users",methods={"POST"})
     * condition="context.getMethod() in ['POST']
     */
    public function insertUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $firstname = $data["firstname"];
        $lastname = $data["lastname"];
        $email = $data["email"];
        $phone = $data["phone"];
        $password = hash('sha256', $data["password"]);
        if (
            isset($firstname)
            && isset($lastname)
            && isset($email)
            && isset($phone)
            && isset($password)
        ) {
            $requestDB = new User();
            $requestDB->insertUserRequest($firstname, $lastname, $email, $phone, $password);
            return $this->mediatypeConverteur($request, ["etat" => "ok"]);
        }
        return $this->mediatypeConverteur($request, ["etat" => "error"]);
    }

    /**
     * users
     * @Route("/users/{id}",methods={"PUT"})
     * condition="context.getMethod() in ['PUT']
     */
    public function updateUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $firstname = $data["firstname"];
        $lastname = $data["lastname"];
        $email = $data["email"];
        $phone = $data["phone"];
        $password = hash('sha256', $data["password"]);
        $id = $request->get('id');
        if (
            isset($firstname)
            && isset($lastname)
            && isset($email)
            && isset($phone)
            && isset($password)
            && isset($id) && is_numeric($id)
        ) {
            $requestDB = new User();
            $requestDB->updateUserRequest($firstname, $lastname, $email, $phone, $password, $id);
            return $this->mediatypeConverteur($request, ["etat" => "ok"]);
        }
        return $this->mediatypeConverteur($request, ["etat" => "error"]);
    }
}
