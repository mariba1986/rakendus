<?php
function readAllMyPictureThumbs()
{
	$privacy = 3;
	$finalHTML = "";
	$html = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE userid=? AND deleted IS NULL");
	echo $conn->error;
	$stmt->bind_param("i", $_SESSION["userid"]);
	$stmt->bind_result($filenameFromDb, $altFromDb);
	$stmt->execute();
	while ($stmt->fetch()) {
		$html .= '<a href="' . $GLOBALS["normalPhotoDir"] . $filenameFromDb . '" target="_blank"><img src="' . $GLOBALS["thumbPhotoDir"] . $filenameFromDb . '" alt="' . $altFromDb . '"></a>' . "\n \t \t";
	}
	if ($html != "") {
		$finalHTML = $html;
	} else {
		$finalHTML = "<p>Kahjuks pilte pole!</p>";
	}

	$stmt->close();
	$conn->close();
	return $finalHTML;
}

function readAllSemiPublicPictureThumbs()
{
	$privacy = 2;
	$finalHTML = "";
	$html = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE privacy<=? AND deleted IS NULL");
	echo $conn->error;
	$stmt->bind_param("i", $privacy);
	$stmt->bind_result($filenameFromDb, $altFromDb);
	$stmt->execute();
	while ($stmt->fetch()) {
		$html .= '<a href="' . $GLOBALS["normalPhotoDir"] . $filenameFromDb . '" target="_blank"><img src="' . $GLOBALS["thumbPhotoDir"] . $filenameFromDb . '" alt="' . $altFromDb . '"></a>' . "\n \t \t";
	}
	if ($html != "") {
		$finalHTML = $html;
	} else {
		$finalHTML = "<p>Kahjuks pilte pole!</p>";
	}
	$stmt->close();
	$conn->close();
	return $finalHTML;
}

function countPics()
{
	$privacy = 2;
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
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

function countPrivatePics()
{
	$notice = null;
	$privacy = 3;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT COUNT(id) FROM vr20_photos WHERE privacy<=? AND userid = ? AND deleted IS NULL");
	echo $conn->error;
	$stmt->bind_param("ii", $privacy, $_SESSION["userid"]);
	$stmt->bind_result($count);
	$stmt->execute();
	$stmt->fetch();
	$notice = $count;

	$stmt->close();
	$conn->close();
	return $notice;
}

function readAllMyPictureThumbsPage($page, $limit)
{
	$privacy = 3;
	$skip = $page * $limit;
	$finalHTML = "";
	$html = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE userid=? AND deleted IS NULL LIMIT ?,?");
	echo $conn->error;
	$stmt->bind_param("iii", $_SESSION["userid"], $skip, $limit);
	$stmt->bind_result($filenameFromDb, $altFromDb);
	$stmt->execute();
	while ($stmt->fetch()) {
		$html .= '<div class="galleryelement">' . "\n";
		//$html .= '<a href="' .$GLOBALS["normalPhotoDir"] .$filenameFromDb .'" target="_blank"><img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" alt="'.$altFromDb .'" class="thumb"></a>' ."\n \t \t";
		$html .= '<img src="' . $GLOBALS["thumbPhotoDir"] . $filenameFromDb . '" alt="' . $altFromDb . '" class="thumb" data-fn="' . $filenameFromDb . '">' . "\n \t \t";
		$html .= "</div> \n \t \t";
	}
	if ($html != "") {
		$finalHTML = $html;
	} else {
		$finalHTML = "<p>Kahjuks pilte pole!</p>";
	}

	$stmt->close();
	$conn->close();
	return $finalHTML;
}
function readGalleryImages($page, $limit)
{
	$privacy = 2;
	$finalHTML = "";
	$html = "";
	$lower_bound = ($page - 1) * $limit;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE privacy<=? AND deleted IS NULL LIMIT ?,?");
	echo $conn->error;
	$stmt->bind_param("iii", $privacy, $lower_bound, $limit);
	$stmt->bind_result($filenameFromDb, $altFromDb);
	$stmt->execute();
	while ($stmt->fetch()) {
		$html .= '<a href="' . $GLOBALS["normalPhotoDir"] . $filenameFromDb . '" target="_blank"><img src="' . $GLOBALS["thumbPhotoDir"] . $filenameFromDb . '" alt="' . $altFromDb . '"></a>' . "\n \t \t";
	}
	if ($html != "") {
		$finalHTML = $html;
	} else {
		$finalHTML = "<p>Kahjuks pilte pole!</p>";
	}
	$stmt->close();
	$conn->close();
	return $finalHTML;
}

function readAllSemiPublicPictureThumbsPage($page, $limit)
{
	$privacy = 3;
	$skip = $page * $limit;
	$finalHTML = "";
	$html = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//$stmt = $conn->prepare("SELECT filename, alttext FROM vr20_photos WHERE privacy<=? AND deleted IS NULL LIMIT ?,?");
	//$stmt = $conn->prepare("SELECT vr20_photos.id, vr20_photos.filename, vr20_photos.alttext, vr20_users.firstname, vr20_users.lastname FROM vr20_photos JOIN vr20_users on vr20_users.id = vr20_photos.userid WHERE vr20_photos.privacy<=? AND vr20_photos.deleted IS NULL LIMIT ?,?");

	$stmt = $conn->prepare("SELECT vr20_photos.id, vr20_users.firstname, vr20_users.lastname, vr20_photos.filename, vr20_photos.alttext, AVG(vr20_photoratings.rating) as AvgValue FROM vr20_photos JOIN vr20_users ON vr20_photos.userid = vr20_users.id LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id WHERE vr20_photos.privacy <= ? AND deleted IS NULL GROUP BY vr20_photos.id DESC LIMIT ?, ?");

	echo $conn->error;
	$stmt->bind_param("iii", $privacy, $skip, $limit);
	$stmt->bind_result($idFromDb, $firstnameFromBb, $lastnameFromDb, $filenameFromDb, $altFromDb, $ratingFromDb);
	$stmt->execute();
	while ($stmt->fetch()) {
		$html .= '<div class="galleryelement">' . "\n";
		$html .= '<a href="' . $GLOBALS["normalPhotoDir"] . $filenameFromDb . '" target="_blank"><img src="' . $GLOBALS["thumbPhotoDir"] . $filenameFromDb . '" alt="' . $altFromDb . '" class="thumb"></a>' . "\n \t \t";

		//$html .= '<img src="' . $GLOBALS["thumbPhotoDir"] . $filenameFromDb . '" alt="' . $altFromDb . '" class="thumb" data-fn="' . $filenameFromDb . '" data-id="' . $idFromDb . '">' . "\n \t \t";
		$html .= "<p>" . $firstnameFromBb . " " . $lastnameFromDb . "</p> \n \t \t";
		//$html .= "<p> Hinne: " . round($ratingFromDb, 2) . "</p> \n";
		$html .= "</div> \n \t \t";
	}
	if ($html != "") {
		$finalHTML = $html;
	} else {
		$finalHTML = "<p>Kahjuks pilte pole!</p>";
	}
	$stmt->close();
	$conn->close();
	return $finalHTML;
}
