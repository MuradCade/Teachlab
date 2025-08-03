<?php

function displaysingleassignmentformdata($connection,$assignmentformid){
    $data = [];
    $sql = "select * from assignmentform where formid = '$assignmentformid'";
    $result = mysqli_query($connection,$sql);
    if(mysqli_num_rows($result) == 0){
        echo 'there is no data to be shown';
    }else{
        while($row = mysqli_fetch_assoc($result)){

            $data[] = $row;
        }
    }

    return $data;
}