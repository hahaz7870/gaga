<?php
ULogin(1);

if ($_POST['enter'] and $_POST['text']) {
$_POST['text'] = FormChars($_POST['text']);


$ID = ModuleId($param['module']);

if ($ID == 1) $Table = 'news';
else if ($ID == 2) $Table = 'load';

$Row = mysqli_fetch_assoc(mysqli_query($connect, 'SELECT `id` FROM `'.$Table.'` WHERE `id` = '.$param['id']));
if (!$Row['id']) MessageSend(1, 'Материал не найден.', '/'.$param['module']);



mysqli_query($connect, "INSERT INTO `comments` VALUES ('', $param[id], $ID,'$_SESSION[USER_LOGIN]', '$_POST[text]',NOW())");

MessageSend(3, 'Комментарий добавлен.', '/'.$param['module']. '/material/id/'.$param['id']);
}
?>