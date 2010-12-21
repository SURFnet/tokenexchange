<?php

mylog(var_export($_REQUEST, true));

require_once("../../Token.php");
require_once("../../config.php");

if (!isset($_REQUEST["appId"])) {
    echo "appId param required";
    die;
} else if (!isset($config["apps"][$_REQUEST["appId"]])) {
    echo "This app is not supported by this tokenexchange";
    die;
}

try {

$token = new Token($_REQUEST["appId"], $config);

if (isset($_REQUEST["notificationToken"]) && isset($_REQUEST["deviceToken"])) {

    // Update of a Token
    $result = $token->update($_REQUEST["notificationToken"], $_REQUEST["deviceToken"]);
    if (is_string($result)) {
        echo $result;
    }
    mylog("Updated token");

} else if (isset($_REQUEST["notificationToken"]) && !isset($_REQUEST["deviceToken"])) {

    // Query for a Token
    $dt = $token->get($_REQUEST["notificationToken"]);
    if (!$dt) { 
        echo "NOT FOUND";
        mylog("Token not found");
    } else {
        echo $dt;
        mylog("Retrieved token");
    }

} else if (!isset($_REQUEST["notificationToken"]) && isset($_REQUEST["deviceToken"])) { 

    // Creation of a Token

    // First check if it doesn't exist.
    $nt = $token->getByDevice($_REQUEST["deviceToken"]);
    if ($nt!=false) {
        $token->update($nt, $_REQUEST["deviceToken"]);
        echo $nt;
        mylog("Created token, but existed, so updated intead");
    } else {

        $notificationToken = $token->uniqueToken();

        $token->create($notificationToken, $_REQUEST["deviceToken"]);
        echo $notificationToken;
        mylog("Created token");
    }
    

} else {

    echo "ERROR";
    mylog("Invalid inputs");

}

} 
catch (Exception $e)
{
    var_dump($e);
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
