<?php
namespace services;
//Confirm if file is local or Public and add the right path
        // the commented part is the original downloaded code
// $url = 'http://' . $_SERVER['SERVER_NAME'];
// if (strpos($url,'localhost')) {
//     //require_once(__DIR__ ."\../../vendor/samayo/bulletproof/src/bulletproof.php");
//     require_once(__DIR__ . "\../vendor/autoload.php");
//     //USE services\configGlobal;
//     require_once(__DIR__."./configGlobal.php");
// } else if (strpos($url,'gaijinmall')) {
//     //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
//     require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
//     //USE services\configGlobal;
//     require_once("configGlobal.php");
// }
// else{
//     //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
//     require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
//     //USE services\configGlobal;
// require_once("configGlobal.php");
// }

$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    //require_once(__DIR__ ."\../../vendor/samayo/bulletproof/src/bulletproof.php");
    require_once(__DIR__ . "\../vendor/autoload.php");
    //USE services\configGlobal;
    require_once("configGlobal.php");
} else if (strpos($url,'gaijinmall')) {
    //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    //USE services\configGlobal;
    require_once("configGlobal.php");
}
else if (strpos($url,'192.168')){
    //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
    require_once(__DIR__ . "\../vendor/autoload.php");
    //USE services\configGlobal;
require_once(__DIR__."./configGlobal.php");
}
else{
    //require_once($_SERVER['DOCUMENT_ROOT']."/vendor/samayo/bulletproof/src/bulletproof.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
    //USE services\configGlobal;
require_once("configGlobal.php");
}

class InitDB{
    public $pdo;
    public function __construct($db, $username = NULL, $password = NULL, $host)
    {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try {
            $this->pdo = new \PDO($dsn, $username, $password, PDO_OPTIONS);
            //CONNECTED TO DB
        } catch (\PDOException $e) {
            //SEND THIS TO AUDIT SERVICE FOR LOGGING
        }
    }
    public function run($sql, $args = NULL)
    {
        $stmt=null;
        
            if (!$args)
        {
            return $this->pdo->query($sql);
        }
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($args);
        } catch (PDOException $e) {
            //SEND THIS TO AUDIT SERVICE FOR LOGGING
            echo $e->getMessage();
        }
        
        return $stmt;
    }
}

/* 

USAGE

$unitTest=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
$stmt=$unitTest->run("SELECT * FROM commission_tb WHERE id <= ?", [5]);
echo $stmt->rowCount(); */
?>