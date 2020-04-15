<?php
    require ("../../../../configuration.php");
    require("fnc_photos.php");
    
    // Sessiooni kasutamine
    require("classes/session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~kristjan.voolaid", "tigu.hk.tlu.ee");

    // $newsHtml = readNews();

    // kas on sisseloginud
    if(!isset($_SESSION["userid"])) {
        // Jõuga avalehel
        header("Location: page.php");
    } 

    // Pildi üleslaadimise osa
    // var_dump($_POST);
    // var_dump($_FILES);

    $originalPhotoDir = "../../uploadOriginalPhoto/";
    $normalPhotoDir = "../../uploadNormalPhoto/";
    $error = null;
    $result = null;
    $notice = null;
    $imageFileType = null;
    $fileUploadSizeLimit = 1048576;
    $fileNamePrefix = "vr20_";
    $fileName = null;
    $maxWidth = 600;
    $maxHeight = 400;

    if(isset($_POST['photoSubmit'])) {
        // func test
        $picture_file = $_FILES["fileToUpload"]["tmp_name"];
        // Kas on üldse pilt
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            // Failitüübi väljaselgitamine ja sobivuse kontroll
            if($check["mime"] == "image/jpeg") {
                $imageFileType = "jpg";
            } elseif($check["mime"] == "image/png") {
                 $imageFileType = "png";   
            } else {
                $error = "Ainult jpg ja png pilid on lubatud";
            }
        } else {
            $error = "Tegemist ei ole pildiga";
        }

        // Pildi suuruse kontroll
        if($_FILES["fileToUpload"]["tmp_name"] > $fileUploadSizeLimit) {
            $error .= "Valitud fail on liiga suur";
        }

        // Loome oma nime failile
        $timeStamp = microtime(1) * 10000;
        $fileName = $fileNamePrefix . $timeStamp . "." .$imageFileType;

        // $originalTarget = $originalPhotoDir .$_FILES["fileToUpload"]["name"];
        $originalTarget = $originalPhotoDir .$fileName;
        // Äkki on fail olemas
        if(file_exists($originalTarget)) {
            $error .= "Selline fail on juba olemas!";
        }
        // Kui vigu pole
        if($error == null) {
            // Teen pildi väiksemaks
            if($imageFileType == "jpg") {
                $myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
            }
            if($imageFileType == "png") {
                $myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
            }

            // Pildi suuruse muutmine
            $myNewImage = changePhotoSize($myTempImage);

            // Salvestame vähendatud kujutise faili
            if($imageFileType == "jpg") {
                if(imagejpeg($myNewImage, $normalPhotoDir . $fileName, 90)) {
                    $notice = "Vähendatud pilt laeti ülesse!";
                } else {
                    $error = "Vähendatud pildi salvestamisel tekkis tõrge!";
                }
            } 

            if($imageFileType == "png") {
                if(imagepng($myNewImage, $normalPhotoDir . $fileName, 6)) {
                    $notice = "Vähendatud pilt laeti ülesse!";
                } else {
                    $error = "Vähendatud pildi salvestamisel tekkis tõrge!";
                }
            }

            // Pilti upload ja andmed andmebaasi
            $userId = $_SESSION['userid'];
            $origName = $_FILES['fileToUpload']['name'];
            $altText = $_POST['altText'];
            $privacy = $_POST['privacy'];

            if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)) {
                $notice .= " Pilt laeti ülesse!"; 
                $result = photoUpload($userId, $fileName, $origName, $altText, $privacy);
                if($result == 1) {
                    $notice .= " Pildi info andmebaasi salvestatud!";
                } else {
                    $error .= " Pildi info andmebaasi salvestamisel tekkis tõrge!";
                }
            } else {
                $error .= "Pildi ülesse laadimisel tekkis viga!";
            }

            // imagedestroy($myTempImage);
            imagedestroy($myNewImage);

            // Andmebaasi
            
        } // Kui vigu pole
        
    }
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Photode üleslaadimine</h1>
	<p>See leht on valminud õppetöö raames!</p>
    <!-- Post meetod kaotab url realt ära kasutaja sisestatud info/teksti. Vaikimisi on method GET -->
    <a href="home.php">Avalehele</a>
    <hr>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <label for="">Vali pildi fail: </label>
        <br>
        <input type="file" name="fileToUpload"><br>
        <label for="">Alt tekst: </label><input type="text" name="altText"><br>
        <label for="">Privaatsus</label><br>
        <label for="priv1">Privaatne</label><input type="radio" name="privacy" id="priv1" value="3" checked><br>
        <label for="priv2">Sisselogitud kasutajatele</label><input type="radio" name="privacy" id="priv2" value="2"><br>
        <label for="priv3">Avalik</label><input type="radio" name="privacy" id="priv3" value="1"><br>
        <input type="submit" name="photoSubmit" value="Lae pilt ülesse">
        <span><?php echo $error; ?></span>
        <span><?php echo $notice; ?></span>
    </form>
</body>
</html>