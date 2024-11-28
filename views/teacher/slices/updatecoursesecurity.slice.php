<?php

// check if the users messes up with coursename id in the url
// image the course id inthe url is 1 but the user change it to 10 , we will check in the database if course id 10 is created
// by the current logged in teacherid if not we will display error message and disable the update button
// because we can't update course that doesn't exist

function securecourseidinurl($courseid,$teacherid, $connection){
     // get the coursename before updating  course information
 if (isset($courseid)) {
    $courseid = $_GET['courseid'];
    $teacherid = $_SESSION['userid'];
    $sqlquery = "select coursename from course where courseid = '$courseid' and teacherid = '$teacherid'";
    $resultquery = mysqli_query($connection, $sqlquery);
    if (mysqli_num_rows($resultquery) == 0) {
    
        $rows = ['coursename' => 'sorry coursename not found'];
       return  $rows['coursename'];
    } else {
        $rows = mysqli_fetch_assoc($resultquery);
        return $rows['coursename'];
    }
}else{
    return false;
}
}
    
    