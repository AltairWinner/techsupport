<?php
session_start();
if (!isset($_COOKIE['user_id'])) {
    header('Location: techsupportlogin.php');
    exit;
}
if ($_COOKIE['roleid'] != 2) {
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
$ticketrrow = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM tickets WHERE ticket_id = $ticketid"));
$workerid = $ticketrrow['current_worker_id'];

//Если заявка не назначена работнику - он не может её просматривать 
if($workerid != $id) {
    header('Location: tickets.php');
}

?>

<html>

<head>
    <title>Просмотр заявки</title>
    <link rel="stylesheet" type="text/css" href="css/techsupportdesign.css" />
</head>

<body>
    <header>
        <div class="header-outer">
            <div class="header-left header-text">Техподдержка</div>
            <div class="header-center header-text"><a href="tickets.php" class="link-design">Список заявок</a> > Заявка</div>
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
            <a href="tickets.php" class="link-design">
                < Вернуться на главную страницу</a> </div> <?php



                                                            $theme = $ticketrrow['ticket_theme'];
                                                            $receivedate = $ticketrrow['ticket_receive_date'];
                                                            $updatedate = $ticketrrow['ticket_update_date'];
                                                            $status = $ticketrrow['ticket_status'];
                                                            $userid = $ticketrrow['user_id'];

                                                            $statusname = ConvertStatusIdToName($status);

                                                            $userlogin_queryresult = mysqli_query($link, "SELECT username FROM users WHERE user_id = $userid");
                                                            if ($userlogin_queryresult != false) {
                                                                if (mysqli_num_rows($userlogin_queryresult) != 0) {
                                                                    $userlogin_queryrow = mysqli_fetch_assoc($userlogin_queryresult);
                                                                    $userlogin = $userlogin_queryrow['account_login'];
                                                                }
                                                            } else
                                                                $userlogin = "Несуществующий пользователь";

                                                            $messagesqueryresult = mysqli_query($link, "SELECT * FROM messages WHERE ticket_id = $ticketid");



                                                            echo "<div class='horizontal-flex-row underlined-block'><div class='ticket-title'>Заявка №$ticketid пользователя $userlogin </div>";
                                                            echo "<div class='ticket-status'>Статус: $statusname </div></div>";
                                                            echo "<div class='ticket-theme'>Тема: $theme </div>";

                                                            echo "<h3>Диалог с пользователем</h3>";
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
                            <div>
                                <button class='button-danger' name="giveAwayButton">Отменить ответ</button>
                            </div>

                            <div>
                                <select name="answerstatus">
                                    <option value="1">Требуется ответ пользователя</option>
                                    <option value="2" selected>Закрыть заявку</option>
                                </select>
                                <button class="button-design" name="answerButton">Ответить</button>
                            </div>
                    </form>


                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if (isset($_POST['giveAwayButton'])) {

                            $link = OpenConnection();
                            $sql = "UPDATE tickets SET current_worker_id=NULL WHERE `ticket_id`='$ticketid'";

                            mysqli_query($link, $sql);

                            CloseConnection($link);
                            header("Location: tickets.php");
                        }

                        if (isset($_POST['answerButton'])) {
                            $text = trim(htmlspecialchars($_POST['answertext']));
                            $answerstatus = trim(htmlspecialchars($_POST['answerstatus']));

                            $answerdate = date('Y-m-d H:i:s');

                            $sql = "INSERT INTO `messages` (`ticket_id`, `message_text`, `message_time`, `message_author_id`) VALUES ('$ticketid', '$text', '$answerdate', '$id');";
                            $sql .= "UPDATE `tickets` SET `current_worker_id`=NULL, `ticket_update_date`='$answerdate', `ticket_status`='$answerstatus' WHERE `ticket_id`='$ticketid';";


                            $link = OpenConnection();
                            $err = mysqli_multi_query($link, $sql);
                        }
                    }
                    ?>
        </div>
    </div>



</body>

</html>