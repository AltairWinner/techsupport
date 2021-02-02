<?php
session_start();
if (!isset($_COOKIE['user_id'])) {
    header('Location: techsupportlogin.php');
    exit;
}
if ($_COOKIE['roleid'] != 0) {
    header('Location: techsupport_logout.php');
    exit;
}
$id = $_COOKIE['user_id'];
$login = $_COOKIE['login'];
$ticketid = $_GET['ticketid'];
include "php/dbconnection.php";

date_default_timezone_set("Europe/Moscow");


include "php/statusconverter.php";
$link = OpenConnection();
$ticketrrow = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM `tickets` WHERE `ticket_id` = '$ticketid'"));

?>

<html>

<head>
    <title>Просмотр заявки</title>
    <link rel="stylesheet" type="text/css" href="css/techsupportdesign.css" />
</head>

<body>
<header>
    <div class="header-outer">
        <div class="header-right header-text">Вы вошли, как <?php echo $login ?>
            <a href="techsupport_logout.php" class="link-design">
                <div class="logout-button-design">
                    Выйти
                </div>
            </a>
        </div>
    </div>
</header>

<div class="content-wrapper">
    <div class="return-button">
        <a href="index.php" class="link-design">
            < Вернуться на главную страницу</a> </div> <?php



    $theme = $ticketrrow['ticket_theme'];
    $receivedate = $ticketrrow['ticket_receive_date'];
    $updatedate = $ticketrrow['ticket_update_date'];
    $status = $ticketrrow['ticket_status'];
    $userid = $ticketrrow['user_id'];
    $ticketstatus = $ticketrrow['ticket_status'];

    $statusname = ConvertStatusIdToName($status);

    $userlogin_queryresult = mysqli_query($link, "SELECT `account_login` FROM `users` WHERE `user_id` = '$userid'");
    if ($userlogin_queryresult != false) {
        if (mysqli_num_rows($userlogin_queryresult) != 0) {
            $userlogin_queryrow = mysqli_fetch_assoc($userlogin_queryresult);
            $userlogin = $userlogin_queryrow['account_login'];
        }
    } else
        $userlogin = "Несуществующий пользователь";

    $messagesqueryresult = mysqli_query($link, "SELECT * FROM `messages` WHERE `ticket_id` = '$ticketid'");




    echo "<div class='horizontal-flex-row underlined-block'><div class='ticket-title'>Заявка №'$ticketid' пользователя '$userlogin' </div>";
    echo "<div class='ticket-status'>Статус: $statusname </div></div>";
    echo "<div class='ticket-theme'>Тема: $theme </div>";

    echo "<h3>Диалог с техподдержкой</h3>";
    echo "<div class='scrollable-chat'>";
    while ($row = mysqli_fetch_assoc($messagesqueryresult)) {


        $messagetext = $row['message_text'];
        $receivedate = $row['message_time'];
        $messageauthorid = $row['message_author_id'];

        if ($messageauthorid == $userid)
            $messageauthor = $userlogin;
        else
            $messageauthor = "Техподдержка";


        echo "<div class='message'>";
        echo "<div class='message-header'>";

        echo "<div class='message-author'><strong>$messageauthor</strong></div> <div class='time'>$receivedate</div>";;
        echo "</div>";
        echo "<div class='message-text'>$messagetext</div>";

        echo "</div>";
    }
    echo "</div>";

    CloseConnection($link);
    ?> <div>
        <form method="POST">
            <h3>Ответить на заявку</h3>
            <p>Текст ответа:</p>
            <textarea class="input-field" placeholder="Введите ответ" name="answertext"></textarea>

            <div class="horizontal-flex-row">
                <?php
                if( $ticketstatus == 2){ ?>
                    <div>
                    <button class="button-design" name="answerButton" disabled>Заявка закрыта</button>
                </div>
                <?php
                } else{ ?>
                    <div>
                    <button class="button-design" name="answerButton">Ответить</button>
                </div> <?php
                }
                ?>

        </form>


        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            if (isset($_POST['answerButton'])) {
                $text = trim(htmlspecialchars($_POST['answertext']));
                $answerstatus = 0;

                $answerdate = date('Y-m-d H:i:s');

                $sql = "INSERT INTO `messages` (`ticket_id`, `message_text`, `message_time`, `message_author_id`) VALUES ('$ticketid', '$text', '$answerdate', '$id');";
                $sql .= "UPDATE `tickets` SET `current_worker_id`=NULL, `ticket_status`='$answerstatus' WHERE `ticket_id`='$ticketid';";



                $link = OpenConnection();
                $err = mysqli_multi_query($link, $sql);
                header("Location: userticket.php?ticketid=$ticketid");
            }
        }
        ?>
    </div>
</div>



</body>

</html>