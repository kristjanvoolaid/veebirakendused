<?php
    require("classes/session.class.php");
    SessionManager::sessionStart("vr20", 0, "/~kristjan.voolaid", "tigu.hk.tlu.ee");
    
    $id = $_REQUEST["photoid"];

    require("../../../../configuration.php");

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $sql = "UPDATE vr20_photos SET deleted = now() WHERE id = $id";
    $conn->query($sql);
    $conn->close();

    echo "Kustutatud!";
?>