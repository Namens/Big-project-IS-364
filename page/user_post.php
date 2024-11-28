<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ваши посты</title>
    <link rel="stylesheet" href="/access/css/style.css">
</head>
<body>
    <header class="header">

        <nav class="nav">
            <a href="check-post.php" class="item-menu">Главная</a>
            <a href="post.php" class="item-menu active">Ваши посты</a>
            <a href="reset-pw-f.php" class="item-menu">Ваш профиль</a>
        </nav>

    </header>

    <main class="main-main">
        <div class="title-box">

            <h2 class="title">Ваши посты</h2>
            <a href="post.php" class="btn">Создание поста</a>
        </div>

    <form action="" method="post" class="card">
        <?php
            include 'function.php';

            $user_id = null;

            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
            } else {
                if (isset($_SESSION['user_id_result_reg'])) {
                    $user_id = $_SESSION['user_id_result_reg'];
                } elseif (isset($_SESSION['user_id_result_log'])) {
                    $user_id = $_SESSION['user_id_result_log'];
                }
            }
            if ($user_id !== null) {

                $sql = "SELECT posts.id, posts.title, posts.content, posts.created_at,  posts.updated_at, users.username
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE posts.user_id = $user_id
                ORDER BY posts.created_at DESC";
                $result = mysqli_query( $_SERVER['link'], $sql);

                
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        if ($row['created_at'] !== null){

                            echo "
                            <div class='card-box'>
                            <div class='cards'>
                            <h3>Заголовок: " . $row["title"] . "</h3>
                            <p>Текст: " . $row["content"] . "</p>
                            <p>Автор: " . $row["username"] . "</p>
                            <p>Дата создания: " . $row["created_at"] . "</p>
                            <p>Дата обновления: " . $row["updated_at"] . "</p>
                            <button type='submit' name='del_post' value='". $row['id']. "'>Удалить</button>
                            <button type='submit' name='edit_post' value='". $row['id']. "'>Редактировать</button>
                            </div>
                            </div>
                            ";
                        }
                    }
                } else {
                    echo "Нет постов для отображения.";
                }
            }

            edit_post();
            delete_post();
        ?>
    </form>

    </main>
    
</body>
</html>