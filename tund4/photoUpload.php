<?php
require("classes/Session.class.php");
SessionManager::sessionStart("vr20", 0, "/~maris.riba/", "tigu.hk.tlu.ee");

//kas pole sisseloginud
if (!isset($_SESSION["userid"])) {
    //jõuga avalehele
    header("Location: page.php");
}

//login välja
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: page.php");
}

require("../../../../configuration.php");
//pildi üleslaadimise osa
//var_dump($_POST); //siin on kogu muu kraam
//var_dump($_FILES); / siin on üleslaetavad failid
$originalPhotoDir = "../../uploadOriginalPhoto/";
$normalPhotoDir = "../../uploadNormalPhoto/";
$error = null;
$notice = null;
$imageFileType = null;
$fileUploadSizeLimit = 1048576;
$fileNamePrefix = "vr_";
$fileName = null;
$maxWidth = 600;
$maxHeight = 400;

if (isset($_POST["photoSubmit"])) {
    //kontrollime kas tegu on üldse pildiga:
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"], $originalTarget);
    if ($check !== false) {
        //failitüübi väljaselgitamine ja sobivuse kontroll
        if ($check["mime"] == "image/jpeg") {
            $imageFileType = "jpg";
        } elseif ($check["mime"] == "image/png") {
        } else {
            $error = "ainult jpg/png on lubatud";
        }
    } else {
        $error = "Valitud fail ei ole pilt";
    }
    //kontrollime et ega pilt liiga suur ei ole
    if ($_FILES["fileToUpload"]["size"] > $fileUploadSizeLimit) {
        $error .= "valitud fail on liiga suur";
    }
    //loome oma failinime
    $timestamp = microtime(1) * 10000;
    $fileName = $fileNamePrefix . $timestamp . "." . $imageFileType;

    // $originalTarget = $originalPhotoDir . $_FILES["fileToUpload"]["name"];
    $originalTarget = $originalPhotoDir . $fileName;

    //kontrollime kas selline fail juba on serveris
    if (file_exists($originalTarget)) {
        $error . "selline fail juba on";
    }


    //kui vigu pole
    if ($error == null) {
        // teen pildi väiksemaks
        if ($imageFileType == "jpg") {
            $myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
        }
        if ($imageFileType == "png") {
            $myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
        }

        $imageW = imagesx($myTempImage);
        $imageH = imagesy($myTempImage);
        if ($imageW / $maxWidth > $imageH / $maxHeight) {
            $imageSizeRatio = $imageW / $maxWidth;
        } else {
            $imageSizeRatio = $imageH / $maxHeight;
        }
        $newW = round($imageW / $imageSizeRatio);
        $newH = round($imageH / $imageSizeRatio);
        //loome uue ajutise pildiobjekti
        $myNewImage = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($myNewImage, $myTempImage, 0, 0, 0, 0, $newW, $newH, $imageW, $imageH);
        //salvestame vähendatud kujutise faili
        if ($imageFileType == "jpg") {
            if (imagejpeg($myNewImage, $normalPhotoDir . $fileName, 90)) { //see imagejpg käsklus muudab pildi failiks
                $notice = "vähendatud pilt laeti üles!";
            } else {
                $error = "vähendatud pildi laadimisel tekkis viga";
            }
        }
        if ($imageFileType == "png") {
            if (imagepng($myNewImage, $normalPhotoDir . $fileName, 6)) { //see käsklus muudab pildi png failiks
                $notice = "vähendatud pilt laeti üles!";
            } else {
                $error = "vähendatud pildi laadimisel tekkis viga";
            }
        }

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)) {
            $notice .= "Originaalpilt laeti üles!";
        } else {
            $error .= "Pildi üleslaadimisel tekkis viga";
        }
        imagedestroy($myTempImage); //et mitte üleliigselt koormata serverit, viskab välja
        imagedestroy($myNewImage);

        //salvestame selle asja andmebaasi
        
        false
        false

    } //kui vigu pole lõppeb siit
}

?>
<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="utf-8">
    <title>Veebirakendused ja nende loomine 2020</title>
</head>

<body>
    <h1>Fotode üleaslaadimine</h1>
    <p>See leht on valminud õppetöö raames!</p>
    <p><?php echo $_SESSION["userFirstName"] . " " . $_SESSION["userLastName"] . "."; ?> Logi <a href="?logout=1">välja</a>!</p>
    <p>Tagasi <a href="home.php">avalehele</a>!</p>
    <hr>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label>Vali pildifail </label><br>
        <input type="file" name="fileToUpload"><br>
        <label>Alt tekst</label><input type="text" , name="altText"><br>
        <label>Privaatsus</label><br>
        <label for="priv1">Privaatne</label><input id="priv1" type="radio" name="privacy" value="3" checked><br>
        <label for="priv2">Sisseloginud kasutajatele</label><input id="priv2" type="radio" name="privacy" value="2"><br>
        <label for="priv3">Avalik</label><input id="priv3" type="radio" name="privacy" value="1"><br>
        <input type="submit" name="photoSubmit" value="Lae valitud pilt üles!">
        <span><?php echo $error;
                echo $notice; ?></span>
    </form>

    <br>
    <hr>
</body>

</html>