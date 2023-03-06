<div class="main">
    <div class="receipt-page">
        <div class="receipt">
            <h2>Order №<?= $_SESSION['order']['id'] ?> successfully completed</h2>
            <p>Our operator will contact you within 15 minutes to clarify the details of the order</p>
            
            <div>
                <div>Date and time of the order: <?= $_SESSION['order']['date'] ?> MSK</div>
                <div>Name: <?= $_SESSION['order']['name'] ?></div>
                <div>Phone: <?= $_SESSION['order']['phone'] ?></div>

                <div>Address:
                    <?php 
                        if (!empty($_SESSION['order']['address'])) { 
                            $str = $_SESSION['order']['address']['street'] . ', ' . $_SESSION['order']['address']['house'] . ', ' . $_SESSION['order']['address']['appartement'];
                            echo $str;
                        } else {
                            echo 'Coffee shop address';
                        }
                    ?>
                </div>
                
                <div>Payment method: <?= $_SESSION['order']['payment_method'] ?></div>
                <div>Points gained: <?= $_SESSION['order']['points_gained'] ?></div>
                <br>

                <div>Your order:</div>
                <?php $i=1; foreach ($_SESSION['order']['cart'] as $elem) { ?>
                    <div class="list-of-products">
                        <p>
                            <?php
                                echo $i++ . ". " . "$elem[name], $elem[count] pcs, $elem[cost] ₽";
                            ?>
                        </p>
                    </div>
                <?php } ?>

                <br>
                <div>Total cost: <?= $_SESSION['order']['total'] + (int)$_SESSION['order']['points_spent'] ?> ₽</div>
                <div>Points used: <?= $_SESSION['order']['points_spent'] ?></div>
                <div>Final cost: <?= $_SESSION['order']['total'] ?> ₽</div>
                <div class="thanks">Thank you for your order!</div>
            </div>
        </div>
    </div>
</div>

<?php
    unset($_SESSION['order']);
?>