<?php

    function showUserPictures($userid, $page, $limit) {
        $response = null;
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT vr20_photos.userid, vr20_photos.filename, vr20_photos.alttext, vr20_users.id, vr20_users.firstname, vr20_users.lastname
                                FROM vr20_photos
                                JOIN vr20_users 
                                ON vr20_photos.userid = vr20_users.id 
                                WHERE vr20_photos.userid=?
                                LIMIT ?,?");
        $stmt->bind_param("iii", $userid, $page, $limit);
        $stmt->bind_result($userid, $fileNameFromDB, $altTextFromDB, $idFromDB, $firstNameFromDB, $lastNameFromDB);
        $stmt->execute();
            
        while($stmt->fetch()) {
            $response .= '<div class="grid-item"><a href="' . $GLOBALS["originalPhotoDir"] . $fileNameFromDB . '">' . '<img src="' . $GLOBALS["thumbPhotoDir"] . $fileNameFromDB . '" alt="' . $altTextFromDB . '">' . "</a> \t";
            $response .= '<p>' . $firstNameFromDB . " " . $lastNameFromDB . '</p></div>';
        }

        if($response == null) {
            $response = "Pildid puuduvad!";
        }

        $stmt->close();
        $conn->close();
        return $response; 
        }

    function showPublicPictures($privacy, $page, $limit) {
        $response = null;
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT vr20_photos.userid, vr20_photos.filename, vr20_photos.alttext, vr20_photos.privacy, vr20_users.id, vr20_users.firstname, vr20_users.lastname 
                                FROM vr20_photos 
                                JOIN vr20_users 
                                ON vr20_photos.privacy = ?
                                WHERE deleted is null
                                LIMIT ?,?");
        $stmt->bind_param("iii", $privacy, $page, $limit);
        $stmt->bind_result($userid, $fileNameFromDB, $altTextFromDB, $privacyFromDB, $idFromDB, $firstNameFromDB, $lastNameFromDB);
        $stmt->execute();

        while($stmt->fetch()) {
            $response .= '<div class="grid-item"><a href="' . $GLOBALS["originalPhotoDir"] . $fileNameFromDB . '">' . '<img src="' . $GLOBALS["thumbPhotoDir"] . $fileNameFromDB . '" alt="' . $altTextFromDB . '">' . "</a> \t";
            $response .= '<p>' . $firstNameFromDB . " " . $lastNameFromDB . '</p></div>';
        }

        if($response == null) {
            $response = "Pildid puuduvad!";
        }

        $stmt->close();
        $conn->close();
        return $response; 
        }

        function countPics($privacy){
            $notice = null;
            $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
            $stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE privacy<=? AND deleted IS NULL");
            echo $conn->error;
            $stmt->bind_param("i", $privacy);
            $stmt->bind_result($count);
            $stmt->execute();
            $stmt->fetch();
            $notice = $count;
            
            $stmt->close();
            $conn->close();
            return $notice;
        }

?>