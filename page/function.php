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
                    header("Location: /page/adm-check.php");
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

    function start_post($title_post, $text_post){
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
                $created_at = date('Y-m-d H:i:s');

                $query_post = "UPDATE posts SET title = '$title_post', content = '$text_post', created_at = '$created_at' WHERE user_id = '$user_id'";
                $result_post = mysqli_query($_SERVER['link'], $query_post);
                
                // header("Location: /page/main.php");
                echo " Пост успешно создан";
            }


    }


?>