<?php
require_once("lib/Wix.php");
require_once("lib/Logic.php");

$wix = new Wix();
$instance = $wix->getDecodedInstance();

// LOAD POSTS
$posts = new Posts();
$posts->loadList();

// LOAD SETTINGS
$widgetSettings = new WidgetSettings($instance ? $instance['instanceId'] : "sample-instance");
$widgetSettings->readSettings();
?>

<!-- POSTS LIST -->
<div style="width: <?php print getWixWidth() ?>">
    <div style="padding: 10px">
        <?php foreach ($posts as $post) : ?>

        <ul>
            <li>
                <a href="<?php echo $wix->getSectionUrl(); ?>/post.show.php?title=<?php echo $post->getTitle(); ?>"
                   style="color: #000; font-size: 12px; text-transform: uppercase"
                   target="<?php echo $wix->getTarget(); ?>"><?php echo $post->getTitle(); ?></a>
                <br/>

                <?php if ($widgetSettings->getSetting("withPostedBy", true)) : // Do we want to see by who? ?>
                <div style="font-size: 10px">Posted by <span style="font-weight: bold">Wix Staff</span>, at
                    <time>10:37 am</time>
                    on this blog
                </div>
                <?php endif; // end withPostedBy ?>

            </li>
        </ul>

        <?php endforeach; ?>
    </div>

    <button onclick="Wix.reportHeightChange($.getDocHeight() + 50);">More Height!</button>
</div>

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