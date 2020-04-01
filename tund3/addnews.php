<?php
//include v require erinevus - kui faili mida vaja ei leita, siis töötab edasi, require'iga tuleb fatal error ja ei tööta
	require("../../../../configuration.php");
	require("fnc_news.php");

	//var_dump($_POST);
	//echo $_POST["newsTitle"];
$newsTitle = null;
$newsContent = null;
$newsError = null;

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
  }

	if(isset($_POST["newsBtn"])){
		if(isset($_POST["newsTitle"]) and !empty(test_input($_POST["newsTitle"]))){
			$newsTitle = test_input($_POST["newsTitle"]);
			} else {$newsError = "Uudise pealkiri on sisestamata!";
			}
		if(isset($_POST["newsEditor"]) and !empty(test_input($_POST["newsEditor"]))){
				$newsContent = test_input($_POST["newsEditor"]);
			} else {$newsError = "Uudise pealkiri on sisestamata!";
			}	
			//echo $newsTitle ."\n";
			//echo $newsContent;
			// Saadame parem andmebaasi!!!
			//kui vigu ei teki siis saadame andmebaasi
			if(empty($newsError)){
				//echo "Salvestame!";
				$response = saveNews($newsTitle, $newsContent);
				if ($response == 1){
					$newsError = "uudis on salvestatud";
				} else {
					$newsError = "uudise salvestamisel tekkis viga!";
				}

			}
		}
	// kui mõlemad on olemas siis salvestatakse uudis
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>



<body>
	<h1>Uudise lisamine</h1>
	<p>Leht on valminud õppetöö raames!</p>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
		<label>Uudise pealkiri:</label><br>
		<input type="text" name="newsTitle" placeholder="Uudise pealkiri" value="<?php echo $newsTitle; ?>"><br>
		<label>Uudise sisu</label><br>
		<textarea name="newsEditor" placeholder="Uudis" rows="5" cols="40"><?php echo $newsContent; ?></textarea>
		<br>
		<input type="submit" name="newsBtn" value="Salvesta uudis!">
		<span><?php echo $newsError; ?></span>
	</form>
	<br>
	<hr>

</body>
</html>