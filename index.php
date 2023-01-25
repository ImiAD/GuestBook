<?php

session_start();

require_once ("config.php");

$errors = [];

if (empty($_SESSION['user_id'])) {
    header('location: /login.php');
}

if(!empty($_POST['exit'])) {
    unset($_SESSION['user_id']);
    header("location: /login.php");
}

if(!empty($_POST['clear'])) {
   $cleaning         = new Comment();
   $cleaning->userId = $_SESSION['user_id'];
   $cleaning->clean();
}

if (!empty($_POST['save'])){
    if (empty($_POST['text'])) {
        $errors[] = 'Введите комментарий.';
    }
}

$comment = new Comment();

if (!empty($_POST['text'])) {
    $comment->text   = $_POST['text'];
    $comment->userId = $_SESSION['user_id'];
    $comment->save();
}
$comments = $comment->findAll();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Комментарии</title>
</head>
<body id="wrapper" style="max-width: 920px; width: 100%;" class="container text-center">
    <div>
        <h1><em>Страница с комментариями</em></h1>
    </div>
    <?php if(!empty($errors)):?>
            <?php foreach ($errors as $error): ?>
                <div class="text-error">
                    <?= $error; ?>
                </div>
            <?php endforeach; ?>
        <?php endif;?>    
    <div>
        <form method="post">
            <div>
                <textarea name="text" placeholder="Напишите Ваш комментарий" class="form-control"></textarea>
            </div>
            <div>
                <br>
                <div><input type="submit" name="save" value="Сохранить" class="btn btn-success col-12"></div>
                <br>
                <div><input type="submit" name="clear" value="Удалить" class="btn btn-danger col-12"></div>
                <br>
                <div><input type="submit" name="exit" value="Выйти" class="btn btn-dark col-12"></div>
            </div>
        </form>
    </div>
    <div>
        <h3>Комментарии:</h3>
        <?php foreach ($comments as $comment): ?>
        <p <?php if ($comment['user_id'] == $_SESSION['user_id']) echo 'style="font-weight: bold;"'; ?>>
            <?= $comment['text']; ?>
            <span>( <?= $comment['created_at']; ?> )</span></p>
        <?php endforeach; ?>
    </div>
</body>
</html>