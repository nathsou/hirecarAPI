<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends MediaTypeController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * login
     * @Route("/login",methods={"POST"})
     * condition="context.getMethod() in ['POST']
     */
    public function login(Request $request)
    {
        $data = $this->inputMediaTypeConverter($request);

        if (array_key_exists("login", $data)) {
            $login_type = $data["login"]["type"];

            switch ($login_type) {
                case "Google":
                    return $this->socialMediaSignIn($request);
                case "Facebook":
                    return $this->socialMediaSignIn($request);
                default:
                    return $this->getUserHash($request);
            }
        }
    }

    public function socialMediaSignIn(Request $request)
    {
        $data = $this->inputMediaTypeConverter($request);
        if (
            array_key_exists("email", $data)
            && array_key_exists("firstname", $data)
            && array_key_exists("lastname", $data)
            && array_key_exists("login", $data)
        ) {
            $email = $data["email"];
            $firstname = $data["firstname"];
            $lastname = $data["lastname"];
            $login_id = $data["login"]["id"];
            if (
                isset($email)
                && isset($firstname)
                && isset($lastname)
            ) {
                $user = new User();
                $args = array(
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "email" => $email,
                    "login_id" => $login_id
                );
                if ($user->checkUserEmailRequest($email, $login_id) === 1) {
                    $user_data = $user->getUserRequest($email);
                    return new Response(json_encode($user_data), Response::HTTP_OK);
                } else {
                    $user_data = $user->insertUserRequest($args);
                    $user_data = $user->getUserRequest($email);
                    return new Response(json_encode($user_data), Response::HTTP_OK);
                }
            }
            return $this->mediaTypeConverter($request);
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }

    public function getUserHash(Request $request)
    {
        $data = $this->inputMediaTypeConverter($request);

        if (
            array_key_exists("email", $data)
            && array_key_exists("password", $data)
            && array_key_exists("login", $data)
        ) {
            $email = $data["email"];
            $password = $data["password"];
            $login_id = $data["login"]["id"];
            if (
                isset($email)
                && isset($password)
            ) {
                $user = new User();
                if ($user->checkUserEmailRequest($email, $login_id) === 1) {
                    if ($user->checkUserPasswordRequest($email, $password)) {
                        $user_data = $user->getUserRequest($email);
                        return new Response(json_encode($user_data), Response::HTTP_OK);
                    } else {
                        return new Response('{"password_error" : "Le mot de passe saisi est incorrect"}', Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return new Response('{"email_error" : "L\'email saisi n\'existe pas"}', Response::HTTP_BAD_REQUEST);
                }
            }
            return $this->mediaTypeConverter($request);
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }
}
