<?php

if ($module == 'logout' and $_SESSION['USER_LOGIN_IN'] == 1) {
if ($_COOKIE['user']) { 
    setcookie('user', '', strtotime('-30 days'), '/');
unset($_COOKIE['user']);
}

session_unset();
exit(header('Location: /'));
}

// СОЗДАНИЕ НОВОГО ПАРОЛЯ/ИМЕНИ ЧЕРЕЗ ПРОФИЛЬ
if ($module == 'edit' and $_POST['enter']){
    ULogin(1);

    $_POST['opassword'] = FormChars($_POST['opassword']);
    $_POST['npassword'] = FormChars($_POST['npassword']);
    $_POST['name'] = FormChars($_POST['name']);

    if($_POST['opassword'] or $_POST['npassword']){
    if (!$_POST['opassword']) MessageSend(2, 'Не указан старый пароль');
    if (!$_POST['npassword']) MessageSend(2, 'Не указан новый пароль');
    if ($_SESSION['USER_PASSWORD'] != GenPass($_POST['opassword'], $_SESSION['USER_LOGIN'])) MessageSend(2, 'Старый пароль указан неверно.');

    $Password = GenPass($_POST['npassword'], $_SESSION['USER_LOGIN']);

    mysqli_query($connect, "UPDATE `users` SET `password` ='$Password' WHERE `id` = $_SESSION[USER_ID]");
    $_SESSION['USER_PASSWORD'] = $Password;
    }

    if($_POST['name'] != $_SESSION['USER_NAME']){
    mysqli_query($connect, "UPDATE `users` SET `name` ='$_POST[name]' WHERE `id` = $_SESSION[USER_ID]");
    $_SESSION['USER_NAME'] = $_POST['name'];
    }

// ЗАГРУЗКА АВАТАРА
    if($_FILES['avatar']['tmp_name']){
        if($_FILES['avatar']['type'] != 'image/jpeg') MessageSend(2, 'Неверный тип изображения.');
        if($_FILES['avatar']['size'] > 700000) MessageSend(2, 'Размер изображения слишком большой.');

        $Image = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
        $Size = getimagesize($_FILES['avatar']['tmp_name']);
        $Tmp = imagecreatetruecolor(180, 180);
        imagecopyresampled($Tmp, $Image, 0, 0, 0, 0, 180, 180, $Size[0], $Size[1]);


        if($_SESSION['USER_AVATAR'] == 0){
            $Files = glob ('resource/avatar/*', GLOB_ONLYDIR);
            foreach($Files as $Num => $Dir){
                $Num ++;
                $Count = sizeof(glob($Dir.'/*.*'));
                if ($Count < 250) {
                    $Download = $Dir.'/'.$_SESSION['USER_ID'];
                    $_SESSION['USER_AVATAR'] = $Num;
                    mysqli_query($connect, "UPDATE `users` SET `avatar` = $Num WHERE `id` = $_SESSION[USER_ID]");
                    break;
                }
            }
        }
        else $Download = 'resource/avatar/'.$_SESSION['USER_AVATAR'].'/'.$_SESSION['USER_ID'];

        imagejpeg($Tmp, $Download.'.jpg');
        imagedestroy($Image);
        imagedestroy($Tmp);
    }


    MessageSend(3, 'Данные изменены');

}


ULogin(0);

// ВОССТАНОВЛЕНИЕ ПАРОЛЯ
if ($module == 'restore' && isset($param['code'])) {
    if (isset($_SESSION['password_changed']) && $_SESSION['password_changed'] === $param['code']) {
        MessageSend(1, 'Пароль по данной ссылке уже был изменен.', '/login');
    }
    // Получаем информацию о пользователе по коду восстановления
    $code = str_replace(md5('Hello'), '', $param['code']);
    $result = mysqli_query($connect, "SELECT `login` FROM `users` WHERE `id` = '$code'");
    $Row = mysqli_fetch_assoc($result);

    // Проверяем, был ли найден пользователь
    if (!$Row || empty($Row['login'])) {
        MessageSend(1, 'Невозможно восстановить пароль.', '/login');
    }

    // Генерируем новый пароль и обновляем его в базе данных
    $Random = RandomString(15);
    $_SESSION['RESTORE'] = $Random;
    $newPassword = GenPass($Random, $Row['login']);
    mysqli_query($connect, "UPDATE `users` SET `password` ='$newPassword' WHERE `login` = '$Row[login]'");

    // Отправляем сообщение с новым паролем пользователю
    MessageSend(1, 'Пароль успешно изменен, для входа используйте новый пароль <b>'.$Random.'</b>', '/login');
}



