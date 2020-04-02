<?php
require("../../../../configuration.php");

$studyOpt = getStudyTopicsOptions()

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
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<label>Õppeaine:</label><br>
			<select name="Õppeaine nimetus:">
				<option value="" selected disabled>Õppeaine</option>
				<?php echo $studyOpt; ?>

			</select> <br>
			<label>Uudise sisu</label><br>
			<textarea name="newsEditor" placeholder="Uudis" rows="5" cols="40"><?php echo $newsContent; ?></textarea>
			<br>
			<input type="submit" name="newsBtn" value="Salvesta uudis!">
			<span><?php echo $newsError; ?></span>
		</form>
	</div>
	<hr>

</body>

</html>