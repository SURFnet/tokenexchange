<?php

if (defined('TX_CONFIG_LOCATION')) {
    // We are wrapped inside a script. 
    // Assume the wrapper has set tx_config_location to the location
    // of our config file and also has included TokenExchange.php
    require_once(TX_CONFIG_LOCATION);
} else {
    require_once("../../TokenExchange.php");
    require_once("../../config.php");
}
if (!defined('VERSION')) {
    // Not included by older version wrappers.
    define('VERSION', 1.1);
}

date_default_timezone_set('UTC');

mylog(var_export($_REQUEST, true));

if (!isset($_REQUEST["appId"])) {
    header("HTTP/1.0 400 Bad Request");
    echo "appId param required";
    die;
} else if (!isset($config["apps"][$_REQUEST["appId"]])) {
    header("HTTP/1.0 403 Forbidden");
    echo "This app is not supported by this tokenexchange";
    die;
} 

try {

$tokenExchange = new TokenExchange($_REQUEST["appId"], $config);

if (isset($_REQUEST["notificationToken"]) && isset($_REQUEST["deviceToken"])) {

    // Update of a Token
    if (!isset($_REQUEST["deviceFamily"]) || !in_array($_REQUEST["deviceFamily"], array("ios","android","blackberry"))) {
        header("HTTP/1.0 400 Bad Request");
        echo "deviceFamily param required (must be 'ios', 'android' or 'blackberry')";
        die;
    }

    $result = $tokenExchange->update($_REQUEST["notificationToken"], $_REQUEST["deviceToken"], $_REQUEST["deviceFamily"]);
    if (is_string($result)) {
        echo $result;
    }
    mylog("Updated token");

} else if (isset($_REQUEST["notificationToken"]) && !isset($_REQUEST["deviceToken"])) {

    // Query for a Token
    $dt = $tokenExchange->get($_REQUEST["notificationToken"]);
    if (!$dt) { 
        echo "NOT FOUND";
        mylog("Token not found");
    } else {
        if (VERSION>=1.1) {
            echo $dt->deviceToken.'@'.$dt->deviceFamily;
        } else {
            echo $dt->deviceToken;
        }
        mylog("Retrieved token");
    }

} else if (!isset($_REQUEST["notificationToken"]) && isset($_REQUEST["deviceToken"])) { 

    // Creation of a Token
    if (!isset($_REQUEST["deviceFamily"]) || !in_array($_REQUEST["deviceFamily"], array("ios","android","blackberry"))) {
        header("HTTP/1.0 400 Bad Request");
        echo "deviceFamily param required (must be 'ios', 'android' or 'blackberry')";
        die;
    } 

    // First check if it doesn't exist.
    $nt = $tokenExchange->getByDevice($_REQUEST["deviceToken"], $_REQUEST["deviceFamily"]);
    if ($nt!=false) {
        $tokenExchange->update($nt->notificationToken, $_REQUEST["deviceToken"]);
        echo $nt->notificationToken;
        mylog("Created token, but existed, so updated instead");
    } else {

        $notificationToken = $tokenExchange->uniqueToken();

        $tokenExchange->create($notificationToken, $_REQUEST["deviceToken"], $_REQUEST["deviceFamily"]);
        echo $notificationToken;
        mylog("Created token");
    }
    

} else {
    header("HTTP/1.0 400 Bad Request");
    echo "ERROR";
    mylog("Invalid inputs");

}

} 
catch (Exception $e)
{
    header("HTTP/1.0 500 Internal Server Error");
    echo "ERROR";
    mylog("Error: ".var_export($e, true));
}

function mylog($txt)
{
    global $config;
    if ($config["logging"]!="") {
        $fh = fopen($config["logging"], "a");
        fwrite($fh, date("c")." ".$txt."\n");
        fclose($fh);
    }
}
