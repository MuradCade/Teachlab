<?php
include('../../../model/dbcon.php');
include_once('../../../include/subscripton_monthly_calcualte.php');
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
  
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty($_POST['fullname'])){
        echo 'emptyfullname';
    }
    else if(empty($_POST['number'])){
        echo 'emptynumber';
        
    }
    else  if(empty($_POST['paymentdate'])){
       echo 'emptypaymentdate';

    }else{
    //   currentdate
    // Get the current month and year
            $current_month = date('m'); // Current month (01-12)
            $current_year = date('Y'); 
        
        // check if there is already subscription with current date
        $sql = "select * from usersubscription_manager where userid = '{$_SESSION['userid']}'AND MONTH(started_date) = '$current_month' AND YEAR(	started_date) = '$current_year' AND payment_status = 'paid' AND subscription_status = 'active' ";
        $result = mysqli_query($connection,$sql);
        if(mysqli_num_rows($result) == 0){
            $subscriptions = calculateSubscription($_POST['paymentdate'],'monthly');
            if($_POST['amount'] == 'pro'){
                $data = ['subplan'=>'pro', 'amount'=>'10','daysleft'=>'30'];
                $sql2 = "insert into usersubscription_manager(userid,fullname,number,subscription_plan,amount,started_date,expire_date,days_left,subscription_status,payment_status)values('{$_SESSION['userid']}','{$_POST['fullname']}', '{$_POST['number']}', '{$data['subplan']}' , '{$data['amount']}', '{$_POST['paymentdate']}' , '{$subscriptions['end_date']}','{$data['daysleft']}','active','paid')";
            }else if($_POST['amount'] == 'one-time-purches'){
                   $data = ['subplan'=>'one-time-purches', 'amount'=>'50','daysleft'=>'infinite'];
                   $sql2 = "insert into usersubscription_manager(userid,fullname,number,subscription_plan,amount,started_date,expire_date,days_left,subscription_status,payment_status)values('{$_SESSION['userid']}','{$_POST['fullname']}', '{$_POST['number']}', '{$data['subplan']}' , '{$data['amount']}', '{$_POST['paymentdate']}' , '{$subscriptions['end_date']}','{$data['daysleft']}','active','paid')";
                
               }
            // nothing found with specified date
            // $sql2 = "insert into usersubscription_manager(userid,fullname,number,subscription_plan,amount,started_date,expire_date,days_left,subscription_status)values('{$_SESSION['userid']}','{$_POST['fullname']}', '{$_POST['number']}', '{$data[$_POST['subplan']]}' , '{$_POST['amount']}', '{$_POST['paymentdate']}' , '{$subscriptions['end_date']}','{$data['daysleft']}','active')";
            $result2 = mysqli_query($connection,$sql2);

            if($result2){
                echo 'success';
            }else{
                echo 'failed';
            }
        }else{
            // data found with specified date
            echo 'subscriptionisexsit';
        }
    }
}