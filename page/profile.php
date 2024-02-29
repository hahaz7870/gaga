<?php
ULogin(1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <style>
        <?php style() ?>

        .form-block {
            display: flex;
            flex-direction: column;
            max-width: 70%;
            margin: auto;
            padding: 20px;
            background-color: #E8E7E7;
            height: 300px;
        }

        .content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .profile-section {
            flex: 0 0 60%;
            display: flex;
            align-items: center;
        }

        .avatar-square {
            width: 180px;
            height: 180px;
            background-color: #ccc;
            margin-right: 20px;
        }

        .avatar-square img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-self: stretch; /* Растянуть по высоте */
        }

        .profile-info p {
            font-size: 22px;
            padding: 5px 0;
            font-family: Inter, sans-serif;
        }

        .block-new_pass {
            text-align: center;
        }

        .button-reg {
            width: 80%;
            height: 25px;
            border: none;
            background-color: #4848EB;
            font-size: 15px;
            font-family: Inter, sans-serif;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
        }

        input#password {
            width: 100%;
            height: 25px;
        }

        input#name {
            width: 100%;
            height: 25px;
        }

        .avatar-square a {
            display: flex;
            justify-content: center;
            color: #558cb7
        }

        .avatar-block {
            display: flex;
        align-items: center;
        }

        .butt-avatar {
            margin: 10px 5px;
            flex-shrink: 0; /* Prevent the label from shrinking */
        background-color: #4848EB;
        color: #fff;
        font-size: 12px;
        font-family: Inter, sans-serif;
        font-weight: bold;
        text-align: center;
        line-height: 30px;
        cursor: pointer;
        border: none;
        position: relative;
        overflow: hidden;
        border-radius: 5px;
        padding: 0 5px;
        }

        input[type="file"] {
        display: none; /* Hide the default file input */
    }

    .custom-file-upload {
        flex-shrink: 0; /* Prevent the label from shrinking */
        background-color: #4848EB;
        color: #fff;
        font-size: 12px;
        font-family: Inter, sans-serif;
        font-weight: bold;
        text-align: center;
        line-height: 30px;
        cursor: pointer;
        border: none;
        margin: 10px 0;
        position: relative;
        overflow: hidden;
        border-radius: 5px;
        padding: 0 5px;
    }

        /* Добавляем медиа-запрос для телефонов */
        @media only screen and (max-width: 600px) {
            .form-block {
                max-width: 100%;
                height: 500px;
            }

            .profile-section {
                flex: 1; /* Изменяем ширину на 100% на телефонах */
                margin-bottom: 20px; /* Добавляем отступ между секциями на телефонах */
            }

            .avatar-square {
                margin-right: 15px; /* Убираем правый отступ на телефонах */
                width: 120px;
                height: 120px;
                background-color: #ccc;
            }

            .button-reg {
                width: 100%; /* Ширина кнопки 100% на телефонах */
            }

            .profile-info p{
                font-size: 15px;
                padding: 5px 0;
                font-family: Inter, sans-serif;
            }

            .block-new_pass {
            text-align: center;
            margin-top: 50px;
            font-size: 10px;
        }




    }

        </style>
</head>
<body>

<?php menu();
MessageShow(); ?>
<?php
if ($_SESSION['USER_AVATAR'] == 0) $Avatar = 0;
else $Avatar = $_SESSION['USER_AVATAR'] . '/' . $_SESSION['USER_ID'];
echo
'
<div class="form-block">
    <form action="/account/edit" method="POST" enctype="multipart/form-data" class="content">
        <div class="profile-section">
            <div class="avatar-square">
                <img src="/resource/avatar/' . $Avatar . '.jpg" alt="">
                <div class="avatar-block">
                <label for="file" class="custom-file-upload">Выберите файл</label>
                <input type="file" name="avatar" id="file">
                <input type="submit" name="enter" value="Сохранить" class="butt-avatar">
                </div>
            </div>
            <div class="profile-info">
                <b><p>'.$_SESSION['USER_NAME'].' ('.UserGroups($_SESSION['USER_GROUPS']).')</p></b>
                <p>Дата регистрации: ' . $_SESSION['USER_REGDATE'] . '</p>
                <p>Email: ' . $_SESSION['USER_EMAIL'] . '</p>
            </div>
        </div>
        <div class="block-new_pass">
            <h1>Изменить имя, пароль</h1>
            <br>
            <div class="input-width">
                <div class="form-group">
                    <input type="text" id="name" name="name" placeholder="Имя" maxlength="50" pattern="[А-Яа-яЁё]{2,50}" title="не менее 2 и не более 50 символов" value="' . $_SESSION['USER_NAME'] . '" required>
                </div>
                <br>
                <div class="form-group">
                    <input type="password" id="password" placeholder="Старый пароль" name="opassword" maxlength="25" pattern="[A-Za-z0-9]{8,25}" title="не менее 8 и не более 25 латынских символов или цифр">
                </div>
                <br>
                <div class="form-group">
                    <input type="password" id="password" placeholder="Новый пароль" name="npassword" maxlength="25" pattern="[A-Za-z0-9]{8,25}" title="не менее 8 и не более 25 латынских символов или цифр">
                </div>
                <br>
                <input type="submit" name="enter" value="Сохранить" class="button-reg">
            </div>
        </div>
    </form>
</div>'
?>
</body>
</html>
