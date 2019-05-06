<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Users extends RequetageBDD
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
        $this->query = "INSERT INTO user (firstname, lastname, email, phone, password) VALUES (:firstname, :lastname, :email, :phone, :password)";

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
            $prep = $this->bdd->prepare($this->query);
            $prep->bindValue("firstname", $firstname);
            $prep->bindValue("lastname", $lastname);
            $prep->bindValue("email", $email);
            $prep->bindValue("phone", $phone);
            $prep->bindValue("password", $password);
            $prep->execute();
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
        $this->query = "UPDATE user SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone, password = :password WHERE id = :id";

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
        ) {
            $prep = $this->bdd->prepare($this->query);
            $prep->bindValue("firstname", $firstname);
            $prep->bindValue("lastname", $lastname);
            $prep->bindValue("email", $email);
            $prep->bindValue("phone", $phone);
            $prep->bindValue("password", $password);
            $prep->bindValue("id", $id);
            $prep->execute();
            return $this->mediatypeConverteur($request, ["etat" => "ok"]);
        }
        return $this->mediatypeConverteur($request, ["etat" => "error"]);
    }
}
