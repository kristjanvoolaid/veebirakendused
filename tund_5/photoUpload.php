<?php
	require("classes/session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~kristjan.voolaid", "tigu.hk.tlu.ee");
	
	//kas pole sisseloginud
	if(!isset($_SESSION["userid"])){
		//jõuga avalehele
		header("Location: page.php");
	}
	
	//login välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}

	// Menubar
    include("menubar.php");
	
	require("../../../../configuration.php");
	require("fnc_photoupload.php");
	require("classes/Photo.class.php");
	
	//pildi üleslaadimine osa
	
	//var_dump($_POST);
	//var_dump($_FILES);
	
	$originalPhotoDir = "../../uploadOriginalPhoto/";
	$normalPhotoDir = "../../uploadNormalPhoto/";
	$thumbPhotoDir = "../../uploadThumbnailPhoto/";
	$error = null;
	$first_check = null;
	$notice = null;
	$imageFileType = null;
	$fileUploadSizeLimit = 1048576;
	$fileNamePrefix = "vr_";
	$fileName = null;
	$maxWidth = 600;
	$maxHeight = 400;
	$thumbSize = 100;
	
	if(isset($_POST["photoSubmit"]) and !empty($_FILES["fileToUpload"]["tmp_name"])){

		//kas üldse on pilt?
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

		if($check !== false){
			//failitüübi väljaselgitamine ja sobivuse kontroll
			if($check["mime"] == "image/jpeg"){
				$imageFileType = "jpg";
			} elseif ($check["mime"] == "image/png"){
				$imageFileType = "png";
			} else {
				$first_check = "Ainult jpg ja png pildid on lubatud! "; 
			}
		} else {
			$first_check = "Valitud fail ei ole pilt! ";
		}
		
		//kui vigu pole
		if($first_check == null){
			
			$photoUp = new Photo($_FILES["fileToUpload"], $imageFileType, $fileUploadSizeLimit);

			// Pildi suuruse kontroll
			$sizeCheck = $photoUp->checkPhotoSize();

			if($sizeCheck == 0) {
				$error = "Valitud pilt on liiga suur!";
			}

			// Pildi nime genereerimine
			$fileName = $photoUp->generateName();
			$originalTarget = $originalPhotoDir .$fileName;
		
			//äkki on fail olemas?
			if(file_exists($originalTarget)){
				$error .= "Selline fail on juba olemas!";
			}
			
			$photoUp->resizePhoto($maxWidth, $maxHeight);

			// Lisan veemärgi
			$photoUp->addWatermark("vr_watermark.png", 3, 10);
			
			// Pildi salvestamine
			$result = $photoUp->saveImgToFile($normalPhotoDir .$fileName);
			if($result == 1) {
				$notice = "Vähendatud pilt laeti üles! ";
			} else {
				$error = "Vähendatud pildi salvestamisel tekkis viga!";
			}
			
			$photoUp->resizePhoto($thumbSize, $thumbSize);
						
			//lõpetame vähendatud pildiga ja teeme thumbnail'i
			
			$result = $photoUp->saveImgToFile($thumbPhotoDir .$fileName);
			if($result == 1) {
				$notice = "Pisipilt laeti üles! ";
			} else {
				$error .= " Pisipildi salvestamisel tekkis viga!";
			}
		
			unset($photoUp);
			
			if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)){
				$notice .= "Originaalpilt laeti üles! ";
			} else {
				$error .= " Pildi üleslaadimisel tekkis viga!";
			}
			
			//kui kõik hästi, salvestame info andmebaasi!!!
			if($error == null){
				$result = addPhotoData($fileName, $_POST["altText"], $_POST["privacy"], $_FILES["fileToUpload"]["name"]);
				if($result == 1){
					$notice .= "Pildi andmed lisati andmebaasi!";
				} else {
					$error .= " Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
				}
			}
			
		}//kui vigu pole
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Fotode üleslaadimine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>
	
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label>Vali pildifail! </label><br>
		<input type="file" name="fileToUpload"><br>
		<label>Alt tekst: </label><input type="text" name="altText"><br>
		<label>Privaatsus</label><br>
		<label for="priv1">Privaatne</label><input id="priv1" type="radio" name="privacy" value="3" checked><br>
		<label for="priv2">Sisseloginud kasutajatele</label><input id="priv2" type="radio" name="privacy" value="2"><br>
		<label for="priv3">Avalik</label><input id="priv3" type="radio" name="privacy" value="1"><br>
		
		<input type="submit" name="photoSubmit" value="Lae valitud pilt üles!">
		<span><?php echo $first_check; echo $error; echo $notice; ?></span>
	</form>
	
	<br>
	<hr>
</body>
</html>