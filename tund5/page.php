<?php
require("../../../../configuration.php");
require("fnc_news.php");
require("fnc_users.php");

require("classes/Session.class.php");

SessionManager::sessionStart("vr20", 0, "/~maris.riba/", "tigu.hk.tlu.ee");
require("classes/Test.class.php");
$test = new Test();
//echo $test->number;
$test->reveal;
unset($test);

//$newsHTML = readNews(1)

$myName = "Maris Riba";
$fullTimeNow = date("d.m.Y H:i:s");
//<p>Lehe avamise hetkel oli: <strong>31.01.2020 11:32:07</strong></p>
$timeHTML = "\n <p>Lehe avamise hetkel oli: <strong>" . $fullTimeNow . "</strong></p> \n";
$hourNow = date("H");
//$partOfDay = "hägune aeg";

if ($hourNow < 10) {
    $partOfDay = "hommik";
    $backColor = '#7CBDC2';
    $fontColor = '#407999';
} elseif ($hourNow >= 10 and $hourNow < 18) {
    $partOfDay = "aeg aktiivselt tegutseda";
    $backColor = '#50C30A';
    $fontColor = '#324726';
} else {
    $partOfDay = "hägune aeg";
    $backColor = '#B2A29F';
    $fontColor = '#7C463C';
}
$partOfDayHTML = "<p>Käes on " . $partOfDay . "!</p> \n";

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
$semesterProgressHTML .= $semesterDuration->format("%r%a");            //kui on negatiivne, siis r on see mis paneb miinuse ette. "a" on kahe kuupäeva erinevuse päevade arv
$semesterProgressHTML .= '"value = "';
$semesterProgressHTML .= $fromSemesterStart->format("%r%a");
$semesterProgressHTML .= '"></meter>.</p>' . "\n";

//Väljundid kui semester ei ole veel alanud ja kui on juba lõppenud
if ($today < $semesterStart) {
    $semesterProgressHTML = "<p> Semester ei ole veel alanud. </p>" . "\n";
}
if ($today > $semesterEnd) {
    $semesterProgressHTML = "<p> Semester on juba kahjuks lõppenud. </p>" . "\n";
}

//loen etteantud kataloogist pildifailid
$picsDir = "../../Pics/";
$photoTypesAllowed = ["image/jpeg", "image/png"];
$photoList = [];
$allFiles = array_slice(scandir($picsDir), 2);
//var_dump($allFiles);
foreach ($allFiles as $file) {
    $fileInfo = getimagesize($picsDir . $file);
    if (in_array($fileInfo["mime"], $photoTypesAllowed) == true) {
        array_push($photoList, $file);
    }
}

$photoCount = count($photoList);
//$photoNum = mt_rand(0, $photoCount - 1);
//$randomImageHTML = '<img src="' .$picsDir .$photoList[$photoNum] .'" alt="juhuslik pilt Haapsalust">' ."\n";

$photoNum = [];
foreach ([0, 1, 2] as $element) {
    do {
        $drawNum = mt_rand(0, $photoCount - 1);
    } while (in_array($drawNum, $photoNum)); {
        array_push($photoNum, $drawNum); //lisab loositud numbrid massiivi
    }
}
///tsükkel et välja tuleks erinevad pildid
$randomImageHTML = '';
foreach ($photoNum as $drawNum) {
    $randomImageHTML .= '<img src="' . $picsDir . $photoList[$drawNum] . '" alt="juhuslik pilt Haapsalust" width="250" height="250"/>' . "\n";
}


$newsHTML = readNewsPage(1);

$notice = null;
$email = null;
$emailError = null;
$passwordError = null;

if (isset($_POST["login"])) {
    if (isset($_POST["email"]) and !empty($_POST["email"])) {
        $email = test_input($_POST["email"]);
    } else {
        $emailError = "Palun sisesta kasutajatunnusena e-posti aadress!";
    }

    if (!isset($_POST["password"]) or strlen($_POST["password"]) < 8) {
        $passwordError = "Palun sisesta parool, vähemalt 8 märki!";
    }

    if (empty($emailError) and empty($passwordError)) {
        $notice = signIn($email, $_POST["password"]);
    } else {
        $notice = "Ei saa sisse logida!";
    }
}
?>
<!DOCTYPE html>
<html lang="et">

<head>
    <style>
        body {
            background-color: <?php echo $backColor; ?>;
        }

        h1 {
            color: <?php echo $fontColor; ?>;
        }

        p {
            color: <?php echo $fontColor; ?>;
        }
    </style>
    <meta charset="utf-8">
    <title>Veebirakendused ja nende loomine 2020</title>
</head>

<body>
    <h1 class="timeBackground"><?php echo $myName; ?></h1>
    <p>See leht on valminud õppetöö raames!</p>

    <h2>Logi sisse</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>E-mail (kasutajatunnus):</label><br>
        <input type="email" name="email" value="<?php echo $email; ?>"><span><?php echo $emailError; ?></span><br>
        <label>Salasõna:</label><br>
        <input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
        <input name="login" type="submit" value="Logi sisse!"><span><?php echo $notice; ?></span>
    </form>

    <p>Loo endale <a href="newuser.php">kasutajakonto</a>!</p>
    <?php
    echo $timeHTML;
    echo $partOfDayHTML;
    echo $semesterProgressHTML;
    echo $randomImageHTML;
    ?>
    <br>
    <hr>
    <h2>Uudis</h2>
    <div><?php echo $newsHTML; ?> </div>
</body>

</html>