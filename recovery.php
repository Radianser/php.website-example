<div class="main">
    <div class="form-content">
        <?php if (empty($_GET)) { ?>
            <h1 class="form-header">Password recovery</h1>
            <form method="POST" class="authorization-form">
                <input type="email" maxlength="25" pattern="([a-z0-9]+)+@[a-z]+\.[a-z]{2,3}" required title="username@site.com" name="login" placeholder="email" class="form-input" value="<?php if (!empty($_POST)) echo $_POST['login'] ?>">
                <input type="submit" class="form-submit" value="Restore">
            </form>
        <?php } else { ?>
            <h1 class="form-header">Set up a new password</h1>
            <form method="POST" class="authorization-form">
                <input type="password" name="password" required minlength="6" placeholder="new password" class="form-input" title="Six or more symbols required" value="<?php if (!empty($_POST)) echo $_POST['password'] ?>" style="
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
                <input type="password" name="confirm" required minlength="6" placeholder="confirm password" class="form-input" title="Repeat your password" value="<?php if (!empty($_POST)) echo $_POST['confirm'] ?>" style="
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
                <input type="submit" class="form-submit" value="Set up">
            </form>
        <?php } ?>

        <?php
            if (!empty($_POST['login'])) {
                $login = $_POST['login'];
                $query = "SELECT login FROM users WHERE login=?";
                $user = execute_query($link, $query, 's', [$login]);
                
                if (!empty($user)) {
                    $destination = $user[0]['login'];
                    $topic = "Password recovery";
                    $text = "Please follow the link below to set up a new password.\r\n\r\nhttp://localhost/recovery?email=$destination";
                    
                    send_email($destination, $topic, $text);
                    $_SESSION['flash'] = "We have sent you an email to change password";
                } else {
                    $_SESSION['flash'] = "The user is not exists";
                }
            }
            if (!empty($_POST['password'])) {
                $password = check_password($_POST['password']);
                $confirm = check_confirm($_POST['password'], $_POST['confirm']);

                if ($password === true) {
                    if ($confirm === true) {
                        $login = $_GET['email'];
                        $key = password_hash($_POST['password'], PASSWORD_DEFAULT);

                        $query = "UPDATE users SET password=? WHERE login=?";
                        $params = [$key, $login];
                        $vartypes = 'ss';
                        execute_query($link, $query, $vartypes, $params);

                        $_SESSION['recovery'] = "The password has been changed";
                        header("Location: /authorization");
                    } else {
                        $_SESSION['flash'] = $confirm;
                    }
                } else {
                    $_SESSION['flash'] = $password;
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
