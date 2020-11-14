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
?>


<html>

<head>
    <title>Пользователи - Панель администратора</title>
    <link rel="stylesheet" type="text/css" href="css/systemdesign.css" />
    <link rel="stylesheet" type="text/css" href="css/tabledesign.css" />
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
        <h2>Пользователи</h2>
        <p>На данной странице выведены все пользователи, существующие в системе. Вы можете просматривать
            информацию о пользователях, а также назначать им роли.
        </p>

        <?php
        //Получаем данные о пользователях
        
        include "php/dbconnection.php";
        include "php/roleconverter.php";
        $link = OpenConnection();
        $query = "SELECT user_id, account_login, email, roleid FROM users";
        $query_result = mysqli_query($link, $query);

        //Строим таблицу
        echo ('
        <div class="divTable">
            <div class="divTableBody">
                <div class="divTableHeading">
                    <div class="divTableCell">ID</div>
                    <div class="divTableCell">Имя пользователя</div>
                    <div class="divTableCell">Электронная почта</div>
                    <div class="divTableCell">Роль</div>
                    <div class="divTableCell">Действия</div>
                </div>
        ');
        //Выводим ряды пользователей
        while($row =mysqli_fetch_assoc($query_result)) {
            $user = $row['account_login'];

            $luserid= $row['user_id'];
            $llogin = $row['account_login'];
            $email= $row['email'];
            $roleid= $row['roleid'];
            $rolestring = ConvertIdToName($roleid);

            echo ("
            <div class='divTableRow'>
                <div class='divTableCell'>$luserid</div>
                <div class='divTableCell'>$llogin</div>
                <div class='divTableCell'>$email</div>
                <div class='divTableCell'>$rolestring</div>
                <div class='divTableCell'>
                    <a href='admin_editaccount.php' class='linkDesign'>Редактировать</a>
                
                </div>
            ");
            /*
            Перенести в редактирование профиля. Возможно. А может и стереть куда подальше.
            echo ("
            <div class='divTableRow'>
                <div class='divTableCell'>$luserid</div>
                <div class='divTableCell'>$llogin</div>
                <div class='divTableCell'>$email</div>
                <div class='divTableCell'>

                <select name='newroleid' class='dropdown-design'>"
            );
            if($roleid == 0) 
            {
                echo("
                <option value='0' selected>Не активирована</option>
                <option value='1'>Администратор</option>
                <option value='2'>Работник техподдержки</option>
                ");
            }
            else if($roleid == 1) 
            {
                echo("
                <option value='0'>Не активирована</option>
                <option value='1' selected>Администратор</option>
                <option value='2'>Работник техподдержки</option>
                ");
            }
            else if($roleid == 2) 
            {
                echo("
                <option value='0' >Не активирована</option>
                <option value='1'>Администратор</option>
                <option value='2' selected>Работник техподдержки</option>
                ");
            }
                    

            echo ("
                </select>
                </div>
                <div class='divTableCell'>Кнопки</div>
            </div>
            ");*/
        }

        echo "</div>";
        echo "</div>";
        ?>
    </div>
</body>

</html>