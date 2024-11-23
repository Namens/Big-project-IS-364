<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="access/css/style.css">
</head>
<body>
    
    <main class="main">

        <form method="POST" class="card" id='form'>

            <h2>Регистрация</h2>
            
            <label for="username">Имя</label>
            <input type="text" name="username" class="inp" required><br>
            
            <label for="email">Эл.почта</label>
            <input type="email" name="email" class="inp" required><br>
            
            <label for="password_hash">Пароль</label>
            <input type="password" name="password_hash" class="inp" required><br>

            <div class="btn-box">
                <a href="page/enter.php">Уже зарегестрирован</a> 
                              
                <button type="submit" class="btn r">Зарегестрироваться</button>
                
            </div>


        </form>

    </main>


</body>
</html>

    <?php 
        include 'access/function.php';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username']);  
            $email = htmlspecialchars($_POST['email']);  
            $password_hash = htmlspecialchars($_POST['password_hash']);  
        
        
            create_user($username, $email, $password_hash);
        };

    ?>
