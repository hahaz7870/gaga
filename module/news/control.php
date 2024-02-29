<?php
UAccess(1);
if($param['id'] and $param['command']){

if ($param['command'] == 'delete'){
    mysqli_query($connect, "DELETE FROM `news` WHERE `id` = $param[id]");
    MessageSend(3, 'Новость удалена.', '/news');
    
} else if ($param['command'] == 'active'){
    mysqli_query($connect, "UPDATE `news` SET `active` = 1 WHERE `id` = $param[id]");
    MessageSend(3, 'Новость активирована.', '/news/material/id/'.$param['id']);
}


}
?>