<?php
require_once("lib/Logic.php");

$title = isset($_GET['title']) ? str_replace("_", " ", $_GET['title']) : "Unknown Posft";

$post = new Post();
$post->loadBody($title);
?>

<html>
<head>
    <title><?php print $title; ?> | PHP Sample Blog</title>

    <style>
        body {
            font-family: verdana, arial;
            font-size: 12px;
            margin: 30px;
            padding: 0px;
        }
    </style>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="js/Wix.js" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            Wix.reportHeightChange($('body').outerHeight(true));
        });
    </script>
</head>
<body style="width: <?php $wix->getWidth() ? $wix->getWidth(). "px" : "auto"; ?>">

<!-- POST -->
<div>
    <h1><?php print $post->getTitle(); ?></h1>

    <div style="padding-top: 10px">
        <?php print nl2br($post->getBody()); ?>
    </div>
</div>

</body>
</html>