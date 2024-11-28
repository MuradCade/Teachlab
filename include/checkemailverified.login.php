<?php
// check if user trying to login is verified or not
function checkifuserverifiedwhenlogin($data1,$data2){
    if($data1==$data2){
        return true;
    }else{
        return false;
    }
}