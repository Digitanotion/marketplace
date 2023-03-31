<?php
namespace services\AccS;
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


class AccountManager
{
    private $defaultColID;
    private $mallUsrID;
    private $mallUsrfirstName;
    private $mallUsrlastName;
    private $mallUsrLocation;
    private $mallUsrBirthday;
    private $mallUsrSex;
    private $mallUsrPhoto;
    private $mallUsrPhone;
    private $mallUsrPhoneNoStaus;
    private $mallUsrEmail;
    private $mallUsrEmailStatus;
    private $mallUsrOnline;
    private $mallUsrPassword;
    private $mallUsrBalance;
    private $mallUsrAccountStatus;
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

    function get_user_info(String $user_email_phone)
    {
        //Sanitize inputs
        $sys_message = [];
        $inputValidator = $this->inputValidatorOb;
        $user_email_phone = $inputValidator->sanitizeItem($user_email_phone, "string");
        //Get DB Info
        $dbHandler = $this->dbHandler;
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
        return $sys_message;
    }
    function get_user_info_admin(String $user_email_phone)
    {
        //Sanitize inputs
        $sys_message = [];
        $inputValidator = $this->inputValidatorOb;
        $user_email_phone = $inputValidator->sanitizeItem($user_email_phone, "string");
        //Get DB Info
        $dbHandler = $this->dbHandler;
        //Query DB
        $sql = "SELECT * FROM mallusrs WHERE mallAdminPhone=? OR mallAdminEmail=?";
        $stmt = $dbHandler->run($sql, [$user_email_phone, $user_email_phone]);
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

    function get_user_info_admin_old(String $user_email_phone)
    {
        //Sanitize inputs
        $sys_message = [];
        $inputValidator = $this->inputValidatorOb;
        $user_email_phone = $inputValidator->sanitizeItem($user_email_phone, "string");
        //Get DB Info
        $dbHandler = $this->dbHandler;
        //Query DB
        $sql = "SELECT * FROM mallusrs WHERE mallAdminPhone=? OR mallAdminEmail=?";
        $stmt = $dbHandler->run($sql, [$user_email_phone, $user_email_phone]);
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

    function userEmailStatus_verified($usrID)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $sql = "UPDATE mallusrs SET mallUsrEmailStatus=?";
        $stmt = $dbHandler->run($sql, ["1"]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function new_user_account($mallUsrfirstName, $mallUsrlastName, $mallUsrEmail, $mallUsrPhone, $mallUsrPassword)
    {
        //Sanitize inputs

        $inputValidator = $this->inputValidatorOb;
        $securityManagerOb = $this->securityManagerOb;
        if (empty($mallUsrfirstName) || empty($mallUsrlastName) || empty($mallUsrEmail) || empty($mallUsrPhone) || empty($mallUsrPassword)) {
            $this->message(500, "All the fields are necessary");
        } else {
            $mallUsrfirstName = $inputValidator->sanitizeItem($mallUsrfirstName, "string");
            $mallUsrlastName = $inputValidator->sanitizeItem($mallUsrlastName, "string");
            $mallUsrEmail = $inputValidator->sanitizeItem($mallUsrEmail, "email");
            $mallUsrPhone = $inputValidator->sanitizeItem($mallUsrPhone, "tel");        
            
            $mallUsrPassword = $inputValidator->sanitizeItem($mallUsrPassword, "string");
            $mallUsrPassword = $securityManagerOb->generateHashBcrypt($mallUsrPassword); //Encrpt and hash password
            //Get DB Info
            $dbHandler = $this->dbHandler;
            //adds new user into the DB. Returns 1 on success, 0 on DB error or 102 on failure if the email and phone number has been used,
            //returns 101 on failure when the email has been used, returns 100 on failure when the phone has been used
            $mallUsrID = $securityManagerOb->generateMallID();
            $mallBizID = "biz-" . $securityManagerOb->generateOtherID();
            $mallBizName = $mallUsrfirstName . " " . $mallUsrlastName;
            $mallBizSlug = explode(" ", $mallBizName);
            $mallBizSlug = implode("_", $mallBizSlug);
            $mallBizSlug = trim($mallBizSlug, ".");
            $emailphone_exist = $this->is_emailphone_exist($mallUsrPhone, $mallUsrEmail);
            $dateRegistered = time();
            

            if ($emailphone_exist['email'] === 1) {
                //both email has phone has been used on an existing account
                $this->message("500", "Email already registered with us");
            } else if ($emailphone_exist['phone'] === 1) {  //only the email has been used on an existing account
                $this->message("500", "Phone number already registered with us");
            } else if (substr($mallUsrPhone, 0,3)  == 0) {
                $this->message("500", "Phone number cannot be zeros");
            }    
             else {
                $sql = "INSERT INTO mallusrs (  mallUsrID,
                                                       mallUsrFirstName,
                                                       mallUsrLastName,
                                                       mallUsrPhoneNo,
                                                       mallUsrEmail,
                                                       mallUsrPassword,
                                                       mallUsrRegTime)  VALUES(?,?,?,?,?,?,?)";
                $stmt = $dbHandler->run($sql, [$mallUsrID, $mallUsrfirstName, $mallUsrlastName, $mallUsrPhone, $mallUsrEmail, $mallUsrPassword, $dateRegistered]);
                $check_status = $stmt->rowCount();
                //Create Business account for user
                $sqlBiz = "INSERT INTO mallusrbiz (mallUsrID,
                                                       mallBizID,
                                                       mallBizName,
                                                       mallBizSlug)  VALUES(?,?,?,?)";
                $stmtBiz = $dbHandler->run($sqlBiz, [$mallUsrID, $mallBizID, $mallBizName, $mallBizSlug]);
                //Create Biz Page with slug and ID
                $usrIDBizSlug = $mallBizSlug;
                $this->createUsrShopPage($usrIDBizSlug);
                //Create USER biz days
                $sqlBiz = "INSERT INTO mallusrbizdays (mallBizID,
                                                       mallBizDayMon,
                                                       mallBizDayTue,
                                                       mallBizDayWed,
                                                       mallBizDayThu,
                                                       mallBizDayFri,
                                                       mallBizStart,
                                                       mallBizEnd)  VALUES(?,?,?,?,?,?,?,?)";
                $stmtBiz = $dbHandler->run($sqlBiz, [$mallBizID, "1", "1", "1", "1", "1", "0:00AM", "0:00AM"]);
                //Create Biz Page with slug and ID
                $usrIDBizSlug = $mallBizSlug;
                //$check_statusBiz=$stmtBiz->rowCount();
                if ($check_status > 0) {
                    $securityManagerOb->generateVerifyToken($mallUsrEmail);
                    $this->message("1", "Registration successfull! Check your email for verification."); //success
                } else {
                    $this->message("500", "Registation error");
                }
            }
        }
        return $this->system_message;
    }
    function createUsrShopPage($usrIDBizSlug)
    {

        $file = $this->HOMEDIR . '/views/shop/page.php';
        $newfile = $this->HOMEDIR . "/views/shop/$usrIDBizSlug.php";

        if (!copy($file, $newfile)) {
            //echo "failed to copy $file...\n";
        }
    }

