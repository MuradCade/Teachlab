<?php
include('../model/dbcon.php');
include('send_email.php');

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    if(empty($email)){
        header('location:../views/forgetpwd.php?emptyemailfield');
        exit();
    }else{
        $sql ="select fullname,userid from users where email = '$email'";
        $result = mysqli_query($connection,$sql);
         //generate random number for email verificiation
         $letterandnumbers = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $verification_code_length = 6; // number of digit and letters in verification code
        $generated_verification_code = substr(str_shuffle($letterandnumbers), 
                       0, $verification_code_length );
        if(mysqli_num_rows($result) == 0){
            header('location:../views/forgetpwd.php?emailnotfound');
            exit();
        }else{
            while($row = mysqli_fetch_assoc($result)){
                // this userid is for the url
                $userid = base64_encode($row['userid']);
                // this is for forgetpwd table
                $useridformdb = $row['userid'];
                $fullname = $row['fullname'];
                $emailarray = ["subject"=>"Forget Your Password",
                        "body"=>"<p>Dear $fullname, Please Click The Link Below To Verify Your Password(Link can be used only once)</p>
                        <p>please copy this code and click link below to change your password: <strong>$generated_verification_code</strong> , and click the link below.</p>
                        <a href='https://teachlabs.unaux.com/views/recoverpassword.php?userid=$userid'>Recover Password</a>"];
               $sql2 = "select  * from forgetpwd where userid = '$userid'";
               $result2 = mysqli_query($connection,$sql2);
               // if user already request new recover and request again we update database
               if(mysqli_num_rows($result2) > 0){
                $sql3= "update forgetpwd set recoverstatus = '0' , code = '$generated_verification_code'  where userid = '$useridformdb'";
                $result3 = mysqli_query($connection,$sql3);
                if($result3){
                    
                sendemail($email,$row['fullname'],$emailarray['subject'],$emailarray['body']);
                   $response = 'success';
                }
            }else{
                $sql4 = "insert into forgetpwd(userid,recoverstatus,code)values('$useridformdb','0','$generated_verification_code')";
                $result4 = mysqli_query($connection,$sql4);
                if($result4){
                    sendemail($email,$row['fullname'],$emailarray['subject'],$emailarray['body']);
                   $response = 'success';
                   
                }

                        
            }

            if(isset($response)){
                header('location:../views/forgetpwd.php?emailsent');
                  exit();
            }else{
                header('location:../views/forgetpwd.php?emailfailed');
                exit();
            }
        }
    }
}
}