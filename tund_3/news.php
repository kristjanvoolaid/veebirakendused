<?php
    require ("../../../../configuration.php");
    require ("fnc_news.php");
    require ("fnc_users.php");

    $newsHtml = readNews();

    require("classes/session.class.php");
    SessionManager::sessionStart("vr20", 0, "/~kristjan.voolaid", "tigu.hk.tlu.ee");
    
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
	<h1>Uudise lisamine</h1>
    <h3>Tere <?php echo $_SESSION['userFirstName'] . " " . $_SESSION['userLastName']; ?></h3>
	<p>See leht on valminud õppetöö raames!</p>
    <div>
        <?php echo $newsHtml; ?>
    </div>
    <a href="?logout=1"><p>Logi välja!</p></a>
</body>
</html>