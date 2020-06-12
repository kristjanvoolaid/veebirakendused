<?php
    require("classes/session.class.php");
    SessionManager::sessionStart("vr20", 0, "/~kristjan.voolaid", "tigu.hk.tlu.ee");
    
    $id = $_REQUEST["photoid"];
    $rating = $_REQUEST["rating"];

    require("../../../../configuration.php");

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("INSERT INTO vr20_photoratings (photoid, userid, rating) VALUES (?,?,?)");
    echo $conn ->error;
    $stmt->bind_param("iii", $id, $_SESSION["userid"], $rating);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("SELECT AVG(rating) FROM vr20_photoratings WHERE photoid = ?");
    $stmt->bind_param("i", $id);
    $stmt->bind_result($score);
    $stmt->execute();
    $stmt->fetch();

    $stmt->close();
    $conn->close();
    echo round($score, 2);
?>