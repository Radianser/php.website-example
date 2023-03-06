<h1 class="account-headers">Password settings</h1>
<form method="POST" class="address-info">
    <div class='info-tile'>
        <input name="oldPassword" type="password" required placeholder="old password" value="<?php if (!empty($_POST)) echo $_POST['oldPassword'] ?>" class="password-input">
    </div>
    <div class='info-tile'>
        <input name="newPassword" type="password" required minlength="6" placeholder="new password" value="<?php if (!empty($_POST)) echo $_POST['newPassword'] ?>" class="password-input">
    </div>
    <div class='info-tile'>
        <input name="confirm" type="password" required minlength="6" placeholder="confirm new password" value="<?php if (!empty($_POST)) echo $_POST['confirm'] ?>" class="password-input">
    </div>
    <div class='info-tile'>
        <input type="submit" value="Change" class='profile-button'>
    </div>
</form>
<?php
    if (!empty($_SESSION['flash'])) {
        echo $_SESSION['flash'];
        unset($_SESSION['flash']);
    }

    if (!empty($_POST)) {
        $oldPassword = $_POST['oldPassword'];
        $newPassword = check_password($_POST['newPassword']);
        $confirm = check_confirm($_POST['newPassword'], $_POST['confirm']);
        
        if ($newPassword === true) {
            if ($confirm === true) {
                if (password_verify($oldPassword, $user['password'])) {
                    $password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
                    $query = "UPDATE users SET password='$password' WHERE id='$id'";
                    mysqli_query($link, $query);
                    $_SESSION['flash'] = "Password successfully changed";
                } else {
                    $_SESSION['flash'] = "Wrong password";
                }
            } else {
                $_SESSION['flash'] = $confirm;
            }
        } else {
            $_SESSION['flash'] = $newPassword;
        }
        header("Location: /profile/3");
    }
?>