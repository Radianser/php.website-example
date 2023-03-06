<?php include 'connect.php' ?>
<?php
    $url = $_SERVER['REQUEST_URI'];
    $route = '#^/[a-z]+/([0-9]+)/[a-z]+$#';
    if (preg_match($route, $url, $match)) {
        $id = $match[1];
    }
    $query = "SELECT products.id as id, products.name as name, products.weight as weight, products.price as price, products.description as description, category FROM products 
        LEFT JOIN categories ON products.category_id=categories.id
        WHERE categories.id='$id'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
?>

<div class="main">
    <div class="menu">
        <p class="chain"><a href="/">Main</a> | <a href="/menu">Menu</a> | <a href="<?= $url ?>"><?php echo ucfirst($data[0]['category']) ?></a></p>
        <h1><?php echo ucfirst($data[0]['category']) ?></h1>
        <hr class="left-underline">
        <div class="tiles">
            <?php
                foreach ($data as $product) {
                    $name = str_replace(' ', '_', $product['name']);
                    $template = "<div class='product-card'>
                                    <img src='/images/products/$name.webp' alt='$name' loading='lazy'/>
                                    <div class='description-screen'></div>
                                    <div class='description'>
                                        <p class='product-price'>$product[price]₽</p>
                                        <br>
                                        <br>
                                        <p>Weight: $product[weight] gr.</p>
                                        <p>Description: $product[description]</p>
                                    </div>
                                    <div class='name'>$product[name]</div>
                                    <div data-id='$product[id]' class='add-to-cart' @click='addProduct'>+ add</div>
                                </div>";
                    echo $template;
                }
            ?>
        </div>
    </div>
</div>