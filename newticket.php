<?php
session_start();
if (!isset($_COOKIE['user_id'])) {
    header('Location: techsupportlogin.php');
    exit;
}

date_default_timezone_set("Europe/Moscow");

$userid = $_COOKIE['user_id'];
$login = $_COOKIE['login'];
?>


<html>

<head>
    <title>Главная страница</title>
    <link rel="stylesheet" type="text/css" href="css/systemdesign.css" />


</head>

<body>
<div class="page-menu">
    <div class="page-menu-upper">
        <div class="menu-header-text">Техподдержка</div>
        <div class="menu-container">
            <a href="index.php" class="menu-link-text">
                <div class="menu-link-button">Список Ваших обращений</div>
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
    <h2>Ваши обращения</h2>
    <form method="POST">
        <h3>Новое обращение</h3>
        <textarea class="input-field-theme" placeholder="Введите тему обращения" name="tickettheme"></textarea>
        <textarea class="input-field-text" placeholder="Введите обращение" name="tickettext"></textarea>
        <div class="horizontal-flex-row">
            <div>
                <button class="button-design" name="newTicket">Отправить обращение</button>
            </div>
    </form>
</div>
</body>

</html>
<?php
    if (isset($_POST['newTicket'])) {
        include "php/dbconnection.php";
        $link = OpenConnection();
        $theme =  trim(htmlspecialchars($_POST['tickettheme']));
        $text = trim(htmlspecialchars($_POST['tickettext']));

        $ticket_date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO `tickets` (`ticket_status`, `ticket_theme`, `ticket_receive_date`, `ticket_update_date`, `user_id`) VALUES ('0', '$theme', '$ticket_date','$ticket_date','$userid')";
        $link->query($sql) === TRUE;


            $last_id = $link->insert_id;
            $sql_msg = "INSERT INTO `messages` (`ticket_id`, `message_text`, `message_time`, `message_author_id`) VALUES ('$last_id', '$text', '$ticket_date','$userid')";
        $link->query($sql_msg) === TRUE;

        CloseConnection($link);
        header("Location: index.php");
    }
?>