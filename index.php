<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .messageBlock{
            text-align: center;
            border: 1px solid #3ed714;
            background-color: #3ed714;
            font-size: 20px;
            padding: 10px;
        }

        .PageSelector{
            margin: 10px;
        }

        .SwchItem, .SwchItemCur{
            padding: 2px 15px;
            background-color: #d44b38;
        }

        .SwchItemCur{
            background-color: #35886E;
        }
    </style>
</head>
<body>

</body>
</html>

<?php 
include_once 'setting.php';
session_start();
$connect = mysqli_connect(HOST, USER, PASS, DB);

if ($_SESSION['USER_LOGIN_IN'] != 1 and $_COOKIE['user']){
    $userCookie = $_COOKIE['user'];
    $userCookie = mysqli_real_escape_string($connect, $userCookie); // Защита от SQL-инъекций

    $Row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT `id`, `name`, `name`, `regdate`, `email`, `avatar`, `groups` FROM `users` WHERE `password` = '$userCookie'"));
    $_SESSION['USER_LOGIN'] = $Row['login'];
$_SESSION['USER_ID'] = $Row['id'];
$_SESSION['USER_NAME'] = $Row['name'];
$_SESSION['USER_REGDATE'] = $Row['regdate'];
$_SESSION['USER_EMAIL'] = $Row['email'];
$_SESSION['USER_AVATAR'] = $Row['avatar'];
$_SESSION['USER_GROUPS'] = $Row['groups'];
$_SESSION['USER_LOGIN_IN'] = 1;
}


if ($_SERVER['REQUEST_URI'] == '/'){
 $page = 'index';
 $module = 'index';
} else {
 $URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
 $URL_Parts = explode('/', trim($URL_Path, ' /'));
 $page = array_shift($URL_Parts);
 $module = array_shift($URL_Parts);

 if(!empty($module)){
    $param = array();
    for($i = 0; $i < count($URL_Parts); $i++){
    $param[$URL_Parts[$i]] = $URL_Parts[++$i];
}
}
}



 if ($page === 'index') include('page/index.php');
else if ($page == 'login') include('page/login.php');
else if ($page == 'register') include('page/register.php');
else if ($page == 'account') include('form/account.php');
else if ($page == 'profile') include('page/profile.php');
else if ($page == 'restore') include('page/restore.php');
else if ($page == 'chat') include('page/chat.php');

else if ($page == 'news'){
if (!$module or $page == 'news' and $module == 'category' or $page == 'news' and $module == 'main') include('module/news/main.php');
else if ($module == 'material'){
    include('module/comments/main.php');
    include('module/news/material.php');    
} 
else if ($module == 'add') include('module/news/add.php');
else if ($module == 'edit') include('module/news/edit.php');
else if ($module == 'control') include('module/news/control.php');
}

else if ($page == 'loads'){
    if (!$module or $page == 'loads' and $module == 'category' or $page == 'loads' and $module == 'main') include('module/loads/main.php');
    
    else if ($module == 'material'){
        include('module/comments/main.php');
        include('module/loads/material.php');
    } 

    else if ($module == 'add') include('module/loads/add.php');
    else if ($module == 'edit') include('module/loads/edit.php');
    else if ($module == 'control') include('module/loads/control.php');
    else if ($module == 'download') include('module/loads/download.php');
}

else if ($page == 'comments'){
    if ($module == 'add') include('module/comments/add.php');
    else if ($module == 'control') include('module/comments/control.php');
}


function ULogin($p1){
if ($p1 <= 0 and $_SESSION['USER_LOGIN_IN'] != $p1) MessageSend(1,'Данная страница доступна только для гостей.', '/');
else if ($_SESSION['USER_LOGIN_IN'] != $p1) MessageSend(1,'Данная страница доступна только для пользователей.', '/');
}


function MessageSend($p1, $p2, $p3 = ''){
    if($p1 == 1) $p1 = 'Ошибка';
    else if($p1 == 2) $p1 = 'Подсказка';
    else if($p1 == 3) $p1 = 'Информация';
        $_SESSION['message'] = '<div class="messageBlock"><b>'.$p1.'</b>: '.$p2.'</div>';
        if($p3) $_SERVER['HTTP_REFERER'] = $p3;
        exit(header('Location: '.$_SERVER['HTTP_REFERER']));
    }
    
    
    function MessageShow(){
    if($_SESSION['message']) $Message = $_SESSION['message'];
    echo $Message;
    $_SESSION['message'] = array();
    }

    function UserGroups($p1){
        if ($p1 == 0) return 'Пользователь';
        else if ($p1 == 1) return 'Модератор';
        else if ($p1 == 2) return 'Администратор';
        else if ($p1 == -1) return 'Заблокирован';
    }

    function UAccess($p1){
        if ($_SESSION['USER_GROUPS'] < $p1) MessageSend(1, 'У вас нет прав доступа для просмотра данной страницы сайта.', '/');
    }


function RandomString($p1){
    $Char = '0123456789abcdefghiklmnopqrstvxyz';
    for ($i = 0; $i < $p1; $i ++) $String .= $Char[rand(0, strlen($Char) - 1)];
    return $String;
}

