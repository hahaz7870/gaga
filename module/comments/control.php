<?php
Uaccess(1);

if ($param['action'] == 'delete'){
    mysqli_query($connect, "DELETE FROM `comments` WHERE `id` = $param[id]");
    MessageSend(3, 'Комментарий удален.');
}
else if ($param['action'] == 'edit'){
    $_SESSION['COMMENTS_EDIT'] = $param['id'];
    exit(header('location: '.$_SERVER['HTTP_REFERER']));
}

else if ($_POST['save']){
    mysqli_query($connect, "UPDATE `comments` SET `text` = '$_POST[text]' WHERE `id` = $_SESSION[COMMENTS_EDIT]");
    unset($_SESSION['COMMENTS_EDIT']);
    MessageSend(3, 'Комментарий отредактирован.');

}

else if ($_POST['cancel']){
    unset($_SESSION['COMMENTS_EDIT']);
    MessageSend(3, 'Редактирование отменено.');
}
?>