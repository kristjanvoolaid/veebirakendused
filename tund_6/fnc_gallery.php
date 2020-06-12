<?php

    function showUserPictures($userid, $page, $limit) {
        $response = null;
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT vr20_photos.id, vr20_users.firstname, vr20_users.lastname, vr20_photos.filename, vr20_photos.alttext, AVG(vr20_photoratings.rating) as AvgValue
                                FROM vr20_photos JOIN vr20_users
                                ON vr20_photos.userid = vr20_users.id
                                LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
                                WHERE vr20_photos.userid <= ?
                                AND deleted IS NULL GROUP BY vr20_photos.id DESC LIMIT ?, ?");
        $stmt->bind_param("iii", $userid, $page, $limit);
        $stmt->bind_result($photoId, $firstNameFromDB, $lastNameFromDB, $fileNameFromDB, $altTextFromDB, $ratingFromDB);
        $stmt->execute();
            
        while($stmt->fetch()) {
            $response = '<div class="galleryelement">' ."\n";
			$response .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$fileNameFromDB .'" alt="'.$altTextFromDB .'" class="thumb" data-fn="' .$fileNameFromDB .'" data-id="'. $photoId . '">' ."\n \t \t";
            $response .= "<p>" .$firstNameFromDB ." " .$lastNameFromDB ."</p> \n \t \t";
            $response.= "<p> Hinne : " . $ratingFromDB . '<p>';
            $response .= "<p>" .$altTextFromDB . "</p>";
            $response .= "</div> \n \t \t";
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
        $stmt = $conn->prepare("SELECT vr20_photos.id, vr20_users.firstname, vr20_users.lastname, vr20_photos.filename, vr20_photos.alttext, AVG(vr20_photoratings.rating) as AvgValue
                                        FROM vr20_photos JOIN vr20_users
                                        ON vr20_photos.userid = vr20_users.id
                                        LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
                                        WHERE vr20_photos.privacy <= ?
                                        AND deleted IS NULL GROUP BY vr20_photos.id DESC LIMIT ?, ?");
        $stmt->bind_param("iii", $privacy, $page, $limit);
        $stmt->bind_result($photoId, $firstNameFromDB, $lastNameFromDB, $fileNameFromDB, $altTextFromDB, $ratingFromDB);
        $stmt->execute();

        while($stmt->fetch()) {
            $response = '<div class="galleryelement">' ."\n";
			$response .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$fileNameFromDB .'" alt="'.$altTextFromDB .'" class="thumb" data-fn="' .$fileNameFromDB .'" data-id="'. $photoId . '">' ."\n \t \t";
            $response .= "<p>" .$firstNameFromDB ." " .$lastNameFromDB ."</p> \n \t \t";
            $response.= "<p> Hinne : " . $ratingFromDB . '<p>';
            $response .= "<p>" .$altTextFromDB . "</p>";
            $response .= "</div> \n \t \t";
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

        function countUsersPictures($userid) {
            $notice = null;
            $privacy = 3;
            $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
            $stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE userid = ? AND privacy <= ? AND deleted IS NULL ");
            echo $conn->error;
            $stmt->bind_param("ii", $userid, $privacy);
            $stmt->bind_result($count);
            $stmt->execute();
            $stmt->fetch();
            $notice = $count;

            $stmt->close();
            $conn->close();

            return $notice;

        }

        function showUsersPrivatePictures($userid, $page, $limit) {
            $response = null;
            $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
            $stmt = $conn->prepare("SELECT vr20_photos.id, vr20_photos.userid, vr20_photos.filename, vr20_photos.alttext, vr20_users.id, vr20_users.firstname, vr20_users.lastname
                                    FROM vr20_photos
                                    JOIN vr20_users 
                                    ON vr20_photos.userid = vr20_users.id 
                                    WHERE vr20_photos.userid=? AND deleted IS NULL
                                    LIMIT ?,?");
            $stmt->bind_param("iii", $userid, $page, $limit);
            $stmt->bind_result($photoId, $userid, $fileNameFromDB, $altTextFromDB, $idFromDB, $firstNameFromDB, $lastNameFromDB);
            $stmt->execute();
                
            while($stmt->fetch()) {                
                $response = '<div class="galleryelement">' ."\n";
                $response .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$fileNameFromDB .'" alt="'.$altTextFromDB .'" class="thumb" data-fn="' .$fileNameFromDB .'" data-id="'. $photoId . '">' ."\n \t \t";
                $response .= "<p>" .$firstNameFromDB ." " .$lastNameFromDB ."</p> \n \t \t";
                // $response.= "<p> Hinne : " . $ratingFromDB . '<p>';
                // $response .= '<button id="deletePic">KUSTUTA</button>';
                $response .= "<p>" .$altTextFromDB . "</p>";
                // $response .= '<p id="deleteInf"></p>';
                $response .= "</div> \n \t \t";
            }

            if($response == null) {
                $response = "Pildid puuduvad!";
            }
    
            $stmt->close();
            $conn->close();
            return $response; 
        }    
?>