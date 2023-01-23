<?php

session_start();

require_once ('config.php');

if(!empty($_SESSION['user_id']))
{
    header("location: /index.php");
}

$errors = [];
if(!empty($_POST))
{
    if (empty($_POST['user_name']))
    {
        $errors[]='Введите, пожалуйста, Ваш логин или Email!';
    }
    if (empty($_POST['password']))
    {
        $errors[]='Введите, пожалуйста, Ваш пароль!';
    }
    if(empty($errors) and !empty($_POST['login']))
    {
        $user = new User();
        $user = $user -> checkLogin($_POST['user_name'], sha1($_POST['password'] . SALT));
        if (!empty($user -> id)) {
            $_SESSION['id'] = $user -> id;
            header('location: /index.php');
        } else {
            $errors[] = 'Введение Вами логин или пароль не верный!';
        }
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
    <div>
        <?php foreach ($errors as $error): ?>
            <div>
                <?= $error; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <h1>Страница авторизации</h1>
    <div>
        <form method="post">
            <div>
                <p>Ваш логин или Email:</p>
                <div>
                    <input type="text" name="user_name" value="<?= (!empty($_POST['user_name']) ? $_POST['user_name']: ''); ?>">
                </div>
            </div>
            <div>
                <p>Пароль:</p>
                <div>
                    <input type="password" name="password" value="">
                </div>
            </div>
            <div>
                <br>
                <button type="submit" name="login">Войти</button>
            </div>
        </form>
    </div>
</body>
</html>
