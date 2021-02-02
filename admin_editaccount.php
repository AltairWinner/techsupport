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


$edited_account_id = $_GET['id'];
include "php/dbconnection.php";
include "php/roleconverter.php";
$link = OpenConnection();
$query = "SELECT user_id, account_login, email, roleid FROM users where user_id=$edited_account_id";
$query_result = mysqli_query($link, $query);

$row = mysqli_fetch_assoc($query_result);
$llogin = $row['account_login'];
$email = $row['email'];
$roleid = $row['roleid'];
$rolestring = ConvertIdToName($roleid);
CloseConnection($link);
?>


<html>

<head>
    <title><?php echo $llogin ?> - Панель администратора</title>
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
        <h2><a href="admin_accounts.php" class="content-address-link">Пользователи</a> - Редактирование пользователя <?php echo $llogin ?></h2>
        <p>На данной странице Вы можете отредактировать профиль пользователя. Вы можете изменить
            имя пользователя, электронную почту, либо сменить его роль. Также вы можете удалить учетную запись,
            если это необходимо.
        </p>

        <form method="post">
            <h3>Данные профиля</h3>
            <div class="divTable">
                <div class="divTableBody">
                    <div class='divTableRow'>
                        <div class="divTableCell">Имя пользователя</div>
                        <div class="divTableCell">
                            <?php echo "<input type='text' value='$llogin' name='newlogin' />"; ?>

                        </div>
                    </div>
                    <div class='divTableRow'>
                        <div class="divTableCell">Электронная почта</div>
                        <div class="divTableCell">
                            <?php echo "<input type='email' value='$email' name='newemail'/>"; ?>
                        </div>
                    </div>


                    <div class='divTableRow'>
                        <div class="divTableCell">Роль</div>
                        <div class="divTableCell">
                            <select name='newroleid' class='dropdown-design'>"
                                <?php
                                if ($roleid == 0) {
                                    echo ("
                        <option value='0' selected>Не активирована</option>
                        <option value='1'>Администратор</option>
                        <option value='2'>Работник техподдержки</option>
                        ");
                                } else if ($roleid == 1) {
                                    echo ("
                        <option value='0'>Не активирована</option>
                        <option value='1' selected>Администратор</option>
                        <option value='2'>Работник техподдержки</option>
                        ");
                                } else if ($roleid == 2) {
                                    echo ("
                        <option value='0' >Не активирована</option>
                        <option value='1'>Администратор</option>
                        <option value='2' selected>Работник техподдержки</option>
                        ");
                                } ?>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <input type="submit" class='button-save' value='Сохранить изменения' />
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newlogin = trim(htmlspecialchars($_POST['newlogin']));
            $newemail = trim(htmlspecialchars($_POST['newemail']));
            $newroleid = trim(htmlspecialchars($_POST['newroleid']));
            $link = OpenConnection();
            $sql = "UPDATE `users` SET `account_login`='$newlogin', `email`='$newemail', `roleid`='$newroleid' WHERE `users`.`user_id`='$edited_account_id'";
            mysqli_query($link, $sql);

            CloseConnection($link);
            header('Location: admin_accounts.php');
        }
        ?>
        <h3>Действия</h3>
        <?php echo "<a href='admin_deleteaccount.php?id=$edited_account_id'>" ?>
        <button class='button-danger'>
            Удалить аккаунт
        </button>
        </a>

    </div>




</body>

</html>