<?php
    require_once 'connect.php';
    require_once 'functions.php';
    session_start();

    if (!empty($_SESSION['user']['id'])) {
        save_session($link);
        unset($_SESSION['user']);
    }

    header("Location: /");
?>