function HideEmail($p1){
    $Explode = explode('@', $p1);
    return $Explode[0].'@*****';
}



function FormChars ($p1){
    return nl2br(htmlspecialchars(trim($p1), ENT_QUOTES), false);
}

function GenPass($p1, $p2){
 return md5('qweasd'.md5('321'.$p1.'123').md5('958'.$p2.'534'));
}

function ModuleId($p1){
if($p1 == 'news') return 1;
else if($p1 == 'loads') return 2;
else MessageSend(1, 'Модуль не найден.', '/');
}


function PageSelector($p1, $p2, $p3, $p4 = 7){
$page = ceil($p3[0] / $p4);
if($page > 1) {
    echo '<div class="PageSelector">';

    for ($i = ($p2 -3); $i < ($page + 1); $i++) {
        if ($i > 0 and $i <= ($p2 + 3)){

            if ($p2 == $i) $Swch = 'SwchItemCur';
            else $Swch = 'SwchItem';

            echo '<a class="'.$Swch.'" href="'.$p1.$i.'">'.$i.'</a>';
}
}
echo '</div>';
}
}


function MiniImg($p1, $p2, $p3, $p4, $p5 = 50){
$Scr = imagecreatefromjpeg($p1);
$Size = getimagesize($p1);
$Tmp = imagecreatetruecolor($p3, $p4);

imagecopyresampled($Tmp, $Scr, 0, 0, 0, 0, $p3, $p4, $Size[0], $Size[1]);

imagejpeg($Tmp, $p2, $p5);
imagedestroy($Scr);
imagedestroy($Tmp);
}


function menu(){
    if ($_SESSION['USER_LOGIN_IN'] != 1) {
        // User is not logged in
        echo '<header>
                <input type="checkbox" id="menu-toggle" class="menu-toggle">
                <div class="header-content">
                    <a href="/" class="header-name">Web-platform</a>
                    <a href="/" class="header-text">Guides</a>
                    <a href="/news" class="header-text">News</a>
                    <a href="/loads" class="header-text">File Catalog</a>
                    <a href="/login" class="log-in-text log-sign-block">Log In</a>
                    <a href="/register"><button class="but-text">Sign Up</button></a>
                </div>
                <label for="menu-toggle" class="burger-icon">&#9776;</label>
                <div class="registration"></div>
              </header>';
    } else {
        // User is logged in
        echo '<header>
                <input type="checkbox" id="menu-toggle" class="menu-toggle">
                <div class="header-content">
                    <a href="/" class="header-name">Web-platform</a>
                    <a href="/" class="header-text">Guides</a>
                    <a href="/news" class="header-text">News</a>
                    <a href="/loads" class="header-text">File Catalog</a>
                    <a href="/profile"><button class="button-registration but-text">Profile</button></a>
                    <a href="/chat"><button class="button-registration but-text">Chat</button></a>
                    <a href="/account/logout"><button class="button-registration but-text">Logout</button></a>
                </div>
                <label for="menu-toggle" class="burger-icon">&#9776;</label>
                <div class="registration"></div>
              </header>';
    }
}

function style() {
    echo '* {padding: 0;margin: 0;box-sizing: border-box;}';

    // Common styles for both large and small screens
    echo 'header {display: flex;justify-content: space-between;align-items: center;height: 53px;border-bottom: 1px solid #7C7B7B;}
    .header-content {display: flex;align-items: center; z-index: 2;}
    .header-content a {font-family: Inter, sans-serif;text-decoration: none;color: #333366;transition: color 0.3s;}
    .header-content a:hover {color: #ff0000;}
    .header-name{font-size: 32px;font-weight: bold;margin-left: 20px;}
    .header-text{font-size: 24px;margin: 0 20px;}
    .registration {display: flex;align-items: center; margin-left: auto;}
    .log-sign-block{
        padding: 20px;
    }
    .but-text{
        padding: 5px 15px;
        font-size: 20px;
        border-radius: 5px;
        border: 0px;
        color: #ffffff;
        background-color: #000000;
        cursor: pointer;
  transition: background-color 0.3s;
    }
    .log-in-text {
        font-size: 20px;
    }

    ';
    

    // Media query to hide the menu by default and show it only when the screen width is 320px or less
    echo '@media only screen and (max-width: 822px) {
        header {position: relative;}.header-content, .registration {display: none;flex-direction: column;position: absolute;top: 53px;left: 0;width: 100%;background-color: #fff;z-index}
        .header-content a, .log-in, .button-registration, .log-in-text, .but-text {display: block;width: 100%;text-align: center;padding: 15px 0;}
        .registration {margin-top: 10px; margin-left: 0;}#menu-toggle:checked + .header-content, #menu-toggle:checked + .burger-icon + .registration {display: flex;}
        .burger-icon {font-size: 28px;margin-right: 10px;}.menu-toggle {display: none;}.but-text {border: 0px;color: #ffffff;background-color: #000000;margin-right: 20px;cursor: pointer;transition: background-color 0.3s;}
    }';

    // Hide checkbox and burger icon on larger screens
    echo '@media only screen and (min-width: 823px) { #menu-toggle, .burger-icon { display: none; }
        .log-in-text, .but-text {display: block; margin-right: 10px;}}';
}
?>

        
    