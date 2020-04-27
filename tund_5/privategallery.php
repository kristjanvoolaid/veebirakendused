<?php
    // Sessiooni haldus
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
    
    // Photos class
    require("classes/Gallery.class.php");
    require("../../../../configuration.php");

    //Piltide väljakutsumine
    $thubnailDir = "../../uploadThumbnailPhoto/";
    $photo = new Gallery();
    // $gallery = $photo->showUserPictures();

    $page = 1;
    $limit = 5;
    $picCount = $photo->countUserPics($_SESSION['userid']);

    if(!isset($_GET["page"]) or $_GET["page"] < 1) {
        $page = 1;
    } elseif(round($_GET["page"] - 1) * $limit >= $picCount) {
        $page = ceil($picCount / $limit);
    }

    $gallery = $photo->showUserPictures($page, $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Sinu pildid</h1>
    <?php 
		if($page > 1){
			echo '<a href="?page=' .($page - 1) .'">Eelmine leht</a> | ';
		} else {
			echo "<span>Eelmine leht</span> | ";
		}
		if($page *$limit <= $picCount){
			echo '<a href="?page=' .($page + 1) .'">Järgmine leht</a>';
		} else {
			echo "<span> Järgmine leht</span>";
		}
	?>
    <div>
        <?php echo $gallery; ?>
    </div>
</body>
</html>