<?php
include('../../../model/dbcon.php');
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['submit'])){
    $teacherid = $_SESSION['userid'];
    $coursename = $_POST['coursename'];
    if(empty($coursename)){
        header('location:../addnewcourse.php?emptycoursenamefield');
        exit();
    }else{
        $sql = "insert into course(coursename,teacherid)values('$coursename','$teacherid')";
        $result = mysqli_query($connection,$sql);
        if($result){
            header('location:../addnewcourse.php?success');
            exit();
        }
    }
}else{
    header('location:../addnewcourse.php');
    exit();
}