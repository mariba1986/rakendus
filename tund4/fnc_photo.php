<?php

function savePhotoData($id, $userid, $fileName, $origName, $altText, $privacy)
{
    $response = null;
    //loon andmebaasiühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    //valmistan ette SQL päringu
    $stmt = $conn->prepare("INSERT INTO vr20_photos (id, userid, filename, origname, alttext, privacy) VALUES (?, ?, ?, ?, ?, ?)");
    echo $conn->error;
    //seon päringuga tegelikud andmed
    $stmt->bind_param("iisssi", $id, $userid, $fileName, $origName, $altText, $privacy);
    //i -integer  s - string d - decimal
    if ($stmt->execute()) {
        $response = 1;
    } else {
        $response = 0;
        echo $stmt->error;
    }
    //sulgen päringu ja andmebaasiühenduse
    $stmt->close();
    $conn->close();
    return $response;
}

function resizePhoto($src, $w, $h, $keepOrigProportion = true)
{
    $imageW = imagesx($src);
    $imageH = imagesy($src);
    $newW = $w;
    $newH = $h;
    $cutX = 0;
    $cutY = 0;
    $cutSizeW = $imageW;
    $cutSizeH = $imageH;

    if ($w == $h) {
        if ($imageW > $imageH) {
            $cutSizeW = $imageH;
            $cutX = round(($imageW - $cutSizeW) / 2);
        } else {
            $cutSizeH = $imageW;
            $cutY = round(($imageH - $cutSizeH) / 2);
        }
    } elseif ($keepOrigProportion) { //kui tuleb originaaproportsioone säilitada
        if ($imageW / $w > $imageH / $h) {
            $newH = round($imageH / ($imageW / $w));
        } else {
            $newW = round($imageW / ($imageH / $h));
        }
    } else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
        if ($imageW / $w < $imageH / $h) {
            $cutSizeH = round($imageW / $w * $h);
            $cutY = round(($imageH - $cutSizeH) / 2);
        } else {
            $cutSizeW = round($imageH / $h * $w);
            $cutX = round(($imageW - $cutSizeW) / 2);
        }
    }

    //loome uue ajutise pildiobjekti
    $myNewImage = imagecreatetruecolor($newW, $newH);
    //kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
    imagesavealpha($myNewImage, true);
    $transColor = imagecolorallocatealpha($myNewImage, 0, 0, 0, 127);
    imagefill($myNewImage, 0, 0, $transColor);
    imagecopyresampled($myNewImage, $src, 0, 0, $cutX, $cutY, $newW, $newH, $cutSizeW, $cutSizeH);
    return $myNewImage;
}

function saveImgToFile($myNewImage, $target, $imageFileType)
{
    $notice = null;
    if ($imageFileType == "jpg") {
        if (imagejpeg($myNewImage, $target, 90)) {
            $notice = 1;
        } else {
            $notice = 0;
        }
    }
    if ($imageFileType == "png") {
        if (imagepng($myNewImage, $target, 6)) {
            $notice = 1;
        } else {
            $notice = 0;
        }
    }
    return $notice;
}
