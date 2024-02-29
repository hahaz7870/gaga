<?php
if ($module == 'category' and $param['id'] != 1  and $param['id'] != 2  and $param['id'] != 3) MessageSend(1, 'Такой категории не существует.', '/loads');
$param['page'] += 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог файлов</title>
    <style>
        
        <?php style() ?>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .CatHead {
            padding: 10px;
            text-align: center;
        }
        
        .CatHead a {
            text-decoration: none;
            color: #333;
        }
        
        .Cat {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px;
            border-radius: 5px;
            background-color: #ddd;
            transition: background-color 0.3s ease;
        }
        
        .Cat:hover {
            background-color: #ccc;
        }
        
        .ChatBlock {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            background-color: #f9f9f9;
        }
        
        .ChatBlock span {
            font-size: 0.8em;
            color: #888;
        }

        a {
            text-decoration: none;
            color: #333; 
        }

        .container {
            max-width: 70%; 
            margin: 0 auto;  
        }
    </style>
</head>
<body>

<?php 
    menu(); 
    MessageShow(); 
?>
<div class="container">
<div class="CatHead">
<?php if ($_SESSION['USER_LOGIN_IN']) echo '<a href="/loads/add"><div class="Cat">Добавить файл</div></a>'; ?>
    <a href="/loads"><div class="Cat"> Все категории</div></a>
    <a href="/loads/category/id/1"><div class="Cat"> Категория 1</div></a>
    <a href="/loads/category/id/2"><div class="Cat"> Категория 2</div></a>
    <a href="/loads/category/id/3"><div class="Cat"> Категория 3</div></a>
</div>

<?php
if (!$module or $module == 'main'){
    if ($_SESSION['USER_GROUPS'] != 2 && $_SESSION['USER_GROUPS'] != 1) $Active = 'WHERE `active` = 1';
    $param1 = 'SELECT `id`, `name`, `added`, `date`, `active` FROM `load` '.$Active.' ORDER BY `id` DESC LIMIT 0, 7';
    $param2 = 'SELECT `id`, `name`, `added`, `date`, `active` FROM `load` '.$Active.' ORDER BY `id` DESC LIMIT START, 7';
    $param3 = 'SELECT COUNT(`id`) FROM `load`';
    $param4 = '/loads/main/page/';

} else if ($module == 'category'){
    if ($_SESSION['USER_GROUPS'] != 2 && $_SESSION['USER_GROUPS'] != 1) $Active = 'AND `active` = 1';
    $param1 = 'SELECT `id`, `name`, `added`, `date`, `active` FROM `load` WHERE `cat` = '.$param['id'].' '.$Active.' ORDER BY `id` DESC LIMIT 0, 7';
    $param2 = 'SELECT `id`, `name`, `added`, `date`, `active` FROM `load` WHERE `cat` = '.$param['id'].' '.$Active.' ORDER BY `id` DESC LIMIT START, 7';
    $param3 = 'SELECT COUNT(`id`) FROM `load` WHERE `cat` = '.$param['id'];
    $param4 = '/loads/category/id/'.$param['id'].'/page/';
}

$Count = mysqli_fetch_row(mysqli_query($connect, $param3));

if (!$param['page']){
    $param['page'] = 1;
    $result = mysqli_query($connect, $param1);
}
else{
    $Start = ($param['page'] - 1) * 7; 
    $result = mysqli_query($connect, str_replace('START', $Start, $param2));
}

PageSelector($param4, $param['page'], $Count);





while ($Row = mysqli_fetch_assoc($result)) {
    if(!$Row['active']) $Row['name'] .= ' (Ожидает модерации)'; 

?>
    <a href="/loads/material/id/<?php echo $Row['id']; ?>">
        <div class="ChatBlock">
            <span>Добавил: <?php echo $Row['added']; ?> | <?php echo $Row['date']; ?></span><br>
            <?php echo $Row['name']; ?>
        </div>
    </a>
<?php
}
?>
</div>

</body>
</html>
