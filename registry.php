<?php
    session_start();
    require_once 'connect.php';

    if (!empty($_GET)) {
        $login = $_GET['login'];
        $birthday_date = $_GET['birthday_date'];
        $password = $_GET['password'];
        $date = $_GET['date'];

        $get = "SELECT login FROM users WHERE login='$login'";
        $user = mysqli_fetch_assoc(mysqli_query($link, $get));

        if (empty($user)) {
            $query = "INSERT INTO users SET login='$login', password='$password', registration_date='$date', birthday_date='$birthday_date'";
            $res = mysqli_query($link, $query);

            if ($res === true) {
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