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

    foreach ($_POST as $key => $value) {
        $validator->checkEmpty($key, $value);
    }

    $validator->checkMaxLen('user_name', $_POST['user_name'], 'users', 'username');
    $validator->checkMaxLen('first_name', $_POST['first_name'], 'users', 'first_name');
    $validator->checkMaxLen('last_name', $_POST['last_name'], 'users', 'last_name');
    $validator->checkMinLen('password', $_POST['password'], MINPASSWORD);
    $validator->checkMatch('password', $_POST['password'], 'confirm_password', $_POST['confirm_password']);
    $errors = $validator->errors;

    if (empty($errors)) {
        $user            = new User();
        $user->userName  = $_POST['user_name'];
        $user->email     = $_POST['email'];
        $user->password  = sha1($_POST['password'].SALT);
        $user->firstName = $_POST['first_name'];
        $user->lastName  = $_POST['last_name'];
        $user->ip        = $_SERVER['SERVER_ADDR'];
        $user->browser   = $_SERVER['HTTP_USER_AGENT'];
        $user->save();
        header('location: /login.php');  
    }
}


?>

<!DOCTYPE html>
<html lang="ru">
<head>
   <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <title>Гостевая книга</title>
</head>
<body id="wrapper" style="max-width: 920px; width: 100%;" class="container text-center">
<h1><em>Страница регистрации</em></h1>
<div>
    <div  class="text-error">
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>
        </div>
   <form method="post">
       <div>
           <p>Ваш логин:</p>
           <input type="text" name="user_name" id="user_name" value="<?= htmlspecialchars(!empty($_POST['user_name']) ? $_POST['user_name']: '', ENT_QUOTES, 'UTF-8'); ?>" class="input">
           <span id="username_error"></span>
       </div>
       <div>
           <p>Ваш e-mail:</p>
           <input type="email" name="email" id="email" value="<?= htmlspecialchars(!empty($_POST['email']) ? $_POST['email']: '', ENT_QUOTES, 'UTF-8'); ?>" class="input">
           <span id="email_error"></span>
       </div>
       <div>
           <p>Ваше имя:</p>
           <input type="text" name="first_name" value="<?= htmlspecialchars(!empty($_POST['first_name']) ? $_POST['first_name']: '', ENT_QUOTES, 'UTF-8'); ?>" class="input">
       </div>
       <div>
           <p>Ваша фамилия:</p>
           <input type="text" name="last_name" value="<?= htmlspecialchars(!empty($_POST['last_name']) ? $_POST['last_name']: '', ENT_QUOTES, 'UTF-8'); ?>" class="input">
       </div>
       <div>
           <p>Ваш пароль:</p>
           <input type="password" name="password" value="" class="input">
       </div>
       <div>
           <p>Повторите Ваш пароль:</p>
           <input type="password" name="confirm_password" value="" class="input">
       </div>
       <div>
            <br>
            <div><input type="submit" name="submit" id="submit" value="Зарегистрироваться" class="btn btn-success col-3"></div>
            <br>
            <div><input type="submit" name="login" value="Авторизация" class="btn btn-primary col-3"></div>
       </div>
   </form>
</div>
<script type="text/javascript" src="/js/check.js"></script>
</body>
</html>