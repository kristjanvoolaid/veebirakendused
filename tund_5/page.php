<?php
	require("../../../../configuration.php");
	require("fnc_users.php");

	require("classes/session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~kristjan.voolaid", "tigu.hk.tlu.ee");

	// require("classes/Test.class.php");
	// $test = new Test();
	// // echo "Test: " . $test->number;
	// $test->reveal();
	// unset($test);

	$myName = "Kristjan Voolaid";
	$fullTimeNow = date("d.m.Y H:i:s");
	// <p> Lehe avamise hetkel oli: 31.01.2020 11:32 11:32</p>
	$timeHtml = "<p>Lehe avamise hetkel oli: <strong>" .$fullTimeNow ."</strong></p>";
	$hourNow = date("H");
	$partOfDay = "Hägune aeg";

	if ($hourNow < 10) {
		$partOfDay = "Hommik";
	}
	elseif ($hourNow >= 10 and $hourNow < 20) {
		$partOfDay = "aeg aktiivselt tegutseda";
	}

	// Kodune ülesanne 2: muuda taustavärvi kellaajasuhtes
	if ($hourNow >= 5 and $hourNow <= 11) {
		$background = '"hommik"';
	} elseif ($hourNow > 11 and $hourNow <= 16) {
		$background = '"louna"';
	} else {
		$background = '"ohtu"';
	}

	$partOfDayHTML = "<p>Käes on " .$partOfDay . "!</p>";

	// info semestri kulgemise kohta
	$semesterStart = new DateTime("2020-01-27");
	$semesterEnd = new DateTime("2020-06-22");
	$semesterDuration = $semesterStart->diff($semesterEnd);
	$today = new DateTime("now");
	$fromSemesterStart = $semesterStart->diff($today);

	// <p>Semester on hoos: <meter value="$fromSemesterStart" min="0" max="$semesterDuration"></meter>.</p>
	$semesterProgressHtml = '<p>Semester on hoos: <meter min="0" max="';
	// %r paneb miinuse ette kui arv on miinuses, %a tulemus päevade arvust
	$semesterProgressHtml .=$semesterDuration->format("%r%a");
	$semesterProgressHtml .='" value="';
	$semesterProgressHtml .= $fromSemesterStart->format("%r%a");
	$semesterProgressHtml .='" </meter></p>'."\n";

	// Kodune ülesanne 3: Semestri kulgemine. Testimiseks tuleks muuta $semesterStart kuupäeva tänasest päevast hilisemaks
	if ($today < $semesterStart) {
		$semesterProgressHtml = '<p>Semester pole alanud!</p>';
	} else {
		$semesterProgressHtml = '<p>Semester on hoos: <meter min="0" max="';
		$semesterProgressHtml .=$semesterDuration->format("%r%a");
		$semesterProgressHtml .='" value="';
		$semesterProgressHtml .= $fromSemesterStart->format("%r%a");
		$semesterProgressHtml .='" </meter></p>'."\n";
	}

	//loen etteantud kataloogist pildi failid
	$imgDir = "../../img/";
	$photoTypesAllowed = ["image/jpeg", "image/png"];
	$allFiles = array_slice(scandir($imgDir), 2);
	$photoList = [];

	// Lisan piltide kaustast pildid listi tsükkli abil
	foreach($allFiles as $file) {
		$fileInfo = getimagesize($imgDir .$file);
		if(in_array($fileInfo["mime"], $photoTypesAllowed) == true) {
			array_push($photoList, $file);
		}
	}
	
	// prindin random pildi piltide listist
	$photoCount = count($photoList);
	$photonum = mt_rand(0, $photoCount - 1);
	$randomImageHtml = '<img src="' .$imgDir .$photoList[$photonum] .'" alt="juhuslik pilt">' ."\n";
	$randomImageHtml2 = '<img src="' .$imgDir .$photoList[$photonum] .'" alt="juhuslik pilt">' ."\n";

	$notice = null;
	$email = null;
	$emailError = null;
	$passwordError = null;

	if(isset($_POST["login"])){
		if (isset($_POST["email"]) and !empty($_POST["email"])){
		  $email = test_input($_POST["email"]);
		} else {
		  $emailError = "Palun sisesta kasutajatunnusena e-posti aadress!";
		}
	  
		if (!isset($_POST["password"]) or strlen($_POST["password"]) < 8){
		  $passwordError = "Palun sisesta parool, vähemalt 8 märki!";
		}
	  
		if(empty($emailError) and empty($passwordError)){
		   $notice = signIn($email, $_POST["password"]);
		} else {
			$notice = "Ei saa sisse logida!";
		}
	}
	  
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>

	<style>
		.hommik {
			background-color: blue;
			font-size: 16px;
			color: pink;
		}

		.louna {
			background-color: orange;
			font-size: 18px;
			color: green;
		}

		.ohtu {
			background-color: red;
			font-size: 20px;
			color: blue;
		}
	</style>

</head>
<body class=<?php echo $background; ?>>
	<h1><?php echo $myName; ?></h1>
	<p>See leht on valminud õppetöö raames!</p>
	<p><a href="newuser.php">Loo endale kasutajakonto</a></p>
	<h2>Logi Sisse</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <label>E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
	  <label>Salasõna (min 8 tähemärki):</label><br>
	  <input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
      <input name="login" type="submit" value="Logi Sisse!"><span><?php echo $notice; ?></span>

    </form>
	<?php 
		echo $timeHtml;
		echo $partOfDayHTML;
		// var_dump($semesterDuration);
		echo $semesterProgressHtml;
		// var_dump($allFiles);

		// Kodune ülesanne number 1:
		// Tegin listi $photonum_2 random numbrite jaoks. Tsükklis loosin random numbri, kontrollin kas see on $photonum_2 listis,
		// kui ei, siis lisan numbri listi
		// Pildid prindin listi indexite abil
		$photonum_2 = [];
		for ($x = 0;$x <= 2;$x++) {
			do {
				$photonum = mt_rand(0, $photoCount - 1);
			} while (in_array($photonum, $photonum_2) == true); {
				array_push($photonum_2, $photonum);
			}
			$y = $photonum_2[$x];
				$randomImageHtml = '<img src="' .$imgDir .$photoList[$y] .'" alt="juhuslik pilt">' ."\n";
				echo $randomImageHtml;

	}	 

	?>
	<br>
	<?php
		echo (int)$photonum_2;
	?>
</body>
</html>