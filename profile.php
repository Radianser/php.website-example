<?php include 'connect.php' ?>
<?php if (!empty($_SESSION['user']['login'])) { ?>
    <?php
        $id = $_SESSION['user']['id'];
        $query = "SELECT * FROM users WHERE id=$id";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $user = mysqli_fetch_assoc($result);
    ?>
    <div class="main">
        <div class="sidebar">
            <?php
                $buttons = ['profile' => 'Profile', 'addresses' => 'Addresses', 'orders' => 'Orders', 'password' => 'Password'];
                $i = 0;
                foreach ($buttons as $key => $value) {
                    echo "<a href='/profile/$i' class='sidebar-button'><p>$value</p><img src='/images/$key.webp' alt='$value'></a>";
                    $i++;
                }
                $url = $_SERVER['REQUEST_URI'];
                preg_match('#/[a-z]+/?(\d+)?$#', $url, $match);
            ?>
        </div>

        <div class="container">
            <?php if ($match[1] == 0) { ?>
                <?php require 'basic_info.php' ?>
            <?php } else if ($match[1] == 1) { ?>
                <?php require 'addresses.php' ?>
            <?php } else if ($match[1] == 2) { ?> 
                <?php require 'order_history.php' ?>
            <?php } else if ($match[1] == 3) { ?>   
                <?php require 'password_change.php' ?>
            <?php } ?> 
        </div>
    </div>
<?php } else { ?>
    <div class="main">
        <div class="log-in">
            <p>Please, log in</p>
        </div>
    </div>
<?php } ?>