<?php

function checksubscriptionstatus($connection,$teacherid,$params){
    $sql = "select * from subscription where userid = '$teacherid'";
    $result = mysqli_query($connection,$sql);

    while($row = mysqli_fetch_assoc($result)){
        return $row[$params];
    }

}