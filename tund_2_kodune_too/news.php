<?php
    require ("../../../../configuration.php");
    require ("fnc_news.php");

    $newsHtml = readNews();
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
    <div>
        <?php echo $newsHtml; ?>
    </div>
</body>
</html>