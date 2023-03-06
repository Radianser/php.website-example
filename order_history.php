<h1 class="account-headers">Your orders</h1>
<?php
    $id = $_SESSION['user']['id'];
    $query = "SELECT * FROM orders WHERE user_id='$id'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

    if (count($data) > 0) {
        $i = 1;
        foreach ($data as $order) {
            $address_id = $order['address_id'];
            $get_address = "SELECT * FROM addresses WHERE id='$address_id'";
            $result = mysqli_query($link, $get_address) or die(mysqli_error($link));
            for ($address = []; $row = mysqli_fetch_assoc($result); $address[] = $row);

            if (count($address) > 0) {
                $address_str = $address[0]['house'] . ' ' . $address[0]['street'] . ' st., apt. ' . $address[0]['appartement'];
            } else {
                $address_str = 'Coffee shop address';
            }
            
            $arr = json_decode($order["products"], true);

            $products = "";
            $j = 1;
            foreach ($arr as $prod) {
                $products .= "<div>$j. $prod[name], $prod[count] шт., $prod[cost] ₽</div>";
                $j++;
            }

            $template = "<div class='order-list'>
                        
                            <div class='order-record'>
                                <div>$i. Order#$order[id]</div>
                                <div>$order[date]</div>
                            </div>

                            <div class='order-record-details'>
                                <div>Date and time of order: $order[date] MSK</div>
                                <div>Address: $address_str</div>
                                </br>
                                <div>Your order:</div>
                                <div>$products</div>
                                </br>
                                <div>Payment method: $order[payment_method]</div>
                                <div>Points awarded: $order[points_gained]</div>
                                <div>Total cost: $order[preliminary_cost] ₽</div>
                                <div>Points used: $order[points_spent]</div>
                                <div>Final cost: $order[total] ₽</div>
                            </div>

                        </div>";
            echo $template;
            $i++;
        }
    } else {
        echo 'No orders';
    }
?>