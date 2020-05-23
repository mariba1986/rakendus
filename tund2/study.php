<?php
require("../../../../configuration.php");
require("fnc_study.php");

$studyTopicsOptions = getStudyTopicsOptions();
$studyActivitiesOptions = getStudyActivitiesOptions();

$studyTopicId = null;
$studyActivity = null;
$elapsedTime = null;
$studyError = "";


if (isset($_POST['studyBtn'])) {
	if (isset($_POST["studyTopicId"]) and !empty(test_input($_POST["studyTopicId"]))) {
		$studyTopicId = test_input($_POST["studyTopicId"]);
	} else {
		$studyError = "Õppeaine on valimata!";
	}

	if (isset($_POST["studyActivity"]) and !empty(test_input($_POST["studyActivity"]))) {
		$studyActivity = test_input($_POST["studyActivity"]);
	} else {
		$studyError .= "Tegevus on valimata! ";
	}

	if (isset($_POST["elapsedTime"]) and !empty(test_input($_POST["elapsedTime"])) and $_POST["elapsedTime"] != 0) {
		$elapsedTime = test_input($_POST["elapsedTime"]);
	} else {
		$studyError .= "Aeg on määramata! ";
	}

	if ($_POST) $response = saveStudy($studyTopicId, $studyActivity, $elapsedTime);
	/* if (empty($studyError)) {

		$response = saveStudy($studyTopicId, $studyActivity, $elapsedTime);
		//echo "Salvestame!";
		if ($response == 1) {
			$studyError = "Salvestatud!";
		} else {
			$studyError = "Salvestamisel tekkis viga!";
		} */
}

?>

<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>



<body>
	<h1>Õppetöö salvestamine</h1>
	<p>Leht on valminud õppetöö raames!</p>
	<div>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
			<select name="õppeaine" required>
				<option value="" selected disabled>Õppeaine</option>
				<option value="1">Veebirakendused ja nende loomine</option>
				<option value="2">Programmeerimine</option>
				<option value="3">Psühholoogia</option>
				<option value="4">Disaini alused</option>
				<option value="5">Andmebaasid</option>
			</select>
			<select name="tegevus" required>
				<option value="" selected disabled>Tegevus</option>
				<option value="1">Iseseisev materjali omandamine</option>
				<option value="2">Koduste ülesannete lahendamine</option>
				<option value="3">Kordamine</option>
				<option value="4">Rühmatöö</option>
			</select>
			<label>Kulunud aeg:</label>
			<input type="number" min=".25" max="24" step=".25" name="elapsedTime" required>
			<input type="submit" name="studyBtn" value="Salvesta valikud"><br>
			<span><?php echo $studyError; ?></span>
		</form>
	</div>
	<hr>
</body>

</html>