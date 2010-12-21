<?php

require_once('TokenExchangeClient.php');

$tx = new TokenExchangeClient('http://example.com/tokenexchange/v1', 'myApp');

$deviceToken = $tx->getDeviceToken('123456789');

// Send push notification to deviceToken
