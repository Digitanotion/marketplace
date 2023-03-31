<?php

namespace services\AdS;
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

use services\AdS\AdManager as AdSAdManager;
use services\InitDB; // Import DB Settings
use services\MsgS\messagingManager;
use services\SecS\InputValidator;
use services\SecS\SecurityManager;
use services\SecS\GetUserIP;

class AdManager
{
	//private $db; No need for this because DB has a setup already in initDB
	public $UID;
	public $AD_ID;
	public $ReqID;
	public $CAT_ID;
	private $msg = [];
	private $col_names = [];
	private $categOptions_cols = [];
	public $dbHandler;
	private $inputValidatorOb;
	public const CURRENCY = "¥";

	/*this constructor will return database connection which would be passed during the class initialization*/

	function __construct()
	{
		$this->securityManagerOb = new SecurityManager();
        $this->inputValidatorOb = new InputValidator();
	}

	/*
		OBJECTIVE OF ADDCATEGORY : Add values to Categories
		STEPS:
		1. pass the paramenters required
		2. Check whether or not these parameters are empty
		3. Also check whether or not the category name is a valid letter. and does not include numbers and special characters
		4. if conditions are met then generate random numbers are ID
		5. sql query to actually insert to a specified table
		6. Covert all arguments/paraments passed  into arrays. bcos, the excute method - run() - requires it to be a array
		7. execute the the query using run method
		8. Check whether the table is affected i.e if insertion was done.
		9. success if insertion was made but error if no insert was made; 


	*/

