<?php

namespace services\MsgS;
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

use services\SecS\GetUserIP;
use services\MsgS\MessageData;
use services\InitDB; // Import DB Settings
use services\SecS\InputValidator;
use services\SecS\SecurityManager;
use services\AdS\AdManager;

class feedbackManager
{
    private $msg = [];
    function sendFeedBack(int $userID)
    {
    }

    function makeNewComment($userID, $adID, $msg, $rating, $commentParent)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = new InputValidator();
        $security_ob = new SecurityManager();
        //$remoteIP=new GetUserIP();
        $adManagerResponse = new AdManager();
        $timePosted = time();
        $commentID = md5("comm-" . $security_ob->generateOtherID() . time());
        $userID = $inputValidator->sanitizeItem($userID, "string");
        $userID = $inputValidator->validateItem($userID, "string");
        $adID = $inputValidator->sanitizeItem($adID, "string");
        $adID = $inputValidator->validateItem($adID, "string");
        $msg = $inputValidator->sanitizeItem($msg, "string");
        $msg = $inputValidator->validateItem($msg, "string");
        $rating = $inputValidator->sanitizeItem($rating, "int");
        $rating = $inputValidator->validateItem($rating, "int");
        $commentParent = $inputValidator->sanitizeItem($commentParent, "string");
        $commentParent = $inputValidator->validateItem($commentParent, "string");
        //Check if Ad Exist
        if ($adManagerResponse->getAdByID($adID)['status'] == 1) {
            $sql = "INSERT INTO mallfeedback (mallUsrID, mallAdID, mallFeedBackID, mallFeedParent, mallFeedLikes, mallFeedMessage, mallFeedTimePosted) VALUES (?,?,?,?,?,?,?)";
            $stmt = $dbHandler->run($sql, [$userID, $adID, $commentID, $commentParent, $rating, $msg, $timePosted]);
            $this->updateAdUsrRating($adID, $userID, $rating);
            if ($stmt->rowCount() > 0) {
                $this->message("1", "Feedback recieved. Thank you");
            } else {
                $this->message("0", "Something Went Wrong");
            }
        } else {
            $this->message("404", "This Ad does not exist or had been deleted");
        }


