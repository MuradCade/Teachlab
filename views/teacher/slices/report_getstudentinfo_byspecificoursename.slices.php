<?php
include('../../../model/dbcon.php');


//this file is responsible to fetch student information according to the course name that has been chosen by the teacher

//  this function is responsible to fecth studentinformation according the coursename that been chosing , also we check if existing teacher owns this course
function getstudentinfo($connection, $teacherid, $coursename)
{
    if (empty($teacherid)) {
        return 'teacheridnotfound';
    }
    $sql = "select * from students where  coursename = '$coursename' and teacherid = '$teacherid'";
    $result = mysqli_query($connection, $sql);
    $coursename = array();
    if (mysqli_num_rows($result) ==  0) {
        return 'empty';
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $coursename[] = $row;
        }
    }
    return $coursename;
}





// get studentname
function getonlystudentname($connection, $studentid, $teacherid)
{
    $studendata = array();
    $sql = "select stdfullname from students where stdid = '$studentid' and teacherid = '$teacherid'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) == 0) {
        return 'N/A';
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            $studendata[] = $row;
        }

        return $studendata ?? 'N/A';
    }
}
// this is statement handles the ajax request and sends back a response
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // isset($_POST['displaystudentdata']) 
    //read all student name related to this teacher and course

    $coursename = mysqli_escape_string($connection, $_POST['coursename']);
    $teacherid = mysqli_escape_string($connection, $_POST['teacherid']);
    // var_dump($_POST);
    $studentdetials = getstudentinfo($connection, $teacherid, $coursename);
    echo json_encode($studentdetials);
    // return $studentdetials;

}

// this code  displays all saved report for teachers
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $teacherid = mysqli_escape_string($connection, $_GET['teacherid']);
    $studentreportdata = array();
    if (empty(trim($teacherid))) {
        echo json_encode('teacheridnotfound');
    } else {
        // var_dump($_GET);
        $sql = "select * from reportsharing where teacherid = '$teacherid' order by created_date asc";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result) == 0) {
            echo json_encode('dbempty');
            exit();
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                // echo json_encode(getonlystudentname($connection, $row['studentid'], $teacherid));
                $studentdata[] = [
                    "reportdata" => $row,
                    "getstudentname" => getonlystudentname($connection,  $row['studentid'], $teacherid),
                ];

                $studentreportdata = $studentdata;
            }
        }
        echo json_encode($studentreportdata);
    }
}
