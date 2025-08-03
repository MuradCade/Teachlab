<?php
include('../../../model/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $coursename = mysqli_escape_string($connection, $_POST['coursename']);
    $teacherid = mysqli_escape_string($connection, $_POST['teacherid']);
    $studentinformation = mysqli_escape_string($connection, $_POST['studentinformation']);
    $sharingability = mysqli_escape_string($connection, $_POST['sharingability']);
    $randomreportid = rand(1, 888888);
    if ($coursename == 'empty') {
        echo 'emptycoursenamefield';
    } else if (empty($studentinformation)) {
        echo 'emptystudentinformationfield';
    } else if (empty($sharingability)) {
        echo 'emptysharingabilityfield';
    } else if (empty($teacherid)) {
        echo 'emptyteacherid';
    } else {
        $sqlmain = "select * from reportsharing where studentid = '$studentinformation' and sharing_ability = '$sharingability'";
        $resultmain = mysqli_query($connection, $sqlmain);

        // this means there is no repeated ssharingability
        if (mysqli_num_rows($resultmain) == 0) {
            // then in here we should store report data
            $sql = "insert into reportsharing(id,studentid,coursename,sharing_ability,teacherid)values('$randomreportid','$studentinformation','$coursename','$sharingability','$teacherid')";

            $result = mysqli_query($connection, $sql);

            if ($result) {
                echo 'success';
            } else {
                echo 'failed';
            }
        } else {
            echo 'failedtosave';
        }
        // var_dump($_POST);

    }
}
