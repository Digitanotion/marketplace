<?php 
namespace services\MedS;

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
USE services\SecS\SecurityManager;
USE services\SecS\GetUserIP;
use services\SecS\InputValidator;
USE services\InitDB;
USE Bulletproof\Image;
use service\SecS\GetUserIP as SecSGetUserIP;


/*

// Pass a custom name, or it will be auto-generated
$image->setName($name);

// define the min/max image upload size (size in bytes) 
$image->setSize($min, $max);

// define allowed mime types to upload
$image->setMime(array('jpeg', 'gif'));

// set the max width/height limit of images to upload (limit in pixels)
$image->setDimension($width, $height);

// pass name (and optional chmod) to create folder for storage
$image->setLocation($folderName, $optionalPermission);

// get the provided or auto-generated image name
$image->getName();

// get the image size (in bytes)
$image->getSize();

// get the image mime (extension)
$image->getMime();

// get the image width in pixels
$image->getWidth();

// get the image height in pixels
$image->getHeight();

// get image location (folder where images are uploaded)
$image->getLocation();

// get the full image path. ex 'images/logo.jpg'
$image->getFullPath();

// get the json format value of all the above information
$image->getJson();

*/

//$image="";
class MediaManager{
    private $thumbImageWidthMax=400;
    private $optImageWidthMax=800;
    private $imgResponse=[];
    private $dbHandler;
    private $securityManager_ob;
    private $remoteIP;
    private $thumbImageAd;
    private $inputValidatorOb;
    private $msg = [];
    function __construct()
    {
        $this->inputValidatorOb = new InputValidator();
    }
    function uploadOptiImage($imageFile,$imageName,$fileLocation,$usrID,$adID){
        /* 
        This function is suppose to create thumbmail size out the uploaded image
        1. Get the image dimensions of image to be uploaded
        2. Compare the values of no 1 to the imageWidthMax, if results is greater than the limit, 
            then substract their difference from the current dimension
        3. Set image params
        4. Upload image, save to DB*/
        
            $this->dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
            $this->securityManager_ob=new SecurityManager();

            $inputValidator = $this->inputValidatorOb;

            // $imageFile = $inputValidator->sanitizeInput($imageFile, "string");
            $imageName = $inputValidator->sanitizeInput($imageName, "string");
            $fileLocation = $inputValidator->sanitizeInput($fileLocation, "string");
            $usrID = $inputValidator->sanitizeInput($usrID, "string");
            $adID = $inputValidator->sanitizeInput($adID, "string");
            $this->remoteIP=new GetUserIP();
            for($i = 0; $i < count($imageFile['name']); $i++) {
  
                $arr_file = array(
                "name" => $imageFile['name'][$i],
                "type" => $imageFile['type'][$i],
                "tmp_name" => $imageFile['tmp_name'][$i],
                "error" => $imageFile['error'][$i],
                "size" => $imageFile['size'][$i],
                );

                $image = new Image($arr_file);
                
                //Get current image dimensions
                $getImageWidth=$image->getWidth();
                $getImageHeight=$image->getHeight();
                $thumbImageWidth=$this->thumbImageWidthMax;
                $thumbImageHeight=null;
                $optImageWidth=$this->optImageWidthMax;
                //Compare the values and reduce the filesize in ratio for thumb images
                $thumbImageDiff = $getImageWidth / $this->thumbImageWidthMax;
                $thumbImageHeight = ($getImageHeight>0)?intval($getImageHeight / $thumbImageDiff):0;
                //Reduce filesize for other images optimised
                $optImageDiff = $getImageWidth / $this->optImageWidthMax;
                $optImageHeight = ($getImageHeight>0)?intval($getImageHeight / $optImageDiff):0;
                
                // define the min/max image upload size (size in bytes) 
                $image->setSize(1000, 5000000);
                // define allowed mime types to upload
                $image->setName((count($imageFile['name'])<2)?$imageName:$imageName."_".($i+1));
                $image->setMime(array('jpg','jpeg', 'gif', 'png')); 
                // set the max width/height limit of images to upload (limit in pixels)
                //Resize image to thumbnail
                
                //$image->setDimension($imageWidth, $imageHeight);
                // pass name (and optional chmod) to create folder for storage
                $image->setLocation($fileLocation);
                if($imageFile){
                $upload = $image->upload(); 
                if(!$upload){
                    $this->imgResponse['status'][$i]=500;
                    $this->imgResponse['message'][$i]=$image->getError(); 
                   
                }else{
                    $mediaID=$this->securityManager_ob->generateOtherID();
                    $this->imgResponse['status'][$i]=1;
                    $imageFileName= $upload->getName().".".$upload->getMime();
                    $this->imgResponse['message'][$i]= $imageFileName; // uploads/cat.gif
                    $this->imgResponse['mediaID'][$i]=$mediaID;
                    $timeUploaded=time();
                    //Save image in DB
                    $userAgent=$_SERVER['HTTP_USER_AGENT'];
                    $userIP=$this->remoteIP->getIpAddress();
                    $values=[$mediaID,$adID,$usrID,$imageFileName,1,$timeUploaded,$userAgent,$userIP];
                    $sql="INSERT INTO mallmedia (mallMediaID,mallAdID,mallUsrID,mallMediaName,mallMediaStatus,mallMediaUploadTime,mallUserAgent,mallUsrIP) VALUES (?,?,?,?,?,?,?,?)";
                    $stmt = $this->dbHandler->run($sql, $values);
                    if ($stmt->rowCount() > 0) {
                        $this->message(1, "Media added successfully");
                    } else {
                        $this->message(0, "Something Went Wrong");
                    }
                    //Compress and optimise
                    $image->setThumbWidthHeight($thumbImageWidth, $thumbImageHeight);
                    $image->setOptWidthHeight($optImageWidth, $optImageHeight);
                    //echo $image->getJson();
                    
                }
                }
                }
                return $this->imgResponse;
            }

