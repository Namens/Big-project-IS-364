
<?php
        include 'function.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['new_password']) && !empty($_POST['new_password'])) {
                $email = $_POST['email'];
                $new_password = $_POST['new_password'];
                reeset_password($email, $new_password);
            } else {
                echo "1";
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
        <button type="submit" class="btn" name="submit1">Да</button>




        </form>
    </main>
</body>
</html>



