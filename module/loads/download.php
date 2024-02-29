<?php
if (!$param['id']) MessageSend(1, 'Файл не указан', '/loads');
$param['id'] += 0;
$Row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `dfile`, `link` FROM `load` WHERE `id` = $param[id]"));
if (!$Row['dfile'] and !$Row['link']) MessageSend(1, 'Файл не найден', '/loads');
mysqli_query($connect, "UPDATE `load` SET `download` = `download` + 1 WHERE `id` = $param[id]");
if ($Row['dfile']) header('location: /catalog/file/'.$Row['dfile'].'/'.$param['id'].'.zip');
else header('location:'.$Row['link']);
?>