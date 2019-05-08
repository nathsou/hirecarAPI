<?php

namespace App\Entity;


class User extends ConnectionDB implements UserInterface
{
    public function insertUserRequest($firstname, $lastname, $email, $phone, $password)
    {
        $this->query = "INSERT INTO user (firstname, lastname, email, phone, password) VALUES (:firstname, :lastname, :email, :phone, :password)";
        $prep = $this->bdd->prepare($this->query);
        $prep->bindValue("firstname", $firstname);
        $prep->bindValue("lastname", $lastname);
        $prep->bindValue("email", $email);
        $prep->bindValue("phone", $phone);
        $prep->bindValue("password", $password);
        $prep->execute();
    }
    public function updateUserRequest($firstname, $lastname, $email, $phone, $password, $id)
    {
        $this->query = "UPDATE user SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone, password = :password WHERE id = :id";
        $prep = $this->bdd->prepare($this->query);
        $prep->bindValue("firstname", $firstname);
        $prep->bindValue("lastname", $lastname);
        $prep->bindValue("email", $email);
        $prep->bindValue("phone", $phone);
        $prep->bindValue("password", $password);
        $prep->bindValue("id", $id);
        $prep->execute();
    }
}