    /* public function getUserDetails($mallUsrID) : array { 

            //returns an associative array of the users details. Requires userID as argument

            include './dbconn.php';

            $sql = "SELECT * FROM mallusrs WHERE mallUsrID=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $mallUsrID);
            $stmt->execute();
            $response = $stmt->get_result();
            $row = $response->fetch_assoc();
            $userDetails = array();
            $userDetails['mallUsrFirstName'] = $row['mallUsrFirstName'];
            $userDetails['mallUsrLastName'] = $row['mallUsrLastName'];
            $userDetails['mallUsrLocation'] = $row['mallUsrLocation'];
            $userDetails['mallUsrID'] = $row['mallUsrID'];
            $userDetails['mallUsrBirthday'] = $row['mallUsrBirthday'];
            $userDetails['mallUsrSex'] = $row['mallUsrSex'];
            $userDetails['mallUsrPhoto'] = $row['mallUsrPhoto'];
            $userDetails['mallUsrPhoneNo'] = $row['mallUsrPhoneNo'];
            $userDetails['mallUsrPhoneNoStatus'] = $row['mallUsrPhoneNoStatus'];
            $userDetails['mallUsrEmail'] = $row['mallUsrEmail'];
            $userDetails['mallUsrEmailStatus'] = $row['mallUsrEmailStatus'];
            $userDetails['mallUsrBalance'] = $row['mallUsrBalance'];
            $userDetails['mallUsrAccountStatus'] = $row['mallUsrAccountStatus'];

            return $userDetails;

        }
 */

    public function getUserBalance($mallUsrID): int
    {

        //retrieves the user's current balance. Requires userID as argument

        //call the getUserDetails method and index the balance
        $userDetails = $this->getUserDetails($mallUsrID);
        return $userDetails['mallUsrBalance'];
    }



    public function addToBalance($mallUsrID, $amount): int
    {

        //returns -1 on failure and 1 on success. Requires userID and new amount to add as arguments

        include './dbconn.php';
        //call the getUserDetails method and index the balance
        $userDetails = $this->getUserDetails($mallUsrID);
        $current_bal =  $userDetails['mallUsrBalance'];

        $new_bal = $amount + $current_bal;
        $sql = "UPDATE mallusrs SET mallUsrBalance=? WHERE mallUsrID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $new_bal, $mallUsrID);
        $response = $stmt->get_result();
        $row_affect = $response->num_rows;
        if ($row_affect <= 0) {
            return -1; //error
        } else {
            return 1; //success
        }
    }



    /* public function updatePersonalDetails($mallUsrID, $detail_to_update, $detail_value) : int {

            //THIS GENERIC METHOD HANDLES ANY UPDATE IN THE USERS DB. IT IS AN ALIAS OF updateUsrPhone, updateUsrEmail,updateUsrPassword 
            //methods found in the class diagram. IT HASHES THE PASSWORD IF THE PASSWORD IS TO BE UPDATED

            //updates user detail according to argument passed in. The naming convention of the detail to be updated must be followed
            //Requires mallUsrID, DB detail to update and the new value 

            //returns -1 if the detail to be updated is not allowed, 0 on failed update, 1 on success
            include './dbconn.php';

            $allowed_details = [    //"mallUsrID",  cannot update user id
                                    "mallUsrFirstName",
                                    "mallUsrLastName",
                                    "mallUsrLocation",
                                    "mallUsrBirthday",
                                    "mallUsrSex",
                                    "mallUsrPhoto",
                                    "mallUsrPhoneNo",
                                    "mallUsrPhoneNoStatus",
                                    "mallUsrEmail",
                                    "mallUsrEmailStatus",
                                    "mallUsrOnline",
                                    "mallUsrPassword",
                                    "mallUsrBalance",
                                    "mallUsrAccountStatus"            ];
            if(! in_array($detail_to_update, $allowed_details, true)){
                return -1; //detail not allowed
            }else{

                //check if the detail to be updated is the password
                if(strcmp($detail_to_update, "mallUsrPassword") == 0){
                        //hash the new password
                        $detail_value = $this->hash_generator($detail_value); //replace with call to security class hash generator
                }

                $query = "UPDATE mallusrs SET X=? WHERE mallUsrID=?";
                $sql = str_replace("X", $detail_to_update, $query);

                $bind_string = "";
                if(strcmp($detail_to_update, "mallUsrBalance") == 0 || strcmp($detail_to_update, "mallUsrOnline") == 0 || strcmp($detail_to_update, "mallUsrAccountStatus") == 0){
                    $bind_string = "is"; //for updating an integer value in the DB
                }else{
                    $bind_string = "ss"; // for updating a string in the DB
                }

                $stmt = $conn->prepare($sql);
                $stmt->bind_param($bind_string, $detail_value, $mallUsrID);
                $stmt->execute();

                $row_affect = $stmt->num_rows;
                if($row_affect > 0){
                    return 1; //successfully updated
                }else{
                    return 0; //DB ERROR    ANY defined constant for DB failed query
                }
            }
    
        } */

    private function is_emailphone_exist($phone, $email): array
    {

        //checks if phone and email is existing in the db to validate creating account 
        //returns 3 if both email and phone has been used, returns 2 if only email has been used, returns 1 if only phone has been used
        //returns 0 if none has been used

        //Sanitize inputs
        $inputValidator = $this->inputValidatorOb;
        $dbHandler = $this->dbHandler;
        $phone = $inputValidator->sanitizeItem($phone, "string");
        $email = $inputValidator->sanitizeItem($email, "string");
        //Check if phone exists
        $sql_phone = "SELECT * FROM mallusrs WHERE mallUsrPhoneNo=?";
        $stmt_phone = $dbHandler->run($sql_phone, [$phone]);
        $row_count_phone = $stmt_phone->rowCount();

        //Check if email exists
        $sql_email = "SELECT * FROM mallusrs WHERE mallUsrEmail=?";
        $stmt_email = $dbHandler->run($sql_email, [$email]);
        $row_count_email = $stmt_email->rowCount();
        $fields_status = [];
        $fields_status["phone"] = 0;
        $fields_status["email"] = 0;
        if ($row_count_phone > 0) {
            $fields_status["phone"] = 1; //Phone number already exists
        } else if ($row_count_email > 0) {
            $fields_status["email"] = 1; //Email already exists
        }

        return $fields_status;
    }




