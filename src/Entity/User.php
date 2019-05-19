<?php

namespace App\Entity;


class User implements UserInterface
{
    public function insertUserRequest($firstname, $lastname, $email, $phone, $password)
    {
        $db = SModel::getInstance();
        $query = "INSERT INTO user (firstname, lastname, email, phone, password) VALUES (:firstname, :lastname, :email, :phone, :password)";
        $prep = $db->prepare($query);
        $prep->bindValue("firstname", $firstname);
        $prep->bindValue("lastname", $lastname);
        $prep->bindValue("email", $email);
        $prep->bindValue("phone", $phone);
        $prep->bindValue("password", $password);
        $prep->execute();
    }
    public function updateUserRequest($firstname, $lastname, $email, $phone, $password, $id)
    {
        $db = SModel::getInstance();
        $query = "UPDATE user SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone, password = :password WHERE id = :id";
        $prep = $db->prepare($query);
        $prep->bindValue("firstname", $firstname);
        $prep->bindValue("lastname", $lastname);
        $prep->bindValue("email", $email);
        $prep->bindValue("phone", $phone);
        $prep->bindValue("password", $password);
        $prep->bindValue("id", $id);
        $prep->execute();
    }
}