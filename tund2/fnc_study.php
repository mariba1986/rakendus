<?php

function getStudyTopicsOptions()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8");

    $stmt = $conn->prepare("SELECT id, course FROM vr20_studytopics order by course asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $courseNameFromDB);
    $stmt->execute();


    while ($stmt->fetch()) {
        $response .= '<option value="' . $idFromDB . '">' . $courseNameFromDB . '</option>\n';
    }

    if ($response == null) {
        $response = "Ei ole kursuseid valitud";
    }

    $stmt->close();
    $conn->close();
    return $response;
}
