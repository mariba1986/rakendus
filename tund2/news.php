<?php
	require("../../../../configuration.php");
	require("fnc_news.php");
	
	$newsHTML = readNews(5);

if (isset($_POST["newsDelBtn"])) {
	deleteNews($_POST["newsDelBtn"]);
	$newsHTML = readNews($_POST["limitSet"]);
}

if (isset($_POST["limitSet"])) {
	$newsHTML = readNews($_POST["limitSet"]);
}

?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>



<body>
	<h1>Uudised</h1>
	<p>Leht on valminud õppetöö raames!</p>
	<div>
		<?php echo $newsHTML; ?>
	</div>
	<hr>

</body>
</html>