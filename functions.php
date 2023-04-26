<?php
// routing functions

    function get_file($name) {
        ob_start();
            include $name;
        return ob_get_clean();
    }
    function check_url($url) {
        $dir = './';
        $files = array_diff(scandir($dir), ['.', '..']);
        $arr = [];
        
        if (preg_match('#^/([a-z]+)$#', $url, $match)) {
            foreach ($files as $page) {
                if ($page === $match[1] . '.php') {
                    $arr[] = $match[1];
                    return $arr;
                }
            }
        } else if (preg_match('#^/([a-z]+)\.(?:(?:php)|(?:html))$#', $url, $match)) {
            foreach ($files as $page) {
                if ($page === $match[1] . '.php') {
                    $arr[] = $match[1];
                    return $arr;
                }
            }
        } else if (preg_match('#^/([a-z]+)/[0-9]+/([a-z]+)$#', $url, $match)) {
            foreach ($files as $page) {
                if ($page === $match[1] . '.php') {
                    $arr[] = $match[1];
                    $arr[] = $match[2];
                    return $arr;
                }
            }
        } else if (preg_match('#^/([a-z]+)/[0-3]$#', $url, $match)) {
            foreach ($files as $page) {
                if ($page === $match[1] . '.php') {
                    $arr[] = $match[1];
                    return $arr;
                }
            }
        } else if (preg_match('#^/([a-z]+)\?(?:.+=.+)+$#', $url, $match)) {
            foreach ($files as $page) {
                if ($page === $match[1] . '.php') {
                    $arr[] = $match[1];
                    return $arr;
                }
            }
        } else if (preg_match('#^/$#', $url, $match)) {
            $arr[] = 'main';
            $arr[] = "Some Coffees";
            return $arr;
        }
        
        return false;
    }

// login functions

	function get_age($string) {
        $birth = explode('-', $string);
        $now = explode('-', date('Y-m-d'));
        $diff = [];

        $diff[] = $now[0] - $birth[0];
        $diff[] = $now[1] - $birth[1];
        $diff[] = $now[2] - $birth[2];

        if ($diff[1] < 0) {
            $age = $diff[0] - 1;
        } else if ($diff[1] > 0) {
            $age = $diff[0];
        } else {
            if ($diff[2] < 0) {
                $age = $diff[0] - 1;
            } else {
                $age = $diff[0];
            }
        }

        return $age;
    }
    function check_data($login, $password, $confirm, $link) {
        $login_check = check_login($login, $link);
        $password_check = check_password($password);
        $confirm_check = check_confirm($password, $confirm);
        
        if ($login_check === true) {
            if ($password_check === true) {
                if ($confirm_check === true) {
                    return true;
                } else {
                    return $confirm_check;
                }
            } else {
                return $password_check;
            }
        } else {
            return $login_check;
        }
    }
    function check_login($login, $link) {
        $query = "SELECT login FROM users WHERE login=?";
        $params = [$login];
        $vartype = 's';
        $user = execute_query($link, $query, $vartype, $params);

        if (empty($user)) {
            return true;
        } else {
            return "The user is already registered";
        }
    }
    function check_password($password) {
        preg_match('#[a-z]#', $password, $small_letter);
        preg_match('#[A-Z]#', $password, $big_letter);
        preg_match('#[0-9]#', $password, $digit);

        if (!empty($small_letter[0])) {
            if (!empty($big_letter[0])) {
                if (!empty($digit[0])) {
                    return true;
                } else {
                    return "Password must contain at least one number";
                }
            } else {
                return "Password must contain at least one capital letter";
            }
        } else {
            return "Password must contain at least one lowercase letter";
        }
    }
    function check_confirm($password, $confirm) {
        if ($password === $confirm) {
            return true;
        } else {
            return "Password did not match";
        }
    }

