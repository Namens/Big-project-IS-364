
<?php
        include 'function.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['email']) && isset($_POST['new_password']) && !isset($_POST['submit_next'])) {
                $email = $_POST['email'];
                $new_password = $_POST['new_password'];
                $_SESSION['new_password'] = $new_password;
                T_password($email, $new_password);
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
        <input type="email" name="email" class="inp" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"><br>
        
        <label for="new_password">Новый пароль</label>
        <input type="text" name="new_password" class="inp" value="<?php echo isset($_POST['new_password']) ? htmlspecialchars($_POST['new_password']) : ''; ?>"><br>
        <button type="submit" class="btn" name="submit">Готово</button>

        <label for="new_password">Шести значный код</label>
        <input type="text" name="input_code" class="inp">
        <button type="submit" class="btn" name="submit_next">Да</button>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if(isset($_POST['submit_next'])){
                    $email = $_POST['email'];


                    $input_code = $_POST['input_code'];             
                    $current_time = time();
                    $start_time = $_SESSION['start_time'];
                    $elapsed_time = $current_time - $start_time;
                            
                    $hach_pw = password_hash($_SESSION['new_password'], PASSWORD_DEFAULT);
                                
                    if ($input_code === $_SESSION['code'] && $elapsed_time <= 10) {
                        $update = "UPDATE users SET password = '$hach_pw' WHERE email = '$email'";
                        $update_result = mysqli_query($_SERVER['link'], $update);
                        echo "Пароль изменен!";
                        // header ("Locatio: ")
                    } else {
                        echo "Неверный код или время ожидания истекло";
                    }
                            
                }
            
        }
        ?>


        </form>
    </main>
</body>
</html>



