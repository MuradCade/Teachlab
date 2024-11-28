<?php
echo "<script>";
echo "setdata('name','helo world')";
echo "</script>";

$Data = "<script>document.write(localStorage.getItem('userid'));</script>";
print_r($Data);

$remove = "<script>localStorage.removeItem('test');</script>";
 echo $remove;

 ?>





<script>

function setdata($keys,$value){
    localStorage.setItem($keys,$value);
}



</script>