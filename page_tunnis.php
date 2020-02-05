<?php
	$myName = "Maris Riba";
	$fullTimeNow = date("d.m.Y H:i:s");
	//<p> Lehe avamise hetkel oli: <strong>31.01.2020 11:32:07</strong> </p>
	$timeHTML = "\n <p> Lehe avamise hetkel oli: <strong>" .$fullTimeNow ."</strong> </p> \n";
	$hourNow = date("H");
	$partOfDay = "hägune aeg";
	
	if($hourNow < 10) {
		$partOfDay = "hommik";
	}
	if($hourNow >= 10 and $hourNow < 18) {
		$partOfDay = "aeg aktiivselt tegutseda";
	}
	$partOfDayHTML = "<p> Käes on " .$partOfDay ." !</p> \n"; 
	
	//info semestri kulgemise kohta
	$semesterStart = new DateTime("2020-01-27");
	$semesterEnd = new DateTime("2020-06-22");
	$semesterDuration = $semesterStart->diff($semesterEnd);
	//echo $semesterDuration;
	//var_dump ($semesterDuration);
	$today = new DateTime("now");
	$fromSemesterStart = $semesterStart->diff($today);
	
	//<p> Semester on hoos: <meter.value="" min="0" max=""> </meter>.</p>

	$semesterProgressHTML = '<p> Semester on hoos: <meter min="0" max="';
	$semesterProgressHTML .= $semesterDuration->format("%r%a");			//kui on negatiivne, siis r on see mis paneb miinuse ette. "a" on kahe kuupäeva erinevuse päevade arv
	$semesterProgressHTML .= '"value = "';
	$semesterProgressHTML .= $fromSemesterStart->format("%r%a");	
	$semesterProgressHTML .= '"></meter>.</p>' ."\n"; 
	
	//loen etteantud kataloogist pildifailid
	$picsDir= "../Pics/";
	$photoTypesAllowed = ["image/jpeg", "image/png"];  //massiv et millised foto tüübid on lubatud
	$photoList = [];
	$allFiles = array_slice(scandir($picsDir), 2);		//scandir loeb kausta sisu, array_slice eemaldab ees olevad kataloogid, et tuleks puhas massiiv ainult piltidest.
	//var_dump($allFiles);
	foreach($allFiles as $file){
		$fileInfo = getimagesize($picsDir .$file);
		if(in_array($fileInfo["mime"],$photoTypesAllowed) == true) {
			array_push($photoList, $file);
		}
	}
	
	$photoCount = count($photoList);
	$photoNum = mt_rand(0, $photoCount -1 ); 		//mt_rand on kiirem kui lihtsalt rand
	$randomImageHTML = '<img src = "' .$picsDir .$photoList[$photoNum] .'" alt="juhuslik pilt Haapsalust">' ."\n";
	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1><?php echo $myName; ?> </h1>
	<p>See leht on valminud õppetöö raames!</p>
	<?php 
		echo $timeHTML;
		echo $partOfDayHTML;
		echo $semesterProgressHTML;
		echo $randomImageHTML;
	?>
</body>
</html>