<?php 
    function saveNews($newsTitle, $newsContent) {
        $response = null;
        // Loon andmebaasiühenduse
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        // Valmistan ette SQL päringu
        $stmt = $conn->prepare("INSERT INTO vr20_news (userid, title, content) VALUES (?, ?, ?)");
        echo $conn->error;
        // Seon pärginguga tegelikud andmed
        $userid = 1;
        $limit = 2;
        // i - integer, s - string, d - decimal
        $stmt->bind_param("iss", $userid, $newsTitle, $newsContent);
        if($stmt->execute()) {
            $response = 1;                
        } else {
            $response = 0;
            echo $stmt->error;
        }
        // Sulgen päringu ja andmebaasiühenduse
        $stmt->close();
        $conn->close();
        return $response;
    }

    function readNews() {
        $response = null;
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT title, content, created FROM vr20_news WHERE deleted IS NULL ORDER BY id DESC LIMIT 3");
        echo $conn->error;
        $stmt->bind_result($titleFromDB, $contentFromDB, $dateFromDB);
        $stmt->execute();
        // if($stmt->fetch())
        // <h2>Uudise pealkiri</h2>
        // <p>Uudis ise</p>
        while ($stmt->fetch()) {
            $response .= "<h2>" . $titleFromDB . "</h2> \n";
            $response .= "<small>Lisatud: " . $dateFromDB . "</small> \n";
            $response .= "<p>" . $contentFromDB . "</p> \n";
        }
        if($response == null) {
            $response = "<p>Kahjuks uudised puuduvad</p> \n";
        }
        // Sulgen andmebaasiühenduse
        $stmt->close();
        $conn->close();
        return $response;
    }

    function saveSchoolLog($courseType, $activityType, $elapsedTime) {
        $response = null;
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("INSERT INTO vr20_study_log (course, activity, time) VALUES (?, ?, ?)");
        echo $conn->error;
        $stmt->bind_param("iid", $courseType, $activityType, $elapsedTime);
        
        if($stmt->execute()) {
            $response = 1;
        } else {
            $response = 0;
            echo $stmt->error;
        }

        $stmt->close();
        $conn->close();
        return $response;
    }

    function readSchoolLogs() {

        $response = null;
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        mysqli_set_charset($conn, "utf8");
        $stmt = $conn->prepare("SELECT vr20_study_log.id, vr20_oppeained.id, vr20_oppeained.course, vr20_study_log.course, vr20_study_log.time, vr20_study_log.activity, vr20_oppetegevused.id, vr20_oppetegevused.oppetegevus 
                                FROM vr20_study_log 
                                JOIN vr20_oppeained ON vr20_study_log.course = vr20_oppeained.id 
                                JOIN vr20_oppetegevused ON vr20_study_log.activity = vr20_oppetegevused.id
                                ORDER BY vr20_study_log.id DESC");

        echo $conn->error;
        $stmt->bind_result($idFromDB, $idCoursesFromDB, $courseNameFromDB, $courseIdFromDB, $elapsedTimeFromDB, $activityIdFromDB, $idActivityFromDB, $activityNameFromDB);
        $stmt->execute();

        while($stmt->fetch()) {
            
            $response .= "<tr><td>" . $courseNameFromDB . "</td><td>" . $activityNameFromDB . "</td><td>" . $elapsedTimeFromDB . " tundi</td></td>";
        }

        if($response == null) {
            echo "<p>Kahjuks andmed puuduvad!</p>";
        }
       
        // Sulgen andmebaasiühenduse
        $stmt->close();
        $conn->close();
        return $response;
    }