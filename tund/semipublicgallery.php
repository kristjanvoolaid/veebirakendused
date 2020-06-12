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
    require("../../../../configuration.php");
    require("fnc_gallery.php");

    //Menubar
    include("menubar.php");

    //Piltide väljakutsumine
    $thubnailDir = "../../uploadThumbnailPhoto/";

    $page = 1;
    $limit = 10;
    $picCount = countPics(2);

    if(!isset($_GET["page"]) or $_GET["page"] < 1){
        $page = 1;
      } elseif(round($_GET["page"] - 1) * $limit >= $picCount){
        $page = ceil($picCount / $limit);
      }	else {
        $page = $_GET["page"];
      }

    $gallery = showPublicPictures(2, $page, $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="modal.css">
    <link rel="stylesheet" href="style.css">
    <script src="javascript/modal.js" defer></script>

</head>
<body>

<div id="modalArea" class="modalArea">
		<!--Sulgemisnupp-->
		<span id="modalClose" class="modalClose">&times;</span>
		<!--pildikoht-->
		<div class="modalHorizontal">
			<div class="modalVertical">
			<p id="modalCaption"></p>
				<img src="empty.png" id="modalImg" class="modalImg" alt="galeriipilt">
				
				<br>
				<div id="rating" class="modalRating">
					<label><input id="rate1" name="rating" type="radio" value="1">1</label>
					<label><input id="rate2" name="rating" type="radio" value="2">2</label>
					<label><input id="rate3" name="rating" type="radio" value="3">3</label>
					<label><input id="rate4" name="rating" type="radio" value="4">4</label>
					<label><input id="rate5" name="rating" type="radio" value="5">5</label>
					<button id="storeRating">Salvesta hinnang!</button>
					<br>
					<p id="avgRating"></p>
				</div>
				
			</div>
		</div>
	  </div>
    <h1>Avalikud pildid</h1>
    <?php 
		if($page > 1){
			echo '<a href="?page=' .($page - 1) .'">Eelmine leht</a> | ';
		} else {
			echo "<span>Eelmine leht</span> | ";
		}
		if(($page + 1) * $limit <= $picCount){
			echo '<a href="?page=' .($page + 1) .'">Järgmine leht</a>';
		} else {
			echo "<span> Järgmine leht</span>";
		}
	?>

    <div class="gallery" id="gallery">
        <?php 
            echo $gallery; 
        ?>
    </div>
</body>
</html>