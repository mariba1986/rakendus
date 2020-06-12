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
        $response = "Õppeaineid ei ole valitud";
    }

    $stmt->close();
    $conn->close();
    return $response;
}
function getStudyActivitiesOptions()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    mysqli_set_charset($conn, "utf8");

    $stmt = $conn->prepare("SELECT id, activity FROM vr20_studyactivities order by activity asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $activityNameFromDB);
    $stmt->execute();


    while ($stmt->fetch()) {
        $response .= '<option value="' . $idFromDB . '">' . $activityNameFromDB . '</option>\n';
    }

    if ($response == null) {
        $response = "Tegevusi ei ole!";
    }

    $stmt->close();
    $conn->close();
    return $response;
}
function saveStudy($studyTopicId, $studyActivity, $elapsedTime)
{

    $response = null;
    //oon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    //valmistame ette SQL päringu //statementist tulnud lühend
    $stmt = $conn->prepare("INSERT INTO vr20_studylog (course, activity, time) VALUES ( ?, ?, ?)");
    //kui asi ei tööta siis tuleb error:
    echo $conn->error;
    //seon päringuga tegelikud andmed
    //i tähistab integeri, s stringi ja d tähistab decimali. (kolme küsimärgi järgi on need kolm tähte, user id on number, tiitel ja content on tekstid ehk stringid) 
    $stmt->bind_param("iid", $studyTopicId, $studyActivity, $elapsedTime);
    if ($stmt->execute()) {
        $response = 1;
    } else {
        $response = 0;
        echo $stmt->error;
    }
    //Sulgen päringu ja andmebaasi ühenduse.
    $stmt->close();
    $conn->close();
    return $response;
}

function getStudyTableHTML()
{

    $response = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT sl.id, st.course, sa.activity, time, day 
                                FROM vr20_studylog sl 
                                JOIN vr20_studytopics st on sl.course=st.id
                                JOIN vr20_studyactivities sa on sl.activity=sa.id
                                order by id asc");
    echo $conn->error;

    $stmt->bind_result($idFromDB, $courseNameFromDB, $activityNameFromDB, $elapsedTimeFromDB, $dateFromDB);
    $stmt->execute();

    $rowCount = 1;
    while ($stmt->fetch()) { //toob nii kaua uudiseid kuni veel on mida tuua

        $response .= '<tr>
        <th scope="row">' . $rowCount . '</th>
        <td>' . $courseNameFromDB . '</td>
        <td>' . $activityNameFromDB . '</td>
        <td>' . $elapsedTimeFromDB . '</td>
        <td>' . $dateFromDB . '</td>
        </tr>';

        $rowCount += 1;
    }

    if ($response == null) {
        $response = "Õppimise andmeid ei ole lisatud";
    }

    $stmt->close();
    $conn->close();
    return $response;
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
