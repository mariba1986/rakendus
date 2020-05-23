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
require("fnc_photoupload.php");
require("fnc_main.php");
require("classes/Photo.class.php");

//pildi üleslaadimine osa

//var_dump($_POST);
//var_dump($_FILES);

//$originalPhotoDir = "../../uploadOriginalPhoto/";
//$normalPhotoDir = "../../uploadNormalPhoto/";
$error = null;
$notice = null;
$imageFileType = null;
$fileUploadSizeLimit = 1048576;
$allowedFileTypes = ["image/jpeg", "image/png"];
$fileNamePrefix = "vr_";
$fileName = null;
$maxWidth = 600;
$maxHeight = 400;
$thumbSize = 100;

if (isset($_POST["photoSubmit"]) and !empty($_FILES["fileToUpload"]["tmp_name"])) {

	$photoUp = new Photo($_FILES["fileToUpload"], $fileUploadSizeLimit, $allowedFileTypes);

	if ($photoUp->error == null) {

		$photoUp->createFileName($fileNamePrefix);

		$photoUp->resizePhoto($maxWidth, $maxHeight);

		//lisan vesimärgi
		$photoUp->addWatermark("vr_watermark.png", 3, 10);

		//loen EXIF
		$photoUp->readExif();
		if ($photoUp->photoDate != null) {
			$size = 14;
			$y = 20;
			$textToImage = "Pildistatud " . $photoUp->photoDate;
			$photoUp->addText($size, $y, $textToImage);
		} else {
			echo "Pildistamiskuupäev pole teada!";
		}

		//$result = saveImgToFile($photoUp->myNewImage, $normalPhotoDir .$fileName, $imageFileType);
		//$notice .= $myPic->savePicFile($pic_upload_dir_w600 .$myPic->fileName);
		$result = $photoUp->saveImgToFile($normalPhotoDir . $photoUp->fileName);
		if ($result == 1) {
			$notice = "Vähendatud pilt laeti üles! ";
		} else {
			$error = "Vähendatud pildi salvestamisel tekkis viga!";
		}

		$photoUp->resizePhoto($thumbSize, $thumbSize);
		$result = $photoUp->saveImgToFile($thumbPhotoDir . $photoUp->fileName);
		if ($result == 1) {
			$notice .= "Pisipilt laeti üles! ";
		} else {
			$error .= " Pisipildi salvestamisel tekkis viga!";
		}

		//kui kõik hästi, salvestame info andmebaasi!!!
		if ($error == null) {
			$result = addPhotoData($photoUp->fileName, test_input($_POST["altText"]), $_POST["privacy"], $_FILES["fileToUpload"]["name"]);
			if ($result == 1) {
				$notice .= "Pildi andmed lisati andmebaasi!";
			} else {
				$error .= " Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " . $result;
			}
		}
	} else {
		//1 - pole pildifail, 2 - liiga suur, pole lubatud tüüp
		if ($photoUp->error == 1) {
			$notice = "Üleslaadimiseks valitud fail pole pilt!";
		}
		if ($photoUp->error == 2) {
			$notice = "Üleslaadimiseks valitud fail on liiga suure failimahuga!";
		}
		if ($photoUp->error == 3) {
			$notice = "Üleslaadimiseks valitud fail pole lubatud tüüpi (lubatakse vaid jpg ja png)!";
		}
	}

	unset($photoUp);
}
?>
<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>

<body>
	<h1>Fotode üleslaadimine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<p><?php echo $_SESSION["userFirstName"] . " " . $_SESSION["userLastName"] . "."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>

	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
		<label>Vali pildifail! </label><br>
		<input type="file" name="fileToUpload"><br>
		<label>Alt tekst: </label><input type="text" name="altText"><br>
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