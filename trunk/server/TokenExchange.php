<?php

class Token
{
    public $deviceToken;
    public $deviceFamily;
    public $notificationToken;
}

class TokenExchange
{  
    protected $_secret;
    protected $_appId;
    protected $_db;
    protected $_appConfig;
 
    public function __construct($appId, $config) {
        $this->_secret = $config["randomSecret"];
        $this->_appId = $appId; 
        $this->_appConfig = $config["apps"][$appId];

        $this->_db = new PDO($config["dbDsn"], $config["dbUser"], $config["dbPassword"]);
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function randomHash()
    {
        $hash = hash_hmac ('sha1', mt_rand(), $this->_secret);
        if (isset($this->_appConfig["tokensize"])) {
            return substr($hash,0,$this->_appConfig["tokensize"]);
        }
        return $hash;
    }

    /**  
     * @param String $notificationToken
     * @return Token or false
     */
    public function get($notificationToken)
    {
        $stmt = $this->_db->prepare("select devicetoken,devicefamily from token 
                                     where 
                                        notificationtoken = :nt 
                                        and appid = :appid");
        $stmt->bindParam("nt", $notificationToken);
        $stmt->bindParam("appid", $this->_appId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && !empty($row["devicetoken"])) {
            $t = new Token();
            $t->deviceToken = $row["devicetoken"];  
            $t->deviceFamily = $row["devicefamily"];
            $t->notificationToken = $notificationToken;
            return $t;
        }
        return false;
    }
 
    public function getByDevice($deviceToken, $deviceFamily)
    {
        $stmt = $this->_db->prepare("select notificationtoken from token 
                                     where 
                                        devicetoken = :dt 
                                        and appid = :appid
                                        and devicefamily = :devicefamily");
        $stmt->bindParam("dt", $deviceToken);
        $stmt->bindParam("appid", $this->_appId);
        $stmt->bindParam("devicefamily", $deviceFamily);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && !empty($row["notificationtoken"])) {
            $t = new Token();
            $t->deviceToken = $deviceToken;
            $t->deviceFamily = $deviceFamily;
            $t->notificationToken = $row["notificationtoken"];
            return $t;
        }
        return false;
    }

    public function create($notificationToken, $deviceToken, $deviceFamily)
    {
        $stmt = $this->_db->prepare("insert into token 
                                        (notificationtoken, devicetoken, 
                                         appid, devicefamily, created_at) 
                                     values (:nt, :dt, :appid, :devicefamily, now())");
        $stmt->bindParam("nt", $notificationToken);
        $stmt->bindParam("dt", $deviceToken);
        $stmt->bindParam("appid", $this->_appId);
        $stmt->bindParam("devicefamily", $deviceFamily);

        if ($stmt->execute()) {
//            $this->_db->commit();
            return true;
        }
        return false;
    }

    public function update($notificationToken, $deviceToken)
    {
        $currentToken = $this->get($notificationToken);
        if ($currentToken!==false) {
            $stmt = $this->_db->prepare("update token set devicetoken=:dt, updated_at=now()
                                         where 
                                            notificationtoken=:nt 
                                            and appid=:appid");
            $stmt->bindParam("nt", $notificationToken);
            $stmt->bindParam("dt", $deviceToken);
            $stmt->bindParam("appid", $this->_appId);
            if ($stmt->execute()) {
//            $this->_db->commit();
                return $notificationToken;
            }
        } else {
            // Getting an update of a token that didn't exist yet.
            $newNotificationToken = $this->uniqueToken(); // generate a valid token to prevent people from posting invalid ids
            if ($this->create($newNotificationToken, $deviceToken)) {
                return $newNotificationToken;
            }
        }
        return false;
   
    }

    public function uniqueToken() 
    {
        $notificationToken = false;
        while ($notificationToken==false || $this->get($notificationToken)!==false) {
           $notificationToken = $this->randomHash();
        }
        return $notificationToken;
    }
}
