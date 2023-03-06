<?php include 'connect.php' ?>
<div class="main">
    <div class="form-content">
        <h1 class="form-header">Authorization</h1>
        <form method="POST" class="authorization-form">
            <input type="email" maxlength="25" pattern="([a-z0-9]+)+@[a-z]+\.[a-z]+" required title="username@site.com" name="login" placeholder="email" class="form-input" value="<?php if (!empty($_POST)) echo $_POST['login'] ?>">
            <input type="password" name="password" required minlength="6" placeholder="password" class="form-input" value="<?php if (!empty($_POST)) echo $_POST['password'] ?>">
            <input type="submit" class="form-submit" value="Log in">
        </form>
        <a href="/recovery" class="password-recovery-link">Forgot your password?</a>

        <?php
            require_once 'functions.php';

            if (!empty($_POST['login']) && !empty($_POST['password'])) {
                $login = $_POST['login'];
                $password = $_POST['password'];
                $query = "SELECT * FROM users WHERE login='$login'";
                $user = mysqli_fetch_assoc(mysqli_query($link, $query));

                if (!empty($user)) {
                    if (password_verify($password, $user['password']) === true ) {
                        $_SESSION['user']['login'] = $user['login'];
                        $_SESSION['user']['id'] = (int)$user['id'];
                        $_SESSION['user']['nickname'] = $user['nickname'];
                        $_SESSION['user']['age'] = get_age($user['birthday_date']);

                        $last_session = json_decode($user['cart'], true);
                        $_SESSION['cart'] = $last_session['cart'];
                        $_SESSION['quantity'] = $last_session['quantity'];
                        $_SESSION['total'] = $last_session['total'];
                        header("Location: /");
                    } else {
                        $_SESSION['flash'] = "Invalid login or password";
                    }
                } else {
                    $_SESSION['flash'] = "This user does not exist";
                }
            }
        ?>

        <div class="flash">
            <?php
                if (!empty($_SESSION['flash'])) {
                    echo $_SESSION['flash'];
                    unset($_SESSION['flash']);
                }
                if (!empty($_SESSION['success'])) {
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                }
                if (!empty($_SESSION['recovery'])) {
                    echo $_SESSION['recovery'];
                    unset($_SESSION['recovery']);
                }
            ?>
        </div>
        
    </div>
</div>