	public function addCategory($mallUsrID, $mallCategName, $mallCategParent, $mallCategIcon)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);

		$inputValidator = $this->inputValidatorOb;

		$mallUsrID= $inputValidator->validateItem($mallUsrID, "string");;
		$mallCategParent = $inputValidator->sanitizeInput($mallCategParent, "string");
		$mallCategName = $inputValidator->sanitizeInput($mallCategName, "string");

		$mallCategAncestors = "";
		if (empty($mallUsrID) ||  empty($mallCategName)) {
			$this->message("404", "Fields cannot be empty");
		} else if (!preg_match("/^[a-zA-Z ]+$/", $mallCategName)) {
			$this->message("301", "Category name must be alphabet");
		} else {

			$this->CAT_ID = substr(number_format(time() * mt_rand(), 0, '', ''), 0, 6);
			$sql = "INSERT INTO malladcategory(mallUsrID,mallCategID,mallCategName,mallCategAncestors,mallCategParent, mallCategIcon) VALUES(?,?,?,?,?,?)";

			// converts the parameter into array before using it in the query
			$values = [$mallUsrID, $this->CAT_ID, $mallCategName, $mallCategAncestors, $mallCategParent,  $mallCategIcon];

			$stmt = $dbHandler->run($sql, $values);
			if ($stmt->rowCount() > 0) {
				//Check if category has a parent, if yes, append category to child field of the parent
				if (!empty($mallCategParent)) {
					$getCatefParentChildren = $this->getCategChildByID($mallCategParent);
					$categChildren = $getCatefParentChildren['message'] . "," . $this->CAT_ID;
					$updStmt = $dbHandler->run("UPDATE malladcategory SET mallCategChild=? WHERE mallCategID=?", [$categChildren, $mallCategParent]);
				}
				$this->message(1, "Category added successfully");
			} else {
				$this->message(0, "Something Went Wrong");
			}
		}

		return $this->msg;
	}

	// Update Category
	public function updateCategory($mallUsrID, $mallCategID, $mallCategName, $mallCategParentID, $mallCategIcon)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);

		$inputValidator = $this->inputValidatorOb;

		$mallUsrID= $inputValidator->validateItem($mallUsrID, "string");;
		$mallCategID= $inputValidator->validateItem($mallCategID, "string");;
		$mallCategParent = $inputValidator->sanitizeInput($mallCategParent, "string");
		$mallCategName = $inputValidator->sanitizeInput($mallCategName, "string");


		$mallCategAncestors = "";
		if (empty($mallUsrID) ||  empty($mallCategID)) {
			$this->message("404", "Fields cannot be empty");
		} else if (!preg_match("/^[a-zA-Z ]+$/", $mallCategName)) {
			$this->message("301", "Category name must be alphabet");
		} else {


			$sql = "UPDATE malladcategory(mallUsrID,mallCategID,mallCategName, mallCategParent, mallCategIcon) VALUES(?,?,?,?,?)";

			// converts the parameter into array before using it in the query
			$values = [$mallUsrID, $mallCategID, $mallCategName, $mallCategParentID,  $mallCategIcon];

			$stmt = $dbHandler->run($sql, $values);
			if ($stmt->rowCount() > 0) {
				//Check if category has a parent, if yes, append category to child field of the parent
				if (!empty($mallCategParent)) {
					$getCatefParentChildren = $this->getCategChildByID($mallCategParent);
					$categChildren = $getCatefParentChildren['message'] . "," . $this->CAT_ID;
					$updStmt = $dbHandler->run("UPDATE malladcategory SET mallCategChild=? WHERE mallCategID=?", [$categChildren, $mallCategParent]);
				}
				$this->message("1", "Category Updated successfully");
			} else {
				$this->message("0", "Something Went Wrong");
			}
		}

		return $this->msg;
	}

	/*
		OBJECTIVE OF addCategory_Params : Add values to addCategory_Params table
		STEPS:
		1. pass the paramenters required
		2. Check whether or not these parameters are empty
		3. sql query to actually insert to a specified table
		4. Covert all arguments/paraments passed  into arrays. bcos, the excute method - run() - requires it to be a array
		5. execute the the query using run method
		6. Check whether the table is affected i.e if insertion was done.
		7. success if insertion was made but error if no insert was made; 
	*/

	public function addCategory_Params($usrID, $categID, $paramName, $paraType, $paraValues)
	{

		$inputValidator = $this->inputValidatorOb;

		$mallUsrID= $inputValidator->validateItem($mallUsrID, "string");;
		$categID= $inputValidator->validateItem($mallCategID, "string");;
		$paramName = $inputValidator->sanitizeInput($mallCategParent, "string");
		$paraType = $inputValidator->sanitizeInput($mallCategName, "string");

		if (empty($usrID) || empty($categID) || empty($paramName) || empty($paraType)) {
			$this->message("404", "Fields cannot be empty");
		} else {
			$sql = "INSERT INTO malldefaultCategoryParams (mallUsrID, mallCategID, mallCategParamName, mallCategParamDType, mallCategParamValues) VALUES (?,?,?,?,?)";

			// converts the parameter into array before using it in the query
			$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
			$values = [$usrID, $categID, $paramName, $paraType, $paraValues];
			$stmt = $dbHandler->run($sql, $values);
			if ($stmt->rowCount() > 0) {
				$this->message("1", "Category Parameters added successfully");
			} else {
				$this->message("0", "Failed to communicate with server");
			}
		}
		return $this->msg;
	}

	//Get all Category Options

	public function getCategOptions(string $categID = null)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "SELECT * FROM malldefaultcategoryparams";
		$stmt = $dbHandler->run($sql);
		$row = $stmt->fetchAll();
		foreach ($row as $key) {
			array_push($this->categOptions_cols, $key['mallCategParamName']);
		}
	}

	public function getCategOptionsByID(int $categID)
	{

		$inputValidator = $this->inputValidatorOb;
		$categID= $inputValidator->validateItem($mallCategID, "string");;


		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "SELECT * FROM malldefaultcategoryparams WHERE mallCategID=?";
		$stmt = $dbHandler->run($sql, [$categID]);
		$row = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $row);
		} else {
			$this->message(404, "No option found for selected category");
		}
		return $this->msg;
	}
	public function getAllLocation()
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);

		$sql = "SELECT DISTINCT * FROM malllocations ORDER BY mallLocState";
		$stmt = $dbHandler->run($sql);
		$row = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $row);
		} else {
			$this->message(404, "Locations not found");
		}
		return $this->msg;
	}
	public function getAllLocationState()
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "SELECT DISTINCT mallLocState FROM malllocations ORDER BY mallLocState";
		$stmt = $dbHandler->run($sql);
		$row = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $row);
		} else {
			$this->message(404, "Locations not found");
		}
		return $this->msg;
	}
	public function getAllLocationCity($stateID)
	{
		$stateID = InputValidator::sanitizeInput($stateID, "string");

		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "SELECT * FROM malllocations WHERE mallLocState=? ORDER BY mallLocCity";
		$stmt = $dbHandler->run($sql, [$stateID]);
		$row = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $row);
		} else {
			$this->message(404, "No city found");
		}
		return $this->msg;
	}

	public function getLocationDetailsBySlug($slug)
	{
		$inputValidator = $this->inputValidatorOb;

		$slug = $inputValidator->sanitizeInput($slug, "string");

		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "SELECT * FROM malllocations WHERE mallLocSlug=?";
		$stmt = $dbHandler->run($sql, [$slug]);
		$row = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $row);
		} else {
			$this->message(404, "No Location found");
		}
		return $this->msg;
	}
	public function getAdDetailsOptions($adID)
	{
		/* Get all none empty ad category options 
			1. Get all category options inserted by admin
			2. Get mallAd tables columns that match the fetched category options
			3. Return none empty fields with their values  
		*/
		$inputValidator = $this->inputValidatorOb;

		$adID = $inputValidator->sanitizeInput($adID, "string");

		$fieldNamesValues = array();
		$fieldValues = array();
		$this->getCategOptions(); //Get all categories
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallAdID = ?";
		$stmt = $dbHandler->run($stmt, [$adID]);
		$stmtData = $stmt->fetch();
		if ($stmt->rowCount() > 0) {
			//Get mallAd tables fields that match the fetched category options
			for ($i = 0; $i < count($this->categOptions_cols); $i++) {
				$categOptions_cols_n = $this->categOptions_cols[$i];
				$categOptions_cols_n = explode("-", $categOptions_cols_n);
				if (!empty($stmtData[$categOptions_cols_n[0]])) {
					$fieldNamesValues[$categOptions_cols_n[1]] = $stmtData[$categOptions_cols_n[0]];
				}
			}
			$this->message(1, $fieldNamesValues);
		} else {
			$this->message(404, "Ad not found");;
			//print_r($fieldNames." <br><br>".$fieldValues);
		}
		return $this->msg;
	}
	public function getAdDetailsOptionByCategID($categID)
	{
		$inputValidator = $this->inputValidatorOb;

		$categID= $inputValidator->validateItem($categID, "string");;
		$categID = $inputValidator->sanitizeInput($categID, "string");

		/* Get all none empty ad category options 
			1. Get all category options inserted by admin
			2. Get mallAd tables columns that match the fetched category options
			3. Return none empty fields with their values  
		*/
		$fieldNamesValues = array();
		$fieldValues = array();
		$this->getCategOptions(); //Get all categories
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallCategID = ?";
		$stmt = $dbHandler->run($stmt, [$categID]);
		$stmtData = $stmt->fetch();
		if ($stmt->rowCount() > 0) {
			//Get mallAd tables fields that match the fetched category options
			for ($i = 0; $i < count($this->categOptions_cols); $i++) {
				$categOptions_cols_n = $this->categOptions_cols[$i];
				$categOptions_cols_n = explode("-", $categOptions_cols_n);
				if (!empty($stmtData[$categOptions_cols_n[0]])) {
					$fieldNamesValues[$categOptions_cols_n[1]] = $stmtData[$categOptions_cols_n[0]];
				}
			}
			$this->message(1, $fieldNamesValues);
		} else {
			$this->message(404, "Ad not found");;
			//print_r($fieldNames." <br><br>".$fieldValues);
		}
		return $this->msg;
	}

	/*
		OBJECTIVE OF removeCategory : Change the status of a category to determine deletion 
		STEPS:
		1. pass the paramenter of category id of the category to br deleted
		2. sql query to actually update the status of the category. P.S "1" means deleted, "0" means active. by defaut it's '0' 
		4. Covert category id  into array. bcos, the excute method - run() - requires it to be a array
		5. execute the the query using run method
		6. Check whether the table is affected i.e if insertion was done.
		7. success if insertion was made but error if no insert was made; 
	*/

	public function removeCategory($categID)
	{
		$inputValidator = $this->inputValidatorOb;

		$categID= $inputValidator->validateItem($categID, "string");;
		$categID = $inputValidator->sanitizeInput($categID, "string");

		//$this->CAT_ID = $categID;
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "DELETE FROM malladcategory WHERE mallCategID = ?";
		$value = [$categID];
		$stmt = $dbHandler->run($sql, $value);
		if ($stmt->rowCount() > 0) {
			$this->message("1", "Category deleted successfully");
		} else {
			$this->message("0", "Something Went Wrong");
		}
		return $this->msg;
	}
	public function removeCategoryOption($categID)
	{
		//$this->CAT_ID = $categID;
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "DELETE FROM malldefaultcategoryparams WHERE mallCategID = ?";
		$value = [$categID];
		$stmt = $dbHandler->run($sql, $value);
		if ($stmt->rowCount() > 0) {
			$this->message("1", "Category deleted successfully");
		} else {
			$this->message("0", "Something Went Wrong");
		}
		return $this->msg;
	}


	/*
		OBJECTIVE OF getAdsFields : Fetch all columns from mallAds Table 
		STEPS:
		1. run a query to display or show all columns from the table
		2. execute the the query using run method
		3. Using while loop to spill out the results
		the result would be listed one by one. Therefore...
		4. push each result into an array created specifically for this. in this case, col_names = [];
		voila! the array now has all the columns
	*/

	private function getAdsFields()
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "SHOW COLUMNS FROM mallads";
		$stmt = $dbHandler->run($sql);
		while ($row = $stmt->fetch()) {
			if ($row['Field'] != 'defaultColID') {
				array_push($this->col_names, $row['Field']);
			}
		}
	}

	/*
		OBJECTIVE OF createAd : Create advert by inserting into it 
		STEPS:
		1. pass the paramenters required. the parameter - ...$params - used means that we don't know how many the parameters would be
		2. call up the getAdsFields method (method that holds all the rows in mallads table);
		3. covert the columns to strings
		4. Check wether or not a parameter is passed
		5. count the number of parameters the method has
		6. create a loop that loops from the number of parameters passed. for each loop create a question mark (?). The essence of the array of ? is to dynamically use it as bind, since the number of parameters are not known.
		7. covert the the array holding ? in string because bind is a string not array
		8. sql query to actually insert the arguments;
		9. execute the the query using run method
		10. Check whether the table is affected i.e if insertion was done.
		11. success if insertion was made but error if no insert was made; 
	*/

	public function createAdCopy(...$params)
	{
		$this->getAdsFields();
		//gets all the fields returned from getAdsFields then convert them to string
		$fields = implode(',', $this->col_names);

		if (empty($params)) {
			$this->message("404", "All field must be filled");
		} else {
			// gets the total number of parameters passed
			$num = (count($params));
			// loops through the numbers and pushes '?' into each loop;
			$bind_array = [];
			for ($i = 0; $i < $num; $i++) {
				array_push($bind_array, '?');
			}
			// coverts all the'?' in the array into strings;
			$bind = implode(',', $bind_array);

			$sql = "INSERT INTO mallAds ($fields) VALUES ($bind)";
			$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
			$stmt = $dbHandler->run($sql, $params);
			if ($stmt->rowCount() > 0) {
				$this->message(1, "Advert added successfully");
			} else {
				$this->message(0, "Something Went Wrong");
			}
		}
		return json_encode($this->msg);
	}
	public function update_Ad($dataAll, $adID)
	{
		if (empty($dataAll)) {
			$this->message("404", "All field must be filled");
		} else {

			$sql = $this->buildSQL_update("mallads", $dataAll, $adID);
			$values = $this->buildSQL_insert_Values($dataAll);
			$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
			$stmt = $dbHandler->run($sql, $values);
			if ($stmt->rowCount() > 0) {
				$this->message(1, "Advert added successfully");
			} else {
				$this->message(0, "Something Went Wrong");
			}
		}
		return $this->msg;
	}
	public function createAd($dataAll)
	{
		/* $this->getAdsFields();
		//gets all the fields returned from getAdsFields then convert them to string
		$fields = implode(',', $this->col_names); 
		PROCESS
		1. Ad creation is done by passing arrays (key and value) as arguments
		2. The buildSQL_insert has two params table name and array of table fields (key) and values (value)
		3. the function in #2 seperates the keys from the values and adds ? (query params) based on the number of arrays, all this will then be used for build a query string
		4. the function buildSQL_insert_Values has one param and seperates tand picks onlt the value part of the array, which will be used as bind values.
		*/

		if (empty($dataAll)) {
			$this->message("404", "All field must be filled");
		} else {

			$sql = $this->buildSQL_insert("mallads", $dataAll);
			$values = $this->buildSQL_insert_Values($dataAll);
			$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
			$stmt = $dbHandler->run($sql, $values);
			if ($stmt->rowCount() > 0) {
				$this->message(1, "Advert added successfully");
			} else {
				$this->message(0, "Something Went Wrong");
			}
		}
		return $this->msg;
	}

	function getTrendingAds()
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallAdStatus=? ORDER BY defaultColID DESC LIMIT 24";
		$stmt = $dbHandler->run($stmt, ["1"]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}

	function getAdByID(string $adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallAdID = ? AND  mallAdStatus=? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$adID, "1"]);
		$stmtData = $stmt->fetch();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}
	function getAllAdByID(string $adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallAdID = ? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$adID]);
		$stmtData = $stmt->fetch();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}
	function getAdByCategID(string $categID)
	{
		$inputValidator = $this->inputValidatorOb;
		$categID = $inputValidator->sanitizeInput($categID, "string");

		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallCategID = ? AND mallAdStatus=? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$categID, "1"]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}

	function searchAds($searchString)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$searchString = htmlspecialchars($searchString);
		$keywords = explode(' ', $searchString);
		$inputValidator = new InputValidator();
		$searchString = $inputValidator->sanitizeItem($searchString, "string");
		$searchTermKeywords = array();
		foreach ($keywords as $word) {
			$searchTermKeywords[] = "mallAdTitle LIKE '%$word%' OR mallAdDesc LIKE '%$word%'";
		}

		$stmt = "SELECT * FROM mallads WHERE mallAdStatus=1 AND " . implode(' OR ', $searchTermKeywords);
		$stmt = $dbHandler->run($stmt);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}
	function getSimilarAds($searchString, $adCategID = null)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$keywords = explode(' ', $searchString);
		$searchTermKeywords = array();
		foreach ($keywords as $word) {
			$searchTermKeywords[] = "mallAdTitle LIKE '%$word%' ";
		}

		$stmt = "SELECT * FROM mallads WHERE " . implode(' OR ', $searchTermKeywords) . " AND mallCategID=? AND mallAdStatus=?";
		$stmt = $dbHandler->run($stmt, [$adCategID, "1"]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}

	function countAdImagesByID($adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallmedia WHERE mallAdID=?";
		$stmt = $dbHandler->run($stmt, [$adID]);
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmt->rowCount());
		} else {
			$this->message(404, 0);;
		}
		return $this->msg;
	}
	function getAdImagesByID($adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallmedia WHERE mallAdID=?";
		$stmt = $dbHandler->run($stmt, [$adID]);
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmt->fetchAll());
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}

	function buildSQL_insert($table, $data)
	{
		$key = array_keys($data);
		$val = array_values($data);
		$valParams = array();
		for ($i = 0; $i < count($data); $i++) {
			$valParams[$i] = "?";
		}
		$sql = "INSERT INTO $table (" . implode(', ', $key) . ") "
			. "VALUES (" . implode(", ", $valParams) . ")";
		return ($sql);
	}
	function buildSQL_update($table, $data, $adID)
	{
		$key = array_keys($data);
		$manualImplode = "";
		for ($i = 0; $i < count($key); $i++) {
			if ($i != count($key) - 1) {
				$manualImplode .= $key[$i] . "=?, ";
			} else {
				$manualImplode .= $key[$i] . "=?";
			}
		}
		$implodedKeys = implode('=?, ', $key);
		$sql = "UPDATE $table SET mallAdStatus=0, " . $manualImplode . " WHERE mallAdID=$adID";
		echo $sql;
		return ($sql);
	}
	function buildSQL_insert_Values($data)
	{
		$val = array_values($data);
		/* $sql = "INSERT INTO $table (" . implode(', ', $key) . ") "
			 . "VALUES (" . implode(", ", $valParams) . ")"; */
		return ($val);
	}
	/*
		OBJECTIVE OF updateAd : Make a chenge to an Advert 
		STEPS:
		1. pass three paramaters, 1. columns to update, 2. values, 3. ID of the advert
		2. get the number of columns passed (column as has to be an array);
		3. use loop to get each column.
		4. for each column append = ? and save it in a variable;
		5. covert this array back to string and seperate it with ','
		6. execute the the query using run method
		7. Check whether the table is affected i.e if insertion was done.
		8. success if insertion was made but error if no insert was made; 
	*/

	public function updateAd($columns, $arg, $adsID)
	{
		$num = (count($columns));
		$bind_array = [];
		foreach ($columns as $value) {
			$va = $value . " = ?";
			array_push($bind_array, $va);
		}
		$set = implode(",", $bind_array);
		if (empty($columns) && empty($args)) {
			$this->message("404", "All field must be filled");
		} else {
			$sql = "UPDATE mallAds SET $set WHERE defaultColID = '$adsID'";
			$stmt = $this->db->run($sql, $arg);
			if ($stmt->rowCount() > 0) {
				$this->message("1", "Update successful");
			} else {
				$this->message("0", "Something Went Wrong");
			}
		}
		return json_encode($this->msg);
	}

	/*
		OBJECTIVE OF deleteAd : Change the status of advert to determine deletion 
		STEPS:
		1. pass the paramenter of advert id of the advert to be deleted
		2. sql query to actually update the status of the category. P.S "1" means deleted, "0" means active. by defaut it's '0' 
		4. Covert category id  into array. bcos, the excute method - run() - requires it to be a array
		5. execute the the query using run method
		6. Check whether the table is affected i.e if insertion was done.
		7. success if insertion was made but error if no insert was made; 
	*/

	public function deleteAd($adid)
	{
		$this->AD_ID = $adid;
		$sql = "UPDATE mallads SET mallAdStatus = 1 WHERE mallAdID = ?";
		$value = [$this->AD_ID];
		$stmt = $this->db->run($sql, $value);
		if ($stmt->rowCount() > 0) {
			$this->message("1", "Advert deleted successfully");
		} else {
			$this->message("0", "Something Went Wrong");
		}
		return json_encode($this->msg);
	}

	public function setOffer()
	{
	}

	/*
		OBJECTIVE OF getSimilarAd : Retrieve all ads that are similar
		STEPS:
		1. JOIN two tables - mallads and malladcategory.
		2. check in the two tables which rows have the same category ID. if they match it means that they are the same product
		3. After checking if they are the same product, only select the products that:
			i. in the same location
			ii. the Ad is still active
		4. run the query
		5. return the outcome
	*/

	public function getSimilarAd($addLoc, $categID)
	{
		$values = [$addLoc, $categID, 1];
		$sql = "SELECT * FROM mallads AS a
    				JOIN malladcategory as c 
    					ON a.mallCategID = c.mallCategID 
    					WHERE (a.mallAdLoc = ? 
        				AND c.mallCategID = ?
            			AND a.mallAdStatus = ?)";
		$stmt = $this->db->run($sql, $values);
		$num = $stmt->rowCount();
		if ($num > 0) {
			while ($row = $stmt->fetch()) {
				$data[] = $row;
			}
			return $data;
		} else {
			$this->message("0", "No data");
			return $this->msg;
		}
	}

	/*
		OBJECTIVE OF getAd : Retrieve all ads from m  allAds Table
		STEPS:
		1. run a query to display or show all fields from the table
		2. execute the the query using run method
		3. execute the the query using run method
		4. if execution is right, get all data. if not make the method false;
	*/
	/* 
	public function getAd(){
		$sql = "SELECT * FROM mallAds WHERE mallAdStatus=?";
		$stmt = $this->db->run($sql,[1]);
        $num = $stmt->rowCount();
		if ($num > 0) {
			while ($row = $stmt->fetch()) {
				$data[] = $row;
			}
			return $data;
		} else{
			$this->message("0", "No data");
			return json_encode($this->msg);
		}
		
	} */

	/*
		This method allows the admin to the add promos which the users are to use
	*/

	public function addPromoPlans($usrID, $adPromoID, $duration, $amount)
	{
		$values = [$usrID, $adPromoID, $duration, $amount];
		// validate absent
		$sql = "INSERT INTO mallAdPromoPlans (mallUsrID, mallAdPromoID, mallAdPromoDuration, mallAdPromoAmount) VALUES (?,?,?,?)";
		$stmt = $this->db->run($sql, $values);
		if ($stmt->rowCount() > 0) {
			$this->message("1", "Promo Plan Successfully Added");
		} else {
			$this->message("0", "Something Went Wrong");
		}

		return json_encode($this->msg);
	}

	/*
		This method allows the user to the add any promo of there choice
	*/

	public function mallUsrPromos($usrID, $promoID, $promoStatus, $promoDate)
	{
		$values = [$usrID, $promoID, $promoStatus, $promoDate];
		$sql = "INSERT INTO mallUsrPromos (mallUsrID, mallAdPromoID, mallAdPromoStatus, mallAdPromoActiveDate) VALUES (?,?,?,?)";
		$stmt = $this->db->run($sql, $values);
		if ($stmt->rowCount() > 0) {
			$this->message("1", "Your promo has been added");
		} else {
			$this->message("0", "Something Went Wrong");
		}

		return json_encode($this->msg);
	}

	/*

	OBJECTIVE: display the information of who is selling
	STEPS:
	1. The parameter passed would be a GET.
	2. run the code;

	*/

	public function getSellerDetails($usrID)
	{
		$usr = [$usrID];
		$stmt = "SELECT * FROM mallUsrs WHERE mallUsrID = ?";
		$stmt = $this->db->run($sql, $usr);
		if ($stmt->rowCount() > 0) {
			return $stmt->fetchAll();
		} else {
			return false;
		}
	}

	/* public function getCategByID($categID){
		//Get Category details by ID
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malladcategory WHERE mallCategID = ?";
		$stmt = $dbHandler->run($stmt, [$categID]);
		$stmtData=$stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
        return $this->msg;
	} */
	public function getCategByID(...$categID)
	{
		$cat = implode(",", $categID);
		// gets the total number of parameters passed
		$num = (count($categID));
		// loops through the numbers and pushes '?' into each loop;
		$bind_array = [];
		for ($i = 0; $i < $num; $i++) {
			array_push($bind_array, '?');
		}
		// coverts all the'?' in the array into strings;
		$bind = implode(',', $bind_array);
		//Get Category details by ID
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malladcategory WHERE mallCategID = $bind";
		$stmt = $dbHandler->run($stmt, $categID);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}
	function getCategInfoByID($categID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malladcategory WHERE mallCategID = ?";
		$stmt = $dbHandler->run($stmt, [$categID]);
		$stmtData = $stmt->fetch();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}

	/* public function getCategOptionByID($categID){
		//Get Category details by ID
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malldefaultcategoryparams WHERE mallCategID = ?";
		$stmt = $dbHandler->run($stmt, [$categID]);
		$stmtData=$stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
        return $this->msg;
	} */
	public function getCategOptionByID($categID)
	{
		//Get Category details by ID
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malldefaultcategoryparams WHERE mallCategID = ?";
		$stmt = $dbHandler->run($stmt, [$categID]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}
	public function getCategParentByID($categID)
	{
		//Get Category Parent by ID
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malladcategory WHERE mallCategID = ?";
		$stmt = $dbHandler->run($stmt, [$categID]);
		$stmtData = $stmt->fetch();
		if ($stmt->rowCount() > 0) {
			$this->message("1", $stmtData['mallCategParent']);
		} else {
			return false;
		}
		return $this->msg;
	}

	/*    public function getCategChildByID($categID){
		//Get Category Parent by ID
        $dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malladcategory WHERE mallCategID = ?";
		$stmt = $dbHandler->run($stmt, [$categID]);
        $stmtData=$stmt->fetch();
		if ($stmt->rowCount() > 0) {
			$this->message("1", $stmtData['mallCategChild']);
		} else {
			return false;
		}
        return $this->msg;
	} */
	public function getCategChildByID($categID)
	{
		//Get Category Parent by ID
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malladcategory WHERE mallCategID = ?";
		$stmt = $dbHandler->run($stmt, [$categID]);
		$stmtData = $stmt->fetch();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData['mallCategChild']);
		} else {
			$this->message(404, "Category Not Found");
		}
		return $this->msg;
	}

	public function countCategoryHasChild(string $parentID = null)
	{
		$totalCount = 0;
		if ($parentID != null) {
			$childrenIDs = $this->getCategChildByID($parentID)['message'];
			$childrenID = explode(",", $childrenIDs);
			for ($i = 0; $i < count($childrenID); $i++) {
				$totalCount += $this->countCategory(intval($childrenID[$i]))['message'];
			}
		}
		$this->message(1, $totalCount);
		return $this->msg;
	}
	public function getAllMallCategory()
	{
		//Get Category details by ID
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malladcategory ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt);
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmt->fetchAll());
		} else {
			$this->message(0, "No Category Yet");
		}
		return $this->msg;
	}
	//Get total number of ads of a category
	public function countCategory(string $categID)
	{
		//Get Category details by ID
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallCategID=? AND mallAdStatus=1 ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$categID]);
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmt->rowCount());
		} else {
			$this->message(0, 0);
		}
		return $this->msg;
	}
	//Get all Parent Category
	public function getAllMallParentCategory()
	{
		//Get Category details by ID
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malladcategory WHERE mallCategParent=? ORDER BY defaultColID ASC";
		$queryValues = ["none"];
		$stmt = $dbHandler->run($stmt, $queryValues);
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmt->fetchAll());
		} else {
			$this->message(0, "No Category Yet");
		}
		return $this->msg;
	}

	//Get all Category Options
	public function getAllCategOption()
	{
		//Get Category details by ID
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malldefaultcategoryparams ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt);
		if ($stmt->rowCount() > 0) {
			$this->message("1", $stmt->fetchAll());
		} else {
			$this->message("0", "No Category Yet");
		}
		return $this->msg;
	}

	function priceSortFilter(string $type = null, string $getAttributes = null, $categID)
	{
		/* 
			OBJECTIVE:
				A. The Type variale house the sortBy integer value (0-Recommended, 1=Newest, 2=Lowest, 3=Highest)
				B. The getAttributes variable house the get url and should be in this format "?filter_attribs=init,mallAdSize_30,mallAdGender_male,sortBy_new" etc.
					1. Capture and sanitize the getAttributes,
					2. Seperate and determine the number of category attributes or options if $getAttributes is not null
					3. clearly seperate the likely fields names gotten from the getAttributes to be appended in the WHERE CLAUSE
					4. Build the Query
					5. Execute and Return result rows
		*/
		$security_ob = new SecurityManager();
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$type = $security_ob->sanitizeItem($type, "string");
		//Execute #1
		$getAttributes = $security_ob->sanitizeItem($getAttributes, "string");
		//Execute #2
		if ($getAttributes != null && $type != null) {
			$getAttrSeperated = explode(",", $getAttributes);
			$numOfSepAttrs = count($getAttrSeperated);

			//Execute #3
			$fieldsFromGet = [];
			$fieldsValuesFromGet = [];
			$fieldsParams = [];
			$sql = "";
			for ($i = 0; $i < $numOfSepAttrs; $i++) {
				$sepFieldFromVals = explode("_", $getAttrSeperated[$i]);
				$fieldsFromGet[$i] = $sepFieldFromVals[0];
				if ($sepFieldFromVals[0] == "mallAdPrice") {
					$priceMinMax = explode("-", $sepFieldFromVals[1]);
					$fieldsParams[$i] = $fieldsFromGet[$i] . " BETWEEN " . $priceMinMax[0] . " AND " . $priceMinMax[1];
				} else {
					$fieldsParams[$i] = $fieldsFromGet[$i] . "=?";
					$fieldsValuesFromGet[$i] = $sepFieldFromVals[1];
				}
			}
			switch ($type) {
				case "rec":
				default:
					$sql = "SELECT * FROM mallads WHERE " . implode(" AND ", $fieldsParams) . " AND mallCategID=" . $categID . " AND mallAdStatus=1";
					break;
				case "new":
					$sql = "SELECT * FROM mallads WHERE " . implode(" AND ", $fieldsParams) . " AND mallCategID=" . $categID . " AND mallAdStatus=1 ORDER BY defaultColID DESC";
					break;
				case "low":
					$sql = "SELECT * FROM mallads WHERE mallAdStatus=1 AND " . implode(" AND ", $fieldsParams) . " AND mallCategID=" . $categID . " ORDER BY mallAdPrice ASC";
					break;
				case "high":
					$sql = "SELECT * FROM mallads WHERE " . implode(" AND ", $fieldsParams) . " AND mallCategID=" . $categID . " AND mallAdStatus=1 ORDER BY mallAdPrice DESC";
					break;
			}

			$values = array_values($fieldsValuesFromGet);
			$stmt = $dbHandler->run($sql, $values);
			if ($stmt->rowCount() > 0) {
				$this->message(1, $stmt->fetchAll());
			} else {
				$this->message(0, "Something Went Wrong");
			}
		} elseif ($getAttributes != null) {
			$getAttrSeperated = explode(",", $getAttributes);
			$numOfSepAttrs = count($getAttrSeperated);

			//Execute #3
			$fieldsFromGet = [];
			$fieldsValuesFromGet = [];
			$fieldsParams = [];
			for ($i = 0; $i < $numOfSepAttrs; $i++) {
				$sepFieldFromVals = explode("_", $getAttrSeperated[$i]);
				$fieldsFromGet[$i] = $sepFieldFromVals[0];
				if ($sepFieldFromVals[0] == "mallAdPrice") {
					$priceMinMax = explode("-", $sepFieldFromVals[1]);
					$fieldsParams[$i] = $fieldsFromGet[$i] . " BETWEEN " . $priceMinMax[0] . " AND " . $priceMinMax[1];
				} else {
					$fieldsParams[$i] = $fieldsFromGet[$i] . "=?";
					$fieldsValuesFromGet[$i] = $sepFieldFromVals[1];
				}
			}
			$sql = "SELECT * FROM mallads WHERE mallAdStatus=1 AND " . implode(" AND ", $fieldsParams) . " AND mallCategID=" . $categID;
			//echo $sql;
			$values = array_values($fieldsValuesFromGet);
			$stmt = $dbHandler->run($sql, $values);
			if ($stmt->rowCount() > 0) {
				$this->message(1, $stmt->fetchAll());
			} else {
				$this->message(0, "Something Went Wrong");
			}
		} elseif ($getAttributes == null && $type != null) {
			switch ($type) {
				case "rec":
				default:
					$sql = "SELECT * FROM mallads WHERE mallAdStatus=1 AND mallCategID=" . $categID;
					break;
				case "new":
					$sql = "SELECT * FROM mallads WHERE mallAdStatus=1 AND mallCategID=" . $categID . " ORDER BY defaultColID DESC";
					break;
				case "low":
					$sql = "SELECT * FROM mallads WHERE mallAdStatus=1 AND mallCategID=" . $categID . " ORDER BY mallAdPrice ASC";
					break;
				case "high":
					$sql = "SELECT * FROM mallads WHERE mallAdStatus=1 AND  mallCategID=" . $categID . " ORDER BY mallAdPrice DESC";
					break;
			}
			$stmt = $dbHandler->run($sql);
			if ($stmt->rowCount() > 0) {
				$this->message(1, $stmt->fetchAll());
			} else {
				$this->message(0, "Something Went Wrong");
			}
		} else {
			$sql = "SELECT * FROM mallads WHERE mallAdStatus=1 AND mallCategID=" . $categID;
			$stmt = $dbHandler->run($sql);
			if ($stmt->rowCount() > 0) {
				$this->message(1, $stmt->fetchAll());
			} else {
				$this->message(0, "Something Went Wrong");
			}
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
	////////////////////////////////////////////////////////////
	//////////// ADDED ON APRIL 11TH, 2022 By Divine Ezelibe//////
	//////////////////////////////////////////////////////////////////////

	function getActiveAdsByID(string $adID)
	{
		$inputValidator = new InputValidator();
		$adID = $inputValidator->sanitizeItem($adID, "int");
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallAdID = ? AND  mallAdStatus=? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$adID, "1"]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}
	function getActiveAdsByUsrID(string $UsrID)
	{
		$inputValidator = new InputValidator();
		$UsrID = $inputValidator->sanitizeItem($UsrID, "int");
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallUsrID = ? AND  mallAdStatus=? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$UsrID, "1"]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}

	function getDeclinedAdsByUsrID(string $UsrID)
	{
		$inputValidator = new InputValidator();
		$UsrID = $inputValidator->sanitizeItem($UsrID, "int");
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallUsrID = ? AND  mallAdStatus=? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$UsrID, "2"]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}
	function getDeletedAdsByUsrID(string $UsrID)
	{
		$inputValidator = new InputValidator();
		$UsrID = $inputValidator->sanitizeItem($UsrID, "int");
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallUsrID = ? AND  mallAdStatus=? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$UsrID, "4"]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}
	function getReviewAdsByUsrID(string $UsrID)
	{
		$inputValidator = new InputValidator();
		$UsrID = $inputValidator->sanitizeItem($UsrID, "int");
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallUsrID = ? AND  mallAdStatus=? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$UsrID, "0"]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}
	function getExpiredAdsByUsrID(string $UsrID)
	{
		$inputValidator = new InputValidator();
		$UsrID = $inputValidator->sanitizeItem($UsrID, "int");
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallUsrID = ? AND  mallAdStatus=? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$UsrID, "3"]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}

	function saveAd($usrID, $adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$savedTime = time();
		$usrID = $inputValidator->sanitizeItem($usrID, "int");
		$adID = $inputValidator->sanitizeItem($adID, "int");
		$stmt = "INSERT INTO mallsavedads (mallUsrID,mallAdID,mallAdSavedTime) VALUES (?,?,?)";
		$checkAdSavedStatus = $this->checkSaveAd($usrID, $adID);
		if (!$checkAdSavedStatus['status']) {
			$stmt = $dbHandler->run($stmt, [$usrID, $adID, $savedTime]);
			$stmtData = $stmt->fetch();
			if ($stmt->rowCount() > 0) {
				$this->message(1, "Ad Saved");
			} else {
				$this->message(500, "Ad not saved");
			}
		} else {
			$this->message(500, "Ad saved already");
		}
		return $this->msg;
	}

	function checkSaveAd($usrID, $adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$usrID = $inputValidator->sanitizeItem($usrID, "string");
		$adID = $inputValidator->sanitizeItem($adID, "string");
		$stmt = "SELECT * FROM mallsavedads WHERE mallUsrID=? AND mallAdID=?";
		$stmt = $dbHandler->run($stmt, [$usrID, $adID]);
		if ($stmt->rowCount() > 0) {
			$this->message(true, "Ad exist already");
		} else {
			$this->message(false, "Ad does not exist");
		}
		return $this->msg;
	}
	function checkPromotedAd($adID, $promoID)
	{
		
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$adID = $inputValidator->sanitizeItem($adID, "string");
		$promoID = $inputValidator->sanitizeItem($promoID, "string");
		$stmt = "SELECT * FROM malladpromoted WHERE mallAdID=? AND mallAdPromoID=?";
		$stmt = $dbHandler->run($stmt, [$adID, $promoID]);
		if ($stmt->rowCount() > 0) {
			$this->message(true, $stmt->fetch()['mallAdPromoID']);
		} else {
			$this->message(false, "Ad not promoted");
		}
		return $this->msg;
	}
	static function displayPromoted($adID, $promoID)
	{
		// $inputValidator = $this->inputValidatorOb;
		// $adID = $inputValidator->sanitizeInput($adID, "string");
		$classOb = new self();
		if ($classOb->checkPromotedAd($adID, $promoID)['status']) {
			echo '<span class="ha-card-content-icon-1 fw-bolder fs-sm-1 d-flex justify-content-center align-items-center" href="#">
			<i class="fa fa-bullhorn mx-auto mx-1 fa-bounce text-primary"></i>&nbsp;<span class="fw-bold"> Ad</span>
		</span>';
		}
	}
	function getSavedAdsByUsrID($usrID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$usrID = $inputValidator->sanitizeItem($usrID, "int");
		$stmt = "SELECT * FROM mallsavedads WHERE mallUsrID = ? ORDER BY defaultColID DESC";
		$stmt = $dbHandler->run($stmt, [$usrID]);
		$stmtData = $stmt->fetchAll();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Not Found");;
		}
		return $this->msg;
	}
	function deleteSingleSavedAd(string $savedID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$savedID = $inputValidator->sanitizeItem($savedID, "int");
		$stmt = "DELETE FROM mallsavedads WHERE defaultColID  = ? ";
		$stmt = $dbHandler->run($stmt, [$savedID]);
		if ($stmt->rowCount() > 0) {
			$this->message(1, "Saved Ad deleted");
		} else {
			$this->message(404, "Not deleted");;
		}
		return $this->msg;
	}
	function clearAllSavedAd(string $usrID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$usrID = $inputValidator->sanitizeItem($usrID, "int");
		$stmt = "DELETE FROM mallsavedads WHERE mallUsrID  = ? ";
		$stmt = $dbHandler->run($stmt, [$usrID]);
		if ($stmt->rowCount() > 0) {
			$this->message(1, "Saved Ads deleted");
		} else {
			$this->message(404, "Not deleted");;
		}
		return $this->msg;
	}

	function updateAdView($adID, $usrID = 0)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$usrID = $inputValidator->sanitizeItem($usrID, "int");
		$adID = $inputValidator->sanitizeItem($adID, "int");
		$metricsTime = time();
		$this->remoteIP = new GetUserIP();
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$userIP = $this->remoteIP->getIpAddress();
		//Check if user already viewed ad
		$sql1 = "SELECT * FROM malladmetrics WHERE mallAdID = ? AND mallUsrIP=? AND mallAdMetrics=? AND mallUsrAgent=?";
		$stmt1 = $dbHandler->run($sql1, [$adID, $userIP, "views", $userAgent]);
		$stmtData1 = $stmt1->fetchAll();
		if ($stmt1->rowCount() > 0) {
			$this->message(501, "Not Allowed");
		} else {
			$sql = "INSERT INTO malladmetrics (mallUsrID,mallAdID,mallAdMetrics,mallAdMetricsTime,mallUsrIP,mallUsrAgent) VALUES (?,?,?,?,?,?)";
			$stmt = $dbHandler->run($sql, [$usrID, $adID, "views", $metricsTime, $userIP, $userAgent]);
			$check_status = $stmt->rowCount();
			if ($check_status > 0) {
				$this->message(1, "View Updated");
				//Set cookie
				setcookie("viewedAd", $adID, time() + 60 * 60 * 24 * 90);
			} else {
				$this->message(500, "Not updated");
			}
		}
		//Use cookie to confirm if user already viewed the ad
		/* if (isset($_COOKIE['viewedAd'])){
			$splitCookie=$_COOKIE['viewedAd'];
			//$splitEachCookie=(count($splitCookieexplode)>0)? explode(",",$_COOKIE['viewedAd']);
			if ($adID==$splitCookie){

			}
			else{
				$sql = "INSERT INTO malladmetrics (mallAdID,mallAdMetrics,mallAdMetricsTime) VALUES (?,?,?)";
				$stmt = $dbHandler->run($sql,[$adID,"views",$metricsTime]);
				$check_status=$stmt->rowCount();
				if ($check_status>0){
					$this->message(1, "View Updated");
					//Set cookie
					setcookie("viewedAd",$adID,time()+60*60*24*90);
				}else{
					$this->message(500, "Not updated");
				}


			}
		}
		else{
			$sql = "INSERT INTO malladmetrics (mallAdID,mallAdMetrics,mallAdMetricsTime) VALUES (?,?,?)";
				$stmt = $dbHandler->run($sql,[$adID,"views",$metricsTime]);
				$check_status=$stmt->rowCount();
				if ($check_status>0){
					$this->message(1, "View Updated");
					//Set cookie
					setcookie("viewedAd",$adID,time()+60*60*24*90);
				}else{
					$this->message(500, "Not updated");
				} 
		}*/
		return $this->msg;
	}

	function updateAdPhoneViews($adID, $usrID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$usrID = $inputValidator->sanitizeItem($usrID, "int");
		$adID = $inputValidator->sanitizeItem($adID, "int");
		$metricsTime = time();
		//Check if user already viewed ad
		$sql1 = "SELECT * FROM malladmetrics WHERE mallUsrID = ? AND mallAdID=? AND mallAdMetrics=?";
		$stmt1 = $dbHandler->run($sql1, [$usrID, $adID, "phoneviews"]);
		if ($stmt1->rowCount() > 0) {
			$this->message(501, "Not Allowed");
		} else {
			$sql = "INSERT INTO malladmetrics (mallUsrID,mallAdID,mallAdMetrics,mallAdMetricsTime) VALUES (?,?,?,?)";
			$stmt = $dbHandler->run($sql, [$usrID, $adID, "phoneviews", $metricsTime]);
			$check_status = $stmt->rowCount();
			if ($check_status > 0) {
				$this->message(1, "Phone View Updated");
				//Set cookie
				setcookie("viewedAd", $adID, time() + 60 * 60 * 24 * 90);
			} else {
				$this->message(500, "Not updated");
			}
		}
		return $this->msg;
	}

	function updateAdChatReq($adID, $usrID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$usrID = $inputValidator->sanitizeItem($usrID, "int");
		$adID = $inputValidator->sanitizeItem($adID, "int");
		$metricsTime = time();
		//Check if user already viewed ad
		$sql1 = "SELECT * FROM malladmetrics WHERE mallUsrID = ? AND mallAdID=? AND mallAdMetrics=?";
		$stmt1 = $dbHandler->run($sql1, [$usrID, $adID, "chatreq"]);
		if ($stmt1->rowCount() > 0) {
			$this->message(501, "Not Allowed");
		} else {
			$sql = "INSERT INTO malladmetrics (mallUsrID,mallAdID,mallAdMetrics,mallAdMetricsTime) VALUES (?,?,?,?)";
			$stmt = $dbHandler->run($sql, [$usrID, $adID, "chatreq", $metricsTime]);
			$check_status = $stmt->rowCount();
			if ($check_status > 0) {
				$this->message(1, "Chat request Updated");
				//Set cookie
				setcookie("viewedAd", $adID, time() + 60 * 60 * 24 * 90);
			} else {
				$this->message(500, "Not updated");
			}
		}
		return $this->msg;
	}

	function updateAdSocialShare($adID, $usrID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$usrID = $inputValidator->sanitizeItem($usrID, "int");
		$adID = $inputValidator->sanitizeItem($adID, "int");
		$metricsTime = time();
		//Check if user already viewed ad
		$sql1 = "SELECT * FROM malladmetrics WHERE mallUsrID = ? AND mallAdID=? AND mallAdMetrics=?";
		$stmt1 = $dbHandler->run($sql1, [$usrID, $adID, "socialshare"]);
		if ($stmt1->rowCount() > 0) {
			$this->message(501, "Not Allowed");
		} else {
			$sql = "INSERT INTO malladmetrics (mallUsrID,mallAdID,mallAdMetrics,mallAdMetricsTime) VALUES (?,?,?,?)";
			$stmt = $dbHandler->run($sql, [$usrID, $adID, "socialshare", $metricsTime]);
			$check_status = $stmt->rowCount();
			if ($check_status > 0) {
				$this->message(1, "Social Share Updated");
				//Set cookie
				setcookie("viewedAd", $adID, time() + 60 * 60 * 24 * 90);
			} else {
				$this->message(500, "Not updated");
			}
		}
		return $this->msg;
	}

	function getAdView($adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$adID = $inputValidator->sanitizeItem($adID, "int");
		//Check if user already viewed ad
		$sql1 = "SELECT * FROM malladmetrics WHERE mallAdID=? AND mallAdMetrics=?";
		$stmt1 = $dbHandler->run($sql1, [$adID, "views"]);
		if ($stmt1->rowCount() > 0) {
			$this->message(1, $stmt1->rowCount());
		} else {
			$this->message(1, 0);
		}
		return $this->msg;
	}
	function getAdSocialShare($adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$adID = $inputValidator->sanitizeItem($adID, "int");
		//Check if user already viewed ad
		$sql1 = "SELECT * FROM malladmetrics WHERE mallAdID=? AND mallAdMetrics=?";
		$stmt1 = $dbHandler->run($sql1, [$adID, "socialshare"]);
		if ($stmt1->rowCount() > 0) {
			$this->message(1, $stmt1->rowCount());
		} else {
			$this->message(1, 0);
		}
		return $this->msg;
	}
	function getAdChatReq($adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$adID = $inputValidator->sanitizeItem($adID, "int");
		//Check if user already viewed ad
		$sql1 = "SELECT * FROM malladmetrics WHERE mallAdID=? AND mallAdMetrics=?";
		$stmt1 = $dbHandler->run($sql1, [$adID, "chatreq"]);
		if ($stmt1->rowCount() > 0) {
			$this->message(1, $stmt1->rowCount());
		} else {
			$this->message(1, 0);
		}
		return $this->msg;
	}
	function getAdPhoneViews($adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$inputValidator = new InputValidator();
		$adID = $inputValidator->sanitizeItem($adID, "int");
		//Check if user already viewed ad
		$sql1 = "SELECT * FROM malladmetrics WHERE mallAdID=? AND mallAdMetrics=?";
		$stmt1 = $dbHandler->run($sql1, [$adID, "phoneviews"]);
		if ($stmt1->rowCount() > 0) {
			$this->message(1, $stmt1->rowCount());
		} else {
			$this->message(1, 0);
		}
		return $this->msg;
	}
	function usrMakeOffer($usrID, $adID, $usrAmt, $recieverUsrID)
	{
		$messaging_ob = new messagingManager();
		$inputValidator = new InputValidator();
		$usrID = $inputValidator->sanitizeItem($usrID, "int");
		$usrAmt = $inputValidator->sanitizeItem($usrAmt, "int");
		$recieverUsrID = $inputValidator->sanitizeItem($recieverUsrID, "int");
		$makeOffer_Response = $messaging_ob->sendMsgUsrToUsr($usrID, $adID, $recieverUsrID, "Hey, I am interested, i would buy " . self::CURRENCY . "" . number_format($usrAmt), "usr_to_usr");
		if ($makeOffer_Response['status'] == 1) {
			$this->message(1, "Offer sent to seller");
		} else {
			$this->message(0, "Offer not sent");
		}
		return $this->msg;
	}
	function getAllAdsCountByUsrID(string $UsrID, $status)
	{
		$inputValidator = new InputValidator();
		$UsrID = $inputValidator->sanitizeItem($UsrID, "string");
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallUsrID = ? AND  mallAdStatus=? ORDER BY defaultColID DESC";
		switch ($status) {
			case 'active':
				$stmt = $dbHandler->run($stmt, [$UsrID, "1"]);
				break;
			case 'inreview':
				$stmt = $dbHandler->run($stmt, [$UsrID, "0"]);
				break;
			case 'declined':
				$stmt = $dbHandler->run($stmt, [$UsrID, "2"]);
				break;
			case 'expired':
				$stmt = $dbHandler->run($stmt, [$UsrID, "3"]);
				break;
			case 'deleted':
				$stmt = $dbHandler->run($stmt, [$UsrID, "43"]);
				break;

			default:
				# code...
				break;
		}


		$stmtData = $stmt->rowCount();
		$this->message(1, $stmtData);
		return $this->msg;
	}

	function promoteAd()
	{
	}

	function recievePromoPayment($paymentFor, $customerEmail, $ad_custID)
	{
		$inputSanitize = new InputValidator();
		$paymentFor = $inputSanitize->sanitizeInput($paymentFor, "string");
		$customerEmail = $inputSanitize->sanitizeInput($customerEmail, "email");
		$ad_custID = $inputSanitize->sanitizeInput($ad_custID, "string");
		$promoPlanChoosen = explode("_", $paymentFor);
		$priceID = "";
		$rand_token = md5(rand(10000, 10000000000000000));
		switch ($paymentFor) {
			case 'business_7':
				$priceID = "price_1LVpIKJYhkX8M0GIFU2BS1I1";
				break;
			case 'business_30':
				$priceID = "price_1LVpIKJYhkX8M0GIV5Dg38uZ";
				break;
			case 'bronze_30':
				$priceID = "price_1LVpIKJYhkX8M0GIjgLyQ89D";
				break;
			case 'bronze_90':
				$priceID = "price_1LVpIKJYhkX8M0GISuMHONVh";
				break;
			case 'silver_150':
				$priceID = "price_1LVpIKJYhkX8M0GIA9CJN1bV";
				break;
			case 'silver_30':
				$priceID = "price_1LVpIKJYhkX8M0GIqojed4YA";
				break;
			case 'silver_360':
				$priceID = "price_1LVpIKJYhkX8M0GIDAJoGaix";
				break;
			case 'gold_360':
				$priceID = "price_1LVpIKJYhkX8M0GIjE4Y73WQ";
				break;
			case 'gold_30':
				$priceID = "price_1LVpIKJYhkX8M0GINUvarNnX";
				break;
			case 'diamond_30':
				$priceID = "price_1LVpILJYhkX8M0GIYXymACFy";
				break;
			case 'diamond_150':
				$priceID = "price_1LVpILJYhkX8M0GInsH8ENvL";
				break;
			case 'diamond_360':
				$priceID = "price_1LVpILJYhkX8M0GIvF5MlTuL";
				break;

			default:
				$priceID = "price_1LVpIKJYhkX8M0GIFU2BS1I1";
				break;
		}
		\Stripe\Stripe::setApiKey('sk_test_51LUVcWJYhkX8M0GIFyUI3G8qVEXCpCCBoHgJFcLEtV13pOHSU3Zbz8udYWuDreOQ8n3tDReSk26Fbacl3E30OaxP009wzby1fy');
		header('Content-Type: application/json');

		$YOUR_DOMAIN = 'http://gaijinmall.com';

		$checkout_session = \Stripe\Checkout\Session::create([
			'client_reference_id' => $paymentFor . "_" . $ad_custID,
			'customer_email' => $customerEmail,
			'line_items' => [[
				# Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
				'price' => $priceID,
				'quantity' => 1,
			]],
			'mode' => 'payment',
			'success_url' => $YOUR_DOMAIN . "/views/pay/?lk_tok=$rand_token&psi={CHECKOUT_SESSION_ID}&adref=$ad_custID",
			'cancel_url' => $YOUR_DOMAIN . '/views/pay/cancel.html',
		]);

		header("HTTP/1.1 303 See Other");
		header("Location: " . $checkout_session->url);
	}

	static function verifyPaySession($sessionID)
	{
		$stripe = new \Stripe\StripeClient(
			'sk_test_51JuDniDv8pLIRel5lqSRP3hIRKx7IiMARiaZGOPpoWl0Yrfhsr9j9DaTwmSOE4oDUVt1pKq8HJBfhKACyEqMylCJ00EZPqTWLe'
		);
		return $stripe->checkout->sessions->retrieve(
			$sessionID,
		);
	}
	static function getAllPromoList()
	{
		$mainClass = new self();
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		//Check if user already viewed ad
		$sql1 = "SELECT * FROM malladpromolist GROUP BY mallAdPromoName ORDER BY defaultColID ASC";
		$stmt1 = $dbHandler->run($sql1);
		if ($stmt1->rowCount() > 0) {
			$mainClass->message(1, $stmt1->fetchAll());
		} else {
			$mainClass->message(1, 0);
		}
		return $mainClass->msg;
	}
	static function getAllPromoListByName($promoName)
	{
		$mainClass = new self();
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		//Check if user already viewed ad
		$sql1 = "SELECT * FROM malladpromolist WHERE mallAdPromoName=?";
		$stmt1 = $dbHandler->run($sql1, [$promoName]);
		if ($stmt1->rowCount() > 0) {
			$mainClass->message(1, $stmt1->fetchAll());
		} else {
			$mainClass->message(1, 0);
		}
		return $mainClass->msg;
	}
	static function addNewUsrPromoRecord($usrReference, $gateReference)
	{
		$mainClass = new self();
		$inputSanitize = new InputValidator();
		$usrReference = explode("_", $inputSanitize->sanitizeInput($usrReference, "string"));
		$gateReference = $inputSanitize->sanitizeInput($gateReference, "string");
		//bronze_90_6268416575_508213105619396
		$usrID = $usrReference[2];
		$adID = $usrReference[3];
		$adPromoID = $usrReference[0] . "_" . $usrReference[1];
		if (empty($usrID) || empty($adID) || empty($adPromoID)) {
			$mainClass->message("404", "Fields cannot be empty");
		} else {
			$sql = "INSERT INTO malladpromoted (mallUsrID, mallAdID, mallAdPromoID, mallAdPromoStart, mallAdPromoEnd,mallAdPromoStatus,mallPromoGateRef) VALUES (?,?,?,?,?,?,?)";

			// converts the parameter into array before using it in the query
			$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);

			$today = time();
			$getPromoDuration = $usrReference[1];
			$getPromoDuration = strtotime("+$getPromoDuration day");
			$values = [$usrID, $adID, $adPromoID, $today, $getPromoDuration, "1", $gateReference];
			$stmt = $dbHandler->run($sql, $values);
			if ($stmt->rowCount() > 0) {
				$mainClass->message("1", "Category Parameters added successfully");
				//User Ad to be promoted
				$dbHandler->run("UPDATE mallads SET mallAdPromoID=? WHERE mallAdID=?", [$adPromoID, $adID]);
			} else {
				$mainClass->message("0", "Failed to communicate with server");
			}
		}
		return $mainClass->msg;
	}

	static function delAdNow($adID)
	{
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$dbHandler->run("UPDATE mallads SET mallAdStatus=? WHERE mallAdID=?", ["4", $adID]);
	}

	//KC PART
	function getAdStats()
	{

		$stat = [];

		//get number of ads in review
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallAdStatus=?";
		$stmt = $dbHandler->run($stmt, ["0"]);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$stat["review"] = $stmt->rowCount();
		} else {
			$stat["review"] = 0;
		}

		//get number of active ads
		$stmt = "SELECT * FROM mallads WHERE mallAdStatus=?";
		$stmt = $dbHandler->run($stmt, ["1"]);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$stat["active"] = $stmt->rowCount();
		} else {
			$stat["active"] = 0;
		}


		//get number of declined ads
		$stmt = "SELECT * FROM mallads WHERE mallAdStatus=?";
		$stmt = $dbHandler->run($stmt, ["2"]);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$stat["declined"] = $stmt->rowCount();
		} else {
			$stat["declined"] = 0;
		}


		//get number of expired ads
		$stmt = "SELECT * FROM mallads WHERE mallAdStatus=?";
		$stmt = $dbHandler->run($stmt, ["3"]);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$stat["expired"] = $stmt->rowCount();
		} else {
			$stat["expired"] = 0;
		}



		//get number of promoted ads
		$stmt = "SELECT * FROM malladpromoted WHERE mallAdPromoStatus=?";
		$stmt = $dbHandler->run($stmt, ['1']);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$stat["promoted"] = $stmt->rowCount();
		} else {
			$stat["promoted"] = 0;
		}



		$this->message(1, $stat);

		return $this->msg;
	}

	function getRevenueStat()
	{
		// to return the revenue statistics
		$stat = [];
		$stat['totRev'] = 300;
		$stat['totCost'] = 200;
		$stat['totPro'] = 500;


		$this->message(1, $stat);
		return $this->msg;
	}

	function getRecentAds()
	{
		// to fetch the last 7 ads
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads ORDER BY defaultColID DESC LIMIT 7";
		$stmt = $dbHandler->run($stmt);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(500, "No records found");
		}
		return $this->msg;
	}
	public function getCategoryByID($categID)
	{
		//Get Category details by ID
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malladcategory WHERE mallCategID = ?";
		$stmt = $dbHandler->run($stmt, [$categID]);
		$stmtData = $stmt->fetch();
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmtData);
		} else {
			$this->message(404, "Category not found");;
		}
		return $this->msg;
	}
	public function getAd()
	{
		$sql = "SELECT * FROM mallads";
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = $dbHandler->run($sql);
		$num = $stmt->rowCount();
		if ($num > 0) {
			$this->message(1, $stmt->fetchAll());
		} else {
			$this->message(0, "No Ad found");
		}
		return	$this->msg;
	}

	public function setAdStatus($adID, $adminID, $value)
	{

		$db = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);

		//check if ad is already deleted
		$sqlCheck = "SELECT * FROM mallads WHERE mallAdID=?";
		$stmtCheck = $db->run($sqlCheck, [$adID]);
		$row = $stmtCheck->fetch();



		if ($row['mallAdStatus'] == $value) {

			$this->message(400, "Status already set");
			return $this->msg;
			exit;
		}




		$sql = "UPDATE mallads SET mallAdStatus=? WHERE mallAdID=?";
		$stmt = $db->run($sql, [$value, $adID]);
		if ($stmt->rowCount() > 0) {
			$this->message(1, "Ad status updated successfully");
			return $this->msg;
			exit;
		} else {
			$this->message(500, "Ad status update failed");
			return $this->msg;
			exit;
		}
	}

	public function getPremiumAds()
	{

		$db = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "SELECT * FROM malladpromoted";
		$stmt = $db->run($sql);
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmt->fetchAll());
			return $this->msg;
			exit;
		} else {
			$this->message(500, "No records found");
			return $this->msg;
			exit;
		}
	}
	public function getAdPromotionLists($promoID)
	{

		$db = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "SELECT * FROM malladpromolist WHERE mallAdPromoType=?";
		$stmt = $db->run($sql, [$promoID]);
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmt->fetch());
			return $this->msg;
			exit;
		} else {
			$this->message(400, "No record found");
			return $this->msg;
			exit;
		}
	}
	public function setPremiumAdStatus($adID, $value)
	{

		$db = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);

		//check if ad is already deleted
		$sqlCheck = "SELECT * FROM malladpromoted WHERE mallAdPromoID=?";
		$stmtCheck = $db->run($sqlCheck, [$adID]);
		$row = $stmtCheck->fetch();

		if ($row['mallAdPromoStatus'] == $value) {

			$this->message(400, "Status already set");
			return $this->msg;
			exit;
		}


		$sql = "UPDATE malladpromoted SET mallAdPromoStatus=? WHERE mallAdPromoID=?";
		$stmt = $db->run($sql, [$value, $adID]);
		if ($stmt->rowCount() > 0) {
			$this->message(1, "Ad status updated successfully");
			return $this->msg;
			exit;
		} else {
			$this->message(500, "Ad status update failed");
			return $this->msg;
			exit;
		}
	}

	function getPremiumAdStats()
	{

		$stat = [];

		//get number of ads in review
		$dbHandler = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$stmt = "SELECT * FROM malladpromoted WHERE mallAdPromoStatus=?";
		$stmt = $dbHandler->run($stmt, ["0"]);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$stat["review"] = $stmt->rowCount();
		} else {
			$stat["review"] = 0;
		}

		//get number of active ads
		$stmt = "SELECT * FROM malladpromoted WHERE mallAdPromoStatus=?";
		$stmt = $dbHandler->run($stmt, ["1"]);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$stat["active"] = $stmt->rowCount();
		} else {
			$stat["active"] = 0;
		}


		//get number of declined ads
		$stmt = "SELECT * FROM malladpromoted WHERE mallAdPromoStatus=?";
		$stmt = $dbHandler->run($stmt, ["2"]);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$stat["declined"] = $stmt->rowCount();
		} else {
			$stat["declined"] = 0;
		}


		//get number of expired ads
		$stmt = "SELECT * FROM malladpromoted WHERE mallAdPromoStatus=?";
		$stmt = $dbHandler->run($stmt, ["3"]);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$stat["expired"] = $stmt->rowCount();
		} else {
			$stat["expired"] = 0;
		}



		//get number of promoted ads
		$stmt = "SELECT * FROM malladpromoted WHERE mallAdPromoStatus=?";
		$stmt = $dbHandler->run($stmt, ["4"]);
		$stmtData = $stmt->fetchAll();

		if ($stmt->rowCount() > 0) {
			$stat["deleted"] = $stmt->rowCount();
		} else {
			$stat["deleted"] = 0;
		}



		$this->message(1, $stat);

		return $this->msg;
	}

	public function getAdDetails($id)
	{

		$db = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0], DB_OPTIONS[1], DB_OPTIONS[3]);
		$sql = "SELECT * FROM mallads WHERE mallAdID=?";
		$stmt = $db->run($sql, [$id]);
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmt->fetch());
			return $this->msg;
			exit;
		} else {
			$this->message(500, "No records found");
			return $this->msg;
			exit;
		}
	}
	public function createPromoAd($plan, $price, $oldprice,$duration, $admin){
		//to create a promotion ad fields
		$secManager=new SecurityManager();
		$hash = $secManager->generateProgramHash("promo__");
		$date = time();
		$plan=explode("_",$plan);
		$planName=ucfirst($plan[0]);
		$planType=$plan[1];
		$db = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$sql = "INSERT INTO malladpromolist (mallAdPromoID,mallAdPromoName, mallAdPromoType, mallAdPromoDuration, mallAdPromoCost, mallAdPromoOldCost, mallAdPromoCreated,mallAdPromoCreatedBy) VALUES (?,?,?,?,?,?,?,?)";
		$stmt = $db->run($sql, [$hash, $planName, $planType, $duration, $price, $oldprice,$date,$admin]);
		if ($stmt->rowCount() > 0) {
			$this->message(1, "Promotion plan created successfully");
		   return $this->msg;
		   exit;
		} else {
		   $this->message(500, "Failed to create promotion plan");
		   return $this->msg;
		   exit;
		}
	}


	public function getPromoAdPlans(){

		$db = new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$sql = "SELECT * FROM malladpromolist";
		$stmt = $db->run($sql);
		if ($stmt->rowCount() > 0) {
			$this->message(1, $stmt->fetchAll());
		   return $this->msg;
		   exit;
		} else {
		   $this->message(500, "No records found");
		   return $this->msg;
		   exit;
		}

	}

	function getUserAdStats($userID){

		$stat = [];
	
		//get number of ads for user
		$dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$stmt = "SELECT * FROM mallads WHERE mallUsrID=?";
		$stmt = $dbHandler->run($stmt,[$userID]);

		if ($stmt->rowCount() > 0) {
			$stat["regular"] = $stmt->rowCount();
		}else {
			$stat["regular"] = 0;
		}




		//get number of promoted ads
		$stmt = "SELECT * FROM malladpromoted WHERE mallUsrID=?";
		$stmt = $dbHandler->run($stmt, [$userID]);

		if ($stmt->rowCount() > 0) {
			$stat["promoted"] = $stmt->rowCount();
		}else {
			$stat["promoted"] = 0;
		}


	
		$this->message(1, $stat);
		
        return $this->msg;
	}

	public function getUserAds($id){
		$sql = "SELECT * FROM mallads WHERE mallUsrID=?";
		$dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$stmt = $dbHandler->run($sql, [$id]);
        $num = $stmt->rowCount();
		if ($num > 0) {
			$this->message(1, $stmt->fetchAll());
		}
		else{
			$this->message(0, "No Ad found");
		}
		return	$this->msg;
	}

	function hasUserPromotedAd($usrID){
		$inputValidator = new InputValidator();
		$usrID = $inputValidator->sanitizeItem($usrID, "string");
		$sql = "SELECT * FROM malladpromoted WHERE mallUsrID=?";
		$dbHandler=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
		$stmt = $dbHandler->run($sql, [$usrID]);
        $num = $stmt->rowCount();
		if ($num > 0) {
			$this->message(1, "User is active");
		}
		else{
			$this->message(404, "User not active");
		}
		return	$this->msg;
		
	}


}

 //$ad=new AdManager();
 //print_r($ad->usrMakeOffer("912031648136282","12000",29019164659));
/*  $getArray=$ad->priceSortFilter($_GET['sortBy'], $_GET['filter_attribs'])['message']; 
foreach ($getArray as $key) {
	echo $key['mallAdTitle']."<br>";
} */
/* unset($_GET['categID']);
$getAllOptions=$ad->getCategOptionsByID($_GET['categID']);
//echo print_r($getAllOptions['message']);
foreach ($getAllOptions['message'] as $key=>$value) {
	echo $value['mallCategParamName'].$value['mallCategParamValues']."<br>";
}
unset($_GET['categID']); */
