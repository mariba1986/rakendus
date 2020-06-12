<?php

//sessiooni käivitamine või kasutamine
//session_start();
//var_dump($_SESSION);
require("classes/Session.class.php");
require("../../../../configuration.php");
require("fnc_gallery.php");
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


$page = 1; //vaikimisi määran lehe numbriks 1 (see on vajalik näiteks siis, kui esimest korda galerii avatakse ja lehtedega pole veel tegeletud)
$limit = 5; //mitu pilti ühele lehele soovin mahutada. Reaalelus oleks normaalne palju suurem number, näiteks 30 jne
$picCount = countPrivatePics(); //küsin kõigi näidatavate piltide arvu, et teada, palju lehekülgi üldse olla võiks. Parameetriks piltide privaatsus. Funktsioon ise näitena allpool.

//kui nüüd tuli ka lehe aadressis GET meetodil parameeter page, siis kontrollin, kas see on reaalne ja, kui pole, siis pane jõuga lehe numbriks 1 või viimase võimaliku lehe numbri
if (!isset($_GET["page"]) or $_GET["page"] < 1) {
	$page = 1;
} elseif (round($_GET["page"] - 1) * $limit >= $picCount) {
	$page = ceil($picCount / $limit);
} else {
	$page = $_GET["page"];
}

$galleryHTML = readgalleryImages($page, $limit); //nõutud piltide jaoks vajaliku HTML koodi loomise funktsioon (loeb andmebaasist nõutud lehe pildid ja koostab html-i). Parameetriteks piltide privaatsus, lehe number ja lehele paigutatavate piltide hulk. 



//$privateThumbnails = readAllMyPictureThumbsPage($page, $limit);
?>
<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-with , inital-scale=1.0">
	<title>Veebirakendused ja nende loomine 2020</title>
	<link rel="stylesheet" type="text/css" href="style/gallery.css">
</head>

<body>
	<h1>Minu lisatud pildid</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<p><?php echo $_SESSION["userFirstName"] . " " . $_SESSION["userLastName"] . "."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>
	<?php
	if ($page > 1) {
		echo '<a href="?page=' . ($page - 1) . '">Eelmine leht</a> | ';
	} else {
		echo "<span>Eelmine leht</span> | ";
	}
	if (($page + 1) * $limit <= $picCount) {
		echo '<a href="?page=' . ($page + 1) . '">Järgmine leht</a>';
	} else {
		echo "<span> Järgmine leht</span>";
	}
	?>
	<div class="gallery" id="gallery">
		<?php echo $galleryHTML; ?>
	</div>
	<hr>
</body>

</html>