// shopping cart functions

    function add_to_cart($data, $link) {
        $id = $data['id'];
        $query = "SELECT * FROM products WHERE id='$id'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $product = mysqli_fetch_assoc($result);

        function add($link, $product, $id) {
            if (!empty($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['count'] += 1;
                $_SESSION['cart'][$id]['cost'] = $_SESSION['cart'][$id]['count'] * $_SESSION['cart'][$id]['price'];
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => (int)$product['id'],
                    'name' => $product['name'],
                    'count' => 1,
                    'price' => (int)$product['price'],
                    'cost' => (int)$product['price']
                ];
            }
            $_SESSION['quantity'] = !empty($_SESSION['quantity']) ? ++$_SESSION['quantity'] : 1;
            $_SESSION['total'] = !empty($_SESSION['total']) ? $_SESSION['total'] + (int)$product['price'] : (int)$product['price'];
            save_session($link);
        }

        if ($product['category_id'] == 10) {
            if (empty($_SESSION['user']['age'])) {
                return 'Failed to verify your age. Authorization required.';
            } else if (!empty($_SESSION['user']['age']) && $_SESSION['user']['age'] < 18) {
                return 'Purchase is not possible. You are under 18 years old.';
            } else {
                add($link, $product, $id);
                return true;
            }
        } else {
            add($link, $product, $id);
            return true;
        }
    }

    function remove_from_cart($data, $link) {
        $id = $data['id'];
        $quantity_decrease = $_SESSION['cart'][$id]['count'];
        $total_decrease = $_SESSION['cart'][$id]['cost'];

        $_SESSION['quantity'] = $_SESSION['quantity'] - $quantity_decrease;
        $_SESSION['total'] = $_SESSION['total'] - $total_decrease;

        unset($_SESSION['cart'][$id]);
        save_session($link);

        return $_SESSION;
    }

    function get_cart_products() {
        if (!empty($_SESSION['cart'])) {
            return $_SESSION;
        } else {
            $_SESSION['cart'] = [];
            return $_SESSION;
        }
    }

    function change_quantity($data, $link) {
        $id = $data['id'];

        if ($data['action'] === 'increment') {
            ++$_SESSION['cart'][$id]['count'];
            ++$_SESSION['quantity'];
            $_SESSION['total'] = $_SESSION['total'] + $_SESSION['cart'][$id]['price'];
        }
        if ($data['action'] === 'decrement') {
            --$_SESSION['cart'][$id]['count'];
            --$_SESSION['quantity'];
            $_SESSION['total'] = $_SESSION['total'] - $_SESSION['cart'][$id]['price'];
        }
        $_SESSION['cart'][$id]['cost'] = $_SESSION['cart'][$id]['count'] * $_SESSION['cart'][$id]['price'];
        save_session($link);

        return $_SESSION;
    }

    function use_points($data, $link) {
        $id = $_SESSION['user']['id'];
        $query = "SELECT points FROM users WHERE id='$id'";
        $user = mysqli_fetch_assoc(mysqli_query($link, $query));
        
        if (($data['points'] >= 0 && $data['points'] <= $user['points'] && $data['points'] <= $_SESSION['total']) || $data['points'] == '') {
            return true;
        } else {
            return false;
        }
    }

