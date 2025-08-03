<?php
include('../../../model/dbcon.php');

// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if(isset($_POST['submit'])){
    $formtitle = mysqli_real_escape_string($connection ,$_POST['formtitle']);
    $formdesc = mysqli_real_escape_string($connection ,$_POST['formdesc']);
    $coursename = mysqli_real_escape_string($connection ,$_POST['coursename']);
    $uploadfiletype = $_POST['uploadfiletype'];
    $teacherid = $_SESSION['userid'];
    $marks = mysqli_real_escape_string($connection ,$_POST['marks']);
    // generate random 6 number for formid
    $formid = random_int(100000, 999999);
    if(empty(trim($formtitle))){
        header('location:../createassignmentform.php?emptyformtitle');
        exit();
    }else if(empty(trim($formdesc))){
        header('location:../createassignmentform.php?emptyformdesc');
        exit();
    }else if(empty(trim($marks))){
        header('location:../createassignmentform.php?emptymarks');
        exit();
    }else{
        $sql = "insert into assignmentform(formid,title,description,coursename,marks,template,uploadedfilename,formstatus,teacherid)values(
        '$formid','$formtitle','$formdesc','$coursename','$marks','default_template','$uploadfiletype','active',$teacherid)";
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