<?php
require_once("lib/Wix.php");
require_once("lib/Logic.php");

$wix = new Wix();
$instance = $wix->getDecodedInstance();

// LOAD SETTINGS
$instanceId = $instance ? $instance['instanceId'] : "sample-instance";

$settings = new Settings($instanceId);
$widgetSettings = new WidgetSettings($instanceId);

// READ SETTINGS
$settings->readSettings();
$widgetSettings->readSettings();

// CHECK IF POSTED CHANGES
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postedSectionSettings = isset($_POST['section']) ? $_POST['section'] : array();
    $postedWidgetSettings = isset($_POST['widget']) ? $_POST['widget'] : array();

    // update function, this will update the settings for &$settings with $arr.
    $postedSettingsUpdate = function(array $arr, &$settings)
    {
        $postedSettingsCheckboxs = array("withImages", "withDescription", "withPostedBy"); // the checkbox input name in the html

        foreach ($postedSettingsCheckboxs as $key)
        {
            $settings->setSetting($key, false); // clean checkboxs values
        }

        foreach ($arr as $key => $value)
        {
            if (in_array($key, $postedSettingsCheckboxs)) {
                // checkbox support
                if ($value == "on") $value = true;
                else $value = false;
            }

            // set the new settings
            $settings->setSetting($key, $value);
        }

        // write it
        $settings->writeSettings();
    };

    $postedSettingsUpdate($postedSectionSettings, $settings); // update section settings, with settings object
    $postedSettingsUpdate($postedWidgetSettings, $widgetSettings); // update widget settings, with widget object
}
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

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        print "<script>Wix.refreshApp()</script>"; // refreash the app (APP_SETTINGS_CHANGED)
    }
    ?>
</head>
<body style="width: <?php print getWixWidth(); ?>">

<!-- HEADER -->
<div class="center">
    <h1>Instance Settings</h1>

    <div>
        <?php print($instance ? $instance['instanceId'] : "No real instance, Just local settings") ?>
    </div>
</div>

<!-- Settings -->
<div class="center" style="width: 600px; padding-top: 40px">

    <form method="post">
        <fieldset>
            <legend>Section Settings</legend>

            <table cellpadding="2" cellspacing="2">
                <tbody>
                <tr>
                    <td width="50%">
                        Blog Title
                    </td>
                    <td>
                        <input type="text" name="section[title]"
                               value="<?php print $settings->getSetting("title", "My Blog") ?>"/>
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top">
                        Blog Summary
                    </td>
                    <td>
                        <textarea rows="6" cols="25"
                                  name="section[summary]"><?php print $settings->getSetting("summary", "Click on a post to read") ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td width="50%">
                        Author
                    </td>
                    <td>
                        <input type="text" name="section[author]"
                               value="<?php print $settings->getSetting("author", "Blog Staff") ?>"/>
                    </td>
                </tr>
                <tr>
                    <td width="50%">
                        With Images
                    </td>
                    <td>
                        <input type="checkbox"
                               name="section[withImages]" <?php print $settings->getSetting("withImages", true) ? "checked=checked" : "" ?>
                        " />
                    </td>
                </tr>
                <tr>
                    <td width="50%">
                        With Description
                    </td>
                    <td>
                        <input type="checkbox"
                               name="section[withDescription]" <?php print $settings->getSetting("withDescription", true) ? "checked=checked" : "" ?>
                        " />
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>

        <br/><br/>
        <fieldset>
            <legend>Widget Settings</legend>

            <table cellpadding="2" cellspacing="2">
                <tbody>
                <tr>
                    <td width="50%">
                        With Posted By?
                    </td>
                    <td>
                        <input type="checkbox"
                               name="widget[withPostedBy]" <?php print $widgetSettings->getSetting("withPostedBy", true) ? "checked=checked" : "" ?> />
                    </td>
                </tr>
                </tbody>
            </table>
        </fieldset>

        <br/>
        <input type="submit" value="Save Settings" style="padding: 10px"/>
    </form>
</div>

</body>
</html>