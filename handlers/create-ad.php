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
$adManager = new AdManager();
$categID = $_POST['category'];
$categObject = $adManager->getCategOptionByID($categID);?>
	
<?php 
foreach ($categObject["message"] as  $value) {
	$categName = explode("-", $value["mallCategParamName"]);
	$categValue = explode(",", $value["mallCategParamValues"]);
	if ($value["mallCategParamDType"] === "select") {?>
		<div class=" d-flex mb-1 col-md-6 col-sm-12" id="multiselec">
			<div class="form-floating w-100">
				<select class="form-select p-3 my-2 select2" id="<?php echo $categName[0]; ?>" name="<?php echo $categName[0]; ?>">
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
	<?php } elseif ($value["mallCategParamDType"] === "textbox") {?>
		<div class="col-md-6 col-sm-12 my-2">
            <input name="<?php echo $categName[0]; ?>" type="text" class="sell-fs-base  p-3 form-control" placeholder="<?php echo $categName[1]; ?> * "
            />
          </div>
	<?php } elseif ($value["mallCategParamDType"] === "textarea") {?>
		<div>
            <label for="textarea1" class="text-end mb-2 me-4 d-block sell-fs-base">0/2000</label
            >
            <textarea id="textarea1" class="form-control sell-fs-base p-3 pt-4" placeholder="<?php echo $categName[1]; ?>*" name="<?php echo $categName[0]; ?>" style="resize: none;"
            ></textarea>
          </div>
	<?php } elseif ($value["mallCategParamDType"] === "radio") {?>
		<div class="mt-4">
          <p class="fw-bold"><?php echo $categName[1]; ?>*</p>
          <div class="form-check">
          	<?php 
                for ($i=0; $i < count($categValue); $i++) {
                	if($categValue[$i] === "Yes"){?>
                		<input class="form-check-input" type="radio" name="<?php echo $categName[0];?>" id="flexRadioDefault1" value = "1"/>
                		<label class="form-check-label sell-fs-base" for="flexRadioDefault1">
		              <?php echo $categValue[$i];?>
		            </label> <br>
                	<?php }elseif($categValue[$i] === "No"){?>
                		<input class="form-check-input" type="radio" name="<?php echo $categName[0];?>" id="flexRadioDefault1" value = "0"/>
                		<label class="form-check-label sell-fs-base" for="flexRadioDefault1">
		              <?php echo $categValue[$i];?>
		            </label> <br>
		        <?php }
               	}
                ?>
            
          </div>
      </div>
	<?php } 
} ?>

