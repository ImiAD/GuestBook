<?php

/**
 * Класс проверки полей input при регистрации пользователей
 */
class Validator
{
    private $_db;
    public $errors = [];

    /**
     * Принимает объект класса подключение к базе данных
     * @param $db
     */
    public function __construct($db)
    {
       $this->_db = $db;
    }

    /**
     * Метод проверки полей формы на пустоту
     * @param $name
     * @param $value
     * @return int|string
     */
    public function checkEmpty($name, $value)
    {
        $name = ucfirst(str_replace("_", " ", $name));

        if (empty($value)) {
            return $this->errors[] = "Пожалустай, заполните поле ". $name;
        } else {
            return 0;
        }
    }

    /**
     * Проверка полей пароля и подтверждения пароля при регистрации пользователя
     * @param $name1
     * @param $value1
     * @param $name2
     * @param $value2
     * @return int|string
     */
    public function checkMatch($name1, $value1, $name2, $value2)
    {
        $name1 = ucfirst(str_replace("_", " ", $name1));
        $name2 = ucfirst(str_replace("_", " ", $name2));

        if ($value1 !== $value2) {
            return $this->errors[] = "Ваш ".$name2." не соответствует ".$name1.'!';
        } else {
            return 0;
        }
    }

    /**
     * Подключение к базе данных через метод getMaxLen($table, $column) класса DB
     * и проверяем максимальную длину заполненных полей формы
     * @param $name
     * @param $value
     * @param $table
     * @param $column
     * @return int|string
     */
    public function checkMaxLen($name, $value, $table, $column)
    {
        $name   = ucfirst(str_replace("_", " ", $name));
        $maxLen = $this->_db->getMaxLen($table, $column);

        if(strlen($value) > $maxLen) {
            return $this->errors[] = $name." слишком много символов".$maxLen." максимальная длинна!";
        } else {
            return 0;
        }
    }

    /**
     * Проверка минимальной длины поля. Длину поля можно указывать в константе MINPASSWORD в файле congif.php
     * @param $name
     * @param $value
     * @param int $int
     * @return string|void
     */
    public function checkMinLen($name, $value, int $int)
    {
        $name = ucfirst(str_replace("_", " ", $name));

        if(strlen($value) < $int) {
            return $this->errors[] = $name." слишком короткий! ".$int." минимальное количество символов!";
        }
    }

    /**
     * Проверяем логин пользователя на уникальность. Используем метод getUserName($userName) класса User
     * для получения данных о пользователе(логина) в базе данных
     * @param $userName
     * @return false|string
     */
    public function isUsernameAvailable($userName)
    {
        $isExist = $this->_db->getUserName($userName);

        if(!$isExist) {
            return false;
        } else {
            return $this->errors = $userName." не доступен для использования!";
        }
    }

    /**
     * Проверяем e-mail пользователя на уникальность. Используем метод getEmail($email) класса User
     * для получения данных о пользователе(e-mail) в базе данных
     * @param $email
     * @return false|string
     */
    public function isEmailAvailable($email)
    {
        $isExist = $this->_db->getEmail($email);

        if(!$isExist) {
            return false;
        } else {
            return $this->errors = $email." не доступен для использования!";
        }
    }
}