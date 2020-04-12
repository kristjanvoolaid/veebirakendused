<?php
    require ("../../../../configuration.php");
    require ("fnc_news.php");
    // var_dump($_POST);
    // echo $_POST["newsTitle"];
    // null tähend on tühi väärtus
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

    $newsTitle = null;
    $newsContent = null;
    $newsError = null;

    // Kontrollin kas buttonit on vajutatud
    if(isset($_POST["newsBtn"])) {
        if(isset($_POST["newsTitle"]) and !empty(test_input($_POST["newsTitle"]))) {
            $newsTitle = test_input($_POST["newsTitle"]);
        } else {
            $newsError = "Uudise pealkiri on sisestamata! ";
        }
        if(isset($_POST["newsEditor"]) and !empty(test_input($_POST["newsEditor"]))) {
            $newsContent = test_input($_POST["newsEditor"]);
        } else {
            $newsError .= "Uudise sisu on kirjutamata!";
        }
        // echo $newsTitle ."\n";
        // echo $newsContent;
        // saadame andmebaasi
        if(empty($newsError)) {
            echo "Salvestame!";
            $response = saveNews($newsTitle, $newsContent);
            if($response == 1) {
                $newsError = "Uudis on salvestatud";
            } else {
                $newsError = "Uudise salvestamisel tekkis tõrge!";
            }
        }
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
	<p>See leht on valminud õppetöö raames!</p>
    <!-- Post meetod kaotab url realt ära kasutaja sisestatud info/teksti. Vaikimisi on method GET -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="">Uudise pealkiri: </label>
        <br>
        <input type="text" name="newsTitle" placeholder="Uudise pealikiri" value="<?php echo $newsTitle; ?>">
        <br>
        <br>
        <label>Uudis</label>
        <br>
        <textarea name="newsEditor" placeholder="Uudise kord" rows="5" col="40"><?php echo $newsContent; ?></textarea>
        <br>
        <input type="submit" name="newsBtn" value="Salvesta uudis!">
        <span><?php echo $newsError; ?></span>
    </form>
</body>
</html>