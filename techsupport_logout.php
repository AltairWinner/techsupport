<?php
    session_start();
    setcookie("user_id", null);
    setcookie("roleid", null);
    setcookie("login", null);
    session_destroy();
    header('Location: techsupportlogin.php');
    exit;
?>