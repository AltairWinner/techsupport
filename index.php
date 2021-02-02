<?php
session_start();
include "php/statusconverter.php";
if (!isset($_COOKIE['user_id'])) {
    header('Location: techsupportlogin.php');
    exit;
}

$id = $_COOKIE['user_id'];
$login = $_COOKIE['login'];
?>


<html>

<head>
    <title>Главная страница</title>
    <link rel="stylesheet" type="text/css" href="css/systemdesign.css" />
    <link rel="stylesheet" type="text/css" href="css/tabledesign.css" />

</head>

<body>
<div class="page-menu">
    <div class="page-menu-upper">
        <div class="menu-header-text">Техподдержка</div>
        <div class="menu-container">
            <a href="newticket.php" class="menu-link-text">
                <div class="menu-link-button">Отправить новое обращение</div>
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
    <?php
    //Получаем данные о пользователях

    include "php/dbconnection.php";
    $link = OpenConnection();
    $query = mysqli_query($link,"SELECT * FROM tickets WHERE user_id = $id");
        if (!$query || mysqli_num_rows($query) > 0) {
        //Строим таблицу
        echo('
        <div class="divTable">
            <div class="divTableBody">
                <div class="divTableHeading">
                    <div class="divTableCell">Номер обращения</div>
                    <div class="divTableCell">Статус обращения</div>
                    <div class="divTableCell">Тема обращения</div>
                    <div class="divTableCell">Дата получения</div>
                    <div class="divTableCell">Дата обновления</div>
                    <div class="divTableCell">Действия</div>
                </div>
        ');
        //Выводим ряды пользователей
            while ($row = mysqli_fetch_array($query)) {

            $ticket_id = $row['ticket_id'];
            $ticket_status = ConvertStatusIdToName($row['ticket_status']);
            $ticket_theme = $row['ticket_theme'];
            $ticket_receive_date = $row['ticket_receive_date'];
            $ticket_update_date = $row['ticket_update_date'];

            echo("
            <div class='divTableRow'>
                <div class='divTableCell'>$ticket_id</div>
                <div class='divTableCell'>$ticket_status</div>
                <div class=\"divTableCell\">$ticket_theme</div>
                <div class='divTableCell'>$ticket_receive_date</div>
                <div class='divTableCell'>$ticket_update_date</div>
                <div class='divTableCell'> 
                <a href='userticket.php?ticketid=$ticket_id' class='linkDesign'>Просмотреть полностью</a>
                </form>
                </div>
            </div>
            ");
        }
        echo "</div>";
        echo "</div>";
    }else{
            echo ("
                    <h3>В данный момент у вас нет обращений.</h3>
                    <h3>Вы можете составить обращение в любое время, используя кнопку слева.</h3>
                   ");
        }

    ?>
</div>
</body>

</html>