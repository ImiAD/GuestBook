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
   $cleaning = new Comment();
   $cleaning -> userId = $_SESSION['user_id'];
   $cleaning -> clean();
}

if (!empty($_POST['save'])){
    if (empty($_POST['text'])) {
        $errors[] = 'Введите комментарий.';
    }
}

$comment = new Comment();
if (!empty($_POST['text'])) {
    $comment->text = $_POST['text'];
    $comment->userId = $_SESSION['user_id'];
    $comment->save();
}
$comments = $comment->findAll();

$_POST['page'] = (int)$_POST['page'];
$pageCount = (new Comment())->lenPage();
if(empty($_POST['page'] )) {
    $_POST['page'] = 1;
}
if (!empty($_POST['end'])) {
    $_POST['page'] = (int)$pageCount;
}
if (!empty($_POST['beginning'])) {
    $_POST['page'] = 1;
}

if(empty($_POST['page'])) {
    $_POST['page'] = 1;
}

$comments = (new Comment())->pagin($_POST['page']);

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
    <?php if(!empty($errors)):?>
            <?php foreach ($errors as $error): ?>
                    <?= $error; ?>
            <?php endforeach; ?>
        <?php endif;?>    
    <div>
        <form method="post">
            <div>
                <textarea name="text" placeholder="Напишите Ваш комментарий" required></textarea>
            </div>
            <div>
                <br>
                <div><input type="submit" name="save" value="Сохранить"></div>
                <br>
                <div><input type="submit" name="clear" value="Удалить"></div>
                <br>
                <div><input type="submit" name="exit" value="Выйти"></div>
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
        <form method="post">
            <div  style="display: flex; justify-content: space-evenly;">
                <p><input name="beginning" type="submit" class="btn btn-info btn-block" style="width: 50px;" value="«"></p>
                <?php for ($i = 1; $i<=$pageCount; $i++): ?>
                    <?php echo"<p><input name=\"page\" type=\"submit\" value=\"$i\"></p>";?>
                <?php endfor ?>
                <p><input name="end" type="submit" class="btn btn-info btn-block" style="width: 50px;" value="»"></p>
            </div>
        </form>
    </div>
</body>
</html>