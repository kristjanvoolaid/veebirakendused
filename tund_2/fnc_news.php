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
        $stmt = $conn->prepare("SELECT title, content FROM vr20_news");
        echo $conn->error;
        $stmt->bind_result($titleFromDB, $contentFromDB);
        $stmt->execute();
        // if($stmt->fetch())
        // <h2>Uudise pealkiri</h2>
        // <p>Uudis ise</p>
        while ($stmt->fetch()) {
            $response .= "<h2>" . $titleFromDB . "</h2> \n";
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