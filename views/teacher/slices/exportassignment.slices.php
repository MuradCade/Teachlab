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
if(isset($_GET['assignmentformid'])){
    $formid = $_GET['assignmentformid'];
    //  read all studendata related to specified coursename with teacherid
    $sql = "select * from assignmententries where formid = '$formid'";
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
            $filesize = round($rows['filesize']);
                //code descriping the cells title
            $activesheet->setCellValue('A1','#');
            $activesheet->setCellValue('B1','Student ID');
            $activesheet->setCellValue('C1','Student Fullname');
            $activesheet->setCellValue('D1','Coursename');
            $activesheet->setCellValue('E1','Assignment Marks');
            $activesheet->setCellValue('F1','Uploaded Filename');
            $activesheet->setCellValue('G1','File_Size');
            $activesheet->setCellValue('H1','Date');
        // code descriping the cells content
        $activesheet->setCellValue('A'.$rowid,$rowid);
        $activesheet->setCellValue('B'.$rowid,$row['stdid']);
        $activesheet->setCellValue('C'.$rowid,$row['stdfullname']);
        $activesheet->setCellValue('D'.$rowid,$row['coursename']);
        $activesheet->setCellValue('E'.$rowid,$row['marks']);
        $activesheet->setCellValue('F'.$rowid,$row['uploadedfile']);
        $activesheet->setCellValue('G'.$rowid,round($row['filesize']).'MB');
        $activesheet->setCellValue('H'.$rowid,date('M-j-Y ', strtotime($row['date'])));

    //   echo $row['stdid'];
        $rowid++;

      

    }
        // if($rowid === mysqli_num_rows($result)){
            // echo 'match';

            // generate filename
            $filename = 'assignmententeries.xlsx';
            
            ob_end_clean();
          
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'.urlencode($filename).'"');
            $excelwrite->save('php://output');
        // }



    }else{
        header('location:../viewassignmentform.php?dbqueryfailed');
    exit();
    }
  
}else{
    header('location:../viewassignmentform.php?redirect');
    exit();
}