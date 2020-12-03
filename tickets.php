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

include "php/dbconnection.php";
?>


<html>

<head>
    <title>Заявки</title>
    <link rel="stylesheet" type="text/css" href="css/techsupportdesign.css" />
    <link rel="stylesheet" type="text/css" href="css/tabledesign.css" />
</head>

<body>
    <header>
        <div class="header-outer">
            <div class="header-left header-text">Техподдержка</div>
            <div class="header-center header-text">Список заявок</div>
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
        <p>Вы можете взять новую заявку на обработку, нажав на следующую кнопку.</p>
        <form method="post">
            <button class="button-design">
                Следующая заявка
            </button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $query = "SELECT ticket_id FROM tickets WHERE (ticket_status='0') AND (current_worker_id IS NULL) ORDER BY ticket_update_date LIMIT 1";

            $conn = OpenConnection();
            $query_result = mysqli_query($conn, $query);
            if (mysqli_num_rows($query_result) == 0) {
                echo "На данный момент открытых заявок не найдено.";
            } else {
                $row = mysqli_fetch_assoc($query_result);
                $ticketid = $row['ticket_id'];
                mysqli_query($conn, "UPDATE `tickets` SET `current_worker_id`='$id' WHERE `ticket_id`='$ticketid'");

                header("Location: showticket.php?ticketid=$ticketid");
            }

            CloseConnection($conn);
        }
        ?>
        <h3>Список взятых Вами заявок</h3>
        <p>Если Вы взяли заявку, но еще не ответили, она будет отображаться в следующей таблице.
            Рекомендуется ответить на все уже взятые заявки либо отдать их, прежде чем брать следующую заявку.
        </p>
        <?php
        $sql = "SELECT * from tickets where current_worker_id=$id";
        $conn = OpenConnection();


        $query_result = mysqli_query($conn, $sql);
        if (!mysqli_num_rows($query_result) == 0) {
            include "php/statusconverter.php";
            //Строим таблицу
            echo ('
        <div class="divTable">
            <div class="divTableBody">
                <div class="divTableHeading">
                    <div class="divTableCell">ID заявки</div>
                    <div class="divTableCell">Тема</div>
                    <div class="divTableCell">Дата создания</div>
                    <div class="divTableCell">Дата обновления</div>
                    <div class="divTableCell">Статус</div>
                    <div class="divTableCell">Действия</div>
                </div>
        ');
            //Выводим ряды пользователей
            while ($row = mysqli_fetch_assoc($query_result)) {
                $oldticketid = $row['ticket_id'];

                $theme = $row['ticket_theme'];
                $receivedate = $row['ticket_receive_date'];
                $updatedate = $row['ticket_update_date'];
                $status = $row['ticket_status'];
                $statusname = ConvertStatusIdToName($status);
                echo ("
                <div class='divTableRow'>

                    <div class='divTableCell'>$oldticketid</div>
                    <div class='divTableCell'>$theme</div>
                    <div class='divTableCell'>$receivedate</div>
                    <div class='divTableCell'>$updatedate</div>
                    <div class='divTableCell'>$statusname</div>
                    <div class='divTableCell'>
                    <a href='showticket.php?ticketid=$oldticketid' class='linkDesign'>Подробнее</a>
                    </div>
                    
                </div>
            </div>
            ");
            }
        } else {
            echo "В настоящее время не завершенных Вами заявок не найдено.";
        }

        CloseConnection($conn);
        ?>

    </div>
</body>

</html>