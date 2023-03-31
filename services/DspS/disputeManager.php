<?php
namespace services\DspS;
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url, 'localhost')) {
    require_once(__DIR__ . "\../../vendor/samayo/bulletproof/src/bulletproof.php");
    require_once(__DIR__ . "\../../vendor/autoload.php");
} else if (strpos($url, 'gaijinmall')) {
    //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
    require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
} 
else if (strpos($url,'192.168')){
    require_once(__DIR__ . "\../../vendor/autoload.php");
}else{
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
//require_once(__DIR__."\../configGlobal.php"); 
use services\InitDB;
use services\MedS\MediaManager;
use services\MsgS\messagingManager;
use services\SecS\SecurityManager;
use services\SecS\InputValidator;
use services\Params;

class DisputeManager
{
    private $defaultColID;
    private $mallUsrID;
    private $mallUsrfirstName;
    private $mallUsrlastName;
    private $mallUsrBalance;
    private $mallAdReportReason;
    private $mallAdReportMsg;
    private $inputValidatorOb;
    private $securityManagerOb;
    private $dbHandler;
    private $system_message;
    public $paramsOb;
    public $HOMEDIR;

    function __construct()
    {
        $url = 'http://' . $_SERVER['SERVER_NAME'];
        if (strpos($url, 'localhost')) {
            $this->HOMEDIR = $_SERVER['DOCUMENT_ROOT'] . "/gaijinmall/";
        } else if (strpos($url, 'gaijinmall')) {
            $this->HOMEDIR = $_SERVER['DOCUMENT_ROOT'];
        } else {
            $this->HOMEDIR = $_SERVER['DOCUMENT_ROOT'] . "/gaijinmall/";
        };
        //echo $this->HOMEDIR;
        $this->securityManagerOb = new SecurityManager();
        $this->inputValidatorOb = new InputValidator();
        $this->dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $this->paramsOb = new Params();
    }

    function message($key, $value)
    {
        $this->system_message['status'] = $key;
        $this->system_message['message'] = $value;
    }

    public function getAllUsrDispute() {
        $dbHandler = $this->dbHandler;
        $sql = "SELECT 
                    u.mallUsrFirstName, u.mallUsrLastName, u.mallUsrID,
                    d.defaultcolid,d.mallAdReportReason, d.mallAdReportMsg, d.mallUsrID
                FROM mallusrs AS u
                JOIN malladdispute AS d
                ON u.mallUsrID = d.mallUsrID";
        $stmt = $dbHandler->run($sql);
        $num = $stmt->rowCount();
        if ($num > 0) {
            $sys_message['status'] = 1;
            $sys_message['message'] = $stmt->fetchAll();
        } else {
            $sys_message['status'] = 404;
            $sys_message['message'] = "No Dispute found";
        }
        return $sys_message;
    }


}

