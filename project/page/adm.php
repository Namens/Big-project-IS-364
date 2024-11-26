<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
    <link rel="stylesheet" href="/access/css/style.css">

</head>
<body>

    <main class="main">

        <form method="POST" class="card" id='form'>

            <h2>Админ панель</h2>
            
            <label for="email">Эл.почта</label>
            <input type="email" name="email" class="inp" required><br>
            
            <label for="password_hash">Пароль</label>
            <input type="password" name="password_hash" class="inp" required><br>

            <div class="btn-box">
                <a href="enter.php">Назад</a>

                <button type="submit" class="btn">Войти</button>
                
            </div>


        </form>

    </main>


</body>
</html>

<?php 
        include 'function.php';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password_hash = $_POST['password_hash'];
        
            Enter_admin($email, $password_hash);
        };

    ?>