<?php 
ULogin(1);
if ($_SESSION['USER_GROUPS'] >= 1) $Active = 1;
else $Active = 0;


if($_POST['enter'] and $_POST['text'] and $_POST['name'] and $_POST['cat']){
$_POST['name'] = FormChars($_POST['name']);
$_POST['text'] = FormChars($_POST['text']);
$_POST['cat'] += 0;
mysqli_query($connect, "INSERT INTO `news` VALUES ('', '$_POST[name]', $_POST[cat], 0, '$_SESSION[USER_LOGIN]', '$_POST[text]', NOW(), $Active)");
MessageSend(2, 'Новость отправлена на модерацию, ожидайте.', '/news');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить новость</title>
    <style>
        <?php style() ?>

        .container {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 60vh; /* Adjust the minimum height as needed */
    }
    form {
        width: 80%;
        max-width: 600px;
        padding: 40px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        margin: 20px;
        border: 1px solid #e1e1e1;
    }
    input[type='text'],
    select,
    textarea {
        width: calc(100% - 20px);
        padding: 15px;
        margin-bottom: 20px;
        border: 2px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 18px;
    }
    textarea {
        height: 200px;
        resize: vertical;
    }
    .button-reg {
        background-color: #4caf50;
        color: white;
        padding: 18px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: block;
        margin: 0 auto;
        font-size: 20px;
        font-weight: bold;
    }
    
    @media screen and (max-width: 600px) {
        form {
            padding: 20px;
            width: 90%;
        }
        input[type='text'],
        select,
        textarea {
            font-size: 16px;
        }
        .button-reg {
            font-size: 18px;
        }
    }
    </style>
</head>
<body>
<?php menu();
MessageShow(); ?>
<div class="container">
<form action="/news/add" method="POST">
        <input type="text" id="name" name="name" required placeholder="Название новости">
        <select name="cat" id="category" size="1">
            <option value="1">Категория 1</option>
            <option value="2">Категория 2</option>
            <option value="3">Категория 3</option>
        </select>
        <textarea name="text" required placeholder="Текст новости"></textarea>
        <input type="submit" name="enter" value="Добавить" class="button-reg">
    </form>
</div>
</body>
</html>