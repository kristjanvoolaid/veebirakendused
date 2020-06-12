<?php
    require ("../../../../configuration.php");
    require ("fnc_news.php");
    require("fnc_users.php");

    // Menubar
    include("menubar.php");

    // Sessiooni kasutamine
    require("classes/session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~kristjan.voolaid", "tigu.hk.tlu.ee");

    // $newsHtml = readNews();

    // kas on sisseloginud
    if(!isset($_SESSION["userid"])) {
        // Jõuga avalehel
        header("Location: page.php");
    }
    
    //login välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
    }

    $courseType = null;
    $activityType = null;
    $elapsedTime = null;
    $logError = null;

    // Kontorllin kas nuppu on vajutatud
    if(isset($_POST["logBtn"])) {
        if(isset($_POST["courseType"]) and !empty($_POST["courseType"])) {
            $courseType = $_POST["courseType"];
        } else {
            $logError = "Õppeaine valimata!";
        }

        if(isset($_POST["activityType"]) and !empty($_POST["activityType"])) {
            $activityType = $_POST["activityType"];
        } else {
            $logError .= " Tegevus valimata!";
        }

        if(isset($_POST["elapsedTime"]) and !empty($_POST["elapsedTime"])) {
            $elapsedTime = $_POST["elapsedTime"];
        } else {
            $logError .= " Aeg valimata!";
        }

        // Andmed andmebaasi
        if(empty($logError)) {
            $response = saveSchoolLog($courseType, $activityType, $elapsedTime);

            if($response == 1) {
                $logError = "Logi on salvestatud!";
            } else {
                $logError = "Tekkis tõrge! Logi ei salvestatud!";
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Õppimise logi</title>
</head>
<body>
    <h1>Siin on minu Õppimise logi</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <strong>Õppeaine: </strong>
        <select name="courseType" id="">
            <option value="" selected disapled>Vali</option>
            <option value="1">Üld- ja sotsiaalpsühholoogia</option>
            <option value="2">Veebirakendused ja nende loomine</option>
            <option value="3">Programmeerimine I</option>
            <option value="4">Disaini alused</option>
            <option value="5">Videomängude disain</option>
            <option value="6">Andmebaasid</option>
            <option value="7">Sissejuhatus tarkvaraarendusse</option>
            <option value="8">Sissejuhatus informaatikasse</option>
        </select>

        <strong>Tegevus: </strong>
        <select name="activityType" id="" value="<?php echo $activityType; ?>">
            <option value="" selected disapled>Vali</option>
            <option value="1">Iseseisva materjali loomine</option>
            <option value="2">Koduste ülesannete lahendamine</option>
            <option value="3">Kordamine</option>
            <option value="4">Rühmatöö</option>
        </select>

        <strong>Kulunud aeg: </strong>
        <input type="number" min=".25" step=".25" name="elapsedTime" value="<?php echo $elapsedTime; ?>">

        <input type="submit" name="logBtn" value="Salvesta logi!">
        <span><?php echo $logError; ?></span>
        <div>
            <a href="readschoollogs.php">Vaata logisid</a>
        </div>
        <br>
    </form>
</body>
</html>