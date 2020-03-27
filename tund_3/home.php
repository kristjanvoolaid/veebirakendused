<?php
    require ("../../../../configuration.php");
    // require ("fnc_news.php");
    require("fnc_users.php");

    // Sessiooni kasutamine
    require("classes/session.class.php");
	SessionManager::sessionStart("vr20", 0, "/kristjan.voolaid", "tigu.hk.tlu.ee");

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
    <p>Tere <?php echo $_SESSION['userFirstName']; ?></p>
	<p>See leht on valminud õppetöö raames!</p>
    <hr>

    <a href="?logout=1"><p>Logi välja!</p></a>

</body>
</html>