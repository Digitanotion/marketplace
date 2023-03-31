<?php 
include_once("../init.php");
include_once("advertManager.php");

$db = new Init();
$cat_connect = new Categories($db);
if (isset($_POST['submit'])) {
	$user = ($_POST["user"]);
	$user2 = ($_POST["user2"]);
	$num = ($_POST["num"]);
	$num2 = ($_POST["num2"]);
	$num2 = ($_POST["num2"]);
$cat = 	$cat_connect->mallUsrPromos($user, $num, $num2, $user2);
print_r($cat);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<form method="post">
		<input type="text" value='' name="user">
		<input type="number" name="num" value=null>
		<input type="number" name="num2">
		<input type="" name="user2" value="NULL">
		<button type="submit" name="submit">submit</button>
	</form>
</body>
</html>
<!-- // $cat = $cat_connect->addCategory(12345, "IPhone", "Electronics,Mobile", "Mobile", "icon");
// print_r($cat);

// $catPara = $cat_connect->addCategory_Params("admin", 7777, "Iphone", "Creating parameters", "fhsdjkf");
// $response=json_decode($catPara);

// if ($response->status=="success"){
// 	echo $response->message;
// } 
// else{
// 	echo "Something went wrong";
// }

/*if (isset($catPara["failed"])) {
	echo "ddfgd";
} else {
	echo "yes";
}*/


// $remCat = $cat_connect->removeCategory(7777);
// print_r($remCat);
// $fields=new array();
// $fields["cat_id"]="";
// $fields["mallAdID"]="";
// $fields["mallAdName"]="";
// $fields["mallAdParking"]="";

// $ads = $cat_connect->createAd($fields["mallAdID"], $fields["mallAdName"], 7777, "Awka", "media", "title", "desc", "price", 0, "promo", "time", "type", "condition", "gender", "age", "brand", "material", "colour", "closure", "exchange","movement", "display", "case", "band", "features", "style", "name", "job", "level", "exp", "deadline", "address", "furnishing", "type", "space", "packing", "meters", "time", "level", "make", "model", "year", "trim", "second", "mission","mileage", "vin", "reg", "body", "fuel", "train", "cy","size", "power", 1);
// print_r($ads);

// $columns = ["mallAdMediaList", "mallAdTitle"];
// $args = ["sing", "Changed"]
// $up =  $cat_connect->updateAd($columns, $args, 2222);
// print_r($up);

// $del = $cat_connect->deleteAd(7777);
// print_r($del);

// $sim = getSimilarAd($awka, 7777);
// print_r($sim);

// $get = $cat_connect->getAd();
// if (isset($get)) {
// 	foreach ($get as $value) {
// 		echo $value[];
// 	}
// } else {
	
// }

// $pro = $cat_connect->addPromoPlans(12345, 00000, "7 Days", 5000);
// print_r($pro);

// $uPro = $cat_connect->mallUsrPromo(12345, 00000, 1, "25-3");
// print_r($uPro);

// $seller = $cat_connect->getSellerDetails(12345);
// foreach ($seller as $value) {
// 	echo $value[];
// }

// $save = $cat_connect->saveAd(12345, 2222);
// print_r($save);

// $getSave = $cat_connect->getSaveAd(12345);
// foreach ($getSave as  $value) {
// 	echo $value[];
// }

// Malachi 09029018963
// Chidunem 08086875721
// Helen 09061882997
 -->



