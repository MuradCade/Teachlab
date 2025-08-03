<?php

// displays singl quiz form information when users clicks on details button in viewquiz file

function displaysinglequizform($connection, $quizformid){
    $data = [];
    $quizformid = base64_decode($quizformid);

    $sql = "select * from quizform where quizformid = '$quizformid'";
    $result = mysqli_query($connection,$sql);
    if(mysqli_num_rows($result) == 0){
        echo 'Sorry , something went wrong please refresh the page and try again later';
    }else{
        while($row = mysqli_fetch_assoc($result)){
            $data[] = $row;
        }

        return $data;
    }
}