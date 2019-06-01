<?php

namespace App\Entity;


interface UserInterface
{
    public function insertUserRequest($args);
    public function updateUserRequest($firstname, $lastname, $email, $phone, $password, $new_password, $id);
}
