<?php

namespace App\Entity;


interface UserInterface
{
    public function insertUserRequest($firstname, $lastname, $email, $phone, $password);
    public function updateUserRequest($firstname, $lastname, $email, $phone, $password, $id);
}
