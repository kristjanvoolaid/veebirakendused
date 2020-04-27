<?php

    class Gallery {

        function __construct() {
        }

        public function showUserPictures($page, $limit) {
            $userid = $_SESSION['userid'];
            $response = null;
            $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
            $stmt = $conn->prepare("SELECT userid, filename, alttext FROM vr20_photos WHERE userid=? LIMIT ?,?");
            $stmt->bind_param("iii", $userid, $page, $limit);
            $stmt->bind_result($userid, $fileName, $altText);
            $stmt->execute();
            
            while($stmt->fetch()) {
                $response .= '<a href="' . $GLOBALS["originalPhotoDir"] . $fileName . '">' . '<img src="' . $GLOBALS["thumbPhotoDir"] . $fileName . '" alt="' . $altText . '">' . "</a> \t";
                $response .=  '<small>' . $_SESSION['userFirstName'] . " " . $_SESSION['userLastName'] . "</small> \n";   
            }

            if($response == null) {
                $response = "Pildid puuduvad!";
            }

            $stmt->close();
            $conn->close();
            return $response; 
        }

        public function showPublicPictures($privacy, $page, $limit) {
            // $privacy = 2;
            $response = null;
            $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
            $stmt = $conn->prepare("SELECT userid, filename, alttext FROM vr20_photos WHERE privacy=? LIMIT ?,?");
            $stmt->bind_param("iii", $privacy, $page, $limit);
            $stmt->bind_result($userid, $fileName, $altText);
            $stmt->execute();

            while($stmt->fetch()) {
                $response .= '<a href="' . $GLOBALS["originalPhotoDir"] . $fileName . '">' . '<img src="' . $GLOBALS["thumbPhotoDir"] . $fileName . '" alt="' . $altText . '">' . "</a> \n \t";
                $response .=  '<small>' . $_SESSION['userFirstName'] . " " . $_SESSION['userLastName'] . "</small> \t";
            }

            if($response == null) {
                $response = "Pildid puuduvad!";
            }

            $stmt->close();
            $conn->close();
            return $response; 
        }

        public function countPics($privacy){
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

        public function countUserPics($userid){
            $notice = null;
            $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
            $stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE userid<=? AND deleted IS NULL");
            echo $conn->error;
            $stmt->bind_param("i", $userid);
            $stmt->bind_result($count);
            $stmt->execute();
            $stmt->fetch();
            $notice = $count;
            
            $stmt->close();
            $conn->close();
            return $notice;
        }
        
    }
?>