<?php
$param['id'] += 0;
if ($param['id'] == 0) MessageSend(1, 'URL адрес указан неверно.', '/loads');
$Row = mysqli_fetch_assoc(mysqli_query($connect, 'SELECT `name`, `reading`, `download`, `added`, `text`, `date`, `active`, `dimg`, `dfile`, `link` FROM `load` WHERE `id` = '.$param['id']));

if (!$Row['name']) MessageSend(1, 'Такой новости не существует.', '/loads');
if (!$Row['active'] and $_SESSION['USER_GROUPS'] != 2 and $_SESSION['USER_GROUPS'] != 1) MessageSend(1, 'Новость ожидает модерации.', '/loads');

if($Row['link'] and !$Row['dfile']) $Download = $Row['link'];
else $Download = '/loads/download/id/'.$param['id'];

mysqli_query($connect, 'UPDATE `load` SET `reading` = `reading` + 1 WHERE `id` = '.$param['id']);

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
            line-height: 1.6;
            background-color: #f5f5f5;
            padding: 20px;
            font-family: Arial, sans-serif;
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

        img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-top: 10px;
        }

        .download-link{
            text-decoration: none;
            color: black;
        }

        @media only screen and (max-width: 600px){
        .container{
        max-width: 100%;
        }
        }

        @media only screen and (max-width: 350px){
        .container{
        max-width: 100%;
        }

        input[type="text"]{
            width: 15px;
            font-size: 11px;
        }

        input[type="submit"]{
            width: 40%;
            font-size: 11px;
        }
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
            if (!$Row['active']) $Active = ' | <a href="/loads/control/id/'.$param['id'].'/command/active">Активировать новость</a>';
            if ($_SESSION['USER_GROUPS'] >= 1) $edit = ' | <a href="/loads/edit/id/'.$param['id'].'">Редактировать новость</a> | <a href="/loads/control/id/'.$param['id'].'/command/delete">Удалить новость</a>'.$Active; 
        ?>

        <div class="news-info">
        <?php echo '<a href="'.$Download.'" class="download-link"><b>Скачать</b></a>'?> | Просмотров: <?php echo ($Row['reading'] + 1); ?> | Скачиваний: <?php echo $Row['download']; ?> | Добавил: <?php echo $Row['added']; ?> | Дата: <?php echo $Row['date']; ?><?php echo $edit; ?>
        </div>
        <div class="news-title">
            <b><?php echo $Row['name']; ?></b>
        </div>
        <div class="news-content">
            <?php echo '<img src="/catalog/img/'.$Row['dimg'].'/'.$param['id'].'.jpg" alt="'.$Row['name'].'">' ?>
        </div>
        <div class="news-content">
            <?php echo $Row['text']; ?>
        </div>
        <?php Comments(); ?>
    </div>
</div>


</body>
</html>