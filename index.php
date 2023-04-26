<?php 
    ob_start();
    session_start();
    // session_destroy();
    require_once 'connect.php';
    require_once 'functions.php';
    require_once 'metatag.php';
    
    $url = $_SERVER['REQUEST_URI'];
    $result = check_url($url);

    if ($result !== false) {
        if (count($result) < 2) {
            $title = ucfirst($result[0]);
        } else {
            $title = ucfirst(str_replace('%20', ' ', $result[1]));
        }
        $content = get_file("$result[0].php");
        $metatag = !empty($metatag[$result[0]]) ? $metatag[$result[0]] : "Our tastiest $title";
    } else {
        $content = get_file("404.php");
        $title = 'Page not found';
        $metatag = $metatag["404"];
    }
?>
    <!DOCTYPE html>
    <html lang="en-GB">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="theme-color" content="#060709" />
            <meta name="description" content="<?= $metatag ?>">
            <title><?= $title ?></title>
            <link rel="stylesheet" href="/css/style.min.css" content-type="text/css">
            <link rel="icon" href="/images/favicon.webp" type="image/webp">
            <?php
                // error_reporting(E_ALL);
                // ini_set('display_errors', 'on');
                // mb_internal_encoding('UTF-8');
            ?>
        </head>
        <body>
            <div id="app">
                <?php
                    require_once 'templates/header.php';
                    echo $content;
                    require_once 'templates/footer.php';
                ?>
            </div>
            <script src="/js/vue@3.2.36.global.min.js" content-type="text/javascript"></script>
            <script src="/js/script.min.js" content-type="text/javascript"></script>
        </body>
    </html>
<?php ob_end_flush(); ?>
