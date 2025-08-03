<?php
include('../../../model/dbcon.php');
require '../../../vendor/autoload.php';


// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$teacherid = $_SESSION['userid'] ?? null;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//export studentdata as excel file
if(isset($_GET['coursename'])){
    $coursename = $_GET['coursename'];
    //  read all studendata related to specified coursename with teacherid
    $sql = "select stdid,stdfullname,coursename,sum(attendedmarks) as totalmarks from markattendence where coursename = '$coursename' and teacherid = '$teacherid' group by stdid";
    $result = mysqli_query($connection,$sql);
   
   
    //object of phpspreadsheet
    $spreadshhet = new Spreadsheet();
    $excelwrite = new Xlsx($spreadshhet);
    $spreadshhet->setActiveSheetIndex(0);
    $activesheet = $spreadshhet->getActiveSheet();




    if(mysqli_num_rows($result) > 0){
        $rowid = 2;
        // $colid = 0;
        while($row = mysqli_fetch_assoc($result)){
                //code descriping the cells title
            $activesheet->setCellValue('A1','#');
            $activesheet->setCellValue('B1','Student ID');
            $activesheet->setCellValue('C1','Student Fullname');
            $activesheet->setCellValue('D1','Course Name');
            $activesheet->setCellValue('E1','Total Marks');
        // code descriping the cells content
        $activesheet->setCellValue('A'.$rowid,$rowid);
        $activesheet->setCellValue('B'.$rowid,$row['stdid']);
        $activesheet->setCellValue('C'.$rowid,$row['stdfullname']);
        $activesheet->setCellValue('D'.$rowid,$row['coursename']);
        $activesheet->setCellValue('E'.$rowid,$row['totalmarks']);

    //   echo $row['stdid'];
        $rowid++;

      

    }
        // if($rowid === mysqli_num_rows($result)){
            // echo 'match';

            // generate filename
            $filename = 'Attandance_document.xlsx';
            
            ob_end_clean();
          
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'.urlencode($filename).'"');
            $excelwrite->save('php://output');
        // }



    }else{
        header('location:../viewstudent.php?dbqueryfailed');
    exit();
    }
  
}else{
    header('location:../viewstudent.php?redirect');
    exit();
}