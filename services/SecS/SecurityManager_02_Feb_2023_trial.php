<?php
//Security Services Class goes here
namespace services\SecS;
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

use services\AccS\AccountManager;
use services\SecS\InputValidator;
use services\InitDB;
use services\MsgS\messagingManager;
use \Delight\Auth;

session_start();
class SecurityManager extends InputValidator
{
    public $CSRFToken;
    public $viewCategory;
    public $updateCategory;
    private $inputValidatorOb;
    private $securityManagerOb;
    private $dbHandler;
    private $system_message = [];

    function __construct()
    {

        //$this->generateCSRF();
        //$this->securityManagerOb=new SecurityManager();
        //$this->inputValidatorOb=new InputValidator();
        //$dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);

    }

    public function sanitizeInputs($inputData)
    {
        //This will sanitize every foriegn input
        $inputData = strip_tags($inputData);
        $inputData = htmlspecialchars($inputData);
        $inputData = stripslashes($inputData);
        //$inputData=escapeshellcmd($inputData);
        $inputData = htmlentities($inputData, ENT_QUOTES, 'UTF-8');
        return $inputData;
    }

    function generateCSRF()
    {
        //This will generate a token hash for checking form submission are done within the page and not by robots
        $token1 = bin2hex(random_bytes(4));
        $token2 = bin2hex(random_bytes(4));
        $token3 = bin2hex(random_bytes(3));
        $token4 = bin2hex(random_bytes(2));
        //$_SESSION['_generatedCSRF']=$token;
        return $this->CSRFToken = $token1 . "-" . $token2 . "-" . $token3 . "-" . $token4;
    }
    function setCSRF()
    {
        session_regenerate_id();
        $genToken = $this->generateCSRF();
        if (isset($_SESSION['mallCSRFToken__alpanum'])) {
            unset($_SESSION['mallCSRFToken__alpanum']);
            $_SESSION['mallCSRFToken__alpanum'] = $genToken;
        } else {
            $_SESSION['mallCSRFToken__alpanum'] = $genToken;
        }
        return $genToken;
    }
    function validateCSRF($token)
    {
        //echo $_SESSION['mallCSRFToken__alpanum']. " | ".$token;
        if (isset($_SESSION['mallCSRFToken__alpanum']) && $_SESSION['mallCSRFToken__alpanum'] == $token) {
            return true;
        } else {
            return false;
        }
    }

    function generateMallID()
    {
        return substr(mt_rand(1000, 99000) . time(), 0, 10); //use only 10 characters for the userID
    }
    function generateOtherID()
    {
        return substr(time() . mt_rand(100000000, 990000000000), 5, 15); //use only 15 characters for the userID
    }

    function newVerifyCode()
    {
        return substr(mt_rand(1000, 99000) . time(), 0, 6);
    }



    public function generateProgramHash(String $data)
    {
        //This will generate hash data for use all through this system sha1(
        $saltedData = bin2hex(openssl_random_pseudo_bytes(24) . uniqid()) . $data;
        return sha1($saltedData);
    }
    function getUserInfoByID(String $usrID)
    {
        //Sanitize inputs
        $sys_message = [];
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $this->inputValidatorOb = new InputValidator();
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        //Query DB
        $sql = "SELECT * FROM mallusrs WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$usrID]);
        $num = $stmt->rowCount();
        if ($num > 0) {
            $sys_message['status'] = 1;
            $sys_message['message'] = $stmt->fetch();
        } else {
            $sys_message['status'] = 404;
            $sys_message['message'] = "User not found";
        }
        return $sys_message;
    }

