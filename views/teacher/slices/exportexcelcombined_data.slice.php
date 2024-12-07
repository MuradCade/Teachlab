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
    $sql = "SELECT markattendence.stdid, 
                                                        markattendence.stdfullname, 
                                                        markattendence.coursename, 
                                                        SUM(markattendence.attendedmarks) AS attandancemarks, 
                                                        COALESCE(assignment_totals.assignmentmarks, 0) AS assignmentmarks,
                                                        COALESCE(quiz_totals.quizmarks, 0) AS quizmarks
                                                     FROM 
                                                        markattendence 
                                                     LEFT JOIN 
                                                        (SELECT stdid, SUM(marks) AS assignmentmarks FROM assignmententries GROUP BY stdid) AS assignment_totals 
                                                     ON 
                                                        markattendence.stdid = assignment_totals.stdid 
                                                     LEFT JOIN 
                                                        (SELECT stdid, quizmarks  FROM studentquiz GROUP BY stdid) AS quiz_totals 
                                                     ON 
                                                        markattendence.stdid = quiz_totals.stdid 
                                                     WHERE 
                                                        teacherid = '$teacherid' 
                                                        AND coursename = '$coursename' 
                                                     GROUP BY 
                                                        markattendence.stdid, 
                                                        markattendence.stdfullname;";
    $result = mysqli_query($connection,$sql);
   if(mysqli_num_rows($result) == 0){
    header('location:../combineresult.php?emptdb');
    exit();
   }else{
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
             $activesheet->setCellValue('D1','Coursename');
             $activesheet->setCellValue('E1','Total_Attandence_Marks');
             $activesheet->setCellValue('F1','Total_Assignment_Marks');
             $activesheet->setCellValue('G1','Total_Quiz_Marks');
         // code descriping the cells content
         $activesheet->setCellValue('A'.$rowid,$rowid);
         $activesheet->setCellValue('B'.$rowid,$row['stdid']);
         $activesheet->setCellValue('C'.$rowid,$row['stdfullname']);
         $activesheet->setCellValue('D'.$rowid,$row['coursename']);
         $activesheet->setCellValue('E'.$rowid,$row['attandancemarks']);
         $activesheet->setCellValue('F'.$rowid,$row['assignmentmarks']);
         $activesheet->setCellValue('G'.$rowid,$row['quizmarks']);
 
     //   echo $row['stdid'];
         $rowid++;
 
       
 
     }
         // if($rowid === mysqli_num_rows($result)){
             // echo 'match';
 
             // generate filename
             $filename = 'AllData'.'xls';
             
             ob_end_clean();
           
             header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
             header('Content-Disposition: attachment; filename="'.urlencode($filename).'"');
             $excelwrite->save('php://output');
         // }
 
 
 
     }else{
         header('location:../viewstudent.php?dbqueryfailed');
     exit();
     }
   }
   
   
  
}else{
    header('location:../viewstudent.php?redirect');
    exit();
}