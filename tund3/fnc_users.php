<?php


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}  
//sessiooni käivitamine või kasutamine
//session_start();

function signUp($name, $surname, $email, $gender, $birthDate, $password){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO vr20_users (firstname, lastname, birthdate, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
    echo $conn->error;
    // nüüd tuleks parool ära krüpteerida:
    $options = ["cost" => 12, "salt"=> substr(sha1(rand()), 0, 22) ];  //1)näitab ära kui palju vaeva nähakse krüpteerimisega. 12 on päris palju. 2)salt - lisatakse paroolile enne krüpteerimist lisastring. Mingi kombinatsioon tähemärke mis muudab parooli keerukamaks ja räsi keerukamas.
    $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);

    $stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash );  //oluline on järjekord mis on sql käsus ehk seal kus se stmt on, mitte sama järjekord mis on ülemises reas(rida 2)
    

    if($stmt->execute()){
        $notice = "ok";
    }   else {
        $notice = $stmt->error;
    }
    
    $stmt->close();
	$conn->close();
	return $notice;
}

function signIn($email, $password) {
    $notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT id, firstname, lastname, password FROM vr20_users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->bind_result($idFromDB, $firstnameFromDB, $lastnameFromDB, $passwordFromDB);
    echo $conn->error;
    $stmt->execute();
    if( $stmt->fetch()){
        if(password_verify($password, $passwordFromDB)){
            $_SESSION["userid"] = $idFromDB;
            $_SESSION["userFirstName"] =$firstnameFromDB;
            $_SESSION["userLastName"] =$lastnameFromDB;

            $stmt->close();
            $conn->close();
            header("Location: home.php");
            exit(); //kui kõik klapid, sulgeb ühendused ja läheb järgmisele lehele ja ei ole vaja enam koodis edasi minna    
        } else {
            $notice = "vale salasõna!";
        }
    } else {
        $notice="Śellist kasutajat(" .$email .") ei leitud!";
    }
    
    $stmt->close();
	$conn->close();
	return $notice;

}
 