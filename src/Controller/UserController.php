<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserController extends MediaTypeController
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
        $data = $this->inputMediaTypeConverter($request);

        if (
            array_key_exists("firstname", $data)
            && array_key_exists("lastname", $data)
            && array_key_exists("email", $data)
            && array_key_exists("phone", $data)
            && array_key_exists("password", $data)
        ) {
            $firstname = $data["firstname"];
            $lastname = $data["lastname"];
            $email = $data["email"];
            $phone = $data["phone"];
            $password = $data["password"];

            if (
                isset($firstname)
                && isset($lastname)
                && isset($email)
                && isset($phone)
                && isset($password)
            ) {
                $requestDB = new User();
                $requestDB->insertUserRequest($firstname, $lastname, $email, $phone, $password);

                // if ($requestDB->checkUserEmailRequest($email) === 0) {
                //     $requestDB->insertUserRequest($firstname, $lastname, $email, $phone, $password);
                //     return $this->mediaTypeConverter($request);
                // } else {
                //     return new Response('{"email_error" : "L\'email est déjà utilisé par un autre utilisateur"}', Response::HTTP_CONFLICT);
                // }
            }

            return $this->mediaTypeConverter($request);
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    /**
     * users
     * @Route("/users/{id}",methods={"PUT"})
     * condition="context.getMethod() in ['PUT']
     */
    public function updateUser(Request $request)
    {
        $data = $this->inputMediaTypeConverter($request);

        if ($data === NULL) {
            return $this->mediaTypeConverter($request, '', Response::HTTP_NO_CONTENT);
        }

        if (
            array_key_exists("firstname", $data)
            && array_key_exists("lastname", $data)
            && array_key_exists("email", $data)
            && array_key_exists("phone", $data)
            && array_key_exists("password", $data)
        ) {
            $firstname = $data["firstname"];
            $lastname = $data["lastname"];
            $email = $data["email"];
            $phone = $data["phone"];
            $password = $data["password"];
            $id = $request->get("id");
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
                return $this->mediaTypeConverter($request);
            }
        }
        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    /**
     * login
     * @Route("/login",methods={"POST"})
     * condition="context.getMethod() in ['POST']
     */
    public function getUserHash(Request $request)
    {
        $data = $this->inputMediaTypeConverter($request);

        if (
            array_key_exists("email", $data)
            && array_key_exists("password", $data)
        ) {
            $email = $data["email"];
            $password = $data["password"];

            if (
                isset($email)
                && isset($password)
            ) {
                $requestDB = new User();
            }

            return $this->mediaTypeConverter($request);
        }


        return new Response('', Response::HTTP_BAD_REQUEST);
    }
}
