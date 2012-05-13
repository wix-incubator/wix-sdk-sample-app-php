<?php
/* Logic.php
 * Author: Idan Harel
 * Company: Wix
 *
 * Common file logic includs get posts or post,
 * set settings for both section or widget.
 * for the exampled blog section/widget
 *
 * The code will get the posts from the posts directory
 * where the title of the post is the file name, excluding the suffix
 * and the post body is the body of the post file
 *
 * The settings is saved the SETTINGS_DIRECTORY, with .json or -widget.json
 * perfix.
 *
 * Settings directory must by +rw (0777) to create settings file
 */

/*
* Configurable
*/

// where the settings will sit in, this must be +rw (0777). We're creating the setting per instance there
define("SETTINGS_DIRECTORY", dirname(__FILE__) . "/../data/settings/");

define("POSTS_DIRECTORY", dirname(__FILE__) . "/../data/posts/"); // where the posts files sits in
define("POSTS_SUFFIX", ".txt");

class Post /* one post */
{
    protected $title;
    protected $body;

    public function getTitle()
    {
        return $this->title;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    /*
    * loadBody
    * loads the body of the specific post
    */
    public function loadBody($title = null)
    {
        if ($title) {
            $this->title = $title;
        }

        $file = POSTS_DIRECTORY . $this->title . POSTS_SUFFIX;
        $data = "";

        if (file_exists($file)) {
            $data = file_get_contents($file);
        }

        $this->setBody($data);
    }
}

class Posts implements IteratorAggregate /* many posts */
{
    private $posts = array();

    public function getIterator()
    {
        return new ArrayIterator($this->posts);
    }

    /*
     * loadList
     * loads the posts list to this class
     */
    public function loadList()
    {
        $posts = array();

        if (file_exists(POSTS_DIRECTORY)) {
            if ($handle = opendir(POSTS_DIRECTORY)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != "..") {
                        $title = str_ireplace(POSTS_SUFFIX, '', $entry);

                        $post = new Post();
                        $post->setTitle($title);

                        array_push($posts, $post);
                    }
                }

                closedir($handle);
            }
        }

        $this->posts = $posts;
    }
}

class Settings /* settings per instance */
{
    protected $instanceId;
    protected $settingsData;

    protected function getSettingsFile()
    {
        return SETTINGS_DIRECTORY . $this->instanceId . ".json";
    }

    public function __construct($instanceId)
    {
        $this->instanceId = $instanceId;
    }

    public function readSettings()
    {
        if (!file_exists($this->getSettingsFile())) {
            return false;
        }

        // get the settings, they should be json-decoded
        $this->settingsData = json_decode(file_get_contents($this->getSettingsFile()), true);
    }

    public function writeSettings()
    {
        file_put_contents($this->getSettingsFile(), json_encode($this->settingsData));
    }

    // set a setting, does not save (you should call writeSettings at the end)
    public function setSetting($name, $value, $overwrite = true)
    {
        if ($overwrite || !isset($this->settingsData[$name])) {
            $this->settingsData[$name] = $value;
        }
    }

    public function getSetting($name, $default = null)
    {
        return isset($this->settingsData[$name]) ? $this->settingsData[$name] : $default;
    }
}

class WidgetSettings extends Settings /* widget settings, stored in a diffrent file */
{
    protected function getSettingsFile()
    {
        return SETTINGS_DIRECTORY . $this->instanceId . "-widget.json";
    }
}

?>