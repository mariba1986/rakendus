<?php
function saveNews($newsTitle, $newsContent)
{
    $response = null;
    //loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    //valmistame ette SQL päringu //statementist tulnud lühend
    $stmt = $conn->prepare("INSERT INTO vr20_news(userid, title, content) VALUES (?, ?, ?)");
    //kui asi ei tööta siis tuleb error:
    echo $conn->error;
    //seon päringuga tegelikud andmed
    $userid = 1;
    $stmt->bind_param("iss", $userid, $newsTitle, $newsContent);
    //i tähistab integeri, s stringi ja d tähistab decimali. (kolme küsimärgi järgi on need kolm tähte, user id on number, tiitel ja content on tekstid ehk stringid) 
    if ($stmt->execute()) {
        $response = 1;
    } else {
        $response = 0;
        echo $stmt->error;
    }
    //sulgen päringu ja andmebaasiühenduse
    $stmt->close();
    $conn->close();
    return $response;
}

function readNews($limit)
{
    $response = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT title, content, created FROM vr20_news WHERE deleted IS NULL ORDER BY id DESC LIMIT ?"); //kui title ja content asemele oleks pannud tärni tuleks kõik andmed
    echo $conn->error;

    //seob andmebaasist tulevad andmed
    $stmt->bind_param("i", $limit);
    $stmt->bind_result($titleFromDB, $contentFromDB, $createdFromDB);
    $stmt->execute();  //toob andmebaasist info
    //if($stmt->fetch()) - toob ühe uudise 
    //<h2>uudisepealkiri</h2>
    //<p>uudis</p>
    while ($stmt->fetch()) { //toob nii kaua uudiseid kuni veel on mida tuua
        $response .= '<div class="jumbotron">';
        $response .= "<h2>" . $titleFromDB . "</h2> \n";
        $response .= '<p>' . $createdFromDB . '</p>';
        $response .= "<p>" . $contentFromDB . "</p> \n";
        $response .= '<hr class="my-5">';
        $response .= '</div>';
    }
    if ($response == null) {
        $response = "<p>Kahjuks uudised puuduvad!</p> \n";
    }
    //sulgen päringu ja andmebaasiühenduse
    $stmt->close();
    $conn->close();
    return $response;
}

function deleteNews($id)
{
    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("UPDATE vr20_news SET deleted = NOW() WHERE Id =?");

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response = 1;
    } else {
        $response = 0;
        echo $stmt->error;
    }

    $stmt->close();
    $conn->close();
    return $response;
}
