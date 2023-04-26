<?php
    if (!empty($_GET)) {
        $login = $_GET['login'];
        $birthday_date = $_GET['birthday_date'];
        $password = $_GET['password'];
        $date = $_GET['date'];

        $query = "SELECT login FROM users WHERE login=?";
        $user = execute_query($link, $query, 's', [$login]);

        if (empty($user)) {
            $query = "INSERT INTO users SET login=?, password=?, registration_date=?, birthday_date=?";
            $params = [$login, $password, $date, $birthday_date];
            $vartypes = 'ssss';
            $result = execute_query($link, $query, $vartypes, $params);
            
            if ($result === false) {
                $_SESSION['success'] = "Registration successfully completed.";
            } else {
                $_SESSION['flash'] = "Something went wrong, please try again later.";
            }
        } else {
            $_SESSION['flash'] = "The user is already registered.";
        }
    }
    header("Location: /authorization");
?>
