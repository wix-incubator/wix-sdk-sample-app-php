<?php
require_once("lib/Wix.php");
require_once("lib/Logic.php");

$wix = new Wix();
$instance = $wix->getDecodedInstance();

// LOAD POSTS
$posts = new Posts();
$posts->loadList();

// LOAD SETTINGS
$settings = new Settings($instance ? $instance['instanceId'] : "sample-instance");
$settings->readSettings();
?>

<html>
<head>
    <!-- Get title settings, or use default "My Blog" -->
    <title>Home | <?php print $settings->getSetting("title", "My Blog") ?></title>

    <style>
        body {
            font-family: verdana, arial;
            font-size: 12px;
            margin: 30px;
            padding: 0px;
        }

        .center {
            width: 400px;
            margin: 0px auto;
        }
    </style>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="js/Wix.js" type="text/javascript"></script>
    <script>
        $.getDocHeight = function () {
            var D = document;
            return Math.max(Math.max(D.body.scrollHeight, D.documentElement.scrollHeight), Math.max(D.body.offsetHeight, D.documentElement.offsetHeight), Math.max(D.body.clientHeight, D.documentElement.clientHeight));
        };

        $(document).ready(function () {
            // report your application height changes
            $('img').load(function () {
                Wix.reportHeightChange($.getDocHeight());
            });

            Wix.reportHeightChange($.getDocHeight());
        });
    </script>
</head>
<body style="width: <?php $wix->getWidth() ? $wix->getWidth(). "px" : "auto"; ?>">

<!-- HEADER -->
<div class="center">
    <h1>Welcome to <?php print $settings->getSetting("title", "My Blog") ?></h1>

    <div>
        <?php print $settings->getSetting("summary", "Click on a post to read") ?>
    </div>
</div>

<!-- POSTS LIST -->
<div style="padding: 10px">
    <?php foreach ($posts as $post) : ?>

    <!-- EACH POST -->
    <div style="padding-top: 20px; margin: 0 auto; width: 700px;">
        <!-- Href's should copy the section-url and work relative to them, they also need to include target="_top" -->
        <a href="<?php echo $wix->getSectionUrl(); ?>/post.show.php?title=<?php echo str_replace(" ", "_", $post->getTitle()); ?>"
           target="<?php echo $wix->getTarget(); ?>"
           style="color: #000; font-size: 28px; text-transform: uppercase"><?php echo $post->getTitle(); ?></a>

        <br/>

        <!-- Post data -->
        <div style="font-size: 10px">
            Posted by <span
            style="font-weight: bold"><?php print $settings->getSetting("author", "Blog Staff") ?></span>, at
            <time>10:37 am</time>
            on this blog
        </div>
        <div style="padding-top: 10px">

            <div style="float: left">
                <?php if ($settings->getSetting("withImages", true)) : // Do we want images? ?>
                <img src="http://lorempixel.com/250/100/?rand=<?php print rand(100, 500); ?>"/> <!-- random image -->
                <?php endif; // end withImages ?>
            </div>

            <?php if ($settings->getSetting("withDescription", true)) : // Do we want description? ?>
            <div style="float: left; padding-left: 20px"> <!-- description -->
                Lorem ipsum dolor sit amet, consectetur adipisicing elit,<br/>
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br/>
                Ut enim ad minim veniam Duis aute irure <br/>
                dolor in reprehenderit in voluptate<br/>
                velit esse cillum dolore eu <br/>
                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proiden
            </div>
            <?php endif; // end withDescription ?>

            <div style="clear: both"></div>
        </div>
    </div>

    <?php endforeach; ?>
</div>

</body>
</html>