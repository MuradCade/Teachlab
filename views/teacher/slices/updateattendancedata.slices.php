<?php
include('../../../model/dbcon.php');

if(isset($_POST['updatesinglestudentattendance'])){
    if(isset($_GET['stdid']) && isset($_GET['columnid'])){
        $studentid = $_GET['stdid'];
        $columnid = $_GET['columnid'];
        $present = $_POST['present'] == 'yes'? '1' : '0' ;
        $attendancemarks = $_POST['attendancemarks'];
        $absent = $_POST['absent'] == 'yes' ? '1' : '0';
        if(empty($present) && empty($absent) && empty($attendancemarks)){
            header('location:../viewattadencemarks.php?emptyfield');
        exit();
        }
        else{

            $sql = "update markattendence set attendedmarks = '$attendancemarks',present_column='$present', absent_column = '$absent' where id = '$columnid' and stdid ='$studentid'";
            $result = mysqli_query($connection,$sql);
            if($result){
                header('location:../viewattadencemarks.php?attendanceupdatedsuccessfully');
                exit();
            }else{
                header('location:../viewattadencemarks.php?updatefailed');
                exit();
            }
        }

    }else{
        header('location:../viewattadencemarks.php?redirectone');
        exit();
    }
}else{
    header('location:../viewattadencemarks.php?redirecttwo');
    exit();
}