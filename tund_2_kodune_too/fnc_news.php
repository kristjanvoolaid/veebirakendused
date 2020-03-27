<?php 
    function saveNews($newsTitle, $newsContent) {
        $response = null;
        // Loon andmebaasiühenduse
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        // Valmistan ette SQL päringu
        $stmt = $conn->prepare("INSERT INTO vr20_news (userid, title, content) VALUES (?, ?, ?) LIMIT ?");
        echo $conn->error;
        // Seon pärginguga tegelikud andmed
        $userid = 1;
        $limit = 2;
        // i - integer, s - string, d - decimal
        $stmt->bind_param("iss", $userid, $newsTitle, $newsContent, $limit);
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
        $stmt = $conn->prepare("SELECT course, activity, time FROM vr20_study_log ORDER BY id DESC");
        echo $conn->error;
        $stmt->bind_result($courseFromDB, $activityFromDB, $timeFromDB);
        $stmt->execute();

        while($stmt->fetch()) {
           $courses = array("Test",
                            "Üld- ja sotsiaalpsühholoogia",
                            "Veebirakendused ja nende loomine", 
                            "Programmeerimine I", 
                            "Disaini alused",
                            "Videomängude disain",
                            "Andmebaasid",
                            "Sissejuhatus tarkvaraarendusse",
                            "Sissejuhatus informaatikasse",
                        );
            
            $activities = array("Test",
                                "Iseseisva materjali loomine",
                                "Koduste ülesannete lahendamine",
                                "Kordamine",
                                "Rühmatöö",

                    );
            
           foreach($courses as $course => $value) {
               if($courseFromDB == $course) {
                   $courseFromDB = $value;
               }
           }

           foreach($activities as $acivity => $value) {
               if($activityFromDB == $acivity) {
                   $activityFromDB = $value;
               }
           }

        $response .= "<tr><td>" . $courseFromDB . "</td><td>" . $activityFromDB . "</td><td>" . $timeFromDB . " tundi</td></td>";
        
        }
        if($response == null) {
            echo "<p>Kahjuks andmed puuduvad!</p>";
        }

        // Sulgen andmebaasiühenduse
        $stmt->close();
        $conn->close();
        return $response;
    }