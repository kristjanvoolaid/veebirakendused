<?php

    // Sessiooni kasutamine
    // session_start();

    

    function signUp($name, $surname, $email, $gender, $birthDate, $password) {
        $notice = null;
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt_email = $conn->prepare("SELECT id FROM vr20_users WHERE email='$email'");
        $stmt_email->bind_result($id);
        $stmt_email->execute();
        if($stmt_email->fetch()) {
            $notice = " Selline kasutaja on juba olemas! ";

            $stmt_email->close();
            $conn->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO vr20_users (firstname, lastname, birthdate, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
            echo $conn->error;

            // Krüpteerin parooli
            $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
            $pwdhash = password_hash($password, PASSWORD_BCRYPT);

            $stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);

            if($stmt->execute()) {
                $notice = "ok";
            } else {
                $notice = $stmt->error;
            }
    
            $stmt->close();
            $conn->close();
        }
        return $notice;
    }

    function signIn($email, $password) {

        $notice = null;
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt_password = $conn->prepare("SELECT password FROM vr20_users");
        $stmt_password->bind_result($checkPasswordFromDB);
        $stmt_password->execute();
        $password_check = null; 

        while($stmt_password->fetch()) {
            if(password_verify($password, $checkPasswordFromDB)) {
                $password_check = 'ok';
                $stmt_password->close();
            break;
            } else {
                $password_check = 'not ok';
                $stmt_password->close();
            break;
            }
        }

        if($password_check == 'ok') {
            $stmt = $conn->prepare("SELECT password, id, firstname, lastname FROM vr20_users");
            $stmt->bind_result($passwordFromDB, $idFromDB, $firstnameFromDB, $lastnameFromDB);
            echo $conn->error;

            $stmt->execute();
            if($stmt->fetch()) {
                if(password_verify($password, $passwordFromDB)) {
                    $_SESSION["userid"] = $idFromDB;
                    $_SESSION["userFirstName"] = $firstnameFromDB;
                    $_SESSION["userLastName"] = $lastnameFromDB;

                    $stmt->close();
                    $conn->close();
                    header("Location: home.php");
                    exit();
                } else {
                    $notice = "Vale parool!";
                    }
            } else {
                    $notice = "Sellist kasutajat ei (" .$email .") ei leitud!";
                }
        } else {
            $notice = "Sellise parooliga kasutajat ei ole!";
        }

        return $notice;
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

?>