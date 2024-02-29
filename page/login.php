<?php
ULogin(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <style>
        <?php style() ?>

        .form-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
        }

        form {
            background-color: #ffffff;
            border: 1px solid black;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 47%; /* Slightly narrower form */
            max-width: 300px;
            padding: 20px;
            box-sizing: border-box;
            box-shadow: 8px 8px 8px 0 rgba(0,0,0,0.2);
        }

        .form-group input {
            width: 100%;
            box-sizing: border-box;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
        }

        label {
            font-size: 18px;
            color: #333;
        }

        .button-reg {
            background-color: #4848EB;
            border: none;
            border-radius: 20px;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            padding: 10px;
            cursor: pointer;
        }

        .button-reg:hover {
            background-color: #3030A0;
        }

        .check-label {
            display: flex;
            align-items: center;
            justify-content: center; /* Center the checkbox */
            font-size: 16px;
            color: #333;
            margin-top: 10px;
        }

        .check-label input[type="checkbox"] {
            margin-right: 5px;
        }

        .res-text {
            display: block;
            text-align: center;
            color: #558cb7;
            text-decoration: none;
            margin-top: 15px;
            font-size: 16px;
        }

        @media only screen and (max-width: 600px) {
            form {
                width: 90%;
            }
        }

        .pass-text{
            margin: 5px 0;
        font-size: 10px;
        color: #737373;
        font-family: Montserrat, sans-serif;
        }





    </style>
</head>
<body>

<?php menu(); 
 MessageShow(); ?>
<div class="form-block">
<form action="/account/login" method="POST">
 
        <div class="form-group">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" maxlength="20" pattern="[A-Za-z0-9]{3,20}" title="не менее 3 и не более 20 латынских символов или цифр" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" maxlength="25"  title="не менее 8 и не более 25 латынских символов или цифр" required>
            <p class="pass-text">Пароли должны содержать не менее восьми символов, включая как минимум 1 букву и 1 цифру.</p>
            <input type="submit" name="enter" value="Вход" class="button-reg">
        </div>
        <label class="check-label">
                <input type="checkbox" name="remember">
                Запомнить меня
            </label>
            <a href="/restore" class="res-text">Забыли пароль?</a>
</form>
</div>


</body>
</html>