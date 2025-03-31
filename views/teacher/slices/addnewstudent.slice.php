<?php
include('../../../model/dbcon.php');
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['submit'])){

    $studentid = mysqli_real_escape_string($connection ,$_POST['studentid']);
    $studentname = mysqli_real_escape_string($connection ,$_POST['studentname']);
    $coursename = mysqli_real_escape_string($connection ,$_POST['coursename']);
    $teacherid = $_SESSION['userid'];
    if(empty($studentid)){
        header('location:../addnewstudent.php?emptystudentid');
        exit();
    }else if(empty($studentname)){
        header('location:../addnewstudent.php?emptystudentname');
        exit();
    }else if(empty($coursename)){
        header('location:../addnewstudent.php?emptycoursename');
        exit();
    }else{
        $sql2 = "select * from students where stdid = '$studentid' and teacherid = '$teacherid'";
        $result2 = mysqli_query($connection,$sql2);

        if(mysqli_num_rows($result2) > 0){
            header('location:../addnewstudent.php?idtaken');
                exit();
        }else{
            $sql = "insert into students(stdid,stdfullname,coursename,teacherid)values('$studentid','$studentname','$coursename',
            '$teacherid')";
            $result = mysqli_query($connection,$sql);
    
            if($result){
                header('location:../addnewstudent.php?addedsuccessfully');
                exit();
            }else{
                header('location:../addnewstudent.php?failedquery');
                exit();
            }
        }
       
    }


}