        return $this->msg;
    }

    function updateAdUsrRating($adID, $adUsrID, $adRating){
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = new InputValidator();
        $adID = $inputValidator->sanitizeItem($adID, "string");
        $adID = $inputValidator->validateItem($adID, "string");
        $adRating = $inputValidator->sanitizeItem($adRating, "string");
        $adRating = $inputValidator->validateItem($adRating, "string");
        $adUsrID = $inputValidator->sanitizeItem($adUsrID, "string");
        $adUsrID = $inputValidator->validateItem($adUsrID, "string");
        //Check if Ad Rating already Exist, if not creating usrAd
        
            $sql = "SELECT * FROM mallproductrating WHERE mallAdID=? AND mallUsrID=? ";
            $stmt = $dbHandler->run($sql, [$adID,$adUsrID]);
            if ($stmt->rowCount() > 0) {
                $sql = "UPDATE mallproductrating SET mallUsrRating=? WHERE mallUsrID=? AND mallAdID=?";
                $stmt = $dbHandler->run($sql, [$adRating, $adUsrID, $adID]);
                if ($stmt->rowCount() > 0) {
                    $this->message("1", "Product rated succefully");
                } else {
                    $this->message("500", "Could not rate product");
                }
            } else {
                $sql = "INSERT INTO mallproductrating (mallUsrID, mallAdID, mallUsrRating) VALUES (?,?,?)";
                $stmt = $dbHandler->run($sql, [$adUsrID, $adID, $adRating]);
                if ($stmt->rowCount() > 0) {
                    $this->message("1", "Product rated succefully");
                } else {
                    $this->message("500", "Could not rate product");
                }
            }


        return $this->msg;
    }

    function getProductFeedbacks($adID){
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = new InputValidator();
        $adID = $inputValidator->sanitizeItem($adID, "string");
        $adID = $inputValidator->validateItem($adID, "string");
        //Check if Ad Exist
        
            $sql = "SELECT * FROM mallfeedback WHERE mallAdID=? ORDER BY defaultColID ASC";
            $stmt = $dbHandler->run($sql, [$adID]);
            if ($stmt->rowCount() > 0) {
                $this->message("1", $stmt);
            } else {
                $this->message("404", "No review found this Ad.");
            }


        return $this->msg;
    }
    function getProductFeedbacksParents($adID){
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = new InputValidator();
        $adID = $inputValidator->sanitizeItem($adID, "string");
        $adID = $inputValidator->validateItem($adID, "string");
        //Check if Ad Exist
        
            $sql = "SELECT * FROM mallfeedback WHERE mallAdID=? AND mallFeedParent!=? ORDER BY defaultColID ASC";
            $stmt = $dbHandler->run($sql, [$adID,$adID]);
            if ($stmt->rowCount() > 0) {
                $this->message("1", $stmt);
            } else {
                $this->message("404", "No review found this Ad.");
            }


        return $this->msg;
    }
    function getProductTotalRating($adID){
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = new InputValidator();
        $adID = $inputValidator->sanitizeItem($adID, "string");
        $adID = $inputValidator->validateItem($adID, "string");
        //Check if Ad Exist
        
            $sql = "SELECT SUM(mallUsrRating) AS ratetotal FROM mallproductrating WHERE mallAdID=? ORDER BY defaultColID ASC";
            $stmt = $dbHandler->run($sql, [$adID]);
            if ($stmt->rowCount() > 0) {
                $averageRate=$stmt->fetch()['ratetotal']/$stmt->rowCount();
                $this->message("1", $averageRate);
            } else {
                $this->message("501", "0");
            }


        return $this->msg;
    }

    function countComments($commentID){
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = new InputValidator();
        $commentID = $inputValidator->sanitizeItem($commentID, "string");
        $commentID = $inputValidator->validateItem($commentID, "string");
        //Check if Ad Exist
        
            $sql = "SELECT * FROM mallfeedback WHERE mallFeedParent=?";
            $stmt = $dbHandler->run($sql, [$commentID]);
                $this->message("1", $stmt->rowCount());


        return $this->msg;
    }

    function getAllUsrAdReviews($userID){
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = new InputValidator();
        $userID = $inputValidator->validateItem($userID, "string");
        //Check if Ad Exist
        
            $sql = "SELECT * FROM mallproductrating WHERE mallUsrID=? ORDER BY defaultColID ASC";
            $stmt = $dbHandler->run($sql, [$userID]);
            if ($stmt->rowCount() > 0) {
                $this->message("1", $stmt);
            } else {
                $this->message("404", "No Ad reviewed yet.");
            }


        return $this->msg;
    }

    function getCommentReplyByParent($parentCommentID){
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = new InputValidator();
        $parentCommentID = $inputValidator->sanitizeItem($parentCommentID, "string");
        $parentCommentID = $inputValidator->validateItem($parentCommentID, "string");
        //Check if Ad Exist
        
            $sql = "SELECT * FROM mallfeedback WHERE mallFeedParent=? ORDER BY defaultColID ASC";
            $stmt = $dbHandler->run($sql, [$parentCommentID]);
            if ($stmt->rowCount() > 0) {
                $this->message("1", $stmt->fetchAll());
            } else {
                $this->message("404", "No reply for this comment.");
            }


        return $this->msg;
    }

    function getAdUsrRating($adID){
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = new InputValidator();
        $adID = $inputValidator->sanitizeItem($adID, "string");
        $adID = $inputValidator->validateItem($adID, "string");
        //Check if Ad Exist
        
            $sql = "SELECT * FROM mallproductrating WHERE mallAdID=? ORDER BY defaultColID ASC";
            $stmt = $dbHandler->run($sql, [$adID]);
            if ($stmt->rowCount() > 0) {
                $this->message("1", $stmt->fetchAll());
            } else {
                $this->message("404", "No review found this Ad.");
            }


        return $this->msg;
    }

    //REPORT AD

    function reportAd($userID,$reason,$reportMessage){
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = new InputValidator();
        $userID = $inputValidator->sanitizeItem($userID, "string");
        $userID = $inputValidator->validateItem($userID, "string");
        $reason= $inputValidator->sanitizeItem($reason, "string");
        $reason= $inputValidator->validateItem($reason, "string");
        $reportMessage= $inputValidator->sanitizeItem($reportMessage, "string");
        $reportMessage= $inputValidator->validateItem($reportMessage, "string");
        $reportTime=time();
        $sql = "INSERT INTO malladdispute (mallUsrID, mallAdReportReason, mallAdReportMsg, mallAdReportTime) VALUES (?,?,?,?)";
                $stmt = $dbHandler->run($sql, [$userID, $reason, $reportMessage,$reportTime]);
                if ($stmt->rowCount() > 0) {
                    $this->message(1, "Your report is received, we'll look into it.");
                } else {
                    $this->message(500, "Report not received, an error occurred");
                }

                return $this->msg;
    }

    protected function message($key, $value)
    {
        /*
		OBJECTIVE: this method adds all messages returned into msg property as an associative array
		Steps:
		1. passes 2 parameters - key and actual value
		2. sets key parameter as the key of msg property and value as the value of the key passed;
		*/
        $this->msg["status"] = $key;
        $this->msg["message"] = $value;
    }
}
