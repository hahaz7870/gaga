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
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 60vh; /* Adjust the minimum height as needed */
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



.form-group {
  margin: 40px 0;
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

.pass-text{
    margin: 5px 0 ;
    font-size: 10px;
    color: #737373;
    font-family: Montserrat, sans-serif;
    font-weight: light;
}

.check-label {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-top: -20px; /* Задаем отрицательный отступ, чтобы поднять чекбокс */
}

.check-label input[type="checkbox"] {
    margin-right: 5px; /* Отступ между чекбоксом и текстом */
}
.res-text{
    display: flex;
    align-items: center;
    justify-content: center;
    color: #558cb7;
    text-decoration: none;
    margin-top: 15px;
}

h1{
    font-size: 25px;
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
<form action="/account/restore" method="POST">
    <h1 class="h1">Восстановление пароля</h1>
        <div class="form-group">
            <label for="login">Логин</label>
            <input type="text" name="login" maxlength="20" pattern="[A-Za-z0-9]{3,20}" title="не менее 3 и не более 20 латынских символов или цифр" required>        </div>
        <div class="form-group">
            <input type="submit" name="enter" value="Восстановить" class="button-reg">
        </div>
</form>
</div>

</body>
</html>