<?php
// ini_set('display_errors', 0);
// error_reporting(E_ALL);
include('../model/dbcon.php');
include_once('checkemailverified.login.php');
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    if(empty($email)){
        header('Location:../views/login.php?emptyemail');
        exit();
    }
    //  if(filter_var($email,FILTER_VALIDATE_EMAIL)){
    //     header('Location:../views/login.php?invalidemail');
    //     exit();
    // }
     else if(empty($pwd)){
        header('Location:../views/login.php?emptypwd');
        exit();
    }
    else{
        $sql = "select * from users where email = '$email'";
        $result = mysqli_query($connection,$sql);
        
        if(mysqli_num_rows($result) == 0){
            
            header('Location:../views/login.php?emaildoesntexist');
            exit();
        }else{
            // $hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);
            while($row = mysqli_fetch_assoc($result)){
                if($row['email'] == $email && password_verify($pwd,$row['pwd'])){
                
                    // check if the user is verified(means if user created account and code was sent to him , but didn't 
                    // verify , we will stop him from login in)
                    if(checkifuserverifiedwhenlogin($row['verified_status'],'1')){
                        // means user is verified
                        session_start();
                        // session_create_id();                      
                        $_SESSION['userid'] = $row['userid'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['fullname'] = $row['fullname'];
                        $_SESSION['role'] = $row['role'];
                        header('location:../views/checkroles.php');
                        exit();
                    }else{
                        // means if the user isn't verified
                        header('Location:../views/login.php?usernotverified');
                        exit();
                    }
                  
                    
                    
                }else{
                    header('Location:../views/login.php?wrongemailorpassword');
                    exit();
                }
                // if($row['email'] == $email && $row['pwd'] == $hashedpwd)
            }
        }
        
    }
}else{
    header('Location:../views/login.php');
    exit();
}