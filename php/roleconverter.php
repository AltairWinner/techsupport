<?php

function ConvertIdToName($id) {
    $rolename = "ОШИБКА: роль не найдена";

    if($id == 0)
    $rolename="Не активирована";
    else if ($id == 1) $rolename = "Администратор";
    else if ($id == 2) $rolename = "Работник техподдержки";

    return $rolename;
}

?>