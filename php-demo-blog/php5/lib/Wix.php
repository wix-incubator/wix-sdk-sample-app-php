<?php
/* Wix.php
 * Author: Idan Harel
 * Company: Wix
 *
 * Wix library file
 */

/* Configurable */
define("WIX_APP_IP", "128bdc66-326c-5e7c-ce0b-da11314be03e");
define("WIX_SECRET", "8f3ae4a8-7483-4976-9918-9c3eefa5de5a");

class Wix
{
    private $appId; // YOUR APP ID
    private $secret; // YOUR SECRET KEY

    private function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    private function parseSignedRequest($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = $this->base64UrlDecode($encoded_sig);
        $data = json_decode($this->base64UrlDecode($payload), true);

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $this->secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    public function __construct($appId = null, $secret = null)
    {
        $this->appId = $appId !== null ? $appId : WIX_APP_IP;
        $this->secret = $secret !== null ? $secret : WIX_SECRET;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    /*
     * returns the parent-url the app should work relative on
     * If inside Wix, it'll return the parent url from the query line,
     * else, we'll return the relative REQUEST_URI for this file
     */
    public function getSectionUrl()
    {
        $parentUrl = isset($_GET['section-url']) ? $_GET['section-url'] : null;

        if (!$parentUrl) {
            // we're not using dirname, becouse if we did not specify a file dirname will give us
            // the parent directory of the REQUEST_URI. this is a little trick to get the right parent
            $parentUrl = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], "/"));
        }

        return $parentUrl;
    }

    public function getTarget()
    {
        return isset($_GET['target']) ? $_GET['target'] : "_self";
    }

    public function getWidth()
    {
        // get width pixels
        return isset($_GET['width']) ? $_GET['width'] : null;
    }

    // returns your decoded instance - can return null
    public function getDecodedInstance()
    {
        $instance = isset($_GET['instance']) ? $_GET['instance'] : null;

        if ($instance === null) {
            error_log("Instance not found!");
            return null;
        }

        $instance = $this->parseSignedRequest($instance);

        return $instance;
    }
}

?>