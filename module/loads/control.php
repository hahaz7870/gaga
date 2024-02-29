<?php
UAccess(1);
if($param['id'] and $param['command']){

if ($param['command'] == 'delete'){
    $Row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `dfile`, `dimg` FROM `load` WHERE `id` = $param[id]"));
    mysqli_query($connect, "DELETE FROM `load` WHERE `id` = $param[id]");
    unlink('catalog/img/'.$Row['dimg'].'/'.$param['id'].'.jpg');

    if ($Row['dfile']) unlink('catalog/file/'.$Row['dfile'].'/'.$param['id'].'.zip');
    MessageSend(3, 'Материал удален.', '/loads'); 
    
} else if ($param['command'] == 'active'){
    mysqli_query($connect, "UPDATE `load` SET `active` = 1 WHERE `id` = $param[id]");
    MessageSend(3, 'Материал активирован.', '/loads/material/id/'.$param['id']);
}


}
?>