<?php
    session_start();
    $_SERVER['link'] = mysqli_connect("151.248.115.10:3306", "root", "Kwuy1mSu4Y", "IS_364_Shmachkov");
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


    function Edit_user_data($user_id, $username, $email, $password_hash, $new_password_hash){

        if (1){ 
            $query = "SELECT * FROM users WHERE email = '$email_log'";
            $result = mysqli_query($_SERVER['link'], $query);

            $password = password_hash($password_hash, PASSWORD_DEFAULT);
        
            $update = "UPDATE users SET username = '$username', email = '$email', password = '$password' WHERE email = '$email_log'";
            $result11 = mysqli_query( $_SERVER['link'], $update);
            
            if ($result11) {
                echo "Данные пользователя изменены";
            } else {
                throw new Exception("Ошибка");
            }
        } elseif (isset($email_reg)){
            $query = "SELECT * FROM users WHERE email = '$email_reg'";
            $result = mysqli_query($_SERVER['link'], $query);

            $password = password_hash($password_hash, PASSWORD_DEFAULT);
        
            $update = "UPDATE users SET username = '$username', email = '$email', password = '$password' WHERE email = '$email_reg'";
            $result11 = mysqli_query( $_SERVER['link'], $update);
            
            if ($result11) {
                echo "Данные пользователя изменены";
            } else {
                throw new Exception("Ошибка");
            }
        } else {
            echo "Нет";
        }       


    }

?>