    public function removeUsedVerifyToken($usrID_token)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]); //Handle DB
        //Delete Token from DB
        $sql = "DELETE FROM malltokens WHERE mallUsrID = ? OR mallToken=?";
        $stmt = $dbHandler->run($sql, [$usrID_token, $usrID_token]);
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function verifyToken($emailToken)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]); //Handle DB
        $account_manager = new AccountManager();
        //Delete Token from DB
        $currentTime = time();
        $sql = "SELECT * FROM malltokens WHERE mallToken = ?";
        $stmt = $dbHandler->run($sql, [$emailToken]);
        $tokeData = $stmt->fetch();
        $tokenGenTime = $tokeData['mallTokenGenTime'];
        $tokenUsrID = $tokeData['mallUsrID'];
        if ($stmt->rowCount() > 0) {
            $timeDiff = abs($tokenGenTime - $currentTime) / 3600;
            if ($timeDiff <= 1) {
                $this->removeUsedVerifyToken($emailToken);
                $account_manager->userEmailStatus_verified($tokenUsrID);
                //ACTIVATE USER ACCOUNT
                $sqlUsrAccStatus = "UPDATE mallusrs SET mallUsrAccountStatus=? WHERE mallUsrID=?";
                $stmtUsrAccStatus = $dbHandler->run($sqlUsrAccStatus, ["1", $tokenUsrID]);
                // ACTIVATE USER BUSINESS INFORMATION

                /*-------------commented out by by Promise**************/
                // $sqlUsrBizStatus = "UPDATE mallusrbiz SET mallBizStatus=? WHERE mallUsrID=?";
                // $stmtUsrBizStatus = $dbHandler->run($sqlUsrBizStatus, ["1", $tokenUsrID]);
                
                return true;
            }
        } else {
            $this->removeUsedVerifyToken($emailToken);
            return false;
        }
    }

    public function generateVerifyToken($user_email, $new_email=null)
    {
        $account_manager = new AccountManager();
        $inputValidatorOb = new InputValidator();
        $mailHandlerOb = new messagingManager();
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]); //Handle DB
        $user_info__ = $account_manager->get_user_info($user_email); //Get User Info
        $newVerifyCode = $this->newVerifyCode();
        $newToken = $this->generateProgramHash("Verify");
        $tokenGenTime = time();
        if ($user_info__['status'] === 1) {
            $user_info_details = $user_info__['message'];
            $usrID = $user_info_details['mallUsrID'];
            $usrEmail = $user_info_details['mallUsrEmail'];
            $usrFirstName = $user_info_details['mallUsrFirstName'];

            $res = $account_manager->checkEmailExist($new_email);

            if ($res === 1) {
                 $this->message(500, "Email already in use");
            } 
            else {

                $this->removeUsedVerifyToken($usrID); //Remove user tokens
                $sql = "INSERT INTO malltokens (  mallUsrID,
                                                           mallTokenType,
                                                           mallToken,
                                                           mallVerifyCode,
                                                           mallTokenGenTime)  VALUES(?,?,?,?,?)";
                $stmt = $dbHandler->run($sql, [$usrID, "verify", $newToken, $newVerifyCode, $tokenGenTime]);
                $check_status = $stmt->rowCount();
                if ($check_status > 0) {
                    //this next line of code is basically for email update. $usrEmail will be converted to what the user inputs
                    $usrEmail = is_null($new_email) ? $usrEmail : $new_email;
                    $mailHandlerOb->sendMail("noreply@gaijinmall.com", $usrEmail, "Gaijinmall Email Verification", $newVerifyCode, "verify", null, $usrFirstName, null, $newToken);
                    $this->message(1, "We've sent a verification link to your email"); //success
                } else {
                    $this->message(500, "Verification failed");
                }
            }
        } elseif ($user_info__['status'] == "404") {
            //$this->message(500, "Registration was not really successful"); //User phone or email does not exist
            $this->message(1, "User does not exist");
        }
        return $this->system_message;
    }

    function generateHashBcrypt($inputData)
    {
        $generatedHash = password_hash($inputData, PASSWORD_DEFAULT);
        return $generatedHash;
    }

    function generate_reset_token($user_key)
    {
        $token_generated = null;
        $token_time = time();
        $token_type = "reset";
        $audit_ob = new AuditService();
        //Generate New Token
        if (function_exists('com_create_guid') === true) {
            $token_generated = trim(com_create_guid(), '{}');
        }
        $token_generated = sprintf('%04g', mt_rand(0, 9999), mt_rand(0, 9999), mt_rand(0, 9999), mt_rand(1111, 9999), mt_rand(2445, 4978), mt_rand(0, 9999), mt_rand(0, 9999), mt_rand(0, 9999));
        //Insert new token to DB
        if ($token_generated != null) {
            $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);

            //this does an insertion first using the user_key
            //Verify if user has an active token already
            $active_user_token_stmt = $dbHandler->run("SELECT * FROM tokens_table WHERE user_key=?", [$user_key]);
            if ($active_user_token_stmt->rowCount() > 0) {
                //Token Exists update with new Token
                $update_user_token_stmt = $dbHandler->run("UPDATE tokens_table SET token__=?, token_time=?, token_status=? WHERE user_key=?", [$token_generated, $token_time, "1", $user_key]);
                if ($update_user_token_stmt->rowCount() == 1) {
                    //SEND SMS
                    $audit_ob->publishRecentActivity($user_key, "1", "Another Token generated for user [$user_key - $token_generated]", "0", "0");
                    return $token_generated;
                } else {
                    $audit_ob->publishRecentActivity($user_key, "1", "Failed to generate another token for user [$user_key - $token_generated]", "2", "1");
                    return false;
                }
            } else {
                $stmt = $dbHandler->run("INSERT INTO tokens_table (user_key,token__,token_type,token_status, token_time) VALUES (?,?,?,?,?)", [$user_key, $token_generated, $token_type, "1", $token_time]);
                if ($stmt->rowCount() == 1) {
                    //SEND SMS
                    $audit_ob->publishRecentActivity($user_key, "1", "Another Token generated for user [$user_key - $token_generated]", "0", "0");
                    return $token_generated;
                } else {
                    return false;
                }
            }
        }
    }
    function getIPAddress()
    {
        foreach (array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
            'REMOTE_HOME'
        ) as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $IPaddress) {
                    $IPaddress = trim($IPaddress); // Just to be safe

                    if (
                        filter_var(
                            $IPaddress,
                            FILTER_VALIDATE_IP,
                            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                        )
                        !== false
                    ) {

                        return $IPaddress;
                    }
                }
            }
        }
    }

    function auth__user($user_email_phone, $password__)
    {
        //This function authenticates a user into the system
        //hash password
        //$password__hash=password_hash($password__hash, PASSWORD_DEFAULT);
        //Fetch user info from DB
        $account_manager = new AccountManager();
        $this->inputValidatorOb = new InputValidator();
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $user_email_phone = $this->inputValidatorOb->sanitizeItem($user_email_phone, "string");
        $password__ = $this->inputValidatorOb->sanitizeItem($password__, "string");
        $user_info__ = $account_manager->get_user_info($user_email_phone);
        $time_login = time();
        if ($user_info__['status'] == 1) {
            $user_info_details = $user_info__['message'];
            $user_email_status = $user_info_details['mallUsrEmailStatus'];
            if ($user_info_details['mallUsrAccountStatus']!=2){
                if ($user_email_status == "1") {
                    if (password_verify($password__, $user_info_details['mallUsrPassword'])) {
                        $_SESSION['gaijinmall_user_'] = $user_info_details['mallUsrID'];
                        if (isset($_SESSION['gaijinmall_user_'])) {
                            //$this->message(1, "Success");
                            session_regenerate_id(); //Medigate session hijacking
                            //SET USER ONLINE PARAMETER IN DB
                            $sqlUsrOnline = "UPDATE mallusrs SET mallUsrOnline=? WHERE mallUsrID=?";
                            $stmtUsrOnline = $dbHandler->run($sqlUsrOnline, ["1", $user_info_details['mallUsrID']]);
                            // SET LAST SEEN 
                            //SET USER ONLINE PARAMETER IN DB
                            $sqlUsrLastSeen = "UPDATE mallusrs SET mallUsrLatestTime=? WHERE mallUsrID=?";
                            $stmtUsrLastSeen = $dbHandler->run($sqlUsrLastSeen, [$time_login, $user_info_details['mallUsrID']]);
                            $this->message(1, $user_info_details);
                            header("location: ./" );
                        } else {
                            $this->message(500, "User session does not exist");
                        }
                    } else {
                        //echo "Welcome";
                        $this->message(404, "Invalid credential"); //User password does not match with account
                    }
                } else {
                    $this->message(500, "Email not verified, Check your mail now");
                    $this->generateVerifyToken($user_email_phone);
                }
            }
            else{
                $this->message(404, "Invalid credential");
            }
                
        } elseif ($user_info__['status'] == "404") {
            $this->message(500, "Invalid credentials"); //User phone or email does not exist
        }
        return $this->system_message;
    }
    function auth__user_admin($user_email_phone, $password__)
    {
        //This function authenticates a user into the system
        //hash password
        //$password__hash=password_hash($password__hash, PASSWORD_DEFAULT);
        //Fetch user info from DB
        $account_manager = new AccountManager();
        $this->inputValidatorOb = new InputValidator();
        $user_email_phone = $this->inputValidatorOb->sanitizeItem($user_email_phone, "string");
        $password__ = $this->inputValidatorOb->sanitizeItem($password__, "string");
        $user_info__ = $account_manager->get_user_info($user_email_phone);
        if ($user_info__['status'] == 1) {
            $user_info_details = $user_info__['message'];
            if ($user_info_details['mallUsrIsAdmin']) {
                if (password_verify($password__, $user_info_details['mallUsrPassword'])) {
                    $_SESSION['gaijinmall_user_admin__'] = $user_info_details['mallUsrID'];
                    if (isset($_SESSION['gaijinmall_user_admin__'])) {
                        $this->message(1, "");
                        session_regenerate_id(); //Medigate session hijacking
                        header("location: cspace.php");
                    } else {
                        $this->message(500, "User session does not exist");
                    }
                } else {
                    $this->message(500, "Invalid Credentials");
                    //$this->generateVerifyToken($user_email_phone);
                }
            } else {
                $this->message(500, "Invalid credentials");
            }
            //$user_email_status=$user_info_details['mallUsrEmailStatus'];
            /*  if ($user_email_status=="1"){
                
                }
                else{
                    echo "Welcome";
                    $this->message(404,"Invalid credential"); //User password does not match with account
                } */
        } elseif ($user_info__['status'] == "404") {
            $this->message(500, "Invalid credentials"); //User phone or email does not exist
        }
        return $this->system_message;
    }
    function is_user_auth__(String $user_email_phone = null)
    {   
        //Check if there is an active session
        session_regenerate_id(); //Stop Session Hijacking
        if (isset($_SESSION['gaijinmall_user_'])) {
            //$this->message("1","User session found");
            return true;
        } else {
            //$this->message("404","User session not found");
            return false;
        }
    }
    function is_user_auth_admin__(String $user_email_phone = null)
    {
        //Check if there is an active session
        session_regenerate_id(); //Stop Session Hijacking
        if (isset($_SESSION['gaijinmall_user_admin__'])) {
            //$this->message("1","User session found");
            return true;
        } else {
            //$this->message("404","User session not found");
            return false;
        }
    }

    function endUserSession(String $user_email_phone = null)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        if (isset($_SESSION['gaijinmall_user_'])) {
            //$this->message("1","User session found");
            $sqlUsrOnline = "UPDATE mallusrs SET mallUsrOnline=? WHERE mallUsrID=?";
            $stmtUsrOnline = $dbHandler->run($sqlUsrOnline, ["0", $_SESSION['gaijinmall_user_']]);
            unset($_SESSION['gaijinmall_user_']);
            session_destroy();
            return true;
            //Close Database
        } else {
            //$this->message("404","User session not found");
            return false;
        }
    }
    function endUserSessionAdmin(String $user_email_phone = null)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        if (isset($_SESSION['gaijinmall_user_admin__'])) {
            //$this->message("1","User session found");
            unset($_SESSION['gaijinmall_user_admin__']);
            session_destroy();
            return true;
            //Close Database
        } else {
            //$this->message("404","User session not found");
            return false;
        }
    }


    protected function message($status, $message)
    {
        $this->system_message["status"] = $status;
        $this->system_message["message"] = $message;
    }
    //////////////////////////////////////////////////////
    //////////NEW ENTRIES ///////////////////////////////
    ///////////////////////////////////////////////////////
    function updateUsrPasswordByID(string $usrID, $oldPassword, $NewPassword)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = $this->inputValidatorOb;
        $usrID = $this->sanitizeItem($usrID, "int");
        $oldPassword = $this->sanitizeItem($oldPassword, "string");
        $NewPassword = $this->sanitizeItem($NewPassword, "string");
        ////////// CHECK OLD PASSWORD /////////////////////////////
        $sql = "SELECT mallUsrID, mallUsrPassword FROM mallusrs  WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$usrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $fetchUsrPass = $stmt->fetch();
            $usrPass = $fetchUsrPass['mallUsrPassword'];
            if (password_verify($oldPassword, $usrPass)) {
                ////////// UPDATE IF OLD PASSWORD IS CORRECT ///////////////////////////
                $newPassHash = $this->generateHashBcrypt($NewPassword);
                $sql1 = "UPDATE mallusrs SET mallUsrPassword=? WHERE mallUsrID=?";
                $stmt1 = $dbHandler->run($sql1, [$newPassHash, $usrID]);
                $check_status1 = $stmt1->rowCount();
                if ($check_status1 > 0) {
                    $this->message(1, "Password updated");
                } else {
                    $this->message(500, "Not updated");
                }
            } else {
                $this->message(500, "Wrong password");
            }
        } else {
            $this->message(404, "User not found");
        }

        return $this->system_message;
    }
    function getUsrURL()
    {
        // Program to display URL of current page.
        $link = "";
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $link = "https";
        } else {
            $link = "http";
        }
        // Here append the common URL characters.
        $link .= "://";
        // Append the host(domain name, ip) to the URL.
        $link .= $_SERVER['HTTP_HOST'];
        // Append the requested resource location to the URL
        $link .= $_SERVER['REQUEST_URI'];
        // Print the link
        return $link;
    }
    function usrLastSeen($usrID)
    {
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        //Get DB Info
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        //Query DB
        $sql = "SELECT * FROM mallusrs WHERE mallUsrPhoneNo=? OR mallUsrEmail=?";
        $stmt = $dbHandler->run($sql, [$user_email_phone, $user_email_phone]);
        $num = $stmt->rowCount();
        if ($num > 0) {
            $sys_message['status'] = 1;
            $sys_message['message'] = $stmt->fetch();
        } else {
            $sys_message['status'] = 404;
            $sys_message['message'] = "User not found";
        }
        return $this->system_message;
    }

    function forgot_password($email)
    {
        //$db=new \PDO('mysql:dbname=gaijinmall;host=localhost;charset=utf8mb4', 'root', 'DB_OPTIONS[2]');
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $auth = new \Delight\Auth\Auth($dbHandler->pdo);

        try {
            $auth->forgotPassword($email, function ($selector, $token, $email) {
                $this->message(1, "$selector:$token");
                $mailHandlerOb = new messagingManager();
                $account_manager_on = new \services\AccS\AccountManager();
                $userFirstName = $account_manager_on->getUsrBasicInfoByEmail($email)['message']['mallUsrFirstName'];
                $message = "<p>To reset the password to your gaijinmall account, click the button below:</p>";
                $mailHandlerOb->sendMail("noreply@gaijinmall.com", $email, "Gaijinmall password reset", $message, "forget_password", null, $userFirstName, null, "$selector:$token");
            });
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $this->message(500, 'Invalid email address');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $this->message(500, 'Email not verified');
        }
        /*  catch (\Delight\Auth\ResetDisabledException $e) {
            die('Password reset is disabled');
        } */ catch (\Delight\Auth\TooManyRequestsException $e) {
            $this->message(500, 'Too many requests');
        }

        return $this->system_message;
    }

    function verify_forgot_password_token($selector, $token)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $auth = new \Delight\Auth\Auth($dbHandler->pdo);
        try {
            $auth->canResetPasswordOrThrow($selector, $token);
            $this->message(1, "$selector:$token");
            //echo 'Put the selector into a "hidden" field (or keep it in the URL)';
            //echo 'Put the token into a "hidden" field (or keep it in the URL)';

            //echo 'Ask the user for their new password';
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            $this->message(500, 'Invalid token');
        } catch (\Delight\Auth\TokenExpiredException $e) {
            $this->message(500, 'Token expired');
        } catch (\Delight\Auth\ResetDisabledException $e) {
            $this->message(500, 'Password reset is disabled');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $this->message(500, 'Too many requests');
        }

        return $this->system_message;
    }

    function reset_password($selector, $token, $newPassord)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $auth = new \Delight\Auth\Auth($dbHandler->pdo);
        try {
            $auth->resetPassword($selector, $token, $newPassord);
            $this->message(1, "Password has been reset");
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            $this->message(500, 'Invalid token');
        } catch (\Delight\Auth\TokenExpiredException $e) {
            $this->message(500, 'Token expired');
        } catch (\Delight\Auth\ResetDisabledException $e) {
            $this->message(500, 'Password reset is disabled');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->message(500, 'Invalid password');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $this->message(500, 'Too many requests');
        }
        catch (\Delight\Auth\NewPasswordExistException $e) {
            $this->message(500, 'Password has been used, try a new password');
        }
        return $this->system_message;
    }

    function generatePhoneVerifyToken($usrID, $usrPhone)
    {
        $inputValidator = new InputValidator();
        $message_ob=new messagingManager();
        $account_manager=new AccountManager();
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        $usrPhone = $inputValidator->sanitizeItem($usrPhone, "string");
        $usrPhone = preg_replace('/[^0-9]/', '', $usrPhone);
        //Get DB Info
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        //Query DB
        $newVerifyCode = $this->newVerifyCode();
        $newToken = $this->generateProgramHash("Verify");
        $tokenGenTime = time();
        $user_info__ = $this->getUserInfoByID($usrID);
        //Check if user exist in token table, delete if yes.
        $sql = "SELECT * FROM mallphoneverify WHERE mallUsrID=? AND mallUsrPhoneNo=?";
        $stmt = $dbHandler->run($sql, [$usrID, $usrPhone]);
        $num = $stmt->rowCount();
        if ($user_info__['status']==1){
            $user_Details=$user_info__['message'];
            $userFirstName=$user_Details['mallUsrFirstName'];
            if ($num > 0) {
                //DELETE RECORD ALREADY
                $sql = "DELETE FROM mallphoneverify WHERE mallUsrID=? AND mallUsrPhoneNo=?";
                $stmt = $dbHandler->run($sql, [$usrID, $usrPhone]);
                $num = $stmt->rowCount();
                if ($num > 0) {
                    $sql = "INSERT INTO mallphoneverify (mallUsrID,
                mallUsrPhoneNo,
                mallToken,
                mallTokenID,
                mallTokenGenTime)  VALUES(?,?,?,?,?)";
                    $stmt = $dbHandler->run($sql, [$usrID, $usrPhone, $newVerifyCode, $newToken, $tokenGenTime]);
                    $check_status = $stmt->rowCount();
                    if ($check_status > 0) {
                        $this->message(1, $newVerifyCode);
                        //echo "Hello $userFirstName, your verification code is $newVerifyCode. For further enquiries, please call +81 80-5696-5135, to $usrPhone";
                        $message_ob->sendSMS($usrPhone,"Hello $userFirstName, your Gaijinmall verification code is $newVerifyCode. For further enquiries, please call +81 80-5696-5135");
                    } else {
                        $this->message(500, "Could not store");
                    }
                }
            } else {
                $sql = "INSERT INTO mallphoneverify (mallUsrID,
                mallUsrPhoneNo,
                mallToken,
                mallTokenID,
                mallTokenGenTime)  VALUES(?,?,?,?,?)";
                $stmt = $dbHandler->run($sql, [$usrID, $usrPhone, $newVerifyCode, $newToken, $tokenGenTime]);
                $check_status = $stmt->rowCount();
                if ($check_status > 0) {
                    $this->message(1, $newVerifyCode);
                    //echo "Hello $userFirstName, your verification code is $newVerifyCode. For further enquiries, please call +81 80-5696-5135, to $usrPhone";
                    var_dump($message_ob->sendSMS($usrPhone,"Hello $userFirstName, your verification code is $newVerifyCode. For further enquiries, please call +81 80-5696-5135"));
                } else {
                    $this->message(500, "Could not store");
                }
            }
        }
        else{
            $this->message(400, "User does not exist");
        }
            //echo $usrID." | ".$usrPhone;

        return $this->system_message;
    }

    function verifyPhoneToken($usrID, $usrPhone, $token)
    {
        $inputValidator = new InputValidator();
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        $usrPhone = $inputValidator->sanitizeItem($usrPhone, "string");
        $token = $inputValidator->sanitizeItem($token, "string");
        //Get DB Info
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $sql = "SELECT * FROM mallphoneverify WHERE mallUsrID=? AND mallUsrPhoneNo=? AND mallToken=?";
        $stmt = $dbHandler->run($sql, [$usrID, $usrPhone, $token]);
        // $usrID." | ".$usrPhone." | ".$token;
        $num = $stmt->rowCount();
        if ($num > 0) {
            $this->message(1, "token matched");
        } else {
            $this->message(404, "do not match");
        }

        return $this->system_message;
    }

    function deletePhoneVerifyToken($usrID, $usrPhone,$token)
    {
        $inputValidator = new InputValidator();
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        $usrPhone = $inputValidator->sanitizeItem($usrPhone, "string");
        $token = $inputValidator->sanitizeItem($token, "string");
        //Get DB Info
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
    
            //DELETE RECORD ALREADY
            $sql = "DELETE FROM mallphoneverify WHERE mallUsrID=? AND mallUsrPhoneNo=? AND mallToken=?";
            $stmt = $dbHandler->run($sql, [$usrID, $usrPhone,$token]);
            $num = $stmt->rowCount();
            if ($num > 0) {
                    $this->message(1, "token deleted");
                } else {
                    $this->message(500, "could not delete");
                }

        return $this->system_message;
    }

    public function checkUserPasswordByIDPasswrd($usrID,$newPasswrd){
        $inputValidator = new InputValidator();
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        $newPasswrd = $inputValidator->sanitizeItem($newPasswrd, "string");
        //Get DB Info
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $sql = "SELECT * FROM mallusrpasswrd WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$usrID]);
        $num = $stmt->rowCount();
        if ($num > 0) {
            $user_info_details=$stmt->fetchAll();
            $match_status=0;
            foreach ($user_info_details as $key) {
                if (password_verify($newPasswrd, $key['mallUsrPasswrd'])){
                    $match_status+=1;
                }
            }
            if ($match_status>0){
                $this->message(1, "Password exist");
            }
            else {
                $this->message(404, "password not found");
            }
        } else {
            $this->message(404, "password not found");
        }

        return $this->system_message;
    }

    public function updateUserPasswordByIDPasswrd($usrID,$newPasswrd){
        $inputValidator = new InputValidator();
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        $newPasswrd = $inputValidator->sanitizeItem($newPasswrd, "string");
        $newPasswrd = \password_hash($newPasswrd, \PASSWORD_DEFAULT);
        //Get DB Info
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $time__=time();
        $sql = "INSERT INTO mallusrpasswrd (mallUsrID,mallUsrPasswrd,mallUsrPassTime) VALUES(?,?,?)";
        $stmt = $dbHandler->run($sql, [$usrID, $newPasswrd,$time__]);
        $num = $stmt->rowCount();
        if ($num > 0) {
            $this->message(1, "New password updated");
        } else {
            $this->message(500, "could not update password");
        }

        return $this->system_message;
    }
}



//$emailob = new SecurityManager();
//$emailob1=$emailob->updateUsrPasswordByID("2901916465","Passwor","Password");
// /echo \password_hash("Password", \PASSWORD_DEFAULT);
//var_dump($emailob->checkUserPasswordByIDPasswrd("6268416575","Password2."));
//print_r($emailob1["message"]); 
