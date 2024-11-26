
<?php
    include 'access/function.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && $_POST['new_password']) {
        $email = $_POST['email'];
        $new_password = $_POST['new_password'];


        $token = bin2hex(random_bytes(8));
        $_SESSION['token'] = $token;
        
        Reset_password($email, $token);

        $code = substr($token, 0, 6);
        $_SESSION['code'] = $code;
        $_SESSION['start_time'] = time();

        echo "<h3>Your code: $code</h3>";
        echo '<form method="post" action="">
                <input type="text" name="input_code" placeholder="Enter code" required>
                <button type="submit" name="submit_code">Submit</button>
            </form>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_code'])) {
        $input_code = $_POST['input_code'];
        $email = $_POST['email'];
        $new_password = $_POST['new_password'];
        $current_time = time();
        $start_time = $_SESSION['start_time'];
        $elapsed_time = $current_time - $start_time;

        if ($input_code === $_SESSION['code'] && $elapsed_time <= 10) {
            // echo " Успешно";
            // header("Location: change-pw.php");
            $update = "UPDATE users SET password = '$new_password' WHERE email = '$email'";
            $update_result = mysqli_query($_SERVER['link'], $update);
            echo "Пароль изменен";

        } else {
            echo " Время использование токена вышло или неправильный код";
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля</title>
    <link rel="stylesheet" href="/access/css/style.css">
</head>
<body>

    <main class="main">
        <form method="POST" class="card" id='form'>
        <h2>Восстановление пароля</h2>


        <label for="email">Эл.почта</label>
        <input type="email" name="email" class="inp"><br>
        
        <label for="new_password">Новый пароль</label>
        <input type="text" name="new_password" class="inp"><br>

        <button type="submit" class="btn" name="submit">Готово</button>


        </form>
    </main>
</body>
</html>




