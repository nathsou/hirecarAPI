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
                if ($requestDB->checkUserEmailRequest($email) === 1) {
                    $user = $requestDB->getUserRequest($email);
                    return new Response(json_encode($user), Response::HTTP_OK);
                } else {
                    return new Response('{"email_error" : "L\'email saisi n\'existe pas"}', Response::HTTP_BAD_REQUEST);
                }
            }
            return $this->mediaTypeConverter($request);
        }

        return new Response('', Response::HTTP_BAD_REQUEST);
    }
}
