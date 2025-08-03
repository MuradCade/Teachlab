<?php

function displayexamform($connection,$teacherid){

    $sql = "select * from examform where teacherid = '$teacherid'order by exam_created_date ASC";
    $result = mysqli_query($connection,$sql);
    if(mysqli_num_rows($result) == 0 ){
        return '';
    }else{
        if($result){
            while($row = mysqli_fetch_assoc($result)){
                $quizform[] = $row;
            }
            return $quizform;
        }
    }
   

}
