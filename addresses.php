<h1 class="account-headers">Your addresses</h1>
<?php
    $id = $_SESSION['user']['id'];
    $query = "SELECT * FROM addresses WHERE user_id=$id";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
?>

<?php 
    if (!empty($data)) {
        foreach ($data as $address) {
            if (!empty($_POST['edit']) && $_POST['edit'] == $address['id']) {
                $template = "<form method='POST' class='address-info'>
                                <div class='info-tile'>
                                    <div class='name_title title'>Name</div>
                                    <input name='name' placeholder='addresse name' value='$address[name]' class='address-input'>
                                </div>
                                <div class='info-tile'>
                                    <div class='street_title title'>Street:</div>
                                    <input name='street' placeholder='addresse name' value='$address[street]' class='address-input'>
                                </div>
                                <div class='info-tile'>
                                    <div class='house_title title'>House:</div>
                                    <input name='house' placeholder='addresse name' value='$address[house]' class='address-input'>
                                </div>
                                <div class='info-tile'>
                                    <div class='entrance_title title'>Entrance:</div>
                                    <input name='entrance' placeholder='addresse name' value='$address[entrance]' class='address-input'>
                                </div>
                                <div class='info-tile'>
                                    <div class='floor_title title'>Floor:</div>
                                    <input name='floor' placeholder='addresse name' value='$address[floor]' class='address-input'>
                                </div>
                                <div class='info-tile'>
                                    <div class='appartement_title title'>Flat:</div>
                                    <input name='appartement' placeholder='addresse name' value='$address[appartement]' class='address-input'>
                                </div>
                                <div class='info-tile'>
                                    <div class='comment_title title'>Comment:</div>
                                    <input name='comment' placeholder='addresse name' value='$address[comment]' class='comment_value address-input'>
                                </div>
                                <div class='info-tile'>
                                    <form method='POST'>
                                        <input name='save' type='hidden' value='$address[id]'></input>
                                        <input type='submit' value='Save' class='profile-button'></input>
                                    </form>
                                </div>
                            </form>";
                echo $template;
            } else {
                $template = "<div class='address-info'>
                            <div class='info-tile'>
                                <div class='name_title address-title title'>Name</div>
                                <div class='name_value address-value'>$address[name]</div>
                            </div>
                            <div class='info-tile'>
                                <div class='street_title address-title title'>Street:</div>
                                <div class='street_value address-value'>$address[street]</div>
                            </div>
                            <div class='info-tile'>
                                <div class='house_title address-title title'>House:</div>
                                <div class='house_value address-value'>$address[house]</div>
                            </div>
                            <div class='info-tile'>
                                <div class='floor_title address-title title'>Entrance:</div>
                                <div class='floor_value address-value'>$address[entrance]</div>
                            </div>
                            <div class='info-tile'>
                                <div class='appartement_title address-title title'>Floor:</div>
                                <div class='appartement_value address-value'>$address[floor]</div>
                            </div>
                            <div class='info-tile'>
                                <div class='entrance_title address-title title'>Flat:</div>
                                <div class='entrance_value address-value'>$address[appartement]</div>
                            </div>
                            <div class='info-tile'>
                                <div class='comment_title address-title title'>Comment:</div>
                                <div class='comment_value address-value'>$address[comment]</div>
                            </div>
                            <div class='info-tile'>
                            <form method='POST' class='edit'>
                                <input name='edit' type='hidden' value='$address[id]'></input>
                                <input type='submit' value='Edit' class='profile-button'></input>
                            </form>
                        
                            <form method='POST' class='remove'>
                                <input name='delete' type='hidden' value='$address[id]'></input>
                                <input type='submit' value='Remove' class='profile-button'></input>
                            </form>
                            </div>
                        </div>";
                echo $template;
            }
        }
    } else {
        echo "No addresses </br>";
    }
?>

<form method="POST" class="address-info">
    <div class='info-tile'>
        <input name="name" placeholder="name" class='add-address-input'>
    </div>
    <div class='info-tile'>
        <input name="street" placeholder="street" class='add-address-input'>
    </div>
    <div class='info-tile'>
        <input name="house" placeholder="house" class='add-address-input'>
    </div>
    <div class='info-tile'>
        <input name="entrance" placeholder="entrance" class='add-address-input'>
    </div>
    <div class='info-tile'>
        <input name="floor" placeholder="floor" class='add-address-input'>
    </div>
    <div class='info-tile'>
        <input name="appartement" placeholder="appartement" class='add-address-input'>
    </div>
    <div class='info-tile'>
        <input name="comment" placeholder="comment" class='comment_value add-address-input'>
    </div>
    <div class='info-tile'>
        <input type="submit" value="Add" class='profile-button'>
    </div>
</form>

<?php 
    echo '</br>';
    if (!empty($_SESSION['flash'])) {
        echo $_SESSION['flash'];
        unset($_SESSION['flash']);
    }
?>

<?php 
    if (!empty($_POST['delete'])) {
        $delete_id = $_POST['delete'];
        $delete = "DELETE FROM addresses WHERE id='$delete_id'";
        $res = mysqli_query($link, $delete) or die(mysqli_error($link));

        if ($res === true) {
            $_SESSION['flash'] = 'Address successfully deleted';
            header("Location: /profile/1");
        } else {
            $_SESSION['flash'] = 'Failed to delete address';
            header("Location: /profile/1");
        }
    }
    if (!empty($_POST['save'])) {
        $save_id = $_POST['save'];
        $name = $_POST['name'];
        $street = $_POST['street'];
        $house = $_POST['house'];
        $entrance = $_POST['entrance'];
        $floor = $_POST['floor'];
        $appartement = $_POST['appartement'];
        $comment = $_POST['comment'];

        $save = "UPDATE addresses SET name='$name', street='$street', house='$house', entrance='$entrance', floor='$floor', appartement='$appartement', comment='$comment' WHERE id='$save_id'";
        $res = mysqli_query($link, $save) or die(mysqli_error($link));

        if ($res === true) {
            $_SESSION['flash'] = 'Address successfully saved';
            header("Location: /profile/1");
        } else {
            $_SESSION['flash'] = 'Failed to save address';
            header("Location: /profile/1");
        }
    }
?>

<?php 
    if (!empty($_POST['name']) && empty($_POST['save'])) {
        $name = $_POST['name'];
        $street = $_POST['street'];
        $house = $_POST['house'];
        $entrance = $_POST['entrance'];
        $floor = $_POST['floor'];
        $appartement = $_POST['appartement'];
        $comment = $_POST['comment'];
        
        $add = "INSERT INTO addresses SET name='$name', street='$street', house='$house', entrance='$entrance', floor='$floor', appartement='$appartement', comment='$comment', user_id='$id'";
        $res = mysqli_query($link, $add) or die(mysqli_error($link));

        if ($res === true) {
            $_SESSION['flash'] = 'Address successfully added';
            header("Location: /profile/1");
        } else {
            $_SESSION['flash'] = 'Failed to add address';
            header("Location: /profile/1");
        }
    }
?>