//order functions

    function order_processing($arr, $link) {
        $_SESSION['order'] = [];
        $_SESSION['order']['date'] = date('d.m.Y H:i:s');
        $_SESSION['order']['cart'] = $_SESSION['cart'];
        $_SESSION['order']['quantity'] = $_SESSION['quantity'];

        if ($arr[2][1] !== 'no_address') {
            $address_id = $arr[2][1];
            $query = "SELECT * FROM addresses WHERE id='$address_id'";
            $address = mysqli_fetch_assoc(mysqli_query($link, $query));
            $_SESSION['order']['address'] = $address;
        } else {
            $_SESSION['order']['address'] = null;
        }
        
        $_SESSION['order']['points_spent'] = !empty($arr[0][1]) ? $arr[0][1] : 0;
        $_SESSION['order']['delivery_method'] = $arr[1][1];
        $_SESSION['order']['name'] = $arr[3][1];
        $_SESSION['order']['phone'] = $arr[4][1];
        $_SESSION['order']['payment_method'] = $arr[5][1];

        if (!empty($_SESSION['user']['id'])) {
            $_SESSION['order']['points_gained'] = round(($_SESSION['total'] - (int)$_SESSION['order']['points_spent']) * 0.05);
            $_SESSION['order']['total'] = $_SESSION['total'] - (int)$_SESSION['order']['points_spent'];
            discount_points_update($link);
        } else {
            $_SESSION['order']['points_gained'] = 0;
            $_SESSION['order']['total'] = $_SESSION['total'];
        }

        $_SESSION['order']['id'] = order_apply($link);

        unset($_SESSION['cart']);
        unset($_SESSION['quantity']);
        unset($_SESSION['total']);
        save_session($link);

        return $_SESSION['order']['id'];
    }

    function user_registration($login, $birthday_date, $password, $date) {
        $destination = $login;
        $topic = "Confirmation of registration";
        $text = "Please follow the link below to verify your email.\r\n\r\nhttp://localhost/registry?login=$destination&&birthday_date=$birthday_date&&password=$password&&date=$date";
        
        send_email($destination, $topic, $text);
    }

    function send_email($destination, $topic, $text) {
        $to = $destination;
        $subject = $topic;
        $message = $text;
        $headers = array(
            'From' => 'username@site.com',
            'Reply-To' => 'username@site.com',
            'X-Mailer' => 'PHP/' . phpversion()
        );

        mail($to, $subject, $message, $headers);
    }

    function make_template() {
        $order_id = $_SESSION['order']['id'];
        $date = $_SESSION['order']['date'];
        $customer_name = $_SESSION['order']['name'];
        $customer_phone = $_SESSION['order']['phone'];
        if (!empty($_SESSION['order']['address'])) {
            $delivery_method = "delivery";
            $customer_address = $_SESSION['order']['address']['street'] . ", " . $_SESSION['order']['address']['house'] . ", " . $_SESSION['order']['address']['appartement'];
        } else {
            $delivery_method = "Self-pickup";
            $customer_address = "Coffee house's address";
        }
        $payment_method = $_SESSION['order']['payment_method'];
        $points_gained = $_SESSION['order']['points_gained'];
        $preliminary_cost = $_SESSION['order']['total'] + $_SESSION['order']['points_spent'];
        $points_spent = $_SESSION['order']['points_spent'];
        $total = $_SESSION['order']['total'];

        $template = "Order №$order_id,\r\nDate and time: $date MSK,\r\nName: $customer_name,\r\nPhone: $customer_phone,\r\nDelivery method: $delivery_method,\r\nAddress: $customer_address,\r\nPayment method: $payment_method,\r\nNew points: $points_gained,\r\nYour order:\r\n";
        
        $i=1;
        foreach ($_SESSION['order']['cart'] as $key => $elem) {
            $name = $elem['name'];
            $count = $elem['count'];
            $cost = $elem['cost'];
            $template .= "$i. $name, $count шт., $cost ₽,\r\n";
            $i++;
        }

        $template .= "Preliminary cost: $preliminary_cost,\r\nSpent points: $points_spent,\r\nTotal cost: $total.";

        return $template;
    }

    function discount_points_update($link) {
        $points_gained = $_SESSION['order']['points_gained'];
        $user_id = $_SESSION['user']['id'];

        $get_points = "SELECT points FROM users WHERE id='$user_id'";
        $user = mysqli_fetch_assoc(mysqli_query($link, $get_points));
        
        $points = (int)$user['points'] - (int)$_SESSION['order']['points_spent'] + $points_gained;

        $update_points = "UPDATE users SET points='$points' WHERE id='$user_id'";
        mysqli_query($link, $update_points);
    }

    function save_session($link) {
        if (!empty($_SESSION['user']['id'])) {
            $id = $_SESSION['user']['id'];
            $cart = !empty($_SESSION['cart']) ? $_SESSION['cart'] : [];
            $quantity = !empty($_SESSION['quantity']) ? $_SESSION['quantity'] : 0;
            $total = !empty($_SESSION['total']) ? $_SESSION['total'] : 0;
            $last_session = ['cart' => $cart, 'quantity' => $quantity, 'total' => $total];

            $json = json_encode($last_session);
            $update = "UPDATE users SET cart='$json' WHERE id='$id'";
            mysqli_query($link, $update);
        }
    }

    function order_apply($link) {
        $products = json_encode($_SESSION['order']['cart']);
        $preliminary_cost = $_SESSION['order']['total'] + $_SESSION['order']['points_spent'];
        $points_spent = $_SESSION['order']['points_spent'];
        $total = $_SESSION['order']['total'];
        $points_gained = $_SESSION['order']['points_gained'];
        $payment_method = $_SESSION['order']['payment_method'];
        $date = $_SESSION['order']['date'];
        $user_id = !empty($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;;
        $address_id = !empty($_SESSION['order']['address']) ? $_SESSION['order']['address']['id'] : 0;
        
        $insert = "INSERT INTO orders SET products='$products', preliminary_cost='$preliminary_cost', points_spent='$points_spent', total='$total', points_gained='$points_gained', payment_method='$payment_method', date='$date', user_id='$user_id', address_id='$address_id'";
        mysqli_query($link, $insert);

        return mysqli_insert_id($link);
    }

//upload files functions

    function upload_file($file, $id, $link) {
        $upload_dir = 'upload';
        $allowed_types = ['image/png','image/jpg','image/jpeg','image/webp','image/gif'];
        $max_size = 1048576;
        $filename = get_filename($file['name'], $id);
        $tmp_name = $file['tmp_name'];
        $path = "$upload_dir/$filename";
        
        if (!in_array($file['type'], $allowed_types)) {
            return ['error' => 'This file type is not supported.'];
        }
        if ($file['size'] > $max_size) {
            return ['error' => 'The file is too large. Maximum size ' . round($max_size/(1024*1024), 1) . 'Mb'];
        }
        if (!move_uploaded_file($tmp_name, $path)) {
            return ['error' => 'An error occurred while loading. Please try again later.'];
        }
        remove_old_image($link, $id);

        return ['path' => $path];
    }

    function get_filename($name, $id) {
        $arr = explode('.', $name);
        $ext = trim(mb_strtolower($arr[count($arr)-1]));
        $str = get_random_str();
        $filename = "user$id.$str.$ext";

        return $filename;
    }

    function get_random_str($num = 3) {
        $arr = [];

        for ($i = 0; $i < $num; $i++) {
            $arr[] = chr(rand(48, 57));
        }
        for ($i = 0; $i < $num; $i++) {
            $arr[] = chr(rand(65, 90));
        }
        for ($i = 0; $i < $num; $i++) {
            $arr[] = chr(rand(97, 122));
        }
        shuffle($arr);

        return implode('', $arr);
    }

    function remove_old_image($link, $id) {
        $query = "SELECT img FROM users WHERE id='$id'";
        $img_path = mysqli_fetch_assoc(mysqli_query($link, $query));

        if ($img_path['img'] !== NULL) {
            unlink($img_path['img']);
        }
    }

    function execute_query($link, $query, $vartypes, $params) {
        // $result = $link->execute_query($query, $params); // for PHP 8.2+

        $stmt = $link->prepare($query);
        $stmt->bind_param($vartypes, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result instanceof mysqli_result) {
            for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
            return $data;
        } else {
            return $result;
        }
    }
?>
