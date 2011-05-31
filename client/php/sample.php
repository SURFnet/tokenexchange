<?php

require_once('TokenExchangeClient.php');

$tx = new TokenExchangeClient('http://example.com/tokenexchange/v1', 'myApp');

$deviceToken = $tx->getDeviceToken('123456789');

$elems = split('@', $deviceToken);

echo 'deviceToken: ' . $elems[0] . "\n";
echo 'device family: ' . $elems[1];

// Send push notification to deviceToken
