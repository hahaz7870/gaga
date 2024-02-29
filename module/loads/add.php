<?php 
ULogin(1);
if ($_SESSION['USER_GROUPS'] >= 1) $Active = 1;
else $Active = 0;

if($_POST['enter'] and $_POST['text'] and $_POST['name'] and $_POST['cat'] ){
if($_FILES['img']['type'] != 'image/jpeg') MessageSend(2, 'Неверный тип изображения.');
$_POST['name'] = FormChars($_POST['name']);
$_POST['text'] = FormChars($_POST['text']);
$_POST['link'] = FormChars($_POST['link']);
$_POST['cat'] += 0;
if (!$_FILES['file']['tmp_name'] and !$_POST['link']) MessageSend(2, 'Необходимо выбрать файл или указать ссылку.');


if ($_FILES['file']['tmp_name']){
if ($_FILES['file']['type'] != 'application/octet-stream' && $_FILES['file']['type'] != 'application/zip' && $_FILES['file']['type'] != 'application/x-zip-compressed') MessageSend(2, 'Неверный тип файла.');
$_POST['link'] = 0;
}
else $Num_file = 0;


$MaxId = mysqli_fetch_row(mysqli_query($connect, 'SELECT max(`id`) FROM `load`'));
if ($MaxId[0] == 0) mysqli_query($connect, 'ALTER TABLE `load` AUTO_INCREMENT = 1');
$MaxId[0] += 1;

foreach(glob ('catalog/img/*', GLOB_ONLYDIR) as $Num => $Dir){
    $Num_img ++;
    $Count = sizeof(glob($Dir.'/*.*'));
    if ($Count < 250) {
        move_uploaded_file($_FILES['img']['tmp_name'], $Dir.'/'.$MaxId[0].'.jpg');
        break;
    }
}

MiniImg('catalog/img/'.$Num_img.'/'.$MaxId[0].'.jpg', 'catalog/mini/'.$Num_img.'/'.$MaxId[0].'.jpg', 220, 220);

if ($_FILES['file']['tmp_name']){
foreach(glob ('catalog/file/*', GLOB_ONLYDIR) as $Num => $Dir){
    $Num_file ++;
    $Count = sizeof(glob($Dir.'/*.*'));
    if ($Count < 250) {
        move_uploaded_file($_FILES['file']['tmp_name'], $Dir.'/'.$MaxId[0].'.zip');
        break;
    }
}
}

mysqli_query($connect, "INSERT INTO `load` VALUES ($MaxId[0], '$_POST[name]', $_POST[cat], 0, 0, '$_SESSION[USER_LOGIN]', '$_POST[text]', NOW(), $Active, $Num_img, $Num_file, '$_POST[link]')");
MessageSend(2, 'Файл добавлен.', '/loads');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить файл</title>
    <style>
        <?php style() ?>

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
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

        input[type='url'],
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
            position: relative;
        }

        .file-upload-btn,
        .file-upload-label {
            background-color: #007bff;
            color: white;
            padding: 12px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        input[type='file'] {
            display: none;
            position: absolute;
            top: 0;
            right: 0;
            opacity: 0;
        }

        .file-name {
            margin-top: 10px;
            font-size: 16px;
        }



        @media screen and (max-width: 768px) {
            form {
                padding: 20px;
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

        .file-container {
            padding: 0 0 20px;
        }

        .file-upload-btn + label,
        .file-upload-label + label {
            margin-left: 0px;
        }
    </style>
</head>
<body>
<?php menu();
MessageShow(); ?>

<div class="container">
    <form action="/loads/add" method="POST" enctype="multipart/form-data">
        <input type="text" id="name" name="name" required placeholder="Название материала">
        <select name="cat" id="category" size="1">
            <option value="1">Категория 1</option>
            <option value="2">Категория 2</option>
            <option value="3">Категория 3</option>
        </select>
        <input type="url" id="name" name="link"  placeholder="Ссылка для скачивания">
        <div class="file-container">
            <label for="file" class="file-upload-label">Выберите файл</label>
            <!-- Используем input type="file" для загрузки и отслеживания изменений в JS -->
            <input type="file" name="file" id="file" class="file-upload-btn"  onchange="updateFileName(this)">
            <label for="img" class="file-upload-label">Выберите изображение</label>
            <input type="file" name="img" id="img" class="file-upload-btn"  onchange="updateFileName(this)">
            <!-- Добавленный div для отображения имени файла -->
            <div id="file-name" class="file-name"></div>
        </div>
        <textarea name="text" required placeholder="Текст материала"></textarea>
        <input type="submit" name="enter" value="Добавить" class="button-reg" onclick="showFileAddedMessage()">
    </form>

    <div id="fileAddedMessage" style="text-align: center; color: green; font-weight: bold;"></div>

    
    <script>
        function updateFileName(input) {
            var fileName = input.files[0].name;
            var button = input.previousElementSibling;
            button.innerHTML = 'Выбран файл: ' + fileName;
        }
    </script>

</div>

</body>
</html>