<?php
include('../../../model/dbcon.php');

// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if(isset($_POST['submit'])){
    $formtitle = $_POST['formtitle'];
    $formdesc = $_POST['formdesc'];
    $coursename = $_POST['coursename'];
    $template = $_POST['template'];
    $uploadfiletype = $_POST['uploadfiletype'];
    $teacherid = $_SESSION['userid'];
    $marks = $_POST['marks'];
    // generate random 6 number for formid
    $formid = random_int(100000, 999999);
    if(empty($formtitle) && empty($formedsc)){
        header('location:../createassignmentform.php?emptyfields');
        exit();
    }else{
        $sql = "insert into assignmentform(formid,title,description,coursename,marks,template,uploadedfilename,formstatus,teacherid)values(
        '$formid','$formtitle','$formdesc','$coursename','$marks','$template','$uploadfiletype','active',$teacherid)";
        $result = mysqli_query($connection,$sql);
        if($result){
            header('location:../createassignmentform.php?formcreated');
            exit();
        }else{
            header('location:../createassignmentform.php?queryfailed');
            exit();
        }
    }
}else{
    header('location:../viewassignmentform.php?redirect');
    exit();
}