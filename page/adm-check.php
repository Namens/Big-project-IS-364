<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ палень</title>
    <link rel="stylesheet" href="/access/css/style.css">
</head>
<body>
    
    <main class="main">

        <form method="POST" class="card" id='form'>

            <h2>Админ панель</h2>

            <?php
                include 'function.php';
 
                users_check();
            ?>

            <a href="adm_main_page.php" class="adm">Назад</a>


        </form>

    </main>
</body>
</html>
