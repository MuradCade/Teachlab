<?php
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../model/dbcon.php');
include_once('send_email.php');
include_once ('subscripton_monthly_calcualte.php');

if(isset($_POST['singup'])){

    $email = $_POST['email'];
    $fullname = $_POST['fullname'];
    $pwd = $_POST['pwd'];
    $subscriptionplan = $_POST['subscriptionplan'];

    


    //validate input fields
    if(empty($subscriptionplan)){
        header('Location:../index.php?#pricing');
        exit();
    }
    else if(empty($email)){
        header('Location:../views/signup.php?emptyemailfield');
        exit();
    }else if(empty($fullname)){
        header('Location:../views/signup.php?emptyfullname');
        exit();
    }else if(empty($pwd)){
        header('Location:../views/signup.php?emptypwdfield');
        exit();
    }   
    else{
       

        //query
        $sql = "select * from users where email = '$email'";
        $result = mysqli_query($connection,$sql);
        
        // if email is not already exists
        if(mysqli_num_rows($result) == 0){

            // hash the password
            $hashpwd = password_hash($pwd,PASSWORD_DEFAULT);
            //generate id for the user
            $userrandomid = rand ( 10000 , 99999 );;
            //generate random number for email verificiation
            $letterandnumbers = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $verification_code_length = 6; // number of digit and letters in verification code
            $generated_verification_code = substr(str_shuffle($letterandnumbers), 
                       0, $verification_code_length );
                
                        $mailinfo = ['subject'=>'Email Confirmation Code' ,'body'=>"
                        <table width='100%' cellspacing='0' cellpadding='0'>
                                <tbody>
                                <tr>
                                <td dir='ltr'>
                                <p style='text-align: center;''>Here is your code:</p>
                                </td>
                                </tr>
                                <tr style='text-align: center;'>
                                <td>
                                <h1><strong>$generated_verification_code</strong></h1>
                                </td>
                                </tr>
                                <tr style='text-align: center;'>
                                <td dir='ltr'>
                                <blockquote>
                                <p>Dear $fullname, The code will be active as long as you need it to verify your account, so don't worry.</p>
                                <p>If you are un able to verify your account now , you can always verify your account later by following link below.</p>
                                <p><a href='https://teachlabs.unaux.com/views/verify_email_code.php?emailsent'>Verify Your Account.</a></p>
                                </blockquote>
                                </td>
                                </tr>
                                </tbody>
                                </table>
                                <p style='text-align: center;'>&nbsp;</p>
                    "];
                    // <h4>Dear $fullname , please verify your email to complete creating your account on TeachLab</h4>
                    // <h4>Please copy code below to verify your account</h4>
                    // <h4><strong> $generated_verification_code</strong></h4>
                
                        // when email is send we should store everything inside the database
                         // store the generated userid  and generated verification code in email_verification table
                        $query = "insert into email_verification(userid,email_code)values('$userrandomid','$generated_verification_code')";
                        $queryresult = mysqli_query($connection,$query);
                    
                        if($queryresult){

                        //     // store the user data inside the users table
                            $sql2 = "insert into users(userid,fullname,email,pwd,role,verified_status)values('$userrandomid','$fullname',
                            '$email','$hashpwd', 'teacher','0')";
                            $result2 = mysqli_query($connection,$sql2);
                 
                            if($result2){        
                                                // get current date
                                                $currentdate = new DateTime();
                                                $subscriptioncalculated = calculateSubscription($currentdate->format('Y-m-d'), 'monthly');
                                                $expire_date = $subscriptioncalculated['end_date'];
                                                // echo $expire_date;
                                                // die();
                                              if($subscriptionplan == 'free'){
                                               

                                                $sql3 = "insert into subscription(userid,subsatus,subplan,subamount,expire_date)values('$userrandomid','active','free','30','$expire_date')";
                                                
                                            }else if($subscriptionplan == 'pro'){

                                                
                                                $sql3 = "insert into subscription(userid,subsatus,subplan,subamount,expire_date)values('$userrandomid','active','pro','0','$expire_date')";
                                                
                                            }else if($subscriptionplan == 'one-time-purches'){
                                              

                                               $sql3 = "insert into subscription(userid,subsatus,subplan,subamount,expire_date)values('$userrandomid','active','one-time-purches','0','$expire_date')";
                                              }

                                $result3 = mysqli_query($connection,$sql3);
                                if($result3){
                                    $_SESSION['verification_userid'] = $userrandomid;
                                    // send the email
                                    $sendmail = sendemail($email,$fullname,$mailinfo['subject'], $mailinfo['body']);
                                        if($sendmail){
        
                                            header('location:../views/verify_email_code.php?emailsent');
                                            exit();
                                        }else{
                                            header('Location:../views/signup.php?failedtosendemail');
                                            exit();
                                        }
                                }
                                
                                  
                            }
                        }
                        
                    
                
               
           
                

        }else{
             // if email is already exists
             header('Location:../views/signup.php?emailistaken');
             exit();
        }
    }


}else{
    header('Location:../views/signup.php');
    exit();
}
