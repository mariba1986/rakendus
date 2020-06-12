<!-- <?php

//function savePhotoData($id, $userid, $fileName, $origName, $altText, $privacy)
//{
  //  $response = null;
    //loon andmebaasiühenduse
    //$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    //valmistan ette SQL päringu
    //$stmt = $conn->prepare("INSERT INTO vr20_photos (id, userid, filename, origname, alttext, privacy) VALUES (?, ?, ?, ?, ?, ?)");
    //echo $conn->error;
    //seon päringuga tegelikud andmed
    //$stmt->bind_param("iisssi", $id, $userid, $fileName, $origName, $altText, $privacy);
    //i -integer  s - string d - decimal
    //if ($stmt->execute()) {
    //    $response = 1;
    //} else {
     //   $response = 0;
       // echo $stmt->error;
    //}
    //sulgen päringu ja andmebaasiühenduse
    //$stmt->close();
    //$conn->close();
    //return $response;
//}
