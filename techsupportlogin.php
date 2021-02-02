<html>

<head>
    <title>Вход в систему</title>
    <meta charset="UTF-8" />

    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/auth.css">
</head>

<body>
    <div class="auth-form">
        <h1 class="auth-header">Вход в систему</h1>
        <form method="post">
            <input type="text" name="login" class="auth-input-field" placeholder="Имя пользователя" required pattern="{1,}" />
            <input type="password" name="pwd" class="auth-input-field" required pattern="{4,}" placeholder="Пароль" />
            <input type="submit" name="btnLogin" value="Войти" class="auth-button" />
        </form>
        <a href="techsupportregister.php" class="register-text"><p class="auth-text">Создать новый аккаунт</p></a>
    </div>
    </div>
</body>

</html>


<?php
session_start(); //Открываем новую сессию            
include "php/dbconnection.php"; //Подключение базы данных

//Проверяем нажатие на клавишу, затем проверяем, все ли поля заполнены
if (isset($_POST['btnLogin'])) {
    $login = trim(htmlspecialchars($_POST['login']));
    $password = trim(htmlspecialchars($_POST['pwd']));

    if ($login === '' || $password === '' || strlen($password) == 0) {
        echo '<div class="php-message">Пожалуйста, заполните оба поля.</div>';
    } else {
        $link = OpenConnection();

        //1. Получаем данные из БД
        $query = "SELECT * FROM users WHERE account_login='$login';";
        $queryResult = mysqli_query($link, $query);
        if($queryResult->num_rows!=0) {
        $row = mysqli_fetch_assoc($queryResult);

        //2. Записываем результаты в переменные
        $hash = $row['password_hash'];
        $roleid = $row['roleid'];
        $id = $row['user_id'];
        //3. Проверяем правильность пароля
        if (password_verify($password, $hash)) {
            // здесь раньше была проверка, активирован ли аккаунт
            if ($roleid == 0) {
                setcookie('user_id', $id, time() + 60 * 60 * 24 * 90);
                setcookie('login', $login, time() + 60 * 60 * 24 * 90);
                setcookie('roleid', $roleid, time() + 60 * 60 * 24 * 90);
                header("Location: index.php");
            } else {

                setcookie('user_id', $id, time() + 60 * 60 * 24 * 90);
                setcookie('login', $login, time() + 60 * 60 * 24 * 90);
                setcookie('roleid', $roleid, time() + 60 * 60 * 24 * 90);
                header("Location: techsupportroute.php");
            }
        } else {
            echo '<div class="php-message">Неверное имя пользователя или пароль.</div>';
        }
    }
    else 
    echo '<div class="php-message">Неверное имя пользователя или пароль.</div>';
        CloseConnection($link);
    }
}


?>