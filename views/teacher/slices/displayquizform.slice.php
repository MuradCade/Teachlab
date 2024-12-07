<?php

function displayquizform($connection,$teacherid){

    $sql = "select * from quizform where teacherid = '$teacherid'";
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
