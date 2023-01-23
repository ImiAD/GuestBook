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
        $validator -> checkEmpty($key, $value);
    }
    $validator -> checkMaxLen('user_name', $_POST['user_name'], 'users', 'username');
    $validator -> checkMaxLen('first_name', $_POST['first_name'], 'users', 'first_name');
    $validator -> checkMaxLen('last_name', $_POST['last_name'], 'users', 'last_name');
    $validator -> checkMinLen('password', $_POST['password'], 4);
    $validator -> checkMatch('password', $_POST['password'], 'confirm_password', $_POST['confirm_password']);
    $errors = $validator -> errors;
    if (empty($errors)) {
        $user = new User();
        $user -> userName = $_POST['user_name'];
        $user -> email = $_POST['email'];
        $user -> password = sha1($_POST['password'] . SALT);
        $user -> firstName = $_POST['first_name'];
        $user -> lastName = $_POST['last_name'];
        $user -> save();
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
   <form method="post">
        <div>
            <?php foreach ($errors as $error): ?>
                <p><?= $error; ?></p>
            <?php endforeach; ?>
        </div>
       <div>
           <p>Логин:</p>
           <input type="text" name="user_name" require="" value="<?= (!empty($_POST['user_name']) ? $_POST['user_name']: ''); ?>">
       </div>
       <div>
           <p>E-mail:</p>
           <input type="email" name="email" require="" value="<?= (!empty($_POST['email']) ? $_POST['email']: ''); ?>">
       </div>
       <div>
           <p>Имя:</p>
           <input type="text" name="first_name" require="" value="<?= (!empty($_POST['first_name']) ? $_POST['first_name']: ''); ?>">
       </div>
       <div>
           <p>Фамилия:</p>
           <input type="text" name="last_name" require="" value="<?= (!empty($_POST['last_name']) ? $_POST['last_name']: ''); ?>">
       </div>
       <div>
           <p>Пароль:</p>
           <input type="password" name="password" require="" value="">
       </div>
       <div>
           <p>Повторите пароль:</p>
           <input type="password" name="confirm_password" require="" value="">
       </div>
       <div>
            <br>
            <div><input type="submit" name="submit" value="Зарегистрироваться"></div>
            <br>
            <div><input type="submit" name="login" value="Авторизация"></div>
       </div>
   </form>
</div>
</body>
</html>