<?php

class User extends DB
{
    public $id;
    public $userName;
    public $password;
    public $email;
    public $firstName;
    public $lastName;

    public function save()
    {
        $stmt = $this -> conn -> prepare('INSERT INTO users(`username`, `email`, `password`, `first_name`, `last_name`)
                                          VALUES (:username, :email, :password, :first_name, :last_name)');
        $stmt->execute([
            'username' => $this -> userName,
            'email' => $this -> email,
            'password' => $this -> password,
            'first_name' => $this -> firstName,
            'last_name' => $this -> lastName
        ]);
        $this -> id = $this -> conn -> lastInsertId();
        return $this -> id;
    }
    public function find($id)
    {
        $stmt = $this -> conn -> prepare('SELECT * FROM users WHERE id = :id');
        $stmt -> execute(['id' => $this -> id]);
        $user = $stmt -> fetch(PDO::FETCH_LAZY);
        if(!empty($user)) {
            $this -> id = $id;
            $this -> userName = $user -> user_name;
            $this -> email = $user -> email;
            $this -> firstName = $user -> first_name;
            $this -> lastName = $user -> last_name;
            return $this;
        }
    }

    public function checkLogin($userName, $password)
    {
        $stmt = $this -> conn -> prepare('SELECT * FROM  users
                                        WHERE (username = :username or email = :username)
                                        and password = :password');
        $stmt -> execute(['username' => $userName, 'password' => $password]);
        $user = $stmt -> fetch(PDO::FETCH_LAZY);
        if(!empty($user)) {
            $this -> id = $user -> id;
            $this -> userName = $user -> user_name;
            $this -> email = $user -> email;
            $this -> firstName = $user -> first_name;
            $this -> lastName = $user -> last_name;
            return $this;
        }
        else return false;
    }

}