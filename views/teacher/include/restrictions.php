<!-- this file is meant to restrict free plan user and only allow 1 course , 1 assignment form , 1 quiz form creation -->

<?php


function checkcourseamount($connection,$userid,$subplan){
    $sql = "select * from subscription where userid = '$userid' and subplan = '$subplan'";
    $result = mysqli_query($connection,$sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            if($row['userid'] == $userid && $row['subplan'] == $subplan){
                $sql2  = "select count(*) as totalcourse from course where teacherid = '$userid'";
                $result2 = mysqli_query($connection,$sql2);
                if($result2){
                    $row2 = mysqli_fetch_assoc($result2);
                    if($row2['totalcourse'] >=1){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
    }
}


function checkassignmentamount($connection,$userid,$subplan){
    $sql = "select * from subscription where userid = '$userid' and subplan = '$subplan'";
    $result = mysqli_query($connection,$sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            if($row['userid'] == $userid && $row['subplan'] == $subplan){
                $sql2  = "select count(*) as totalassignment from assignmentform where teacherid = '$userid'";
                $result2 = mysqli_query($connection,$sql2);
                if($result2){
                    $row2 = mysqli_fetch_assoc($result2);
                    if($row2['totalassignment'] >=1){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
    }
}


function checkquizamount($connection,$userid,$subplan){
    $sql = "select * from subscription where userid = '$userid' and subplan = '$subplan'";
    $result = mysqli_query($connection,$sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            if($row['userid'] == $userid && $row['subplan'] == $subplan){
                $sql2  = "select count(*) as totalquiz from quizform where teacherid = '$userid'";
                $result2 = mysqli_query($connection,$sql2);
                if($result2){
                    $row2 = mysqli_fetch_assoc($result2);
                    if($row2['totalquiz'] >=1){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
    }
   
}




function checkifusersubsscriptionexpired($connection,$userid,$subplan,$substatus){
    $sql = "select * from subscription where userid = '$userid'";
    $result = mysqli_query($connection,$sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            if($row['subsatus'] == 'pro' &&$row['subplan'] == 'expire'){
                return true;
            }else{
                return false;
            }
        }
    }
}
