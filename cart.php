<?php include 'connect.php' ?>
<?php
    session_start();
    // session_destroy();
    require_once 'functions.php';
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data)) {
        switch($data['action']) {
            case 'add':
                $confirmation = add_to_cart($data, $link);
                echo json_encode([$_SESSION, $confirmation]);
            break;

            case 'remove':
                $whole_session = remove_from_cart($data, $link);
                echo json_encode($whole_session);
            break;

            case 'get':
                $whole_session = get_cart_products();
                echo json_encode($whole_session);
            break;

            case 'increment':
                $whole_session = change_quantity($data, $link);
                echo json_encode($whole_session);
            break;

            case 'decrement':
                if ($_SESSION['cart'][$data['id']]['count'] > 1) {
                    $whole_session = change_quantity($data, $link);
                    echo json_encode($whole_session);
                } else {
                    echo json_encode($_SESSION);
                }
            break;

            case 'use_points':
                $check_points = use_points($data, $link);
                $result = ['check' => $check_points, 'total' => $_SESSION['total']];
                echo json_encode($result);
            break;

            case 'order':
                if (!empty($_SESSION['cart'])) {
                    $response = order_processing($data['order_details'], $link);
                    if (!empty($response)) {
                        $destination = "emailToReceiveOrders@site.com";
                        $topic = 'New order';
                        $text = make_template();
                        
                        send_email($destination, $topic, $text);
                        echo json_encode(1);
                    } else {
                        echo json_encode(0);
                    }
                }
            break;

            case 'sync':
                echo json_encode($_SESSION);
            break;
        }
    }
?>