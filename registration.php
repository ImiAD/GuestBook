<?php

require_once ('config.php');

if(!empty($_SESSION['user_id'])) {
    header("location: /index.php");
}

if (!empty($_POST['login'])) {
    unset($_SESSION['user_id']);
    header("location: /login.php");
}

$errors = [];
if(!empty($_POST['submit'])) {
    $validator = new Validator(new DB());
    $validator->checkMaxLen('user_name', $_POST['user_name'], 'users', 'username');
    $validator->checkMaxLen('first_name', $_POST['first_name'], 'users', 'first_name');
    $validator->checkMaxLen('last_name', $_POST['last_name'], 'users', 'last_name');
    $validator->checkMinLen('password', $_POST['password'], MINPASSWORD);
    $validator->checkMatch('password', $_POST['password'], 'confirm_password', $_POST['confirm_password']);
    $errors = $validator->errors;
    if (empty($errors)) {
        $user = new User();
        $user->userName = $_POST['user_name'];
        $user->email = $_POST['email'];
        $user->password = sha1($_POST['password'] . SALT);
        $user->firstName = $_POST['first_name'];
        $user->lastName = $_POST['last_name'];
        $user->ip = $_SERVER['SERVER_ADDR'];
        $user->browser = $_SERVER['HTTP_USER_AGENT'];
        $user->homePage = $_POST['home_page'];
        $user->save();
        header('location: /login.php');  
    }
}


?>

<!DOCTYPE html>
<html lang="ru">
<head>
   <meta charset="UTF-8">
   <title>Гостевая книга</title>
</head>
<body>
<h1>Страница регистрации</h1>
<div>
    <div>
        <?php foreach ($errors as $error): ?>
            <p><?= $error; ?></p>
            <?php endforeach; ?>
        </div>
   <form method="post">
       <div>
           <p>Логин:</p>
           <input type="text" name="user_name" id="user_name" required value="<?= (!empty($_POST['user_name']) ? $_POST['user_name']: ''); ?>">
           <span id="username_error"></span>
       </div>
       <div>
           <p>E-mail:</p>
           <input type="email" name="email" id="email" required value="<?= (!empty($_POST['email']) ? $_POST['email']: ''); ?>">
           <span id="email_error"></span>
       </div>
       <div>
           <p>Имя:</p>
           <input type="text" name="first_name" required value="<?= (!empty($_POST['first_name']) ? $_POST['first_name']: ''); ?>">
       </div>
       <div>
           <p>Фамилия:</p>
           <input type="text" name="last_name" required value="<?= (!empty($_POST['last_name']) ? $_POST['last_name']: ''); ?>">
       </div>
       <div>
           <p>Домашняя страница:</p>
           <input type="text" name="home_page" value="">
       </div>
       <div>
           <p>Пароль:</p>
           <input type="password" name="password" required value="">
       </div>
       <div>
           <p>Повторите пароль:</p>
           <input type="password" name="confirm_password" required value="">
       </div>
       <div>
            <br>
            <div><input type="submit" name="submit" id="submit" value="Зарегистрироваться"></div>
            <br>
            <div><input type="submit" name="login" value="Авторизация"></div>
       </div>
   </form>
</div>
<script type="text/javascript" src="/js/check.js"></script>
</body>
</html>