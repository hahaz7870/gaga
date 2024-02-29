<?php

$param['id'] += 0;
if ($param['id'] == 0) MessageSend(1, 'URL адрес указан неверно.', '/news');
$Row = mysqli_fetch_assoc(mysqli_query($connect, 'SELECT `name`, `added`, `date`, `reading`, `text`, `active` FROM `news` WHERE `id` = '.$param['id']));

if (!$Row['name']) MessageSend(1, 'Такой новости не существует.', '/news');
if (!$Row['active'] and $_SESSION['USER_GROUPS'] != 2 and $_SESSION['USER_GROUPS'] != 1) MessageSend(1, 'Новость ожидает модерации.', '/news');

mysqli_query($connect, 'UPDATE `news` SET `reading` = `reading` + 1 WHERE `id` = '.$param['id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новости</title>
    <style>
        <?php style() ?>
        .new-container {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .news {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .container {
            max-width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .news-info {
            font-size: 0.8em;
            color: #666;
        }

        .news-title {
            font-size: 1.5em;
            margin-top: 10px;
        }

        .news-content {
            margin-top: 20px;
        }

        .edit-link {
            font-size: 0.9em;
            color: #007bff;
        }
    </style>
</head>
<body>

<?php menu(); 
 MessageShow(); ?>
 <div class="new-container">
<div class="news">
        <h1>Новости</h1>
</div>
     <div class="container">
<?php
    if (!$Row['active']) $Active = ' | <a href="/news/control/id/'.$param['id'].'/command/active">Активировать новость</a>';
    if ($_SESSION['USER_GROUPS'] >= 1) $edit = ' | <a href="/news/edit/id/'.$param['id'].'">Редактировать новость</a> | <a href="/news/control/id/'.$param['id'].'/command/delete">Удалить новость</a>'.$Active; 
?>
<div class="news-info">
            Просмотров: <?php echo ($Row['reading'] + 1); ?> | Добавил: <?php echo $Row['added']; ?> | Дата: <?php echo $Row['date']; ?><?php echo $edit; ?>
        </div>
        <div class="news-title">
            <b><?php echo $Row['name']; ?></b>
        </div>
        <div class="news-content">
            <?php echo $Row['text']; ?>
        </div>
        <?php Comments(); ?>
        </div>
        </div>




</body>
</html>