    public function updateBizDetails($mallUsrID, $detail_to_update, $detail_value): int
    {

        //THIS GENERIC METHOD HANDLES ANY UPDATE IN THE USERS BIZ DB.

        //updates user detail according to argument passed in. The naming convention of the detail to be updated must be followed
        //Requires mallUsrID, DB detail to update and the new value 

        //returns -1 if the detail to be updated is not allowed, 0 on failed update, 1 on success
        include './dbconn.php';

        $allowed_details = [    //"mallUsrID",  cannot update user id
            "mallUsrBizID",
            "mallUsrBizName",
            "mallUsrBizUrl",
            "mallUsrBizUrlStatus",
            "mallUsrBizDelivery",
            "mallUsrAbout",
            "mallUsrBizAddr",
            "mallUsrBizAddrVisib",
            "mallUsrBizAddrStatus",
            "mallUsrBizWorkHrs",
            "mallUsrWorkMon",
            "mallUsrWorkTue",
            "mallUsrWorkWed",
            "mallUsrWorkThur",
            "mallUsrWorkFri",
            "mallUsrWorkSat",
            "mallUsrWorkSun"
        ];


        if (!in_array($detail_to_update, $allowed_details, true)) {
            return -1; //detail not allowed
        } else {

            $query = "UPDATE mallUsrBiz SET X=? WHERE mallUsrID=?";
            $sql = str_replace("X", $detail_to_update, $query);

            $bind_string = "";
            if (
                strcmp($detail_to_update, "mallUsrBizUrlStatus") == 0  ||
                strcmp($detail_to_update, "mallUsrBizDelivery") == 0   ||
                strcmp($detail_to_update, "mallUsrBizAddrVisib") == 0  ||
                strcmp($detail_to_update, "mallUsrBizAddrStatus") == 0 ||
                strcmp($detail_to_update, "mallUsrWorkMon") == 0       ||
                strcmp($detail_to_update, "mallUsrWorkTue") == 0       ||
                strcmp($detail_to_update, "mallUsrWorkWed") == 0       ||
                strcmp($detail_to_update, "mallUsrWorkThur") == 0      ||
                strcmp($detail_to_update, "mallUsrWorkFri") == 0       ||
                strcmp($detail_to_update, "mallUsrWorkSat") == 0       ||
                strcmp($detail_to_update, "mallUsrWorkSun") == 0
            ) {
                $bind_string = "is"; //for updating an integer value in the DB
            } else {
                $bind_string = "ss"; // for updating a string in the DB
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param($bind_string, $detail_value, $mallUsrID);
            $stmt->execute();

            $row_affect = $stmt->num_rows;
            if ($row_affect > 0) {
                return 1; //successfully updated
            } else {
                return 0; //DB ERROR    ANY defined constant for DB failed query
            }
        }
    }



    //a version of the security class dehashing function
    private function deHash($string)
    {

        return $string;
    }


    //a version of the security class hash generator
    private function hash_generator($string)
    {

        $string = $string . uniqid(true);
        $hash = md5($string);
        return $hash;
    }


    //a version of the security class input validator
    public function input_validator($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    ////////////////////////////////////////////////////////////
    //////////// ADDED ON APRIL 11TH, 2022 By Divine Ezelibe//////
    //////////////////////////////////////////////////////////////////////

    function getUsrBasicInfoByID(string $usrID)
    {
        $sys_message = [];
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        //Get DB Info
        $dbHandler = $this->dbHandler;
        //Query DB
        $sql = "SELECT * FROM mallusrs WHERE mallUsrID=? AND mallUsrAccountStatus=?";
        $stmt = $dbHandler->run($sql, [$usrID, "1"]);
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
    function getUsrBasicInfoByEmail(string $email)
    {
        $sys_message = [];
        $inputValidator = $this->inputValidatorOb;
        $email = $inputValidator->sanitizeItem($email, "string");
        //Get DB Info
        $dbHandler = $this->dbHandler;
        //Query DB
        $sql = "SELECT * FROM mallusrs WHERE mallUsrEmail=? AND mallUsrAccountStatus=?";
        $stmt = $dbHandler->run($sql, [$email, "1"]);
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


    public function checkEmailExist($email) {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $sql = "SELECT mallUsrEmail FROM mallusrs WHERE mallUsrEmail = ?";
        $stmt = $dbHandler->run($sql, [$email]);
        $num = $stmt->rowCount();
        return $num;
    }


    function updateUsrPersonaInfoByID(string $usrID, $firstname, $lastname, $location, $birthday, $sex, $address, $postalAddress, $connectFacebook = null, $connectTwitter = null)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        $firstname = $inputValidator->sanitizeItem($firstname, "string");
        $lastname = $inputValidator->sanitizeItem($lastname, "string");
        $location = $inputValidator->sanitizeItem($location, "string");
        $birthday = $inputValidator->sanitizeItem($birthday, "string");
        $sex = $inputValidator->sanitizeItem($sex, "string");
        $address = $inputValidator->sanitizeItem($address, "string");
        $postalAddress = $inputValidator->sanitizeItem($postalAddress, "string");
        $sql = "UPDATE mallusrs SET mallUsrFirstName=?, mallUsrLastName=?,mallUsrLocation=?,mallUsrBirthday=?,mallUsrSex=?,mallUsrAddress=?,mallUsrPostal=? WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$firstname, $lastname, $location, $birthday, $sex, $address, $postalAddress, $usrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, "Personal information updated");
        } else {
            $this->message(500, "Not updated");
        }
        return $this->system_message;
    }
    function updateUsrIcon(string $usrID, $imgFilename, $firstname = null, $lastname = null, $imgFile = null)
    {
        //$imgFile=$_FILES["imageFile"];
        $sys_message = [];
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        /* $mediA=new MediaManager();
        $usrFirstLastName=$firstname." ".$lastname; */
        /* $removeSpaceInFileName=explode(" ",$usrFirstLastName);
        $removeSpaceInFileName="user_icon_".implode("_",$removeSpaceInFileName);
        $imageResponse=$mediA->uploadOptiImage($imgFile,$removeSpaceInFileName,"uploads",$usrID,"101"); */
        $sql = "UPDATE mallusrs SET mallUsrPhoto=? WHERE mallUsrID=?";

        $stmt = $dbHandler->run($sql, [$imgFilename, $usrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, "Photo updated");
        } else {
            $this->message(500, "Not updated");
        }
        return $sys_message;
    }

    function getUsrPicsByID(string $usrID)
    {
        $sys_message = [];
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        //Get DB Info
        $dbHandler = $this->dbHandler;
        //Query DB
        $sql = "SELECT * FROM mallusrs WHERE mallUsrID=? AND mallUsrAccountStatus=?";
        $stmt = $dbHandler->run($sql, [$usrID, "1"]);
        $num = $stmt->rowCount();
        $userImage = $stmt->fetch();
        $userImage = $userImage['mallUsrPhoto'];
        if ($num > 0) {
            $sys_message['status'] = 1;
            $sys_message['message'] = $userImage;
        } else {
            $sys_message['status'] = 404;
            $sys_message['message'] = "User not found";
        }
        return $sys_message;
    }

    function getUsrBizInfoByID(string $usrID)
    {
        $sys_message = [];
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        //Get DB Info
        $dbHandler = $this->dbHandler;
        //Query DB
        $sql = "SELECT * FROM mallusrbiz INNER JOIN mallusrbizdays ON mallusrbiz.mallBizID=mallusrbizdays.mallBizID WHERE mallUsrID=?";
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
    // GET USER BUSINESS INFORMATION USING BIZ_SLUG
    function getUsrBizInfoBySlug(string $bizSlug)
    {
        $sys_message = [];
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($bizSlug, "string");
        //Get DB Info
        $dbHandler = $this->dbHandler;
        //Query DB
        $sql = "SELECT * FROM mallusrbiz WHERE mallBizSlug=?";
        $stmt = $dbHandler->run($sql, [$bizSlug]);
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
    function updateUsrBizInfoByID(string $usrID, $bizID, $bizname, $bizDelivery, $bizAbout, $bizAddress, $bizPostalAddress, $bizDaysStart, $bizDaysEnd, $bizDaysMon, $bizDaysTue, $bizDaysWed, $bizDaysThur, $bizDaysFri, $bizDaysSat, $bizDaysSun)
    {   

        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "int");
        $bizname = $inputValidator->sanitizeItem($bizname, "string");
        $bizDelivery = $inputValidator->sanitizeItem($bizDelivery, "string");
        $bizAbout = $inputValidator->sanitizeItem($bizAbout, "string");
        $bizAddress = $inputValidator->sanitizeItem($bizAddress, "string");
        $bizPostalAddress = $inputValidator->sanitizeItem($bizPostalAddress, "string");
        $bizDaysStart = $inputValidator->sanitizeItem($bizDaysStart, "string");
        $bizDaysEnd = $inputValidator->sanitizeItem($bizDaysEnd, "string");
        $bizDaysMon = $inputValidator->sanitizeItem($bizDaysMon, "int");
        $bizDaysTue = $inputValidator->sanitizeItem($bizDaysTue, "int");
        $bizDaysWed = $inputValidator->sanitizeItem($bizDaysWed, "int");
        $bizDaysThur = $inputValidator->sanitizeItem($bizDaysThur, "int");
        $bizDaysFri = $inputValidator->sanitizeItem($bizDaysFri, "int");
        $bizDaysSat = $inputValidator->sanitizeItem($bizDaysSat, "int");
        $bizDaysSun = $inputValidator->sanitizeItem($bizDaysSun, "int");
        $bizID = $inputValidator->sanitizeItem($bizID, "string");
        $sql = "UPDATE mallusrbiz SET mallBizName=?, mallBizDelivery=?,mallBizAbout=?,mallBizAddress=?,mallBizPostalAddress=? WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$bizname, $bizDelivery, $bizAbout, $bizAddress, $bizPostalAddress, $usrID]);
        $check_status = $stmt->rowCount();
        //build second query for biz working days and hours
        $sql1 = "UPDATE mallusrbizdays SET mallBizStart=?, mallBizEnd=?,mallBizDayMon=?,mallBizDayTue=?,mallBizDayWed=?,mallBizDayThu=?,mallBizDayFri=?,mallBizDaySat=?,mallBizDaySun=? WHERE mallBizID=?";
        $stmt1 = $dbHandler->run($sql1, [$bizDaysStart, $bizDaysEnd, $bizDaysMon, $bizDaysTue, $bizDaysWed, $bizDaysThur, $bizDaysFri, $bizDaysSat, $bizDaysSun, $bizID]);
        $check_status1 = $stmt1->rowCount();
        if ($check_status > 0 || $check_status1 > 0) {
            $this->message(1, "Business information updated");
            $this->createUsrShopPage($usrIDBizSlug);
        } else {
            $this->message(500, "Not updated");
        }
        return $this->system_message;
    }
    function updateUsrIDPhone(string $usrID, string $docPhone)
    {

        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        $docPhone = $inputValidator->sanitizeItem($docPhone, "string");

        $docPhone = preg_replace('/[^0-9]/', '', $docPhone);

        if (!empty($docPhone)) {

                if ($docPhone == 0) {
                    $this->message(500, "Invalid number");
                } else {
                    $sql = "UPDATE mallusrs SET mallUsrPhoneNo = ?, mallUsrPhoneNoStatus = ? WHERE mallUsrID = ?";
                    $stmt = $dbHandler->run($sql, [$docPhone, 1, $usrID]);
                    $check_status = $stmt->rowCount();

                    if ($check_status > 0) {
                        $sql2 = "UPDATE mallusridrec SET mallIDPhone = ? WHERE mallUsrID = ?";
                        $stmt2 = $dbHandler->run($sql2, [$docPhone, $usrID]);
                        $this->message(1, "Successful");
                    }  else {
                        $this->message(500, "Number already in use!");
                    }
                }
        } else {
            $this->message(500, "Field is required");
        }

        return $this->system_message;
    }

   
    

     function updateUsrIDByID($usrID, $docBizID, $dob, $docFile, $docFirstname, $docLastname, $docRequiredBy = null, $docPhone, $usrEmail)
    {
        include_once (__DIR__ . "/MediaPdf.php");
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "int");
        $dob = $inputValidator->sanitizeItem($dob, "string");
        $docFirstname = $inputValidator->sanitizeItem($docFirstname, "string");
        $docLastname = $inputValidator->sanitizeItem($docLastname, "string");
        $docPhone = $inputValidator->sanitizeItem($docPhone, "string");

            //Upload ID Card
        $mediA = new MediaManager();
        
        $usrFirstLastName = $docFirstname . " " . $docLastname;
        $removeSpaceInFileName = explode(" ", $usrFirstLastName);
        $removeSpaceInFileName_ = explode(" ", $usrFirstLastName);
        $removeSpaceInFileName = "biz_file_" . implode("_", $removeSpaceInFileName);
        $removeSpaceInFileName_ = "id_file_" . implode("_", $removeSpaceInFileName_);
        $idToken = md5(mt_rand(1, 10000000000000) . mt_rand(2000, 9999999999999));  

        $docPhone = preg_replace('/[^0-9]/', '', $docPhone);

             $check = 1; // monitors the status of file;

        if (!empty($docBizID["name"][0]) && !empty($docFile["name"][0]) && !empty($docPhone)) {
            $uploader   =   new Uploader();
            $uploader_id   =   new Uploader();
            $uploader->setDir($this->paramsOb::MEDIA_STORE . "/idFiles/uploads/");
            $uploader->setExtensions(array('jpg', 'png', 'jpg'));  //allowed extensions list//
            $ext1 = $uploader->getExtension($docBizID["name"][0]);
            $uploader->setMaxSize(.7);

            $uploader_id->setDir($this->paramsOb::MEDIA_STORE . "/idFiles/uploads/");
            $uploader_id->setExtensions(array('jpg', 'png', 'jpeg'));  //allowed extensions list//
            $ext2 = $uploader_id->getExtension($docFile["name"][0]);  //allowed extensions list//
            $uploader_id->setMaxSize(.7);                          //set max file size to be allowed in MB//

            if($uploader->uploadFile($docBizID, $removeSpaceInFileName) ) {   //txtFile is the filebrowse element name //   
            } else { //upload failed
                $imageResponse = ($uploader->getMessage()); //get upload error message
                if ($imageResponse['status'] == 0) {
                    $check = 0;
                    $this->message(500, $imageResponse['message']);   
                } 
            } 

            if($uploader_id->uploadFile($docFile, $removeSpaceInFileName_) ) {   //txtFile is the filebrowse element name   
            } else { //upload failed
                $imageResponse_id = ($uploader->getMessage()); //get upload error message
                if ($imageResponse_id['status'] == 0) {
                    $check = 0;
                    $this->message(500, $imageResponse_id['message']);   
                } 
            }  

            // Insert into DB

            if ($check === 1) {
                $removeSpaceInFileName = $removeSpaceInFileName.".".$ext1;
                $removeSpaceInFileName_ = $removeSpaceInFileName_.".".$ext2;
                $sql2 = "INSERT INTO mallusridrec (mallUsrID,mallIDDocType,mallIDDOB,mallIDDocFile,mallIDFirstname,mallIDLastname,mallIDRequiredBy,mallIDPhone,mallIDEmail,mallIDToken) VALUES (?,?,?,?,?,?,?,?,?,?)";
                $stmt2 = $dbHandler->run($sql2, [$usrID, $removeSpaceInFileName, $dob,  $removeSpaceInFileName_, $docFirstname, $docLastname, $docRequiredBy, $docPhone, $usrEmail, $idToken]);
                $check_status2 = $stmt2->rowCount();

                if ($check_status2 > 0) {
                    $sq = "UPDATE mallusrbiz SET mallBizStatus = ? WHERE mallUsrID = ?";
                    $st = $dbHandler->run($sq, [2,$usrID]);

                    $this->message(1, "Thanks, We'll review your request and revert as soon as possible");
                    $msgOb = new MessagingManager();
                    $msgOb->sendMail("noreply@gaijinmall.com","noreply@gaijinmall.com", "Business Verification Request", "Hello Administrator, There is a business verication request waiting for you. <a href='gaijinmall.com/cspace/views/business_verifications.php'>View here</a>");
                }  else {
                    $this->message(500, "Something Went Wrong!");
                }
            } 

        } else {
            $this->message(500, "All fields are required");
        }

        

        return $this->system_message;
    } 
        /*---------------OLD------------------*/
    // function updateUsrIDByID($usrID, $docType, $dob, $docID, $docFile, $docFirstname, $docLastname, $docRequiredBy = null, $docPhone)
    // {
    //     include_once "../services/AccS/MediaPdf.php";

    //     $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
    //     $inputValidator = $this->inputValidatorOb;
    //     $usrID = $inputValidator->sanitizeItem($usrID, "int");
    //     $docType = $inputValidator->sanitizeItem($docType, "string");
    //     $dob = $inputValidator->sanitizeItem($dob, "string");
    //     $docID = $inputValidator->sanitizeItem($docID, "string");
    //     $docFirstname = $inputValidator->sanitizeItem($docFirstname, "string");
    //     $docLastname = $inputValidator->sanitizeItem($docLastname, "string");
    //     $docPhone = $inputValidator->sanitizeItem($docPhone, "string");
    //     //Upload ID Card
    //     $mediA = new MediaManager();
    //     $usrFirstLastName = $docFirstname . " " . $docLastname;
    //     $removeSpaceInFileName = explode(" ", $usrFirstLastName);
    //     $removeSpaceInFileName = "id_file_" . implode("_", $removeSpaceInFileName);
    //     $idToken = md5(mt_rand(1, 10000000000000) . mt_rand(2000, 9999999999999));  

    //     $docPhone = preg_replace('/[^0-9]/', '', $docPhone);



    //     // $imageResponse = $mediA->uploadOptiImage($docFile, $removeSpaceInFileName, $this->paramsOb::MEDIA_STORE . "/idFiles/uploads", $usrID, "101");

            

    //     //$sql = "UPDATE mallusrs SET mallUsrPhoto=? WHERE mallUsrID=?";

    //     $check = 1; // monitors the status of file;

    //     if (!empty($docFirstname) && !empty($docPhone)) {
    //         // check if file is selected
    //         if (!empty($docFile["name"][0])) {
    //             $uploader   =   new Uploader();
    //             $uploader->setDir($this->paramsOb::MEDIA_STORE . "/idFiles/uploads/");
    //             $uploader->setExtensions(array('pdf'));  //allowed extensions list//
    //             $uploader->setMaxSize(.7);                          //set max file size to be allowed in MB//

    //             if($uploader->uploadFile($docFile, $removeSpaceInFileName)) {   //txtFile is the filebrowse element name //     
    //                 // $image  =   $uploader->getUploadName(); //get uploaded file name, renames on upload//
    //                 // echo $image;

    //             }else{//upload failed
    //                 $imageResponse = ($uploader->getMessage()); //get upload error message
    //                 if ($imageResponse['status'] == 0) {
    //                     $check = 0;
    //                     $this->message(500, $imageResponse['message']);   

    //                 }  
    //             }

    //         } else {
    //             $removeSpaceInFileName ="";
    //         }

    //         if (!empty($removeSpaceInFileName)) {
    //             //populate
    //                 $mediA->updatePhoneMedia("101",$usrID,$removeSpaceInFileName.".pdf");
    //         }
    //         // Insert into DB

    //         if ($check === 1) {
    //              $removeSpaceInFileName = $removeSpaceInFileName.".pdf";
    //             $sql2 = "INSERT INTO mallusridrec (mallUsrID,mallIDDocType,mallIDDOB,mallIDDocNum,mallIDDocFile,mallIDFirstname,mallIDLastname,mallIDRequiredBy,mallIDPhone,mallIDEmail,mallIDToken) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    //             $stmt2 = $dbHandler->run($sql2, [$usrID, $docType, $dob, $docID, $removeSpaceInFileName, $docFirstname, $docLastname, $docRequiredBy, $docPhone, $docPhone, $idToken]);
    //             $check_status2 = $stmt2->rowCount();

    //             if ($check_status2 > 0) {
    //                 $this->message(1, $idToken);
    //             }  else {
    //                 $this->message(500, "Something Went Wrong!");
    //             }
    //         }

            

            
    //     } else {
    //         $this->message(500, "First name and Phone number are required");
    //     }

    //     return $this->system_message;
    // }


    function usrIDUploadedStatusByID($usrID)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "int");
        $sql = "SELECT * FROM mallusridrec WHERE mallUsrID=? AND mallIDApproved=1";
        $stmt = $dbHandler->run($sql, [$usrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, "ID Uploaded");
        } else {
            $this->message(0, "ID not uploaded");
        }
        return $this->system_message;
    }

