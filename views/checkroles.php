<?php
// check if session already runing if not run new session
session_start();


if(isset($_SESSION['userid']) && isset($_SESSION['role'])){
    if($_SESSION['role'] == 'teacher'){
       
        header('location:teacher/index.php');
        exit();
    }else if($_SESSION['role'] == 'admin'){
        header('location:admin/index.php');
        exit();
    }else{
        header('location:login.php?usernotfound');
        exit();
    }
}
else{
    header('location:login.php?redirect');
    exit();
}