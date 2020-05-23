<?php
require("../../../../configuration.php");
require("fnc_study.php");

$studyLogHTML = getStudyTableHTML();
?>

<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <title>Õppelogi</title>
</head>

<style>
    table {
        background-color: beige;
        border: 1px solid black;
        table-layout: fixed;
    }

    td {
        padding-left: 5px;
        padding-right: 5px;
        border: 1px solid black;
        text-align: center;
    }
</style>

<body>
    <table>
        <tr>
            <th>Järjekorra nr.</th>
            <th>Õppeaine</th>
            <th>Tegevus</th>
            <th>Kulunud aeg</th>
            <th>Kuupäev</th>
        </tr>
        <?php echo $studyLogHTML; ?>

    </table>
</body>

</html>