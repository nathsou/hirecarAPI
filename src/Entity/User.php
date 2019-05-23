<?php

namespace App\Entity;


class User implements UserInterface
{
    public function checkUserEmailRequest($email)
    {
        $db = SModel::getInstance();
        $query = "SELECT DISTINCT id FROM user WHERE email = :email";
        $prep = $db->prepare($query);
        $prep->bindValue("email", $email);
        $prep->execute();
        $result = $prep->fetchAll(\PDO::FETCH_ASSOC);
        return count($result);
    }
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
    public function updateUserRequest($firstname, $lastname, $email, $phone, $new_password, $id)
    {
        $db = SModel::getInstance();
        if (empty($new_password)) {
            $query = "UPDATE user SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone WHERE id = :id";
        } else {
            $query = "UPDATE user SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone, password = :new_password WHERE id = :id";
        }
        $prep = $db->prepare($query);
        $prep->bindValue("firstname", $firstname);
        $prep->bindValue("lastname", $lastname);
        $prep->bindValue("email", $email);
        $prep->bindValue("phone", $phone);
        empty($new_password) ? null : $prep->bindValue("new_password", $new_password);
        $prep->bindValue("id", $id);
        $prep->execute();
    }
    public function getUserRequest($email)
    {
        $db = SModel::getInstance();
        $query = "SELECT id, firstname, lastname, password, email, phone FROM user WHERE email = :email";
        $prep = $db->prepare($query);
        $prep->bindValue("email", $email);
        $prep->execute();
        $result = $prep->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }
}
