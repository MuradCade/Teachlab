<?php

include('../../../model/dbcon.php');

if(isset($_POST['submit'])) {
    $userid = mysqli_escape_string($connection,base64_decode($_POST['userid']));
    $substatus = trim($_POST['substatus']);

    if(empty(trim($userid))){
        header('location:../viewusers.php?useridnotpassed');
        exit();
    }else{
        // if substatus equals expire we change subscription substatus and we make it expire and also we change subamount to 0
        if ($substatus == 'expire') {
            
            $sql = "update subscription set subplan = 'pro',  subsatus ='expire' , subamount = '0' where  userid = '$userid'";
            $result = mysqli_query($connection, $sql);
            if ($result) {
                header('location:../viewusers.php?subscriptionsuccess');
                exit();
            } else {
                header('location:../viewusers.php?subscriptionfailed');
                exit();
            }
        }else if($substatus == 'active'){
            $sql2 = "update subscription set subplan = 'pro',  subsatus ='active' , subamount = '30' where  userid = '$userid'";
            $result2 = mysqli_query($connection, $sql2);
            if ($result2) {
                header('location:../viewusers.php?subscriptionsuccess');
                exit();
            } else {
                header('location:../viewusers.php?subscriptionfailed');
                exit();
            }
        }else if($substatus == 'one-time-purches'){
            $sql2 = "update subscription set subplan = 'one-time-purches', subsatus ='active' , subamount = 'infinite' where  userid = '$userid'";
            $result2 = mysqli_query($connection, $sql2);
            if ($result2) {
                header('location:../viewusers.php?subscriptionsuccess');
                exit();
            } else {
                header('location:../viewusers.php?subscriptionfailed');
                exit();
            }
        }
        
        else{
            header('location:../viewusers.php?actionredirected');
            exit();
        }

       
}
}else{
    header('location:../viewusers.php?redirected');
    exit();
}