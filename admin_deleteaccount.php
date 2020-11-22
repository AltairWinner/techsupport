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
$query = "SELECT user_id, account_login, email, roleid FROM users";
$query_result = mysqli_query($link, $query);

$row = mysqli_fetch_assoc($query_result);
$llogin = $row['account_login'];
$email = $row['email'];
$roleid = $row['roleid'];
$rolestring = ConvertIdToName($roleid);
?>


<html>

<head>
    <title>Удаление <?php echo $llogin ?> - Панель администратора</title>
    <link rel="stylesheet" type="text/css" href="css/systemdesign.css" />
    <link rel="stylesheet" type="text/css" href="css/edit_design.css" />
</head>

<body>
    <div class="page-menu">
        <div class="page-menu-upper">
            <div class="menu-header-text">Техподдержка</div>
            <div class="menu-container">
                <a href="admin.php" class="menu-link-text">
                    <div class="menu-link-button">Статистика</div>
                </a>
                <a href="admin_accounts.php" class="menu-link-text-selected">

                    <div class="menu-link-button-selected">Пользователи</div>
                </a>
                <a href="tickets.php" class="menu-link-text">
                    <div class="menu-link-button">Заявки</div>
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
        <h2><a href="admin_accounts.php" class="content-address-link">Пользователи</a> - Редактирование пользователя <?php echo $llogin ?> - Удаление</h2>

        <p>Удаление аккаунта приведет к уничтожению всех связанных с аккаунтом данных.</p>
        <p>Для удаления учетной записи требуется Ваше подтверждение.</p>
        <form method="POST" name="delete_form">
            <button class='button-danger' name="delete_button">
                Удалить аккаунт
            </button>
        </form>
    </div>
</body>

</html>



<?php
if(isset($_POST['delete_button'])) {
    $query="DELETE FROM users WHERE user_id=$edited_account_id";
    $link = OpenConnection();
    mysqli_query($link, $query);

    header('Location: admin_accounts.php');
}
?>