<?php
function OpenConnection()
 {
    $dbhost = "localhost";
    $dbuser = "techsupportsystem";
    $dbpass = "techsupport";
    $db = "techsupportdb";
    $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Ошибка подключения к базе данных: %s\n". $conn -> error);
    return $conn;
 }

function CloseConnection($conn)
 {
    $conn -> close();
 }
   
?>