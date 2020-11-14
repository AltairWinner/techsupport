<?php
session_start();
if (!isset($_COOKIE['user_id'])) {
    header('Location: techsupportlogin.php');
    exit;
}
if ($_COOKIE['roleid'] != 1) {
    header('Location: techsupportlogin.php');
    exit;
}
$id = $_COOKIE['user_id'];
$login = $_COOKIE['login'];
?>


<html>

<head>
    <title>Статистика - Панель администратора</title>
    <link rel="stylesheet" type="text/css" href="css/systemdesign.css" />

</head>

<body>
    <div class="page-menu">
        <div class="page-menu-upper">
            <div class="menu-header-text">Техподдержка</div>
            <div class="menu-container">
            <a href="admin.php" class="menu-link-text-selected">
                <div class="menu-link-button-selected">Статистика</div>
            </a>
            <a href="admin_accounts.php" class="menu-link-text">
                
                <div class="menu-link-button">Пользователи</div>
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
        <h2>Статистика</h2>
        <p>Здесь, возможно, будет какая-то статистика, если будет время на её реализацию.</p>
    </div>
</body>

</html>


<?php
