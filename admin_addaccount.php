<?php
session_start();
if (!isset($_COOKIE['user_id'])) {
    header('Location: techsupportlogin.php');
    exit;
}
if ($_COOKIE['roleid'] != 1) {
    header('Location: techsupport_logout.php');
    exit;
}
$id = $_COOKIE['user_id'];
$login = $_COOKIE['login'];

include "php/dbconnection.php";
include "php/roleconverter.php";
?>


<html>

<head>
    <title>Новый пользователь - Панель администратора</title>
    <link rel="stylesheet" type="text/css" href="css/systemdesign.css" />
    <link rel="stylesheet" type="text/css" href="css/edit_design.css" />
</head>

<body>
    <div class="page-menu">
        <div class="page-menu-upper">
            <div class="menu-header-text">Техподдержка</div>
            <div class="menu-container">
                <a href="admin.php" class="menu-link-text">
                    <div class="menu-link-button">Главная</div>
                </a>
                <a href="admin_accounts.php" class="menu-link-text-selected">

                    <div class="menu-link-button-selected">Пользователи</div>
                </a>
            </div>
        </div>

        <div class="page-menu-down">
            <div class="menu-bottom-container">
                <?php
                echo "<div class='menu-bottom-text'>Пользователь: $login</div>";
                ?>
                <div class='menu-bottom-logout-button'>
                    <a href="techsupport_logout.php" class="menu-bottom-logout-link">
                        Выйти
                    </a>
                </div>
            </div>
        </div>

    </div>


    <div class="page-content">
        <h2><a href="admin_accounts.php" class="content-address-link">Пользователи</a> - Добавление пользователя</h2>
        <p>На данной странице Вы можете добавить нового пользователя и назначить ему роль в системе.
        </p>

        <form method="post">
            <h3>Данные нового профиля</h3>
            <div class="divTable">
                <div class="divTableBody">
                    <div class='divTableRow'>
                        <div class="divTableCell">Имя пользователя</div>
                        <div class="divTableCell">
                            <?php echo "<input type='text'  name='newlogin' />"; ?>

                        </div>
                    </div>
                    <div class='divTableRow'>
                        <div class="divTableCell">Электронная почта</div>
                        <div class="divTableCell">
                            <?php echo "<input type='email' name='newemail'/>"; ?>
                        </div>
                    </div>
                    <div class='divTableRow'>
                        <div class="divTableCell">Пароль</div>
                        <div class="divTableCell">
                            <input type="password" name="pwd" class="auth-input-field" placeholder="Пароль" />

                        </div>
                    </div>
                    <div class='divTableRow'>
                        <div class="divTableCell">Подтверждение пароля</div>
                        <div class="divTableCell">
                            <input type="password" name="pwdConfirm" class="auth-input-field" placeholder="Подтвердите пароль" />

                        </div>
                    </div>




                    <div class='divTableRow'>
                        <div class="divTableCell">Роль</div>
                        <div class="divTableCell">
                            <select name='newroleid' class='dropdown-design'>
                                <option value='1'>Администратор</option>
                                <option value='2' selected>Работник техподдержки</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <input type="submit" class='button-save' value='Добавить нового пользователя' />
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $llogin = trim(htmlspecialchars($_POST['newlogin']));
            $password = trim(htmlspecialchars($_POST['pwd']));
            $password_confirm = trim(htmlspecialchars($_POST['pwdConfirm']));
            $newroleid = trim(htmlspecialchars($_POST['newroleid']));
            $email = trim(htmlspecialchars($_POST['newemail']));

            $link = OpenConnection(); //Соединяемся с БД
            $query_result = mysqli_query($link, "SELECT * FROM users WHERE account_login = $llogin OR email=$email");

            if(mysqli_num_rows($query_result) != 0) {
                $row = mysqli_fetch_assoc($query_result);
                if($row['account_login']==$llogin)
                    echo '<div class="php-message">Имя пользователя уже занято.</div>';
                if($row['email']==$email)
                    echo '<div class="php-message">На данную электронную почту уже есть созданная учетная запись.</div>';
                    
                exit;
            }

            if ($login === '' || $password === '' || strlen($password) == 0 || $password_confirm === '' || $email === '') {
                echo '<div class="php-message">Пожалуйста, заполните все поля.</div>';
            } else {
                if ($password === $password_confirm) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO users (account_login, password_hash, email, roleid) VALUES ('$llogin', '$hash', '$email', $newroleid)";

                    $link->query($sql) === TRUE;
                    header('Location: admin_accounts.php');
                    exit();
                    CloseConnection($link);
                } else
                    echo '<div class="php-message">Пароли должны совпадать.</div>';
            }
            
        }
        ?>
    </div>




</body>

</html>