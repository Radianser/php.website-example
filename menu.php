<?php include 'connect.php' ?>
<?php
    $query = "SELECT * FROM categories";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
?>

<div class="main">
    <div class="menu">
        <p class="chain"><a href="/">Main</a> | <a href="/menu">Menu</a></p>
        <h1>Menu</h1>
        <hr class="left-underline">
        <div class="tiles">
            <?php
                foreach ($data as $category) {
                    $template = "<a href='./category/$category[id]/$category[category]' class='card'>
                                    <img src='/images/categories/$category[category].webp' alt='$category[category]' loading='lazy'/>
                                    <div class='name'>$category[category]</div>
                                </a>";
                    echo $template;
                }
            ?>
        </div>
    </div>
</div>