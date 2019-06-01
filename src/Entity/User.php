<?php

namespace App\Entity;


class User implements UserInterface
{
    public function checkUserEmailRequest($email, $login_id)
    {
        $db = SModel::getInstance();
        $query = "SELECT DISTINCT id FROM user WHERE email = :email AND login_id = :login_id";
        $prep = $db->prepare($query);
        $prep->bindValue("email", $email);
        $prep->bindValue("login_id", $login_id);
        $prep->execute();
        $result = $prep->fetchAll(\PDO::FETCH_ASSOC);
        return count($result);
    }
    public function checkUserPasswordRequest($email, $password)
    {
        $db = SModel::getInstance();
        $query = "SELECT DISTINCT password FROM user WHERE email = :email";
        $prep = $db->prepare($query);
        $prep->bindValue("email", $email);
        $prep->execute();
        $retrieved_pwd = $prep->fetchAll(\PDO::FETCH_ASSOC)[0]["password"];
        return $password === $retrieved_pwd ? true : false;
    }
    public function insertUserRequest($args)
    {
        $db = SModel::getInstance();
        if ($args["login_id"] === 1) {
            $query = "INSERT INTO user (firstname, lastname, email, phone, password, login_id) VALUES (:firstname, :lastname, :email, :phone, :password, :login_id)";
        } else {
            $query = "INSERT INTO user (firstname, lastname, email, login_id) VALUES (:firstname, :lastname, :email, :login_id)";
        }
        $prep = $db->prepare($query);
        foreach ($args as $key => $value) {
            $prep->bindValue($key, $value);
        }
        $prep->execute();
    }
    public function updateUserRequest($firstname, $lastname, $email, $phone, $password, $new_password, $id)
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
