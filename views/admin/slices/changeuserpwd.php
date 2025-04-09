<?php
include('../../../model/dbcon.php');
// change user password
if(isset($_POST['changepwdbtn'])){
    $userid = base64_decode($_POST['userid']);
    $changepwd  = mysqli_escape_string($connection,$_POST['changepwd']);
    $hashpwd = password_hash($changepwd,PASSWORD_DEFAULT);

    if(empty(trim($userid))){
        header('location:../viewusers.php?emptyuserid');
        exit();
    }else if(empty(trim($changepwd))){
        header('location:../viewusers.php?emptychangepwdfield');
        exit();
    }else{
        $sql = "update users set pwd ='$hashpwd' where userid = '$userid'";
    $result = mysqli_query($connection,$sql);

    if($result){
        header('location:../viewusers.php?pwdchangedsuccess');
        exit();
    }else{
        header('location:../viewusers.php?pwdfailed');
        exit();
    }
    }

  
}
else{
    header('location:../viewusers.php?redirected');
            exit();
}