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
            && array_key_exists("login", $data)
        ) {
            $firstname = $data["firstname"];
            $lastname = $data["lastname"];
            $email = $data["email"];
            $phone = $data["phone"];
            $password = $data["password"];
            $login_id = $data["login"]["id"];
            if (
                isset($firstname)
                && isset($lastname)
                && isset($email)
                && isset($phone)
                && isset($password)
                && isset($login_id)
            ) {
                $user = new User();
                $args = array(
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "email" => $email,
                    "phone" => $phone,
                    "password" => $password,
                    "login_id" => $login_id
                );
                if ($user->checkUserEmailRequest($email, $login_id) === 0) {
                    $user->insertUserRequest($args);
                    return $this->mediaTypeConverter($request);
                } else {
                    return new Response('{"email_error" : "L\'email est déjà utilisé par un autre utilisateur"}', Response::HTTP_CONFLICT);
                }
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
            && array_key_exists("new_password", $data)
        ) {
            $firstname = $data["firstname"];
            $lastname = $data["lastname"];
            $email = $data["email"];
            $phone = $data["phone"];
            $password = $data["password"];
            $new_password = $data["new_password"];
            $id = $request->get("id");
            if (
                isset($firstname) && !empty($firstname)
                && isset($lastname) && !empty($lastname)
                && isset($email) && !empty($email)
                && isset($phone) && !empty($phone)
                && isset($password)
                && isset($new_password)
                && isset($id) && is_numeric($id)
            ) {
                $user = new User();
                if ($user->checkUserPasswordRequest($email, $password)) {
                    $user->updateUserRequest($firstname, $lastname, $email, $phone, $password, $new_password, $id);
                    return new Response('', Response::HTTP_OK);
                } else {
                    return new Response('{"password_error" : "Le mot de passe actuel saisi est incorrect"}', Response::HTTP_BAD_REQUEST);
                }

                return $this->mediaTypeConverter($request);
            }
        }
        return new Response('', Response::HTTP_BAD_REQUEST);
    }
}
