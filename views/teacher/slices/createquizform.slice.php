<?php
include('../../../model/dbcon.php');
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['submit'])) {
   
    $teacherid = $_SESSION['userid'];
    $quizid =mysqli_real_escape_string($connection  , htmlspecialchars($_POST['quizid']));
    $quiztitle = mysqli_real_escape_string($connection , htmlspecialchars($_POST['quiztitle']));
    $quizdescription = mysqli_real_escape_string($connection , htmlspecialchars($_POST['quizdescription']));
    $courseName = mysqli_real_escape_string($connection , htmlspecialchars($_POST['coursename']));
    $quizstatus = mysqli_real_escape_string($connection , htmlspecialchars($_POST['quizstatus']));
    $numberofquestion = mysqli_real_escape_string($connection , htmlspecialchars($_POST['numberofquestion']));
    $quiztype = $_POST['quiztype'];

    if(empty($quizid)) {
        header('location: ../createquiz.php?emptyquizid');
        exit();
    }
    else if(empty($quiztitle)) {
        header('location: ../createquiz.php?emptyquiztitle');
        exit();
    }
    else if(empty($quizdescription)) {
        header('location: ../createquiz.php?emptyquizdescription');
        exit();
    }
    else if(empty($courseName)) {
        header('location: ../createquiz.php?emptycoursename');
        exit();
    }
    else if(empty($quizstatus)) {
        header('location: ../createquiz.php?emptyquizstatus');
        exit();
    }
    
    else if(empty($quiztype)) {
        header('location: ../createquiz.php?emptyquiztype');
        exit();
    }
    else if(empty($numberofquestion)) {
        header('location: ../createquiz.php?emptynumberofquestion');
        exit();
    }
    else {
        $sql ="insert into quizform(quizformid,quiztitle,quizdesc,coursename,quizstatus,teacherid,number_of_questions,quiztype) values('$quizid','$quiztitle','$quizdescription','$courseName','$quizstatus','$teacherid','$numberofquestion','$quiztype')";
        $result = mysqli_query($connection, $sql);
        if($result) {
            header('location: ../createquiz.php?success');
            exit();
        }else{
            header('location: ../createquiz.php?error');
            exit();
        }
    }   
}
