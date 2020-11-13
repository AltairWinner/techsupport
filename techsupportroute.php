<?php
//Данная страница пересылает пользователя на страницу, связанную с его ролью.


session_start();

if (!isset($_COOKIE['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_COOKIE['roleid'] == 0) {
    header('Location: techsupportlogin.php');
    exit;
} else if ($_COOKIE['roleid'] == 1) {
    header('Location: admin.php');
    exit;
} else if ($_COOKIE['roleid'] == 2) {
    header('Location: tickets.php');
    exit;
}
?>

<html>
    <head>
        <title>Переадресация</title>
    </head>
</html>