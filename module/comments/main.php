<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

.comment-info {
            color: red;
            font-style: italic;
        }

        .comment-form {
            margin-bottom: 20px;
        }

        .comment-form form {
            display: flex;
            gap: 10px;
        }

        .comment-form input[type="text"] {
            flex: 1;
            padding: 8px;
        }

        .comment-form input[type="submit"] {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .comment-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .comment {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }

        .comment-date {
            font-weight: bold;
            color: #333;
        }

        .comment-text {
            margin-top: 5px;
        }

        .admin-text {
            text-decoration: none;
            color: blue;
            margin-left: 10px;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ccc;
        }

        .edit-comment-form {
    margin: 10px 0;
}

.edit-comment-form textarea {
    width: 100%;
    height: 100px;
    padding: 8px;
    margin-bottom: 10px;
    
}


.save-button,
.cancel-button {
    padding: 8px 15px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.save-button {
    background-color: #4CAF50;
    color: white;
}

.save-button:hover {
    background-color: #45a049;
}

.cancel-button {
    background-color: #ccc;
    color: black;
}

.cancel-button:hover {
    background-color: #999;
}

        
    </style>
</head>
<body>
<?php
function Comments(){
    echo '<hr>';
    global $connect, $module, $page, $param;

    if ($_SESSION['USER_LOGIN_IN'] != 1) {
        echo '<div class="comment-info">Оставлять комментарии могут только зарегистрированные пользователи.</div>';
    } else {
        echo '<div class="comment-form">
                <form action="/comments/add/module/' . $page . '/id/' . $param['id'] . '" method="POST">
                    <input type="text" name="text" placeholder="Введите сообщение..." required>
                    <input type="submit" name="enter" value="Отправить">
                </form>
            </div>';
    }

    $ID = ModuleId($page);

    $Count = mysqli_fetch_row(mysqli_query($connect, 'SELECT COUNT(`id`) FROM `comments` WHERE `module` = '.$ID.' AND `material` = '.$param['id']));

    if (!$param['page']){
        $param['page'] = 1;
        $result = mysqli_query($connect, 'SELECT `id`, `added`, `date`, `text` FROM `comments` WHERE `module` = '.$ID.' AND `material` = '.$param['id'].' ORDER BY `id` DESC LIMIT 0, 7');
    } else {
        $Start = ($param['page'] - 1) * 7; 
        $result = mysqli_query($connect, str_replace('START', $Start, 'SELECT `id`, `added`, `date`, `text` FROM `comments` WHERE `module` = '.$ID.' AND `material` = '.$param['id'].' ORDER BY `id` DESC LIMIT START, 7'));
    }

    PageSelector('/loads/main/page/', $param['page'], $Count);

    while ($Row = mysqli_fetch_assoc($result)) {
        if ($_SESSION['USER_GROUPS'] >= 1) $Admin = ' |<a href="/comments/control/action/delete/id/'.$Row['id'].'" class="admin-text">Удалить</a> | <a href="/comments/control/action/edit/id/'.$Row['id'].'" class="admin-text">Редактировать</a>';
        if ($Row['id'] == $_SESSION['COMMENTS_EDIT']) $Row['text'] = 
    '<form action="/comments/control" method="POST" class="edit-comment-form">
        <textarea name="text" placeholder="Введите свой коммментарий" required>'.$Row['text'].'</textarea>
        <div class="edit-comment-buttons">
            <input type="submit" name="save" value="Сохранить" class="save-button">
            <input type="submit" name="cancel" value="Отменить" class="cancel-button">
        </div>
    </form>';




        echo '<div class="comment">
                <span class="comment-date"><b>'.$Row['added'].'</b> | '.$Row['date'].$Admin.'</span>
                <div class="comment-text">'.$Row['text'].'</div>
            </div>';
    }
}

?>
</body>
</html>
