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
<div style="width: <?php $wix->getWidth() ? $wix->getWidth(). "px" : "auto"; ?>">
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
</div>