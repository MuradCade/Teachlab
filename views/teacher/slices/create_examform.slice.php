<?php
include('../../../model/dbcon.php');
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['submit'])) {
    
    $teacherid = $_SESSION['userid'];
    $examid =mysqli_real_escape_string($connection  , htmlspecialchars($_POST['examid']));
    $examtitle = mysqli_real_escape_string($connection , htmlspecialchars($_POST['examtitle']));
    $examdescription = mysqli_real_escape_string($connection , htmlspecialchars($_POST['examdescription']));
    $courseName = mysqli_real_escape_string($connection , htmlspecialchars($_POST['coursename']));
    $examstatus = mysqli_real_escape_string($connection , htmlspecialchars($_POST['examstatus']));
    $numberofquestion = mysqli_real_escape_string($connection , htmlspecialchars($_POST['numberofquestion']));
    $examtype = $_POST['examtype'];
    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';
    // die();

    if(empty($examid)) {
        header('location: ../create_examform.php?emptyexamid');
        exit();
    }
    else if(empty($examtitle)) {
        header('location: ../create_examform.php?emptyexamtitle');
        exit();
    }
    else if(empty($examdescription)) {
        header('location: ../create_examform.php?emptyexamdescription');
        exit();
    }
    else if(empty($courseName)) {
        header('location: ../create_examform.php?emptycoursename');
        exit();
    }
    else if(empty($examstatus)) {
        header('location: ../create_examform.php?emptyexamstatus');
        exit();
    }
    
    else if(empty($examtype)) {
        header('location: ../create_examform.php?emptyexamtype');
        exit();
    }else if(empty($numberofquestion)) {
        header('location: ../create_examform.php?emptynumberofquestion');
        exit();
    }
    else {
        $sql ="insert into examform(examformid,examtitle,examdesc,coursename,examstatus,teacherid,number_of_questions,examtype) values('$examid','$examtitle','$examdescription','$courseName','$examstatus','$teacherid','$numberofquestion','$examtype')";
        $result = mysqli_query($connection, $sql);
        if($result) {
            header('location: ../create_examform.php?success');
            exit();
        }else{
            header('location: ../create_examform.php?error');
            exit();
        }
    }   
}
