<?php
ULogin(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>


    <style>
        <?php style() ?>

        .form-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh; /* Adjusted form height */
             /* Adjusted margin to lift the form higher */
        }

        form {
            border: 1px solid black;
            padding: 20px;
            border-radius: 10px;
            width: 80%; /* Adjusted width for better responsiveness */
            max-width: 400px;
            box-shadow: 8px 8px 8px 0 rgba(0,0,0,0.2);
            box-sizing: border-box;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            height: 40px;
            border-radius: 10px;
            border: 1px solid #979797;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-size: 20px;
        }

        .button-reg {
            border: none;
            border-radius: 20px;
            background-color: #4848EB;
            font-size: 20px;
            font-family: Inter, sans-serif;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
        }

        .button-reg:hover {
            background-color: #3030A0;
        }

        .pass-text {
            margin: 5px 0;
            font-size: 10px;
            color: #737373;
            font-family: Montserrat, sans-serif;
            font-weight: light;
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
 MessageShow(); 
?>
<div class="form-block">

    <form action="/account/register" method="POST">
        <div class="input-width"> 
        <div class="form-group">
            <label for="name">Имя</label>
            <input type="text" id="name" name="name" maxlength="50" pattern="[А-Яа-яЁё]{2,50}" title="не менее 2 и не более 50 символов" required>
        </div>
        <div class="form-group">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" maxlength="20" pattern="[A-Za-z0-9]{3,20}" title="не менее 3 и не более 20 латынских символов или цифр" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" maxlength="25"  title="не менее 8 и не более 25 латынских символов или цифр" required>
            <p class="pass-text">Пароли должны содержать не менее восьми символов, включая как минимум 1 букву и 1 цифру.</p>
        </div>
        <br>
        <input type="submit" name="enter" value="Зарегистрироваться" class="button-reg">
        
        
    </form>
    </div>
</div>


</body>
</html>