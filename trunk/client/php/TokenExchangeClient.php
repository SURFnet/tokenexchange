<?php

/**
 * A simple client for the Token Exchange. 
 * Note: uses url wrappers.
 */
class TokenExchangeClient 
{  
    protected $_tokenExchangeURL;
    protected $_appId;
 
    public function __construct($tokenExchangeURL, $appId)
    {
        $this->_tokenExchangeURL = $tokenExchangeURL;
        $this->_appId = $appId;
    }
   
    public function getDeviceToken($notificationToken)
    {
        $url = $this->_tokenExchangeURL."?appId=".$this->_appId;
        
        $url.= "&notificationToken=".$notificationToken;
        
        $output = file_get_contents($url);
        if (stripos($output, "not found")!==false) return false;
        if (stripos($output, "error")!==false) return false;
        return trim($output);
    }
}
