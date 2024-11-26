<?php
    session_start();
    $_SERVER['link'] = mysqli_connect("192.168.199.13", "learn", "learn", "learn_is_64_shmachkov");
    // $_SERVER['link'] = mysqli_connect("151.248.115.10:3306", "root", "Kwuy1mSu4Y", "IS_364_Shmachkov");
        if(!$_SERVER['link']) {
            die("Connection failed: " . mysqli_connect_error());
        }
        echo 'База данных подключена';


    function create_user ($username, $email, $password_hash){
        try{
            $query = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($_SERVER['link'], $query);
            

            $password = password_hash($password_hash, PASSWORD_DEFAULT);

            $create = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            $result1 = mysqli_query( $_SERVER['link'], $create);

            if ($result1) {
                echo "Пользователь добавлен";
                header("Location: /page/main.php");            
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
                header("Location: /page/main.php");
            } else {
                echo "Неверный пароль";
            }
        } else {
            echo "Пользователь не найден";
        }
        

    };


    function Edit_user_data($old_email, $username, $email, $password_hash){
        try{
            // $user_id = "SELECT id FROM users WHERE email = '$old_email'";
            // $user_id = mysqli_fetch_assoc(mysqli_query($_SERVER['link'], $user_id))['id'];

            $query = "SELECT * FROM users WHERE email = '$old_email'";
            $result = mysqli_query($_SERVER['link'], $query);
            
            $hashed_password = password_hash($password_hash, PASSWORD_DEFAULT);
            $update = "UPDATE users SET username = '$username', email = '$email', password = '$hashed_password' WHERE email = '$old_email'";
            $update_result = mysqli_query($_SERVER['link'], $update);
            return $update_result;
        } catch (Exception $e) {
            echo "Ошибка, этота Эл.почта заната";
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

    function Reset_password($email, $token) {
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
    }

    function reeset_password($email, $new_password) {
        $token = bin2hex(random_bytes(8));
        $_SESSION['token'] = $token;
    
        Reset_password($email, $token);
    
        $code = substr($token, 0, 6);
        $_SESSION['code'] = $code;
        $_SESSION['start_time'] = time();
    
        echo "<h3>Your code: $code</h3>";
        if(isset($_POST['submit1'])){

            $input_code = $_POST['input_code'];             
            $_SESSION['input_code'] = $input_code;
                if (!empty($_SESSION['input_code'])){
                    echo 'not emp ';
                };
            $current_time = time();
            $start_time = $_SESSION['start_time'];
            $elapsed_time = $current_time - $start_time;
            
            $hach_pw = password_hash($new_password, PASSWORD_DEFAULT);
            echo "123 ";

            if ($elapsed_time <= 10) {
                $update = "UPDATE users SET password = '$hach_pw' WHERE email = '$email'";
                $update_result = mysqli_query($_SERVER['link'], $update);
                echo "Password changed";
            } else{
                echo " 23";
            }
            
        }

    }

?>