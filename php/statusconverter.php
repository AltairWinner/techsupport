<?php
function ConvertStatusIdToName($id) {
    $statusname = "ОШИБКА: статус не найден";

    if($id == 0)
        $statusname="Открыта";
    else if ($id == 1) $statusname = "Ожидание ответа";
    else if ($id == 2) $statusname = "Закрыта";

    return $statusname;
}
?>