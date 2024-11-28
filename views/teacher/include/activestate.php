<?php
// sidebar is not showing color of activeness to indicate where is the user or what page is the user is inside so 
// this function below handles that it shows active color to let user now where he is
function checkactivesidebar($url , $path){

    if(basename($url) == $path){
        echo "bg-primary ";
        echo "text-white ";
    }else{
        echo " text-secondary ";
    }

}