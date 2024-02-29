
<?php
ULogin(1);

if ($_POST['enter'] and $_POST['text']) {
$_POST['text'] = FormChars($_POST['text']);
mysqli_query($connect, "INSERT INTO `chat` VALUES ('', '$_POST[text]', '$_SESSION[USER_LOGIN]', NOW())");
exit(header('location: /chat'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чат</title>
    <style>
        <?php style() ?>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .chat-block {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            height: calc(100vh - 100px);
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            overflow-y: auto;
            border-radius:  0 0 8px 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }


        .bc-text {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .bc-text input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 31vw;
        }

        .bc-text input[type="submit"] {
            border: none;
            border-radius: 5px;
            background-color: #4848EB;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            padding: 8px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .bc-text input[type="submit"]:hover {
            background-color: #3030a5;
        }

        form{
            margin: auto;
        }
        .ChatBlock {
            margin: 10px 0;
            padding: 10px;
            background-color: #e0e0e0;
            border-radius: 8px;
        }

        .roll{
            overflow-y: auto;
    scrollbar-width: thin; /* For Firefox */
    scrollbar-color: transparent transparent; /* For Firefox */
            
        }

        
.roll::-webkit-scrollbar {
    width: 0; /* Make scrollbar invisible */
}

/* Optional: Style the scrollbar track and handle for WebKit browsers */
.roll::-webkit-scrollbar-track {
    background: transparent;
}

.roll::-webkit-scrollbar-thumb {
    background-color: transparent;
}

@media only screen and (max-width: 600px) {
    .bc-text input[type="text"] {
  flex: 1;
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 5px;
  width: 45vw;
    }

    .bc-text input[type="submit"] {
  border: none;
  border-radius: 5px;
  background-color: #4848EB;
  font-size: 16px;
  font-weight: bold;
  color: #fff;
  padding: 5px 10px;
  cursor: pointer;
  transition: background-color 0.3s ease;
    }
}





    </style>
</head>
<body>

<?php menu(); 
 MessageShow(); ?>


<div class="chat-block">
    <div class="roll">

<?php
$Query = mysqli_query($connect, 'SELECT * FROM `chat` ORDER BY `time` DESC LIMIT 50');
$messages = [];
while ($Row = mysqli_fetch_assoc($Query)){
    $messages[] = '<div class="ChatBlock"><span><b>' . $Row['user'] . '</b> | ' . $Row['time'] . '</span><br>' . $Row['message'] . '</div>';
}

// Reverse the messages array to display newer messages at the bottom
$messages = array_reverse($messages);

echo implode('', $messages); // Output the messages

?>


</div>
    <div class="bc-text">
        <form action="/chat" method="POST">
            <input type="text" name="text" placeholder="Введите сообщение..." required>
                <input type="submit" name="enter" value="Отправить">
            </div>
        </form>
</div>
</div>

</body>
</html>