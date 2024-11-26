<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание поста</title>
    <link rel="stylesheet" href="/access/css/style.css">
</head>
<body>

<main class="main">

    <form method="POST" class="card" id='form'>

        <h2>Создание поста</h2>

        <label for="title_post">Заголовок поста</label>
        <input type="text" name="title_post" class="inp" required><br>
        
        <label for="text_post">Содержание поста</label>
        <input type="text" name="text_post" class="inp" required><br>
        
        <div class="btn-box">
            <a href="#">Назад</a> 
                        
            <button type="submit" class="btn r">Создать</button>
            
        </div>


    </form>

</main>
    
</body>
</html>

<?php
    include 'function.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title_post = $_POST['title_post'];
        $text_post = $_POST['text_post'];
        
        start_post($title_post, $text_post);
    }
?>