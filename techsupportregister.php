<html>
<head>
    <title>Создание нового аккаунта</title>
    <meta charset="UTF-8" />

    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/auth.css">
</head>

<body>
    <div class="auth-form">
        <h1 class="auth-header">Создание нового аккаунта</h1>
        <form method="post">
            <input type="text" name="login" class="auth-input-field" placeholder="Имя пользователя" required pattern="{1,}" />
            <input type="email" name="email" class="auth-input-field" placeholder="Электронная почта" required pattern="{1,}" />
            <input type="password" name="pwd" class="auth-input-field" required pattern="{4,}" placeholder="Пароль" />
            <input type="password" name="pwdConfirm" class="auth-input-field" required pattern="{4,}" placeholder="Подтвердите пароль" />
            <input type="submit" name="btnRegister" value="Регистрация" class="auth-button" />
        </form>
        <a href="techsupportlogin.php" class="register-text"><p class="auth-text">Войти в систему</p></a>
    </div>
    </div>
</body>

</html>


<?php
session_start(); //Открываем новую сессию            
include "php/dbconnection.php"; //Подключение базы данных

//Проверяем нажатие на клавишу, затем проверяем, все ли поля заполнены
if (isset($_POST['btnRegister'])) 
{
    $login = trim(htmlspecialchars($_POST['login']));
    $password = trim(htmlspecialchars($_POST['pwd']));
    $password_confirm = trim(htmlspecialchars($_POST['pwdConfirm']));
    $email = trim(htmlspecialchars($_POST['email']));
    if ($login === '' || $password === '' || strlen($password) == 0 || $password_confirm==='' || $email === '') 
    {
        echo '<div class="php-message">Пожалуйста, заполните все поля.</div>';
    } 
    else 
    {
        if($password===$password_confirm) {
            $link=OpenConnection(); //Соединяемся с БД
            $hash=password_hash($password, PASSWORD_DEFAULT);

            $sql="INSERT INTO users (account_login, password_hash, email, roleid) VALUES ('$login', '$hash', '$email', 0)";

            $link->query($sql) === TRUE;
            header("Location: techsupport_registrationsuccess.php");  //TODO: проверить работу!
            exit();
            CloseConnection($link);
        }
        else
            echo '<div class="php-message">Пароли должны совпадать.</div>';  
    }
}
?>
