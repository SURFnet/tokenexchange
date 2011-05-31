<?php

/**
 * A simple client for the Token Exchange. 
 * Since this is a PHP script the client only supports getting
 * a deviceToken based on a notificationToken and not viceversa.
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
   

    /**
     * @return String The devicetoken (with an @ and the device family 
     * appended to it if using a server >=1.1)
     */
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
