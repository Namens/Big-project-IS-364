<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="/access/css/style.css">
</head>
<body>
    
    <main class="main">

        <form method="POST" class="card" id='form'>

            <h2>Вход</h2>
            
            <label for="email">Email</label>
            <input type="email" name="email" class="inp" required><br>
            
            <label for="password_hash">Пароль</label>
            <input type="password" name="password_hash" class="inp" required><br>

            <div class="btn-box">
                <a href="/index.php">Не зарегестрирован</a>

                <button type="submit" class="btn">Войти</button>
                
            </div>


        </form>

    </main>


</body>
</html>

    <?php 
        include 'access/function.php';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email']);  
            $password_hash = htmlspecialchars($_POST['password_hash']);  
        
        
            login_user($email, $password_hash);
        };

    ?>
