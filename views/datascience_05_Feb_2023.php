<?php 
//Confirm if file is local or Public and add the right path
$url = 'http://' . $_SERVER['SERVER_NAME'];
if (strpos($url,'localhost')) {
    require_once(__DIR__ . "\../vendor/autoload.php");
} else if (strpos($url,'gaijinmall')) {
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
}
else{
    require_once($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
} 
use services\AdS\AdManager as AdSAdManager;
USE services\InitDB; // Import DB Settings
use services\MsgS\messagingManager;
use services\SecS\InputValidator;
use services\SecS\SecurityManager;
use services\SecS\GetUserIP;

$adManager=new AdSAdManager();
//Remove data from options
 if(isset($_POST['analyze__btn'])){
    $state=$_POST['analyze__state'];
    $dataForAnalysis=explode(',',$_POST['dataArea']);
    $counti=1;
    foreach ($dataForAnalysis as $dat){
        echo $dat. " > ";
        //$latestFilter=explode("|",$dat);
        //echo $dat;
        if (!empty($dat)){
            //echo trim($latestFilter[0]).trim($latestFilter[1]).trim($latestFilter[2])."<br>";
            //$mallLocState=trim($latestFilter[0]);
            //$mallLocCity=$latestFilter[2];
            $mallLocSlug=strtolower(str_replace(' ', '_', trim($dat)));
            echo $mallLocSlug." | ";
            $sql = "INSERT INTO malllocations (mallLocState, mallLocCity, mallLocSlug, mallLocCountry) VALUES (?,?,?,?)";

			// converts the parameter into array before using it in the query
            $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		 	$values = [$state, $dat, $mallLocSlug, "Japan"];
		 	$stmt = $dbHandler->run($sql, $values);
		 	if ($stmt->rowCount() > 0) {
		 		//echo "Added ".$stmt->rowCount()." Locations";
		 	} else {
		 		echo "****Could not add, Location****";
		 	} 
            //$counti++;
        }
            
        
        
    }
    //echo $counti;
} 

if(isset($_POST['analyzefile__btn'])){
    $file=$_FILES['analyze__filename'];
    $category=$_POST['txtCategParent'];
    $mainFilename=explode("-",$file['name']);
    //$mainFilename[0];
    $sql = "UPDATE malladcategory SET mallCategIcon=? WHERE mallCategID=?";

    // converts the parameter into array before using it in the query
    $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
     $values = [$mainFilename[0], $category];
     $stmt = $dbHandler->run($sql, $values);
     if ($stmt->rowCount() > 0) {
         echo "Category Icon Updated for [$category]";
     } else {
         echo "Could not update category";
     } 
}



/* if(isset($_POST['analyze__btn'])){
    $dataForAnalysis=explode(",",$_POST['dataArea']);
    $counti=1;
    foreach ($dataForAnalysis as $dat){
        //$latestFilter=explode("<",$dat);
         $sql = "INSERT INTO malldefaultCategoryParams (mallUsrID, mallCategID, mallCategParamName, mallCategParamDType, mallCategParamValues) VALUES (?,?,?,?,?)";

			// converts the parameter into array before using it in the query
            $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		 	$values = [$usrID, $categID, $paramName, $paraType, $paraValues];
		 	$stmt = $dbHandler->run($sql, $values);
		 	if ($stmt->rowCount() > 0) {
		 		$this->message("1", "Category Parameters added successfully");
		 	} else {
		 		$this->message("0", "Failed to communicate with server");
		 	} 
        if (!empty($dat)){
            echo $dat."<br>";
            $counti++;
        }
        
    }
    echo $counti;
} */

?>

<form action="" method="POST">
<input type="text" name="analyze__state" placeholder="State">
    <textarea name="dataArea" placeholder="Cities"></textarea>
    <input type="submit" name="analyze__btn" value="Analyze">
</form>



<p>///////////////////////////////////////////////////////////////////////////////<br>
/////////////// UPLOAD ICONS /////////////////////////////////////////////////////<br>
//////////////////////////////////////////////////////////////////////////////
</p>
<p><form action="" method="POST" enctype="multipart/form-data">
<select class="form-control select2" name="txtCategParent" id="txtCategParent">
                        <option selected value="none">Select Category</option>
                        <?php $getCategoryAll=$adManager->getAllMallCategory();
                        if ($getCategoryAll['status']=="1"){
                        foreach ($getCategoryAll['message'] as  $value) {
                        ?>  
                        <option value="<?php echo $value['mallCategID'];?>"><?php echo $value['mallCategName'];?></option>
                        <?php }}
                      elseif ($getCategoryAll['status']=="0"){ ?>
                          <option value="none">No Category Yet</option>
                      <?php }?>
                    </select>
    <input type="file" name="analyze__filename">
    <input type="submit" name="analyzefile__btn" value="Analyze">
</form></p>