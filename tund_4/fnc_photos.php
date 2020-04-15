<?php
    function photoUpload($userdId, $fileName, $origName, $altText, $privacy) {
        $result = null;
        $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("INSERT INTO vr20_photos (userid, filename, origname, alttext, privacy)
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $userdId, $fileName, $origName, $altText, $privacy);
        
        if($stmt->execute()) {
            $result = 1;
        } else {
            $result = 0;
            echo $stmt->error;
        }

        $stmt->close();
        $conn->close();

        return $result;
    }

    function changePhotoSize($picture) {
        
        $maxWidth = 600;
        $maxHeight = 400;
        // $myTempImage = null;

        $imageW = imagesx($picture);
        $imageH = imagesy($picture);

        if($imageW / $maxWidth > $imageH / $maxHeight) {
            $imageSizeRatio = $imageW / $maxHeight;
        } else {
            $imageSizeRatio = $imageH / $maxHeight;
        }

        $newW = round($imageW / $imageSizeRatio);
        $newH = round($imageH / $imageSizeRatio);
        // Loome järgmise uue ajutise objekti
        $myNewImage = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($myNewImage, $picture, 0, 0, 0, 0, $newW, $newH, $imageW, $imageH);

        return $myNewImage;
    }

    function makeThumbnail($picture) {

        $maxWidth = 100;
        $maxHeight = 100;
        $myTempImage = null;

        $imageW = imagesx($picture);
        $imageH = imagesy($picture);

        if($imageW / $maxWidth > $imageH / $maxHeight) {
            $imageSizeRatio = $imageW / $maxHeight;
        } else {
            $imageSizeRatio = $imageH / $maxHeight;
        }

        $newW = round($imageW / $imageSizeRatio);
        $newH = round($imageH / $imageSizeRatio);
        // Loome järgmise uue ajutise objekti
        $myNewImage = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($myNewImage, $picture, 0, 0, 0, 0, $newW, $newH, $imageW, $imageH);

        return $myNewImage;
    }

?>