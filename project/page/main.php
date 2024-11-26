<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование аккаунта</title>
    <link rel="stylesheet" href="/access/css/style.css">
</head>
<body>
    
    <main class="main">

        <form method="POST" class="card" id='form'>

            <h2>Редактирование аккаунта</h2>

            <label for="old_email">Старая Эл. почта</label>
            <input type="email" name="old_email" class="inp" required><br>
            
            <label for="username">Изменить имя</label>
            <input type="text" name="username" class="inp" required><br>
            
            <label for="email">Изменить Эл.почту</label>
            <input type="email" name="email" class="inp" required><br>
            
            <label for="password_hash">Изменить Пароль</label>
            <input type="password" name="password_hash" class="inp" required><br>

            <div class="btn-box">
                
                <button type="submit" class="btn">Изменить</button>
                
            </div>


        </form>

    </main>
</body>
</html>

<?php 
        include 'function.php';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old_email = $_POST['old_email'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password_hash = $_POST['password_hash'];
        
            Edit_user_data($old_email, $username, $email, $password_hash);
        };

    ?>