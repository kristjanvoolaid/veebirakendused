<?php
    require ("../../../../configuration.php");
    // require ("fnc_news.php");
    require("fnc_users.php");

    // Sessiooni kasutamine
    require("classes/session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~kristjan.voolaid", "tigu.hk.tlu.ee");

    // $newsHtml = readNews();

    // kas on sisseloginud
    if(!isset($_SESSION["userid"])) {
        // Jõuga avalehel
        header("Location: page.php");
    } 

    // Login välja
    if(isset($_GET["logout"])) {
        session_destroy();
        header("Location: page.php");

    }
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Meie äge koduleht</h1>
    <p>Tere <?php echo $_SESSION['userFirstName'] . " " . $_SESSION['userLastName'];?></p>
	<p>See leht on valminud õppetöö raames!</p>
    <hr>

    <a href="?logout=1"><p>Logi välja!</p></a>

    <a href="school_log.php">Sisesta õppimise logi</a>
    <br>
    <br>
    <a href="readschoollogs.php">Õppimise logid</a>
    <br>
    <br>
    <a href="photoUpload.php">Piltide ülesse laadmine</a>
    <br>
    <br>
    <a href="semipublicgallery.php">Avalik Galerii</a>
    <br>
    <br>
    <a href="privategallery.php">Sinu pildid</a>

</body>
</html>