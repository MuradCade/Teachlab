<?php

// dis function  is responsible to notify teacher that it should add  new   coursename before adding new  student
// and then it displays coursenames that is created by the teacher , and teacher have the baility to create student because
// it created coursname first(ka hor inta uu macalinku diwan gelin aray wa inuu soo samayee course magacisa)

function coursenames($teacherid,$connection){
    $sql = "select * from course where teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    if(mysqli_num_rows($result) == 0){
        return false;
    }
    $data= array();
   while($row = mysqli_fetch_assoc($result))
    $data[]  = $row;  
  
    
    return $data;
    
}  