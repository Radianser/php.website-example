<h1 class="account-headers">Personal info</h1>
<div class='profile-info'>
    <div>
        <h3>Avatar</h3>
        <div>
            <img src="/<?php echo !empty($user['img']) ? $user['img'] : "images/unnamed.webp";?>" class="edit img edit-button" data-form="img" alt="" @click="showEditForm">
        </div>
    </div>

    <div>
        <div>
            <h3>Nickname</h3>
            <div class="user-data edit edit-button" data-form="nickname" @click="showEditForm">
                <?php echo !empty($user['nickname']) ? $user['nickname'] : "Unnamed"; ?>
            </div>
        </div>
        <div>
            <h3>E-mail</h3>
            <div>
                <?= $user['login'] ?>
            </div>
        </div>
    </div>

    <div>
        <div>
            <h3>Phone number</h3>
            <div class="user-data edit edit-button" data-form="phone" @click="showEditForm">
                <?php echo !empty($user['phone']) ? $user['phone'] : "User phone number is not set"; ?>
            </div>
        </div>
        <div>
            <h3>Birthday</h3>
            <div>
                <?= $user['birthday_date'] ?>
            </div>
        </div>
    </div>

    <div>
        <div>
            <h3>Age</h3>
            <div>
                <?= get_age($user['birthday_date']) ?>
            </div>
        </div>
        <div>
            <h3>Points</h3>
            <div>
                <?php echo !empty($user['points']) ? $user['points'] : 0; ?>
            </div>
        </div>
    </div>

</div>

<form method="POST" class="edit-form nickname hidden">
    <input name="nickname" pattern="[^ -]+" title="Spaces and dashes are not allowed" value="<?php
        if (!empty($_POST['nickname'])) {
            echo $_POST['nickname'];
        } else {
            echo $user['nickname'];
        }
    ?>">
	<input type="submit" value="Change" class="address-input-button">
</form>

<form method="POST" class="edit-form phone hidden">
    <input type="tel" pattern="\+?[0-9]{10,}" name="phone" class="phone-input" placeholder="+7 (900) 000-00-00" value="<?php
        if (!empty($_POST['phone'])) {
            echo $_POST['phone'];
        } else {
            echo $user['phone'];
        }
    ?>">
	<input type="submit" value="Change" class="address-input-button">
</form>

<form method="POST" class="edit-form img hidden" enctype="multipart/form-data">
    <input type="file" name="img">
	<input type="submit" value="Change" class="address-input-button">
</form>

<?php
    if (!empty($_SESSION['flash'])) {
        echo $_SESSION['flash'];
        unset($_SESSION['flash']);
    }
    
    if (!empty($_POST) || !empty($_FILES)) {
        $id = $_SESSION['user']['id'];
        $vartypes = 'si';

        if (!empty($_POST['nickname'])) {
            if (strlen($_POST['nickname']) <= 20) {
                $nickname = $_POST['nickname'];
                $query = "UPDATE users SET nickname=? WHERE id=?";
                $params = [$nickname, $id];
            } else {
                $error = "Too long nickname. Maximum 20 letters allowed.";
            }
        }
        if (isset($_POST['phone'])) {
            if (strlen($_POST['phone']) <= 17) {
                if ($_POST['phone'] == '') {
                    $query = "UPDATE users SET phone=? WHERE id=?";
                    $params = [null, $id];
                } else {
                    $phone = $_POST['phone'];
                    $query = "UPDATE users SET phone=? WHERE id=?";
                    $params = [$phone, $id];
                }
            } else {
                $error = "Too long phone number. Maximum 17 digits allowed.";
            }
        }
        if (!empty($_FILES['img'])) {
            $result = upload_file($_FILES['img'], $id, $link);

            if (!empty($result['path'])) {
                $path = $result['path'];
                $query = "UPDATE users SET img=? WHERE id=?";
                $params = [$path, $id];
            } else {
                $error = $result['error'];
            }
        }

        if (!empty($query) && empty($error)) {
            $result = execute_query($link, $query, $vartypes, $params);
            if ($result === false) {
                $_SESSION['flash'] = "Data successfully changed";
                header("Location: /profile/0");
            } else {
                $_SESSION['flash'] = "Failed to load image";
                header("Location: /profile/0");
            }
        } else {
            $_SESSION['flash'] = $error;
            header("Location: /profile/0");
        }
    }
?>
