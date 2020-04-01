<?php
	$myName = "Maris Riba";
	$fullTimeNow = date("d.m.Y H:i:s");
	//<p> Lehe avamise hetkel oli: <strong>31.01.2020 11:32:07</strong> </p>
	$timeHTML = "\n <p> Lehe avamise hetkel oli: <strong>" .$fullTimeNow ."</strong> </p> \n";
	$hourNow = date("H");
	//$partOfDay = "hägune aeg";
	
	if($hourNow < 10) {
        $partOfDay = "hommik";
		$backColor = '#7CBDC2';
		$fontColor = '#407999';
    }
	elseif($hourNow >= 10 and $hourNow < 18) {
        $partOfDay = "aeg aktiivselt tegutseda";
		$backColor = '#50C30A';
		$fontColor = '#324726';
	}
	else {
        $partOfDay = "hägune aeg";
		$backColor = '#B2A29F';
		$fontColor = '#7C463C';
		
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
    
    //Väljundid kui semester ei ole veel alanud ja kui on juba lõppenud
    if($today < $semesterStart ){
        $semesterProgressHTML = "<p> Semester ei ole veel alanud. </p>" ."\n";
    }
    if ($today > $semesterEnd){
        $semesterProgressHTML = "<p> Semester on juba kahjuks lõppenud. </p>" ."\n";
    }

	//loen etteantud kataloogist pildifailid
	$picsDir= "../Pics/";
	$photoTypesAllowed = ["image/jpeg", "image/png"];  //massiv et millised foto tüübid on lubatud
	$photoList = [];
	$allFiles = array_slice(scandir($picsDir), 2);		//scandir loeb kausta sisu, array_slice eemaldab ees olevad kataloogid, et tuleks puhas massiiv ainult piltidest.
	//var_dump($allFiles);
	foreach($allFiles as $file){
		$fileInfo = getimagesize($picsDir .$file);
		if(in_array($fileInfo["mime"],$photoTypesAllowed) == true) {
			array_push($photoList, $file);  //lisab iga tingimustele vastava foto massiivi
		}
	}
	
	$photoCount = count($photoList);
	//$photoNum = mt_rand(0, $photoCount -1 ); 	//mt_rand on kiirem kui lihtsalt rand
	//$randomImageHTML = '<img src = "' .$picsDir .$photoList[$photoNum] .'" alt="juhuslik pilt Haapsalust">' ."\n";

   
    $photoNum = [];
    foreach ([0,1,2] as $element) {
        do {
            $drawNum = mt_rand(0, $photoCount-1); 
		} while (in_array($drawNum, $photoNum)); {
			array_push($photoNum, $drawNum); //lisab loositud numbrid massiivi
		}
    }
///tsükkel et välja tuleks erinevad pildid
    $randomImageHTML = '';
    foreach ($photoNum as $drawNum) {
        $randomImageHTML .= '<img src="'. $picsDir . $photoList[$drawNum].'" alt="juhuslik pilt Haapsalust" width="250" height="250"/>' ."\n";
    }	


?>
<!DOCTYPE html>
<html lang="et">
<head>
<style>
 body {background-color: <?php echo $backColor; ?>;} h1 {color: <?php echo $fontColor; ?>;} p {color:<?php echo $fontColor; ?>;}
  </style>

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