            public function updatePhoneMedia($adID,$usrID,$imageFileName) {

                 $this->dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
                 $this->securityManager_ob=new SecurityManager();
                 $this->remoteIP=new GetUserIP();

                $mediaID=$this->securityManager_ob->generateOtherID();
                $timeUploaded=time();
                $userAgent=$_SERVER['HTTP_USER_AGENT'];
                $userIP=$this->remoteIP->getIpAddress();
                $values=[$mediaID,$adID,$usrID,$imageFileName,1,$timeUploaded,$userAgent,$userIP];
                $sql="INSERT INTO mallmedia (mallMediaID,mallAdID,mallUsrID,mallMediaName,mallMediaStatus,mallMediaUploadTime,mallUserAgent,mallUsrIP) VALUES (?,?,?,?,?,?,?,?)";
                $stmt = $this->dbHandler->run($sql, $values);
                if ($stmt->rowCount() > 0) {
                        return 1;
                    } else {
                       return 2;
                    }
                    return $this->imgResponse;
            }
            function getThumbImage($adID){

                $inputValidator = $this->inputValidatorOb;

                $adID = $inputValidator->sanitizeInput($adID, "string");

                $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
                $stmt = "SELECT * FROM mallmedia WHERE mallAdID=? ORDER BY defaultColID DESC";
                $stmt = $dbHandler->run($stmt,[$adID]);
                if ($stmt->rowCount() > 0) {
                    $this->message(1, $stmt->fetch());
                } else {
                    $this->message(404, "No image found");
                }
                return $this->msg;
            }
            function getUsrThumbImage($usrID){
                $inputValidator = $this->inputValidatorOb;

                $usrID = $inputValidator->sanitizeInput($usrID, "string");

                $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
                $stmt = "SELECT * FROM mallusrs WHERE mallUsrID=?";
                $stmt = $dbHandler->run($stmt,[$usrID]);
                if ($stmt->rowCount() > 0) {
                    $this->message(1, $stmt->fetch());
                } else {
                    $this->message(404, "User Not found");
                }
                return $this->msg;
            }
            function getAllAdImage($adID){

                $inputValidator = $this->inputValidatorOb;

                $usrID = $inputValidator->sanitizeInput($adID, "string");

                $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
                $stmt = "SELECT * FROM mallmedia WHERE mallAdID=? ORDER BY defaultColID DESC";
                $stmt = $dbHandler->run($stmt,[$adID]);
                if ($stmt->rowCount() > 0) {
                    $this->message(1, $stmt->fetchAll());
                } else {
                    $this->message(404, "No image found");
                }
                return $this->msg;
            }

            function delMedia($mediaID){

                $inputValidator = $this->inputValidatorOb;

                $mediaID = $inputValidator->sanitizeInput($mediaID, "string");

                $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
                $sql = "DELETE FROM mallmedia WHERE mallMediaID = ?";
                $value = [$mediaID];
                $stmt = $dbHandler->run($sql, $value);
                if ($stmt->rowCount() > 0) {
                    $this->message(1, "Media Deleted");
                } else {
                    $this->message(500, "Could not delete media");
                }
                return $this->msg;
            }
            protected function message($key, $value){
                $this->msg["status"] = $key;
                $this->msg["message"] = $value;
            }



        
    }




   


?>

