<?php

session_start();

require_once ('config.php');

if (!empty($_POST['goRegist'])) {
    unset($_SESSION['user_id']);
    header("location: /registration.php");
}

if(!empty($_SESSION['user_id'])) {
    header("location: /index.php");
}

$errors = [];
if(!empty($_POST)) {
    if (empty($_POST['user_name'])) {
        $errors[] = 'Введите, пожалуйста, Ваш логин или Email!';
    }
    if (empty($_POST['password'])) {
        $errors[] = 'Введите, пожалуйста, Ваш пароль!';
    }
    if(empty($errors) and !empty($_POST['login'])) {
        $user = new User();
        $user = $user->checkLogin($_POST['user_name'], sha1($_POST['password'].SALT));
        if (!empty($user->id)) {
            $_SESSION['user_id'] = $user->id;
            header('location: /index.php');
        } else {
            $errors[] = 'Введеннные Вами логин или пароль не верные!';
        }
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
    <h1><em>Страница авторизации</em></h1>
    <div>
        <?php foreach ($errors as $error): ?>
            <div class="text-error">
                <?= $error; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div>
        <form method="post">
            <div>
                <p>Введите логин или E-mail:</p>
                <div>
                    <input type="text" name="user_name" value="<?= (!empty($_POST['user_name']) ? $_POST['user_name']: ''); ?>" class="input"
                </div>
            </div>
            <div>
                <p>Пароль:</p>
                <div>
                    <input type="password" name="password" value="" class="input">
                </div>
            </div>
            <div>
                <br>
                <input type="submit" name="login" value="Войти" class="btn btn-success col-3">
            </div>
            <div>
                <br>
                <div><input type="submit" name="goRegist" value="Регстрация" class="btn btn-primary col-3"></div>
            </div>
        </form>
    </div>
</body>
</html>
