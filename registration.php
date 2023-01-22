<?php

require_once ('config.php');

$user = new User();
$user -> userName = $_POST['user_name'];
$user -> email = $_POST['email'];
$user -> password = sha1($_POST['password'].SALT);
$user -> firstName = $_POST['first_name'];
$user -> lastName = $_POST['last_name'];
$user -> save();

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
            <button type="submit" name="submit">Зарегистрироваться</button>
       </div>
   </form>
</div>
</body>
</html>