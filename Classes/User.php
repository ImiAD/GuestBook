<?php
/**
 * Класс регистрации и манипуляции пользователями
 */

class User extends DB
{
    public $id;
    public $userName;
    public $password;
    public $email;
    public $firstName;
    public $lastName;
    public $ip;
    public $browser;

    /**
     *Сохранение данных пользователя базу данных при регистрации
     * @return false|string
     */
    public function save()
    {
        $stmt = $this->conn->prepare('INSERT INTO users(`username`, `email`, `password`, `first_name`, `last_name`, `ip`, `browser`)
                                            VALUES (:username, :email, :password, :first_name, :last_name, :ip, :browser)');
        $stmt->execute([
            'username'   => $this->userName,
            'email'      => $this->email,
            'password'   => $this->password,
            'first_name' => $this->firstName,
            'last_name'  => $this->lastName,
            'ip'         => $this->ip,
            'browser'    => $this->browser
        ]);
        $this->id = $this->conn->lastInsertId();

        return $this->id;
    }

    /**
     * Позволяет авторизироваться по логину или e-mail
     * @param $userName
     * @param $password
     * @return $this|false
     */
    public function checkLogin($userName, $password)
    {
        $stmt = $this->conn->prepare('SELECT * FROM  users
                                            WHERE (username = :username or email = :username)
                                            and password = :password');
        $stmt->execute(['username' => $userName, 'password' => $password]);
        $user = $stmt->fetch(PDO::FETCH_LAZY);

        if(!empty($user)) {
            $this->id        = $user->id;
            $this->userName  = $user->user_name;
            $this->email     = $user->email;
            $this->firstName = $user->first_name;
            $this->lastName  = $user->last_name;

            return $this;
        }
        else {
            return false;
        }
    }

    /**
     * Метод необходим для проверки на уникальность логина пользователя. Возвращает данные пользователя(логин),
     * если он уже существует в базе данных или 0, если - отсутствует.
     * @param $userName
     * @return int
     */
    public function getUserName($userName)
    {
        $stmt = $this->conn->prepare('SELECT username FROM users WHERE username = :username');
        $stmt->execute(['username' => $userName]);
        $user = $stmt->fetch(PDO::FETCH_LAZY);

        if (!empty($user->username)) {
            return $user->username;
        } else {
            return 0;
        }
    }

    /**
     * Метод необходим для проверки на уникальность e-mail пользователя. Возвращает данные пользователя(e-mail),
     * если он уже существует в базе данных или 0, если - отсутствует.
     * @param $email
     * @return int
     */
    public function getEmail($email)
    {
        $stmt = $this->conn->prepare('SELECT email FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_LAZY);

        if (!empty($user->email)) {
            return $user->email;
        } else {
            return 0;
        }
    }
}