<?php

session_start();

require_once ("config.php");

if (empty($_SESSION['user_id'])) {
    header('location: /login.php');
}

$comment = new Comment();
if (!empty($_POST['text'])) {
    $comment -> text = $_POST['text'];
    $comment -> userId = $_SESSION['user_id'];
    $comment -> save();
}
$comments = $comment -> findAll();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Комментарии</title>
</head>
<body>
    <div>
        <h1>Страница с комментариями</h1>
    </div>
    <div>
        <h1>Добавьте свой комментарий</h1>
        <form method="post">
            <div>
                <p>Комментарий</p>
                <div>
                    <textarea name="text"></textarea>
                </div>
            </div>
            <div>
                <br>
                <button type="submit" name="save" value="Сохранить">Сохранить</button>
            </div>
        </form>
    </div>
    <div>
        <h3>Комментарии:</h3>
        <?php foreach ($comments as $comment): ?>
        <p <?php if ($comment['user_id'] == $_SESSION['user_id']) echo 'style="font-weight: bold;"'; ?>>
            <?= $comment['text']; ?>
            <span>(<?= $comment['created_at']; ?>)</span></p>
        <?php endforeach; ?>
    </div>
</body>
</html>