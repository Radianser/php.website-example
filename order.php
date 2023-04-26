<div class="main">
    <div class="order-page">
        <h1>Order details</h1>

        <shopping-cart
            :SESSION="SESSION"
            :img-name="imgName"
            :return-data="returnData"
            :change-quantity="changeQuantity"
            :remove-product="removeProduct">
        </shopping-cart>

        <p>* Delivery is available to authorized users with orders over {{ min }} rubles</p>
        <p>** All authorized users participate in the loyalty program</p>

        <?php
            if (!empty($_SESSION['user']['id'])) { 
                $id = $_SESSION['user']['id'];
                $query = "SELECT * FROM addresses WHERE user_id='$id'";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

                $get_user = "SELECT * FROM users WHERE id='$id'";
                $res = mysqli_query($link, $get_user) or die(mysqli_error($link));
                $user = mysqli_fetch_assoc($res);
            }
        ?>
        
        <div class='order-settings' v-if="SESSION['cart'].length != 0">
            <form id="order-form" action="" method="POST" v-on:submit="checkFormData">
                
                <div v-if="SESSION['user']['id'] != null">
                    <h2>Discount points</h2>
                    <label><input class='discount form-input order-input' name='discount_points' placeholder='Price reduce ability' @blur='usePoints'> You have: <?php echo !empty($user['points']) ? $user['points'] : 0; ?></label>
                </div>
                <div v-else>
                    <input type='hidden' name='discount_points' value='' class='discount' checked>
                </div>

                <h2>Delivery method</h2>
                <div v-if="SESSION['user']['id'] != null && SESSION['total'] >= min" v-cloak>
                    <label class='self-pickup' @click="offAddresses"><input class='self-pickup' type='radio' name='delivery_method' value='self-pickup' :checked='true'>self-pickup</label>
                    <label class='delivery' @click="onAddresses"><input class='delivery' type='radio' name='delivery_method' value='delivery' :key="Math.random()">delivery</label>
                </div>
                <div v-else v-cloak>
                    <label class='self-pickup'><input class='self-pickup' type='radio' name='delivery_method' value='self-pickup' checked>self-pickup</label>
                </div>

                <div v-if="SESSION['user']['id'] != null && SESSION['total'] >= min" v-cloak>
                    <div class='shopping-cart-addresses hidden'>
                        <h2>Addresses</h2>
                        <?php
                            if (!empty($data)) {
                                foreach ($data as $address) {
                                    $template = "<div class='address-info order-address-info'>
                                                    <input type='radio' name='address_id' value='no_address' class='hidden' title='.' placeholder='.' checked :key='$address[id]'>
                                                    <label class='label'>
                                                        <div class='info-tile'>
                                                            <input type='radio' name='address_id' value='$address[id]' class='address-switcher'>
                                                        </div>
                                                        <div class='info-tile'>
                                                            <div class='name_title'>Name</div>
                                                            <div class='name_value address-value'>$address[name]</div>
                                                        </div>
                                                        <div class='info-tile'>
                                                            <div class='street_title'>Street:</div>
                                                            <div class='street_value address-value'>$address[street]</div>
                                                        </div>
                                                        <div class='info-tile'>
                                                            <div class='house_title'>House:</div>
                                                            <div class='house_value address-value'>$address[house]</div>
                                                        </div>
                                                        <div class='info-tile'>
                                                            <div class='floor_title'>Entrance:</div>
                                                            <div class='floor_value address-value'>$address[entrance]</div>
                                                        </div>
                                                        <div class='info-tile'>
                                                            <div class='appartement_title'>Floor:</div>
                                                            <div class='appartement_value address-value'>$address[floor]</div>
                                                        </div>
                                                        <div class='info-tile'>
                                                            <div class='entrance_title'>Flat:</div>
                                                            <div class='entrance_value address-value'>$address[appartement]</div>
                                                        </div>
                                                        <div class='info-tile'>
                                                            <div class='comment_title'>Comm:</div>
                                                            <div class='comment_value address-value'>$address[comment]</div>
                                                        </div>
                                                    </label>
                                                </div>";
                                    echo $template;
                                }
                            } else {
                                echo "<input type='radio' name='address_id' value='no_address' class='hidden' title='.' placeholder='.' checked>
                                    <a href='/profile/1'>Add an address</a>";
                            }
                        ?>
                    </div>
                </div>
                <div v-else v-cloak>
                    <input type='radio' name='address_id' value='no_address' class='hidden' checked>
                </div>

                <div>
                    <h2>Contact Information</h2>
                    <input name='customer_name' placeholder='How can we call you' required class="form-input order-input" value="<?php echo !empty($_SESSION['user']['id']) ? $user['nickname'] : ''; ?>">
                    <input type="tel" name='phone_number' placeholder='Your phone number' required class="form-input order-input phone-input" value="<?php echo !empty($_SESSION['user']['id']) ? $user['phone'] : ''; ?>">
                </div>

                <div class='shopping-cart-payment-method'>
                    <h2>Payment method</h2>
                    <label><input type='radio' name='payment_method' value='cash'>Cash</label>
                    <label><input type='radio' name='payment_method' value='credit card' checked>Credit card</label>
                </div>

                <input type="submit" value="Make an order" class="order-page-submit">
            </form>
        </div>
    </div>
</div>
