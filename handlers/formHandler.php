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
USE services\AdS\AdManager;
use services\SecS\InputValidator;

$adManager = new AdManager();

if (isset($_POST['category'])){
	$categID = $_POST['category'];
	$categObject = $adManager->getCategOptionByID($categID);
	$arrayPosition=0;

	//$mallPrefetchForms=[];
	if ($categObject["status"]==1){
		global $mallPrefetchForms;
		foreach ($categObject["message"] as  $value) {
			//array_push($mallPrefetchForms, $value["mallCategParamName"]);;
			$categName = explode("-", $value["mallCategParamName"]);
			$categValue = explode(",", $value["mallCategParamValues"]);
			if ($value["mallCategParamDType"] == "select") {?>
				<div class=" d-flex mb-1 col-md-6 col-sm-12 my-2" id="multiselec">
				<div class="form-floating w-100">
					<select class="form-select select2" id="<?php echo $categName[0]; ?>" name="genForms[<?php echo $categName[0]; ?>]">
						<!--<option value="<?php //echo $categName[1];?>"> <?php //echo $categName[1];?></option>-->
						<?php 
						//Check if there is data from the column, based on the Ad ID Supplied on GET
							for ($i=0; $i < count($categValue); $i++) {
								if (isset($_POST['adUp'])){
									$adUpID=InputValidator::sanitizeInput($_POST['adUp'],"string");
									$usrAdUpdateInfo=$adManager->getAllAdByID($adUpID);
									$getCategInfo_UPT="";
									if ($usrAdUpdateInfo['status']==1){
										$usrAdUpdateInfo=$usrAdUpdateInfo['message'];
										$getCategChildInfo_UPT=$adManager->getCategInfoByID($usrAdUpdateInfo['mallCategID'])['message'];
										$getCategParentID_UPT=$adManager->getCategParentByID($getCategChildInfo_UPT['mallCategID'])['message'];
										$getCategParentInfo_UPT=$adManager->getCategInfoByID($getCategParentID_UPT)['message'];
									?>
										<option value="<?php echo $categValue[$i]; ?>" <?php echo ($categValue[$i]==$usrAdUpdateInfo[$categName[0]])? "selected":""; ?>> <?php echo $categValue[$i];?></option>
									<?php 
									}else{
									    ?>
									    <option value="<?php echo $categValue[$i]; ?>"> <?php echo $categValue[$i];?></option>
									    <?php
									}
								}else{
								?>
									<option value="<?php echo $categValue[$i]; ?>"> <?php echo $categValue[$i];?></option>
							<?php } }
						?>
					</select>
					<label for="<?php echo $categName[0]; ?>"><?php echo $categName[1];?></label>     
				</div>
				</div>
				
			<?php } elseif ($value["mallCategParamDType"] == "textbox") {
				$adUpID=InputValidator::sanitizeInput($_POST['adUp'],"string");
									$usrAdUpdateInfo=$adManager->getAllAdByID($adUpID);
									$getCategInfo_UPT="";
									if ($usrAdUpdateInfo['status']==1){
										$usrAdUpdateInfo=$usrAdUpdateInfo['message'];
										$getCategChildInfo_UPT=$adManager->getCategInfoByID($usrAdUpdateInfo['mallCategID'])['message'];
										$getCategParentID_UPT=$adManager->getCategParentByID($getCategChildInfo_UPT['mallCategID'])['message'];
										$getCategParentInfo_UPT=$adManager->getCategInfoByID($getCategParentID_UPT)['message'];
										?>
										<div class=" d-flex mb-1 col-md-6 col-sm-12 my-2" id="multiselec">
					<div class="form-floating w-100">
						<input type="text" class="form-control" id="<?php echo $categName[0]; ?>" name="genForms[<?php echo $categName[0]; ?>]" placeholder="<?php echo $categName[1]; ?>" value="<?php echo $usrAdUpdateInfo[$categName[0]];?>">
						<label for="<?php echo $categName[0]; ?>"><?php echo $categName[1]; ?></label>
					</div>
				</div>
										<?php
										} else {?>
				<div class=" d-flex mb-1 col-md-6 col-sm-12 my-2" id="multiselec">
					<div class="form-floating w-100">
						<input type="text" class="form-control" id="<?php echo $categName[0]; ?>" name="genForms[<?php echo $categName[0]; ?>]" placeholder="<?php echo $categName[1]; ?>">
						<label for="<?php echo $categName[0]; ?>"><?php echo $categName[1]; ?></label>
					</div>
				</div>
			<?php }} elseif ($value["mallCategParamDType"] === "textarea") {?>
				<!-- <div>
					<label for="textarea1" class="text-end mb-2 me-4 d-block sell-fs-base">0/2000</label
					>
					<textarea id="textarea1" class="form-control sell-fs-base p-3 pt-4" placeholder="<?php echo $categName[1]; ?>*" name="<?php echo $categName[0]; ?>" style="resize: none;"
					></textarea>
				</div> -->
				<div class=" d-flex mb-1 col-md-6 col-sm-12 my-2" id="multiselec">
				<div class="form-floating w-100">
					<select class="form-select select2" id="<?php echo $categName[0]; ?>" name="genForms[<?php echo $categName[0]; ?>]">
						<!-- <option value=""> <?php //echo $categName[1];?></option> -->
						<?php 
							for ($i=0; $i < count($categValue); $i++) {?>
								<option value="<?php echo $categValue[$i]; ?>"> <?php echo $categValue[$i];?></option>
							<?php }
						?>
					</select>
					<label for="<?php echo $categName[0]; ?>"><?php echo $categName[1];?></label>     
				</div>
				</div>
			<?php } elseif ($value["mallCategParamDType"] === "radio") {?>
				<!-- <div class="mt-4">
					<p class="fw-bold"><?php echo $categName[1]; ?>*</p>
					<div class="form-check">
						<?php 
						/* 	for ($i=0; $i < count($categValue); $i++) {?>
									<input class="form-check-input" type="radio" name="<?php echo $categName[0];?>" id="flexRadioDefault1" value = "1"/>
									<label class="form-check-label sell-fs-base" for="flexRadioDefault1">
								<?php echo $categValue[$i];?>
								</label> <br><!-- 
								<?php //}elseif($categValue[$i] === "No"){?>
									<input class="form-check-input" type="radio" name="<?php echo $categName[0];?>" id="flexRadioDefault1" value = "0"/>
									<label class="form-check-label sell-fs-base" for="flexRadioDefault1">
								<?php echo $categValue[$i];?>
								</label> <br> -->
							<?php //} 
							}*/
							?>
						
					</div>
				</div> -->
				<div class=" d-flex mb-1 col-md-6 col-sm-12 my-2" id="multiselec">
				<div class="form-floating w-100">
					<select class="form-select select2" id="<?php echo $categName[0]; ?>" name="genForms[<?php echo $categName[0]; ?>]">
						<!-- <option value=""> <?php //echo $categName[1];?></option> -->
						<?php 
							for ($i=0; $i < count($categValue); $i++) {?>
								<option value="<?php echo $categValue[$i]; ?>"> <?php echo $categValue[$i];?></option>
							<?php }
						?>
					</select>
					<label for="<?php echo $categName[0]; ?>"><?php echo $categName[1];?></label>     
				</div>
				</div>
			<?php } 
			$arrayPosition++;
		}
	}
	//print_r($mallPrefetchForms);
}



	 
