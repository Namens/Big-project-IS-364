<?php
    session_start();
    // $_SERVER['link'] = mysqli_connect("192.168.199.13", "learn", "learn", "learn_is_64_shmachkov");
    $_SERVER['link'] = mysqli_connect("151.248.115.10:3306", "root", "Kwuy1mSu4Y", "IS_364_Shmachkov");
        if(!$_SERVER['link']) {
            die("Connection failed: " . mysqli_connect_error());
        }
        echo 'База данных подключена';

    
    function add_users_to_bd($email){
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($_SERVER['link'], $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result); 
            $value_id = $row['id'];

            $query_check = "SELECT * FROM posts WHERE user_id = '$value_id'";
            $result_check = mysqli_query($_SERVER['link'], $query_check);

            if (mysqli_num_rows($result_check) === 0) {
                $query_add = "INSERT INTO posts (user_id) VALUES ('$value_id')";
                $result_add = mysqli_query($_SERVER['link'], $query_add);
            }
        };
    }


    function create_user ($username, $email, $password_hash){
        try{
            $query = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($_SERVER['link'], $query);
            

            $password = password_hash($password_hash, PASSWORD_DEFAULT);

            $create = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            $result1 = mysqli_query( $_SERVER['link'], $create);

            if ($result1) {
                echo "Пользователь добавлен";
                
                $user_id_query_reg = "SELECT id FROM users WHERE email = '$email'";
                $user_id_result_reg = mysqli_fetch_assoc(mysqli_query($_SERVER['link'], $user_id_query_reg))['id'];
                $_SESSION['user_id_result_reg'] = $user_id_result_reg;
                
                // header("Location: /page/main.php");
                header("Location: /page/check-post.php");

            } else {
                throw new Exception("Ошибка");
            }
        }
        catch (Exception $e) {
            echo "Пользователь уже есть!";
        }

    };


    function login_user($email, $password_hash){
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($_SERVER['link'], $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password_hash, $row['password'])) {
                echo "Вы успешно вошли";
                
                $user_id_query_log = "SELECT id FROM users WHERE email = '$email'";
                $user_id_result_log = mysqli_fetch_assoc(mysqli_query($_SERVER['link'], $user_id_query_log))['id'];
                $_SESSION['user_id_result_log'] = $user_id_result_log;
                
                // header("Location: /page/main.php");
                header("Location: /page/check-post.php");



            } else {
                echo "Неверный пароль";
            }
        } else {
            echo "Пользователь не найден";
        }
        

    };


    function Edit_user_data($old_email, $username, $email, $password_hash){
        try{
            $query = "SELECT * FROM users WHERE email = '$old_email'";
            $result = mysqli_query($_SERVER['link'], $query);
            
            $hashed_password = password_hash($password_hash, PASSWORD_DEFAULT);
            $update = "UPDATE users SET username = '$username', email = '$email', password = '$hashed_password' WHERE email = '$old_email'";
            $update_result = mysqli_query($_SERVER['link'], $update);
            return $update_result;
        } catch (Exception $e) {
            echo "Ошибка, эта Эл.почта заната";
        }
    }

    function Enter_admin($email, $password_hash){
        try{

            $query = "SELECT * FROM users WHERE email = '$email' AND role = 'admin'";
            $result = mysqli_query($_SERVER['link'], $query);
            
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if (password_verify($password_hash, $row['password'])) {
                    echo "Вы успешно вошли";
                    header("Location: /page/adm_main_page.php");
                } else {
                    echo "Неверный пароль";
                }
            } else {
                echo "Пользователь не найден";
            }


        } catch (Exception $e) {
            echo "Ошибка, неправильные данные";
        }
    }

    function users_check() {
        $query = "SELECT username, email, role, id FROM users";
                $result = mysqli_query($_SERVER['link'], $query);
                
                
                if (mysqli_num_rows($result) > 0) {
                    
                    echo "<h2>Список зарегистрированных пользователей</h2>";
                    echo "<table border='1'>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Эл.почта</th>
                                <th>Роль</th>
                            </tr>";
                    
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['role']) . "</td>
                            </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "Нет зарегистрированных пользователей.";
                }
    }

    function T_password($email, $new_password) {
        $token = bin2hex(random_bytes(8));
    
        $query = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($_SERVER['link'], $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result); 
            $value_id = $row['id'];

            $created_at = date('Y-m-d H:i:s');
            $expired_at = date('Y-m-d H:i:s', strtotime('+10 second'));

            $query_up = "INSERT INTO password_reset_tokens (user_id, token, created_at, expired_at) VALUES ('$value_id', '$token', '$created_at', '$expired_at')";
            $result_up = mysqli_query($_SERVER['link'], $query_up);
        }
    
        $code = substr($token, 0, 6);
        echo "<h3>Your code: $code</h3>";
        
        $_SESSION['code'] = $code;
        $_SESSION['start_time'] = time();


    }

    function start_post ($title_post, $text_post){
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
                
                // Проверка на наличие постов у текущего пользователя
                $query_check_post = "SELECT user_id FROM posts WHERE created_at IS NOT NULL";
                $check_result = mysqli_query($_SERVER['link'], $query_check_post);
                $created_at = date('Y-m-d H:i:s');

                if (mysqli_num_rows($check_result) > 0){


                    $new_post_query = "INSERT INTO posts (user_id, title, content, created_at) VALUES ('$user_id', '$title_post', '$text_post', '$created_at')";
                    $result_post = mysqli_query($_SERVER['link'], $new_post_query);

                } else {
                    
                    $query_post = "UPDATE posts SET title = '$title_post', content = '$text_post', created_at = '$created_at' WHERE user_id = '$user_id'";
                    $result_post = mysqli_query($_SERVER['link'], $query_post);
                }

                header("Location: /page/user_post.php");
                echo " Пост успешно создан";
            }


    }

    function edit_post() {
        if (isset($_POST['edit_post'])) {
            $post_id = $_POST['edit_post'];
    
            $sql = "SELECT title, content FROM posts WHERE id = $post_id";
            $result = mysqli_query($_SERVER['link'], $sql);
    
            if ($result && mysqli_num_rows($result) > 0) {
                $post = mysqli_fetch_assoc($result);
    
                echo "
                    </form>
                    <form action='' method='post' class='edit-form'>
                        <h3>Редактирование поста</h3>
                        <input type='hidden' name='post_id' value='$post_id'>
                        <label for='title' class='lab'>Заголовок:</label>
                        <input type='text' name='title' id='title' value='" . htmlspecialchars($post['title'], ENT_QUOTES) . "' required>
                        <label for='content' class='lab'>Текст:</label>
                        <textarea name='content' id='content' required>" . htmlspecialchars($post['content'], ENT_QUOTES) . "</textarea>
                        <button type='submit' name='save_post' class='btn m'>Сохранить</button>
                    </form>
                ";
            } else {
                echo "Ошибка: Пост не найден.";
            }
        }
    
        if (isset($_POST['save_post'])) {
            $updated_at = date('Y-m-d H:i:s');

            $post_id = $_POST['post_id'];
            $title = mysqli_real_escape_string($_SERVER['link'], $_POST['title']);
            $content = mysqli_real_escape_string($_SERVER['link'], $_POST['content']);
    
            $sql = "UPDATE posts SET title = '$title', content = '$content', updated_at = '$updated_at' WHERE id = $post_id";
            if (mysqli_query($_SERVER['link'], $sql)) {
                echo "Пост успешно обновлен."; 
                header("Refresh: 0");

            } else {
                echo "Ошибка при обновлении поста: " . mysqli_error($_SERVER['link']);
            }
        }
    }

    function delete_post() {
        if (isset($_POST['del_post'])) {
                $post_id = $_POST['del_post'];
        
                $sql = "DELETE FROM posts WHERE id = $post_id";
            if (mysqli_query($_SERVER['link'], $sql)) {
                echo "Пост успешно удален.";
                header("Refresh: 0");

            } else {
                echo "Ошибка при удалении поста: " . mysqli_error($_SERVER['link']);
            }
        }
    }

    function delete_adm(){
        if (isset($_POST['delete'])) {

            $post_id = $_POST['post_id'];
            $delete_sql = "DELETE FROM posts WHERE id = $post_id";

            if (mysqli_query($_SERVER['link'], $delete_sql)) {
                echo "<p>Пост успешно удален.</p>";
                header("Refresh:1"); 
            } else {
                echo "<p>Ошибка при удалении поста: " . mysqli_error($_SERVER['link']) . "</p>";
            }
        }
    }

    function edit_adm(){
        if (isset($_POST['edit'])) {
            $post_id = $_POST['post_id'];

            $sql = "SELECT title, content FROM posts WHERE id = ?";
            $stmt = mysqli_prepare($_SERVER['link'], $sql);
            mysqli_stmt_bind_param($stmt, 'i', $post_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $post = mysqli_fetch_assoc($result);
            
            echo "
            <div id='editModal' class='modal'>
                <div class='modal-content'>
                    <span class='close'>&times;</span>
                    <h2 class='title-t'>Редактировать пост</h2>
                    <form method='post' action='' class='card edit'>
                        <input type='hidden' name='post_id' value='$post_id'>
                        <label for='title'>Заголовок</label>
                        <input type='text' id='title' name='title' value='" . htmlspecialchars($post['title']) . "' required>
                        <label for='content'>Текст</label>
                        <textarea id='content' name='content' required class='textar'>" . htmlspecialchars($post['content']) . "</textarea>
                        <button type='submit' name='update'>Сохранить изменения</button>
                    </form>
                </div>
            </div>
            <script>
                var modal = document.getElementById('editModal');
                var span = document.getElementsByClassName('close')[0];
                modal.style.display = 'block';
                span.onclick = function() {
                    modal.style.display = 'none';
                }
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                }
            </script>
            ";
        }
    
        if (isset($_POST['update'])) {
            $post_id = $_POST['post_id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
    
            $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
            $stmt = mysqli_prepare($_SERVER['link'], $sql);
            mysqli_stmt_bind_param($stmt, 'ssi', $title, $content, $post_id);
            mysqli_stmt_execute($stmt);
    
            header("Location: " . $_SERVER['PHP_SELF']);
        }
    }

    function exit_log() {
        if (isset($_POST['exti'])) {
            session_unset();
        }
    }


?>