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

        
        <label for="new_password">Новый пароль</label>
        <input type="email" name="new_password" class="inp"><br>

        <button type="submit" class="btn">Готово</button>

        </form>
    </main>
</body>
</html>

<?php
        include 'function.php';
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $new_password = $_POST['new_password'];
            
                Change_password($new_password);
            };
    
        ?>