if ($module == 'restore' and $_POST['enter']){
    $_POST['login'] = FormChars($_POST['login']);
    if(!$_POST['login']) MessageSend(1,'Невозможно обработать форму.');

    $Row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `id`, `email` FROM `users` WHERE `login` = '$_POST[login]'"));
    if(!$Row['email']) MessageSend(1,'Пользователь не найден.');

    if (isset($_SESSION['restore_requested']) && $_SESSION['restore_requested'] === $_POST['login']) {
        MessageSend(1, 'Вы уже отправляли заявку на восстановление пароля. Проверьте ваш Email <b>'.HideEmail($Row['email']).'</b>.', '/login');
    }

    mail ($Row['email'], 'Web-platform', 'Ссылка для восстановления: http://forum/account/restore/code/'.md5('Hello').$Row['id'], 'From: Web-platform');


        $_SESSION['restore_requested'] = $_POST['login'];
    
        MessageSend(2, 'На ваш Email <b>'.HideEmail($Row['email']).'</b> отправлено сообщение о подтверждении смены пароля');
}



if ($module == 'register' and $_POST['enter']) {
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['login'] = FormChars($_POST['login']);
    $_POST['email'] = FormChars($_POST['email']);
    $_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);

    if (!$_POST['login'] or !$_POST['password'] or !$_POST['name'] or !$_POST['email']) MessageSend(1,'Невозможно обработать форму.');

    $Row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `login` FROM `users` WHERE `login` = '$_POST[login]'"));
    if($Row['login']) exit('Логин <b>'.$_POST['login'].'</b> уже используется.');

    $Row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `email` FROM `users` WHERE `email` = '$_POST[email]'"));
    if($Row['email']) exit('Email <b>'.$_POST['email'].'</b> уже используется.');

    mysqli_query($connect, "INSERT INTO `users` (`login`, `password`, `name`, `regdate`, `email`, `avatar`, `country`) VALUES ('$_POST[login]', '$_POST[password]', '$_POST[name]', NOW(), '$_POST[email]', 0, 0)");


    $code = base64_encode($_POST['email']);
    mail ($_POST['email'], 'Регистрация на Web-platform', 'Ссылка для активации: http://forum/account/activate/code/'.$code, 'From: Web-platform');
    MessageSend(3, 'Регистрация аккаунта успешно завершена, на указанный email адрес <b>'.$_POST['email'].'</b> отправленно письмо о подтверждении регистрации.');
}

else if ($module == 'activate' and $param['code']) {
    $email = base64_decode($param['code']);
    
    // Проверяем, активен ли email
    $result = mysqli_query($connect, "SELECT `active` FROM `users` WHERE `email` = '$email'");
    $user = mysqli_fetch_assoc($result);

    if ($user && $user['active'] == 0) {
        // Если email не активен, активируем его и устанавливаем сессию
        $_SESSION['USER_ACTIVE_EMAIL'] = $email;
        mysqli_query($connect, "UPDATE `users` SET `active` = 1 WHERE `email` = '$email'");
        MessageSend(3, 'Email <b>'.$email.'</b> подтвержден.', '/login');
    } else {
        // Если email уже активен или не найден, выводим ошибку
        MessageSend(1, 'Email адрес уже подтвержден или недействителен.', '/login');
    }
}



else if ($module == 'login' and $_POST['enter']) {

$_POST['login'] = FormChars($_POST['login']);
$_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);

if(!$_POST['login'] or !$_POST['password']) MessageSend(1,'Невозможно обработать форму.');

$Row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `password`, `active` FROM `users` WHERE `login` = '$_POST[login]'"));
if($Row['password'] != $_POST['password']) MessageSend(1,'Неверный логин или пароль.');
if($Row['active'] == 0) MessageSend(1,'Аккаунт пользователя <b>'.$_POST['login'].'</b> не подтвержден.');

$Row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `id`, `login`, `password`, `name`, `regdate`, `email`, `avatar`, `groups` FROM `users` WHERE `login` = '$_POST[login]'"));

$_SESSION['USER_LOGIN'] = $Row['login'];
$_SESSION['USER_PASSWORD'] = $Row['password'];

$_SESSION['USER_AVATAR'] = $Row['avatar'];
$_SESSION['USER_ID'] = $Row['id'];
$_SESSION['USER_NAME'] = $Row['name'];
$_SESSION['USER_REGDATE'] = $Row['regdate'];
$_SESSION['USER_EMAIL'] = $Row['email'];
$_SESSION['USER_GROUPS'] = $Row['groups'];
$_SESSION['USER_LOGIN_IN'] = 1;

if ($_REQUEST['remember']) setcookie('user', $_POST['password'], strtotime('+30 days'), '/');


exit(header('Location: /profile'));
}

?>