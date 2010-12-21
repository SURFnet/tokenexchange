<?php


class Token
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

    public function get($notificationToken)
    {
        $stmt = $this->_db->prepare("select devicetoken from token where notificationtoken = :nt and appid = :appid");
        $stmt->bindParam("nt", $notificationToken);
        $stmt->bindParam("appid", $this->_appId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && !empty($row["devicetoken"])) {
            return $row["devicetoken"];
        }
        return false;
    }
 
    public function getByDevice($deviceToken)
    {
        $stmt = $this->_db->prepare("select notificationtoken from token where devicetoken = :dt and appid = :appid");
        $stmt->bindParam("dt", $deviceToken);
        $stmt->bindParam("appid", $this->_appId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($row) && !empty($row["notificationtoken"])) {
            return $row["notificationtoken"];
        }
        return false;
    }

    public function create($notificationToken, $deviceToken)
    {
        $stmt = $this->_db->prepare("insert into token (notificationtoken, devicetoken, appid) values (:nt, :dt, :appid)");
        $stmt->bindParam("nt", $notificationToken);
        $stmt->bindParam("dt", $deviceToken);
        $stmt->bindParam("appid", $this->_appId);
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
            $stmt = $this->_db->prepare("update token set devicetoken=:dt where notificationtoken=:nt and appid=:appid");
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
