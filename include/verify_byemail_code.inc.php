<?php
include('../model/dbcon.php');

// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if(isset($_POST['verify'])){
    if(isset($_SESSION['verification_userid']))
    $userid = $_SESSION['verification_userid'];
    
    $v_code = $_POST['v_code'];
    if(empty($v_code)){
        header('location:../views/verify_email_code.php?emptyverificationfield');
        exit();
    }else{
         //  select email code from table
    $sql = "select * from email_verification where userid ='$userid'";
    $result = mysqli_query($connection,$sql);
    while($row = mysqli_fetch_assoc($result)){
        // var_dump($row['email_code'] == $v_code);
        // check if the input code equals the code inside the db
        if($row['email_code'] == $v_code){
            $sql2 = "update  email_verification set email_verified = '1' where userid = '$userid'";
            $result2 = mysqli_query($connection,$sql2);
            if($result2){
                $sql3 = "update  users set verified_status = '1' where userid = '$userid'";
                $result3 = mysqli_query($connection,$sql3);
                if($result3){
                    session_destroy();
                    header('location:../views/login.php?emailverified');
                    exit();
                }
      }
        }else{
            // if verification code doesn't equal the one in the database
            header('location:../views/verify_email_code.php?wrongverificationfield');
            exit();
        }
    }
    }
   


}else{
    header('location:../views/verify_email_code.php');
    exit();
}