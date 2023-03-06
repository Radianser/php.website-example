<?php include 'connect.php' ?>
<div class="main">
    <div class="form-content">
        <h1 class="form-header">Registration</h1>
        <form method="POST" class="registration-form">
            <input type="email" name="login" required maxlength="25" pattern="([a-z0-9]+)+@[a-z]+\.[a-z]+" title="username@site.com" placeholder="email" class="form-input" value="<?php if (!empty($_POST)) echo $_POST['login'] ?>" style="
                <?php 
                    if (!empty($_POST)) {
                        $res = check_login($_POST['login'], $link);

                        if ($res === true) {
                            echo "border: 1px solid gray;";
                        } else {
                            echo "border: 1px solid red;";
                        }
                    }
                ?>"
            >
            <input type="date" name="birthday_date" required min="1950-01-01" max="2020-12-31" class="form-input" title="Your date of birth" value="<?php if (!empty($_POST['birthday_date'])) echo $_POST['birthday_date']?>">
            <input type="password" name="password" required placeholder="password" class="form-input" title="Six or more symbols required" value="<?php if (!empty($_POST)) echo $_POST['password'] ?>" style="
                <?php 
                    if (!empty($_POST)) {
                        $res = check_password($_POST['password']);

                        if ($res === true) {
                            echo "border: 1px solid gray;";
                        } else {
                            echo "border: 1px solid red;";
                        }
                    }
                ?>"
            >
            <input type="password" name="confirm" required placeholder="confirm password" class="form-input" title="Repeat your password" value="<?php if (!empty($_POST)) echo $_POST['confirm'] ?>" style="
                <?php 
                    if (!empty($_POST)) {
                        $res = check_confirm($_POST['password'], $_POST['confirm']);

                        if ($res === true) {
                            echo "border: 1px solid gray;";
                        } else {
                            echo "border: 1px solid red;";
                        }
                    }
                ?>"
            >
            <input type="submit" class="form-submit" value="Sign up">
        </form>

        <?php
            if (!empty($_POST)) {
                $check = check_data($_POST['login'], $_POST['password'], $_POST['confirm'], $link);
                
                if ($check === true) {
                    $login = $_POST['login'];
                    $birthday_date = $_POST['birthday_date'];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $date = date('Y-m-d');

                    user_registration($login, $birthday_date, $password, $date);
                    $_SESSION['flash'] = "We have sent you an email to confirm";
                } else {
                    $_SESSION['flash'] = $check;
                }
            }
        ?>

        <div class="flash">
            <?php
                if (!empty($_SESSION['flash'])) {
                    echo $_SESSION['flash'];
                    unset($_SESSION['flash']);
                }
            ?>
        </div>
    </div>
</div>