<?php
	$myName = "Kristjan Voolaid";
	$fullTimeNow = date("d.m.Y H:i:s");
	// <p> Lehe avamise hetkel oli: 31.01.2020 11:32 11:32</p>
	$timeHtml = "<p>Lehe avamise hetkel oli: <strong>" .$fullTimeNow ."</strong></p>";
	$hourNow = date("H");
	$partOfDay = "Hägune aeg";

	if ($hourNow < 10) {
		$partOfDay = "Hommik";
	}
	elseif ($hourNow >= 10 and $hourNow < 18) {
		$partOfDay = "aeg aktiivselt tegutseda";
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
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1><?php echo $myName; ?></h1>
	<p>See leht on valminud õppetöö raames!</p>
	<?php 
		echo $timeHtml;
		echo $partOfDayHTML;
		var_dump($semesterDuration);
		echo $semesterProgressHtml;
		var_dump($allFiles);
		echo $randomImageHtml;
	?>
</body>
</html>