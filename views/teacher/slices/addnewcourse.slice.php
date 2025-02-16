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
        $sql = "select * from course where coursename = '$coursename' and teacherid = '$teacherid'";
        $result = mysqli_query($connection,$sql);
        // check if the coursename is already exist with specific teacher id
        if(mysqli_num_rows($result) == 0){
            $sql2 = "insert into course(coursename,teacherid)values('$coursename','$teacherid')";
            $result2 = mysqli_query($connection,$sql2);
        if($result2){
            header('location:../addnewcourse.php?success');
            exit();
        }else{
            header('location:../addnewcourse.php?failed');
            exit();
        }
        }else{
            header('location:../addnewcourse.php?exists');
            exit();
        }
        
    }
}else{
    header('location:../addnewcourse.php');
    exit();
}