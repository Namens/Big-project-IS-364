<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование поста</title>
</head>
<body>



    <form method="post">
        <label for="title">Заголовок:</label>
        <input type="text" name="title" id="title" value="<?php echo $post['title']; ?>" required>
        <label for="content">Текст:</label>
        <textarea name="content" id="content" required><?php echo $post['content']; ?></textarea>
        <button type="submit">Сохранить изменения</button>
    </form>
    
    <?php
        include 'function.php';
    ?>
    
</body>
</html>