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

    $logsHtml = readSchoolLogs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>


<style>
    table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

    td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

    tr:nth-child(even) {
  background-color: #dddddd;
}

</style>
<body>
    <br>
    <br>
    <br>
    <table>
        <tr>
            <th>Õppeaine</th>
            <th>Tegevus</th>
            <th>Kulunud aeg</th>
        </tr>
        <?php echo $logsHtml; ?>

    </table>
</body>
</html>