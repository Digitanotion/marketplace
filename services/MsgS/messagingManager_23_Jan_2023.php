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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use services\AccS\AccountManager;
use services\AdS\AdManager;
use services\MsgS\MessageData;
use services\MedS\MediaManager;
USE services\SecS\SecurityManager;
USE services\SecS\InputValidator;
USE services\InitDB;
USE services\SecS\GetUserIP;
class messagingManager{
    private $system_message=[];
    private $messageData_ob=null;
    public $imgSrc;
    public $adTitle;
    public $adNotifyBody;
    public $mainMsg;
    public $msgLink;
    public $toName;
    function sendMail($from, $to,$subject,$message1=null,$msg_type=null,$from_name=null,$to_name=null,$message2=null, $link1=null,$link2=null){
        $mail = new PHPMailer(true);
        $messageData_ob=new MessageData();
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'server.uxh.gxw.mybluehost.me';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'smtp_sen__@gaijinmall.com';                     //SMTP username
            $mail->Password   = 'w00o#@8A35?-';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($from, $from_name);
            $mail->addAddress($to);     //Add a recipient        
            //$mail->addReplyTo($reply_to);
            //$mail->addCC($cc);

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            switch ($msg_type) {
                case 'welcome':
                $mail->Body    = $messageData_ob->_welcome_msg($to_name,$message1);
                    break;
                case 'verify':
                    $mail->Body    = $messageData_ob->_verify_email($to_name,$message1,$from_name,null,null,null,$link1);
                    break;
                case 'forget_password':
                    $selectortoken=explode(":",$link1);
                    $selector=$selectortoken[0];
                    $token=$selectortoken[1];
                    $mail->Body = $messageData_ob->_forget_password($message1,$selector,$token);
                    break;
                case 'ad_notification':
                    $mail->Body = $message1;
                        break;
                default:
                $mail->Body    = "";
                    break;
            }
            
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $this->message(1,"Mail Sent");
        } catch (Exception $e) {
            $this->message(500,"Mail sending failed");
        }
        return $this->system_message;
    }

   
    protected function message($status, $message){
        $this->system_message["status"] = $status;
        $this->system_message["message"] = $message;
    }
    ////////////////////// NEW UPDATE /////////////////////////////
    function initMessageSender($usrID,$adID, $recieverUsrID){
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$inputValidator=new InputValidator();
        $security_ob=new SecurityManager();
        $remoteIP=new GetUserIP();
        $msg_id=md5("msg-".$security_ob->generateOtherID());
		$msgTime=time();
        $userAgent=$_SERVER['HTTP_USER_AGENT'];
        $userIP=$remoteIP->getIpAddress();
        $usrID=$inputValidator->sanitizeItem($usrID, "int");
        $usrID=$inputValidator->validateItem($usrID, "int");

        //////////CHECK IF CONVERATION HAS BEEN CREATED BEFORE
        $sql = "SELECT * FROM mallmsggroups WHERE mallMsgSenderID=? AND mallMsgReceiverID=?";
		$stmt = $dbHandler->run($sql, [$usrID,$recieverUsrID]);
        $sql2 = "SELECT * FROM mallmsggroups WHERE mallMsgReceiverID=? AND mallMsgSenderID=?";
		$stmt2 = $dbHandler->run($sql2, [$usrID,$recieverUsrID]);
        if ($stmt->rowCount() > 0 || $stmt2->rowCount() > 0) {
            $getMsgID=$stmt->fetch();
			$this->message(1, $getMsgID['mallMsgID']);
		} else {
			$sql1 = "INSERT INTO mallmsggroups (mallAdID,mallMsgID,mallMsgSenderID,mallMsgReceiverID,mallMsgStartTime,mallMsgSenderAgent,mallMsgSenderIP) VALUES (?,?,?,?,?,?,?)";
            $stmt1 = $dbHandler->run($sql1, [$adID,$msg_id,$usrID, $recieverUsrID, $msgTime, $userAgent, $userIP]);
            //$stmtData=$stmt->fetch();
            if ($stmt1->rowCount() > 0) {
                $this->message(1, $msg_id);
            } else {
                $this->message(500, "Could not create conversation");
            }

		}
        return $this->system_message;
    }
    /**
     * This function compiles messages sent from a user to a user
     *
     * @param string  $msgID Gotten from the initMessage function, used to initiate a conversation, every message is group under a conversation with an ID 
     * @param string $msgType usr_to_usr or sys_to_usr or usr_to_sys
     * @param int $usrID Equivalent to sender id
     */
    function getAllUserMsgGroup($usrID){
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$inputValidator=new InputValidator();
        $usrID=$inputValidator->sanitizeItem($usrID, "int");
        $usrID=$inputValidator->validateItem($usrID, "int");
        //Check if conversation was created
        $sql = "SELECT * FROM mallmsggroups WHERE mallMsgSenderID=? OR mallMsgReceiverID=?";
		$stmt = $dbHandler->run($sql, [$usrID,$usrID]);
        if ($stmt->rowCount() > 0) {
            $getMsgID=$stmt->fetchAll();
			$this->message(1, $getMsgID);
		} 
        else {
            $this->message(404, "No Conversation");
        }
        return $this->system_message;

    }

    function getAllUsrNotifi_ByID($usrID){
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$inputValidator=new InputValidator();
        $msgID=$inputValidator->sanitizeItem($usrID, "string");
        $msgID=$inputValidator->validateItem($usrID, "string");
        //Check if conversation was created
        $sql = "SELECT * FROM mallnotifications WHERE mallNotifyToUserID=? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($sql, [$usrID]);
        if ($stmt->rowCount() > 0) {
            $getMsgs=$stmt->fetchAll();
			$this->message(1, $getMsgs);
		} 
        else {
            $this->message(404, "No Notification found");
        }
        return $this->system_message;

    }
    function getMsgGroupInfoByMsgID($msgID){
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$inputValidator=new InputValidator();
        $usrID=$inputValidator->sanitizeItem($msgID, "string");
        $usrID=$inputValidator->validateItem($msgID, "string");
        //Check if conversation was created
        $sql = "SELECT * FROM mallmsggroups WHERE mallMsgID=?";
		$stmt = $dbHandler->run($sql, [$msgID]);
        if ($stmt->rowCount() > 0) {
            $getMsgID=$stmt->fetch();
			$this->message(1, $getMsgID);
		} 
        else {
            $this->message(404, "Message group ID Does not exist");
        }
        return $this->system_message;

    }
    

    function getAllUserMsgsByMsgID($msgID){
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$inputValidator=new InputValidator();
        $msgID=$inputValidator->sanitizeItem($msgID, "string");
        $msgID=$inputValidator->validateItem($msgID, "string");
        //Check if conversation was created
        $sql = "SELECT * FROM mallmsgs WHERE mallMsgID=?";
		$stmt = $dbHandler->run($sql, [$msgID]);
        if ($stmt->rowCount() > 0) {
            $getMsgs=$stmt->fetchAll();
			$this->message(1, $getMsgs);
		} 
        else {
            $this->message(404, "No Messages for the group");
        }
        return $this->system_message;

    }
    function getLastUserMsgsByMsgID($msgID,$pointer){
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$inputValidator=new InputValidator();
        $msgID=$inputValidator->sanitizeItem($msgID, "string");
        //Check if conversation was created
        $sql = "SELECT * FROM mallmsgs WHERE mallMsgID=? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($sql, [$msgID]);
        if ($stmt->rowCount() > 0) {
            $getMsgs=$stmt->fetch();
			$this->message(1, $getMsgs);
		} 
        else {
            $this->message(404, "Message not found");
        }
        return $this->system_message;

    }

    function sendMsgUsrToUsr($usrID,$adID,$recieverUsrID,$UsrMsg,$msgType,$msgID=""){
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$inputValidator=new InputValidator();
        $security_ob=new SecurityManager();
		$msgTime=time();
        $msgInitID=md5($msgTime);
        $usrID=$inputValidator->sanitizeItem($usrID, "int");
        $recieverUsrID=$inputValidator->sanitizeItem($recieverUsrID, "int");
        $UsrMsg=$inputValidator->sanitizeItem($UsrMsg, "stringLite");
        $msgType=$inputValidator->sanitizeItem($msgType, "string");
        $msg_id=$this->initMessageSender($usrID,$adID,$recieverUsrID);
        $msg_id_main=$msg_id['message'];
        //Check if conversation was created
        if ($msg_id['status']==1){
            if (!empty($msgID)){
                $msg_id=$msgID;
            }
            if ($msg_id_main==""){
                $msg_id_main=$msgID;
            }
            
            //Add message to queue
            $sql1 = "INSERT INTO mallmsgs (mallMsgID,mallMsgSenderID,mallMsgReceiverID,mallMsgTime,mallMsgValue,mallMsgSendStatus,mallMsgType,mallMsgInitUsr) VALUES (?,?,?,?,?,?,?,?)";
            $stmt1 = $dbHandler->run($sql1, [$msg_id_main, $usrID, $recieverUsrID, $msgTime, $UsrMsg, "1",$msgType,$msgInitID]);
            //$stmtData=$stmt->fetch();
            if ($stmt1->rowCount() > 0) {
                $this->message(1, $msgInitID);
            } else {
                $this->message(500, "Message not sent");
            }
        }
        else{
            $this->message(500, $msg_id['message']);
        }
        return $this->system_message;

    }

    function sendMsgUsrToSys($usrID,$adID,$msgReason,$UsrMsg,$msgType){
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$inputValidator=new InputValidator();
        $security_ob=new SecurityManager();
		$msgTime=time();
        $usrID=$inputValidator->sanitizeItem($usrID, "int");
        $usrID=$inputValidator->validateItem($usrID, "int");
        $UsrMsg=$inputValidator->sanitizeItem($UsrMsg, "stringLite");
        $UsrMsg=$inputValidator->validateItem($UsrMsg, "stringLite");
        $msgReason=$inputValidator->sanitizeItem($msgReason, "string");
        $msgReason=$inputValidator->validateItem($msgReason, "string");
        $msgType=$inputValidator->sanitizeItem($msgType, "string");
        $msgType=$inputValidator->validateItem($msgType, "string");
        $msg_id=$this->initMessageSender($usrID,$adID,"1029384756");
        //Check if conversation was created
        if ($msg_id['status']==1){
            $msg_id=$msg_id['message'];
            //Add message to queue
            $sql1 = "INSERT INTO mallmsgs (mallMsgID,mallMsgSenderID,mallMsgReceiverID,mallMsgTime,mallMsgValue,mallMsgSendStatus,mallMsgType) VALUES (?,?,?,?,?,?,?)";
            $stmt1 = $dbHandler->run($sql1, [$msg_id, $usrID, "1029384756", $msgTime, $UsrMsg, "1","sys_to_sys_abuse"]);
            //$stmtData=$stmt->fetch();
            if ($stmt1->rowCount() > 0) {
                $this->message(1, "Message sent");
            } else {
                $this->message(500, "Message not sent");
            }
        }
        else{
            $usrID=$inputValidator->sanitizeItem($usrID, "int");
            $this->message(500, $msg_id['message']);
        }
        return $this->system_message;

    }

    //KC PART
    public function sendAdNotification($userID, $adminID, $adID, $msgBody,$extraInfo,$type){

        //a method for sending notification messagesfrom admins to users
        $securityManagerOb=new SecurityManager();
        $inputValidatorOb=new InputValidator();
        $userID=$inputValidatorOb->sanitizeItem($userID,"string");
        $userID=$inputValidatorOb->validateItem($userID,"string");
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
        $accManager_ob=new AccountManager();
        $msgData=new MessageData();
        $adManager=new AdManager();
        $mediaManager=new MediaManager();
        $getAdDetails=$adManager->getAllAdByID($adID)['message'];
        $getAdTitle=$getAdDetails['mallAdTitle'];
        $getAdImageFile="https://gaijinmall.com/handlers/uploads/optimized/".$mediaManager->getAllAdImage($adID)['message'][0]['mallMediaName'];
        $buildAdLink=explode(" ", $getAdTitle);
        $buildAdLink=implode("_",$buildAdLink);
        $adLink="https://gaijinmall.com/product.php?$buildAdLink&adID=$adID";
        //var_dump($getAdImageFile);
        $userInfo=$accManager_ob->getUsrBasicInfoByID($userID)['message'];
        $userEmail=$userInfo['mallUsrEmail'];
        $userFirstName=$userInfo['mallUsrFirstName'];
        if (empty($msgBody)){
            $this->message(500, "Input field is necessary");
         }
         else{
            $msgBody = $inputValidatorOb->sanitizeItem($msgBody, "string");
            $msgBody = $inputValidatorOb->validateItem($msgBody, "string");
            $notifyTime = time();
            $sql = "INSERT INTO mallnotifications( mallNotifyTime, 
                                                   mallNotifyAdminInitID, 
                                                   mallNotifyToUserID,
                                                   mallNotifyContent,
                                                   mallNotifyReadStat,
                                                   mallNotifyextraInfo,
                                                   mallNotifyType ) VALUES (?,?,?,?,?,?,?)";
            $stmt = $dbHandler->run($sql, [$notifyTime, $adminID, $userID, $msgBody, 0,$extraInfo,$type]);
            if($stmt->rowCount() > 0){
                $this->message(1, "Notification sent successfully");//ad_notification
                //Initialize and set message data properties
                $mainMsgBody=$msgData->_ad_msg($userFirstName,$getAdTitle,$getAdImageFile,$msgBody,$adLink);
                $this->sendMail("noreply@gaijinmall.com",$userEmail,$msgBody,$mainMsgBody,"ad_notification");
                // $msgData->adTitle=$getAdTitle;
                // $msgData->imgSrc=$getAdImageFile;
                // $msgData->mainMsg=$msgBody;
                return $this->system_message;
            }else{
                $this->message(500, "Error sending notification");
                return $this->system_message;
            }
         }

    }

    public function sendNotification($mallUsrID, $mallAdminID, $mallMessage) {
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
        $inputValidatorOb=new InputValidator();
        $mallUsrID=$inputValidatorOb->sanitizeItem($mallUsrID, "string");
        $mallAdminID =$inputValidatorOb->sanitizeItem($mallAdminID, "string");
        $mallMessage =$inputValidatorOb->sanitizeItem($mallMessage, "string");
        $notifyTime = time();
            $sql = "INSERT INTO mallnotifications( mallNotifyTime, 
                                                   mallNotifyAdminInitID, 
                                                   mallNotifyToUserID,
                                                   mallNotifyContent,
                                                   mallNotifyReadStat,
                                                   mallNotifyType ) VALUES (?,?,?,?,?,?)";
            $stmt = $dbHandler->run($sql, [$notifyTime, $mallAdminID, $mallUsrID, $mallMessage, 0,"verification"]);
    }

    function usrTousrNotification($to, $from,$msg){
        //$securityManagerOb=new SecurityManager();
        $inputValidatorOb=new InputValidator();
        $to=$inputValidatorOb->sanitizeItem($to, "string");
        $to=$inputValidatorOb->validateItem($to, "string");
        $from=$inputValidatorOb->sanitizeItem($from, "string");
        $from=$inputValidatorOb->validateItem($from, "string");
        $msg=$inputValidatorOb->sanitizeItem($msg, "string");
        $msg=$inputValidatorOb->validateItem($msg, "string");
        //$extra=$inputValidatorOb->sanitizeItem($extra, "string");
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
        //$accManager_ob=new AccountManager();
        //$msgData=new MessageData();
        //$adManager=new AdManager();
        $notifyTime=time();
        $type="usrFollow";
        $sql = "INSERT INTO mallnotifications( mallNotifyTime, 
                                                   mallNotifyAdminInitID, 
                                                   mallNotifyToUserID,
                                                   mallNotifyContent,
                                                   mallNotifyReadStat,
                                                   mallNotifyextraInfo,
                                                   mallNotifyType ) VALUES (?,?,?,?,?,?,?)";
            $stmt = $dbHandler->run($sql, [$notifyTime, "1111100000", $to, $msg, 0,$from,$type]);
            if($stmt->rowCount() > 0){
                $this->message(1, "User notified");
            }
            else{
                $this->message(0, "Could not notify user");
            }
            
            //$this->message(500, $msg_id['message']);
        return $this->system_message;
        }

    function sendSMS($to, $body) {
        $inputValidatorOb=new InputValidator();
        $to=$inputValidatorOb->sanitizeItem($to, "tel");
        $to=$inputValidatorOb->validateItem($to, "tel");
        $username = 'gaijinmall';
        $password = '@AWS#freedomf88a';
        $messages = array(
        array('to'=>'+'.$to, 'body'=>$body)
        ); 
        $post_body=json_encode($messages); 
        $url='https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30';
        $ch = curl_init( );
        $headers = array(
        'Content-Type:application/json',
        'Authorization:Basic '. base64_encode("$username:$password")
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_body );
        // Allow cUrl functions 20 seconds to execute
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
        // Wait 10 seconds while trying to connect
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
        $output = array();
        $output['server_response'] = curl_exec( $ch );
        $curl_info = curl_getinfo( $ch );
        $output['http_status'] = $curl_info[ 'http_code' ];
        $output['error'] = curl_error($ch);
        curl_close( $ch );
        return $output;
      } 
}

//$maka=new messagingManager();
//var_dump($maka->sendSMS("819044844801","Hello, Welcome to Gaijinmall, this is a test sms from our website"));
//print_r($maka->getAllUserMsgsByMsgID("msg-294808811568962"));//,"90180164654665","How much is your blanket","usr_to_usr"));
//$response=$maka->sendMail("noreply@gaijinmall.com","digitanotion@gmail.com","Welcome to Gaijinmall","Welcome","welcome","","john");
//echo $response['message']; 
?>