    function updateUsrPhoneByID(string $usrID, $newPhone, $token = null, $oldPhone = null)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = $this->inputValidatorOb;
        $securityManager = new SecurityManager();
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        $newPhone = $inputValidator->sanitizeItem($newPhone, "string");
        $oldPhone = $inputValidator->sanitizeItem($oldPhone, "string");
        $isTokenMatch = $securityManager->verifyPhoneToken($usrID, $newPhone, $token)['status'];
        if ($isTokenMatch == 1) {
            $sql = "UPDATE mallusrs SET mallUsrPhoneNo=?, mallUsrPhoneNoStatus=? WHERE mallUsrID=?";
            $stmt = $dbHandler->run($sql, [$newPhone, "1", $usrID]);
            $check_status = $stmt->rowCount();
            if ($check_status > 0) {
                $this->message(1, "number updated");
                $securityManager->deletePhoneVerifyToken($usrID, $newPhone, $token);
            } else {
                $this->message(500, "not updated");
            }
        } else {
            $this->message(500, "wrong token");
        }
        return $this->system_message;
    }
    function updateUsrEmailByID(string $usrID, $newEmail, $oldEmail = null)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "int");
        $newEmail = $inputValidator->sanitizeItem($newEmail, "email");
        $oldEmail = $inputValidator->sanitizeItem($oldEmail, "email");
        $sql = "UPDATE mallusrs SET mallUsrEmail=?, mallUsrEmailStatus=? WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$newEmail, "0", $usrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, "Email updated");
        } else {
            $this->message(500, "Not updated");
        }
        return $this->system_message;
    }
    //KC PART
    function getUserStats()
    {

        $stat = [];

        //get number of non active users
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $stmt = "SELECT * FROM mallusrs WHERE mallUsrAccountStatus=?";
        $stmt = $dbHandler->run($stmt, [0]);


        if ($stmt->rowCount() > 0) {
            $stat["notactive"] = $stmt->rowCount();
        } else {
            $stat["notactive"] = 0;
        }

        //get number of active users
        $stmt = "SELECT * FROM mallusrs WHERE mallUsrAccountStatus=?";
        $stmt = $dbHandler->run($stmt, [1]);


        if ($stmt->rowCount() > 0) {
            $stat["active"] = $stmt->rowCount();
        } else {
            $stat["active"] = 0;
        }


        //get number of deactivated users
        $stmt = "SELECT * FROM mallusrs WHERE mallUsrAccountStatus=?";
        $stmt = $dbHandler->run($stmt, [2]);


        if ($stmt->rowCount() > 0) {
            $stat["deactivated"] = $stmt->rowCount();
        } else {
            $stat["deactivated"] = 0;
        }



        $this->message(1, $stat);

        return $this->system_message;
    }


    function deleteUsrAccount($usrID, $reason, $other)
    {
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "int");
        $reason = $inputValidator->sanitizeItem($reason, "string");
        $other = $inputValidator->sanitizeItem($other, "string");
        $time = time();
        $reason = $reason . ":" . $other;
        $sql = "UPDATE mallusrs SET mallUsrAccountStatus=?, mallUsrLatestTime=?, mallUsrLatestMsg=? WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, ["2", $time, $reason, $usrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, "Account deleted forever");
        } else {
            $this->message(500, "Could not delete account");
        }
        return $this->system_message;
    }

    public function getAdminDetailsByID($id)
    {

        //returns an associative array of the users details. Requires userID as argument

        $dbHandler = $this->dbHandler;
        $sql = "SELECT * FROM mallusrs WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$id]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, $stmt->fetch());
        } else {
            $this->message(0, "No user found");
        }


        return $this->system_message;
    }
    public function getUserDetails($mallUsrID)
    {

        //returns an associative array of the users details. Requires userID as argument

        $dbHandler = $this->dbHandler;
        $sql = "SELECT * FROM mallusrs WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$mallUsrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, $stmt->fetch());
        } else {
            $this->message(0, "No user found");
        }


        return $this->system_message;
    }
    function getRecentUsers()
    {
        // to fetch the last 7 ads
        $dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
        $stmt = "SELECT * FROM mallusrs ORDER BY defaultUsrID DESC LIMIT 8";
        $stmt = $dbHandler->run($stmt);
        $stmtData = $stmt->fetchAll();

        if ($stmt->rowCount() > 0) {
            $this->message(1, $stmtData);
        } else {
            $this->message(500, "No records found");
        }
        return $this->system_message;
    }
    public function updateUserStatus($mallUsrID, $adminID, $status)
    {

        $dbHandler = $this->dbHandler;

        //check for existing status
        $sqlCheck = "SELECT * FROM mallusrs WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$mallUsrID]);
        $row = $stmtCheck->fetch();
        if ($row['mallUsrAccountStatus'] == $status) {
            $this->message(400, "Status already set");
            return $this->system_message;
            exit;
        }

        $sql = "UPDATE mallusrs SET mallUsrAccountStatus=? WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$status, $mallUsrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, "Status updated successfully");
        } else {
            $this->message(0, "Error updating status");
        }


        return $this->system_message;
    }
    public function getAllUsrID()
    {

        $dbHandler = $this->dbHandler;
        $sql = "SELECT * FROM mallusrs";
        $stmt = $dbHandler->run($sql);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, $stmt->fetchAll());
        } else {
            $this->message(0, "No IDS found");
        }

        return $this->system_message;
    }

    public function getUserDetailByDefaultColID($defaultColID)
    {

        //returns an associative array of the users details. Requires userID as argument

        $dbHandler = $this->dbHandler;
        $sql = "SELECT * FROM mallusrs WHERE defaultUsrID=?";
        $stmt = $dbHandler->run($sql, [$defaultColID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, $stmt->fetch());
        } else {
            $this->message(0, "No user found");
        }


        return $this->system_message;
    }

    public function getUserKyc($userID)
    {


        $dbHandler = $this->dbHandler;
        $sql = "SELECT * FROM mallusridrec WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$userID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, $stmt->fetchAll());
        } else {
            $this->message(0, "No user found");
        }


        return $this->system_message;
    }

    public function getKycs()
    {

        $dbHandler = $this->dbHandler;
        $sql = "SELECT * FROM mallusridrec";
        $stmt = $dbHandler->run($sql);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, $stmt->fetchAll());
        } else {
            $this->message(0, "No user found");
        }


        return $this->system_message;
    }

    public function updatePhoneStatus($mallUsrID,  $status)
    {

        $dbHandler = $this->dbHandler;

        //check for existing status
        $sqlCheck = "SELECT * FROM mallusrs WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$mallUsrID]);
        $row = $stmtCheck->fetch();
        if ($row['mallUsrPhoneNoStatus'] == $status) {
            $this->message(400, "Status already set");
            return $this->system_message;
            exit;
        }

        $sql = "UPDATE mallusrs SET mallUsrPhoneNoStatus=? WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$status, $mallUsrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, "Status updated successfully");
        } else {
            $this->message(0, "Error updating status");
        }


        return $this->system_message;
    }

    public function updateEmailStatus($mallUsrID,  $status)
    {

        $dbHandler = $this->dbHandler;

        //check for existing status
        $sqlCheck = "SELECT * FROM mallusrs WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$mallUsrID]);
        $row = $stmtCheck->fetch();
        if ($row['mallUsrEmailStatus'] == $status) {
            $this->message(400, "Status already set");
            return $this->system_message;
            exit;
        }

        $sql = "UPDATE mallusrs SET mallUsrEmailStatus=? WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$status, $mallUsrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, "Status updated successfully");
        } else {
            $this->message(0, "Error updating status");
        }


        return $this->system_message;
    }

    public function verifyBiz($mallUsrID, $status,$usrPhone="",$kycID=null)
    {

        $dbHandler = $this->dbHandler;

        //check for existing status
        $sqlCheck = "SELECT * FROM mallusridrec WHERE mallIDToken=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$kycID]);
        $row = $stmtCheck->fetch();
        if ($row['mallIDApproved'] == $status) {
            $this->message(400, "Status already set");
            return $this->system_message;
            exit;
        }

        $sql = "UPDATE mallusridrec SET mallIDApproved=? WHERE mallIDToken=?";
        $stmt = $dbHandler->run($sql, [$status, $kycID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $this->message(1, "Business Verified");
            $sql2 = "UPDATE mallusrbiz SET mallBizStatus=? WHERE mallUsrID=?";
            $stmt2 = $dbHandler->run($sql2, [$status, $mallUsrID]);
            // $check_status2 = $stmt3->rowCount();
            
        } else {
            $check = 0;
            $this->message(0, "Error updating status");
        }



        return $this->system_message;
    }

    public function declineBiz($mallUsrID, $status) {
        $dbHandler = $this->dbHandler;

        // check for existing status
        $sqlCheck = "SELECT * FROM mallusridrec WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$mallUsrID]);
        $row = $stmtCheck->fetch();
        if ($row['mallIDApproved'] == $status) {
            $this->message(400, "Status already set");
            return $this->system_message;
            exit;
        }

        $sql = "UPDATE mallusridrec SET mallIDApproved=? WHERE mallUsrID=?";
        $stmt = $dbHandler->run($sql, [$status, $mallUsrID]);
        $check_status = $stmt->rowCount();
        if ($check_status > 0) {
            $sql2 = "UPDATE mallusrbiz SET mallBizStatus=? WHERE mallUsrID=?";
            $stmt2 = $dbHandler->run($sql2, [0, $mallUsrID]);
            $this->message(1, "Verification Denied!");

        } else {
            $this->message(0, "Error updating status");
        }

        return $this->system_message;
    }

    // public function updateKycStatus($mallUsrID, $status,$usrPhone,$kycID=null)
    // {

    //     $dbHandler = $this->dbHandler;

    //     //check for existing status
    //     $sqlCheck = "SELECT * FROM mallusridrec WHERE mallIDToken=?";
    //     $stmtCheck = $dbHandler->run($sqlCheck, [$kycID]);
    //     $row = $stmtCheck->fetch();
    //     if ($row['mallIDApproved'] == $status) {
    //         $this->message(400, "Status already set");
    //         return $this->system_message;
    //         exit;
    //     }

    //     $sql = "UPDATE mallusridrec SET mallIDApproved=? WHERE mallIDToken=?";
    //     $stmt = $dbHandler->run($sql, [$status, $kycID]);
    //     $check_status = $stmt->rowCount();
    //     if ($check_status > 0) {
    //         $sql = "UPDATE mallusrs SET mallUsrPhoneNoStatus=?, mallUsrPhoneNo=? WHERE mallUsrID=?";
    //         $stmt = $dbHandler->run($sql, [$status, $usrPhone,$mallUsrID]);
    //         $this->message(1, "Status updated successfully");

    //     } else {
    //         $this->message(0, "Error updating status");
    //     }


    //     return $this->system_message;
    // }

    public function getUserVerifiedNumberByID($mallUsrID)
    {
        $dbHandler = $this->dbHandler;

        //check for existing status
        $sqlCheck = "SELECT * FROM mallusrs WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$mallUsrID]);
        $row = $stmtCheck->fetch();
        if ($stmtCheck->rowCount() > 0) {
            if ($row['mallUsrPhoneNoStatus'] == 1) {
                $sql = "SELECT * FROM mallusridrec WHERE mallUsrID=?";
                $stmt = $dbHandler->run($sql, [$mallUsrID]);
                $check_status = $stmt->rowCount();
                $row = $stmt->fetch();
                if ($check_status > 0) {
                    if ($row['mallIDApproved'] == 1) {
                        $this->message(1, $row['mallIDPhone']);
                    } else {
                        $this->message(500, "not approved");
                    }
                } else {
                    $this->message(500, "ID not submitted");
                }
            } else {
                $this->message(500, "not verified");
            }
        } else {
            $this->message(404, "do not exist");
        }

        return $this->system_message;
    }
    public function getUserVerifiedNumberByIDandPhone($mallUsrID,$phoneNo)
    {
        $dbHandler = $this->dbHandler;

        //check for existing status
        $sqlCheck = "SELECT * FROM mallusrs WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$mallUsrID]);
        $row = $stmtCheck->fetch();
        $check = 0;
        $phone = "";
        if ($stmtCheck->rowCount() > 0) {
            if ($row['mallUsrPhoneNoStatus'] == 1) {
                $check = 1;
                $phone = $row['mallUsrPhoneNoStatus'];
                // $sql = "SELECT * FROM mallusridrec WHERE mallUsrID=? AND mallIDPhone=? ORDER BY defaultColID DESC";
                // $stmt = $dbHandler->run($sql, [$mallUsrID,$phoneNo]);
                // $check_status = $stmt->rowCount();
                // $row = $stmt->fetch();
                // if ($check_status > 0) {
                //     if ($row['mallIDApproved'] == 1) {
                //         $this->message(1, $row['mallIDPhone']);
                //     } else {
                //         $this->message(500, "not approved");
                //     }
                // } else {
                //     $this->message(500, "ID not submitted");
                // }
                $sql = "SELECT * FROM mallusridrec WHERE mallUsrID=? AND mallIDPhone=? ORDER BY defaultColID DESC";
                $stmt = $dbHandler->run($sql, [$mallUsrID,$phoneNo]);
                $check_status = $stmt->rowCount();
                $row = $stmt->fetch();
                if ($check_status > 0) {
                    if (!empty($row['mallIDPhone'])) {
                        $check = 1;
                    } else {
                        $this->message(500, "not approved");
                    }
                } 
            } else {
                $this->message(500, "not verified");
            }
        } else {
            $this->message(404, "do not exist");
        }

        if ($check == 1) {
            $this->message(1, $phone);
        }

        return $this->system_message;
    }

    public function updateUsrChats($mallUsrID, $currentStatus)
    {

        $dbHandler = $this->dbHandler;
        $inputValidator = $this->inputValidatorOb;
        $mallUsrID = $inputValidator->sanitizeItem($mallUsrID, "string");
        $currentStatus = $inputValidator->sanitizeItem($currentStatus, "string");
        //check for existing status
        $sqlCheck = "SELECT * FROM mallusroptions WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$mallUsrID]);
        $row = $stmtCheck->fetch();
        if ($stmtCheck->rowCount() > 0) {
            //UPDATE field
            $sql = "UPDATE mallusroptions SET mallUsrChats=? WHERE mallUsrID=?";
            $stmt = $dbHandler->run($sql, [$currentStatus, $mallUsrID]);
            $check_status = $stmt->rowCount();
            if ($check_status > 0) {
                $this->message(1, "Chats disabled, you can't receive messages from other users");
            } else {
                $this->message(0, "We could not disable chats at the moment");
            }
        } else {
            //INSERT user field
            $sql = "INSERT INTO mallusroptions SET mallUsrChats=?, mallUsrID=?";
            $stmt = $dbHandler->run($sql, [$currentStatus, $mallUsrID]);
            $check_status = $stmt->rowCount();
            if ($check_status > 0) {
                $this->message(1, "Chats disabled, you can't receive messages from other users");
            } else {
                $this->message(0, "We could not disable chats at the moment");
            }
        }

        return $this->system_message;
    }
    public function updateUsrFeed($mallUsrID, $currentStatus)
    {

        $dbHandler = $this->dbHandler;
        $inputValidator = $this->inputValidatorOb;
        $mallUsrID = $inputValidator->sanitizeItem($mallUsrID, "string");
        $currentStatus = $inputValidator->sanitizeItem($currentStatus, "string");
        //check for existing status
        $sqlCheck = "SELECT * FROM mallusroptions WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$mallUsrID]);
        $row = $stmtCheck->fetch();
        if ($stmtCheck->rowCount() > 0) {
            //UPDATE field
            $sql = "UPDATE mallusroptions SET mallUsrFeedbacks=? WHERE mallUsrID=?";
            $stmt = $dbHandler->run($sql, [$currentStatus, $mallUsrID]);
            $check_status = $stmt->rowCount();
            if ($check_status > 0) {
                $this->message(1, "Feedback disabled, you can't receive messages from other users");
            } else {
                $this->message(0, "We could not disable feedbacks at the moment");
            }
        } else {
            //INSERT user field
            $sql = "INSERT INTO mallusroptions SET mallUsrFeedbacks=?, mallUsrID=?";
            $stmt = $dbHandler->run($sql, [$currentStatus, $mallUsrID]);
            $check_status = $stmt->rowCount();
            if ($check_status > 0) {
                $this->message(1, "Feedback disabled, you can't receive messages from other users");
            } else {
                $this->message(0, "We could not disable feedbacks at the moment");
            }
        }

        return $this->system_message;
    }

    function getUsrOptionsStatus($mallUsrID)
    {
        $dbHandler = $this->dbHandler;
        $inputValidator = $this->inputValidatorOb;
        $mallUsrID = $inputValidator->sanitizeItem($mallUsrID, "string");
        //check for existing status
        $sqlCheck = "SELECT * FROM mallusroptions WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$mallUsrID]);
        $row = $stmtCheck->fetch();
        if ($stmtCheck->rowCount() > 0) {
            if ($row["mallUsrChats"] === 0) {
                $this->message(500, "disabled");
            } else {
                $this->message(1, "enabled");
            }
        } else {
            $this->message(0, "");
        }
        return $this->system_message;
    } 

    function getUsrOptionsFeedStatus($mallUsrID)
    {
        $dbHandler = $this->dbHandler;
        $inputValidator = $this->inputValidatorOb;
        $mallUsrID = $inputValidator->sanitizeItem($mallUsrID, "string");
        //check for existing status
        $sqlCheck = "SELECT * FROM mallusroptions WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$mallUsrID]);
        $row = $stmtCheck->fetch();
        if ($stmtCheck->rowCount() > 0) {
            if ($row["mallUsrFeedbacks"] === 0) {
                $this->message(500, "disabled");
            } else {
                $this->message(1, "enabled");
            }
        } else {
            $this->message(0, "");
        }
        return $this->system_message;
    } 

    //Follow Vendors
    function followVendor($followStatus,$followingUser,$vendor){
        $dbHandler = $this->dbHandler;
        $inputValidator = $this->inputValidatorOb;
        $followingUser = $inputValidator->sanitizeItem($followingUser, "string");
        $followStatus = $inputValidator->sanitizeItem($followStatus, "string");
        $vendor = $inputValidator->sanitizeItem($vendor, "string");
        $followTime=time();
        $userDetails=$this->getUsrBasicInfoByID($followingUser)['message'];
        $userName=$userDetails['mallUsrFirstName'];
        $messageOb=new messagingManager();
        //check for existing status
        $sqlCheck = "SELECT * FROM mallvendorfollowers WHERE mallUsrID=? AND mallFollowerUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$vendor,$followingUser]);
        $row = $stmtCheck->fetch();
        //Check user is logged in
        if ($followingUser=="none" || $followingUser=="" || $followingUser==null){
            $this->message(501, "Please sign in follow this user");
        }
        else{
            //USER WANTS TO UNFOLLOW
             if ($followStatus=="followed"){
            
                //INSERT user field
                $sql = "DELETE FROM mallvendorfollowers WHERE mallFollowerUsrID=? AND mallUsrID=?";
                $stmt = $dbHandler->run($sql, [$followingUser, $vendor]);
                $check_status = $stmt->rowCount();
                $allFollowers=number_format($this->countFollowersByID($vendor)['message'],0);

                if ($check_status > 0) {
                    $this->message(1, $allFollowers);
                    $messageOb->usrTousrNotification($vendor,$followingUser,"$userName just unfollowed you");
                } else {
                    $this->message(0, $allFollowers);
                }
            }
            elseif ($followStatus=="unfollowed"){   
          
                //INSERT user field
                $sql = "INSERT INTO mallvendorfollowers SET mallFollowerUsrID=?, mallUsrID=?, mallFollowTime=?";
                $stmt = $dbHandler->run($sql, [$followingUser, $vendor,$followTime]);
                $check_status = $stmt->rowCount();
                $allFollowers=number_format($this->countFollowersByID($vendor)['message'],0);
                if ($check_status > 0) {
                    $this->message(1, $allFollowers);
                    $messageOb->usrTousrNotification($vendor,$followingUser,"$userName is now following you");
                } else {
                    $this->message(0, $allFollowers);
                }
            }
        }
        
        

        return $this->system_message;
    }

    function countFollowersByID($usrID){
        $dbHandler = $this->dbHandler;
        $inputValidator = $this->inputValidatorOb;
        $usrID = $inputValidator->sanitizeItem($usrID, "string");
        //check for existing status
        $sqlCheck = "SELECT * FROM mallvendorfollowers WHERE mallUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$usrID]);
        $row = $stmtCheck->fetch();
        if ($stmtCheck->rowCount() > 0) {
            $this->message(1, $stmtCheck->rowCount());
        } else {
            $this->message(0, 0);
        }

        return $this->system_message;
    }
   function checkUserFollowingByID($followingUser,$vendor){
        $dbHandler = $this->dbHandler;
        $inputValidator = $this->inputValidatorOb;
        $followingUser = $inputValidator->sanitizeItem($followingUser, "string");
        $vendor = $inputValidator->sanitizeItem($vendor, "string");
        $followTime=time();
        //check for existing status
        $sqlCheck = "SELECT * FROM mallvendorfollowers WHERE mallUsrID=? AND mallFollowerUsrID=?";
        $stmtCheck = $dbHandler->run($sqlCheck, [$vendor,$followingUser]);
        $row = $stmtCheck->fetch();
        if ($stmtCheck->rowCount()>0){
            $this->message("followed", "Following");
        }else{
            $this->message("unfollowed", "No follower");
        }

        return $this->system_message;
   }



    public function uploadPdf($file,$fileName,$fileLocation,$usrID,$adID) {
        
            $uploader   =   new Uploader();
            $uploader->setDir($fileLocation);
            $uploader->setExtensions(array('jpg'));  //allowed extensions list//
            $uploader->setMaxSize(.7);                          //set max file size to be allowed in MB//

            if($uploader->uploadFile($file, $fileName)) {   //txtFile is the filebrowse element name //     
                $image  =   $uploader->getUploadName(); //get uploaded file name, renames on upload//
                echo $image;

            }else{//upload failed
            echo $uploader->getMessage(); //get upload error message 
        }
}
    
//class ends here
}

    // //UNIT TESTS

    //$account = new AccountManager();
    //$res = $account->updateUsrEmailByID("2901916465","divduall@dfdd.com");
    //print_r($res);

    // // if($res == 3){
    // //     echo "Email and phone number has been used already";
    // // }else if($res == 2){
    // //     echo "Email has been used";
    // // }else if($res == 1){
    // //     echo "Phone has been used";
    // // }else if ($res == 0){
    // //     echo "success";
    // // }

    // $res = $account->Signin();

    // if($res == -1){
    //     //failed verification
    //     echo "Invalid login details";
    // }else{
    //     //use the returned ID to fetch the users details
    //     // $details = $account->getUserDetails($res);
    //     // var_dump($details);

    //     //user balance
    //     $bal = $account->getUserBalance($res);
    //     echo "Balance before update: ".$bal;


    //     //update balance here
    //     $res_bal = $account->updatePersonalDetails($res, "mallUsrAccountStatus", 2);
    //     $details = $account->getUserDetails($res);
    //     var_dump($details);

    //     echo "<br><br>"."after deleting account"."<br><br>";
    //     $account->deleteAccountForever($res);

    //     $details = $account->getUserDetails($res);
    //     var_dump($details);

    //     // if($res_bal == 1){
    //     //     $bal = $account->getUserBalance($res);
    //     //     echo "Balance after update: ".$bal;
    //     // }else{
    //     //     echo "update failed";
    //     // }

    
    // }

