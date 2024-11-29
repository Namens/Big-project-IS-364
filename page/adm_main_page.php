<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все посты</title>
    <link rel="stylesheet" href="/access/css/style.css">
</head>
<body>

<header class="header header-adm">

<nav class="nav">
    <a href="check-post.php" class="item-menu active">Главная</a>

    <a href="adm-check.php" class="item-menu">Все пользователи</a>
    <a href="adm_post.php" class="item-menu">Ваши посты</a>
    <a href="reset-pw-adm.php" class="item-menu">Ваш профиль</a>
    <a href="enter.php" name='exit' class="item-menu">Выход</a>

</nav>

</header>

    <main class="main-main">
    <h2 class="title">Все посты</h2>
    <div class='card'>

        <form action="" method="post" class="card">
        <?php
            include 'function.php';

            $sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, posts.updated_at, users.username
            FROM posts
            JOIN users ON posts.user_id = users.id
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
                                <form method='post' action=''>
                                    <input type='hidden' name='post_id' value='" . $row["id"] . "'>
                                    <button type='submit' name='edit'>Редактировать</button>
                                    <button type='submit' name='delete'>Удалить</button>
                                </form>
                            </div>
                        </div>
                        ";
                    }
                }
            } else {
                echo "Нет постов для отображения.";
            }

            delete_adm();
            edit_adm();
            exit_log();

        ?>
        </form>
    </div>

</main>
    
</body>
</html>