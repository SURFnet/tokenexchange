<?php

// v1 interface is present for devices that do not have an up to date client.
// v1 makes the following assumptions:
// - deviceFamily wasn't supported in v1, so we default it to 'ios' (the only 
//   one supported in v1)

$_REQUEST["deviceFamily"] = "ios";

define('VERSION', 1);

require_once("../v1.1/index.php");

