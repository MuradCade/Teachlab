<?php
 error_reporting(E_ALL);
 ini_set('display_errors', '1');
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
if(isset($_GET['quizformid'])){
    $quizformid = $_GET['quizformid'];
    // check if the quiztype is true and false / single choice question
    $sqlmain = "select quiztype from quizform where quizformid = '$quizformid'";
    $resultmain = mysqli_query($connection,$sqlmain);
    $quizformdata = mysqli_fetch_assoc($resultmain);
    
if($quizformdata['quiztype'] == 'trueandfalse'){
    //  read all studendata related to specified coursename with teacherid
    $sql1 = "SELECT
                                            sq.stdfullname,
                                            sq.stdid,
                                            sq.quizmarks,
                                            sq.quiz_taken_date,
                                            qf.number_of_questions,
                                            COUNT(sq.question_id) AS total_questions,
                                            COUNT(
                                                CASE WHEN sq.selected_option = o.is_correct_option THEN 1 ELSE NULL END
                                                            ) AS correct_count,
                                                            COUNT(
                                                                CASE WHEN sq.selected_option <> o.is_correct_option THEN 1 ELSE NULL END
                                                            ) AS wrong_count
                                                        FROM
                                                            studentquiz sq
                                                        JOIN TRUE_false_options o
                                                        ON
                                                            sq.question_id = o.questionid 
                                                            AND sq.quizformid = o.quizformid
                                                        JOIN quizform qf 
                                                        ON
                                                            sq.quizformid = qf.quizformid
                                                        WHERE
                                                            qf.teacherid = '$teacherid'
                                                            AND sq.quizformid = '$quizformid'
                                                        GROUP BY
                                                            sq.stdfullname,
                                                            sq.stdid,
                                                            sq.quizmarks,
                                                            sq.quiz_taken_date,
                                                            qf.number_of_questions
                                                            ";
    $result1 = mysqli_query($connection,$sql1);
   
   
    //object of phpspreadsheet
    $spreadshhet = new Spreadsheet();
    $excelwrite = new Xlsx($spreadshhet);
    $spreadshhet->setActiveSheetIndex(0);
    $activesheet = $spreadshhet->getActiveSheet();


    // var_dump(mysqli_num_rows($result));

    if(mysqli_num_rows($result1) > 0){
        $rowid = 2;
        while($row = mysqli_fetch_assoc($result1)){
     

            
                //code descriping the cells title
            $activesheet->setCellValue('A1','#');
            $activesheet->setCellValue('B1','Student ID');
            $activesheet->setCellValue('C1','Student Fullname');
            $activesheet->setCellValue('D1','Wrong Answers');
            $activesheet->setCellValue('E1','Correct Answers');
            $activesheet->setCellValue('F1','Total Question');
            $activesheet->setCellValue('G1','Total Marks');
            $activesheet->setCellValue('H1','Quiz Taken Date');
        // code descriping the cells content
        $activesheet->setCellValue('A'.$rowid,$rowid);
        $activesheet->setCellValue('B'.$rowid,$row['stdid']);
        $activesheet->setCellValue('C'.$rowid,$row['stdfullname']);
        $activesheet->setCellValue('D'.$rowid,$row['wrong_count']);
        $activesheet->setCellValue('E'.$rowid,$row['correct_count']);
        $activesheet->setCellValue('F'.$rowid,$row['number_of_questions']);
        $activesheet->setCellValue('G'.$rowid,$row['quizmarks']);
        $activesheet->setCellValue('H'.$rowid,date('M-j-Y ', strtotime($row['quiz_taken_date'])));

    //   echo $row['stdid'];
        $rowid++;

      

    }
        // if($rowid === mysqli_num_rows($result1)){

            // generate filename
            $filename = 'quizformdata.xls';
            
            ob_end_clean();
          
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'.urlencode($filename).'"');
            $excelwrite->save('php://output');
        // }



    }
    else{
        header('location:../viewquizform.php?dbqueryfailed');
        exit();
    }

}else if($quizformdata['quiztype'] == 'singlechoicequestion'){
//  read all studendata related to specified coursename with teacherid
$sql2 = "SELECT sq.stdfullname,sq.stdid,sq.quizmarks,sq.quiz_taken_date,number_of_questions, COUNT(CASE WHEN sq.selected_option = o.is_correct_option THEN 1 END) AS correct_count, COUNT(CASE WHEN sq.selected_option <> o.is_correct_option THEN 1 END) AS wrong_count, qf.number_of_questions FROM studentquiz sq JOIN options o ON sq.question_id = o.questionid AND sq.quizformid = o.quizformid JOIN quizform qf ON sq.quizformid = qf.quizformid WHERE qf.teacherid = '$teacherid' and sq.quizformid = '$quizformid' GROUP BY sq.stdfullname, sq.stdid, sq.quizmarks, qf.number_of_questions ";
$result2 = mysqli_query($connection,$sql2);


//object of phpspreadsheet
$spreadshhet = new Spreadsheet();
$excelwrite = new Xlsx($spreadshhet);
$spreadshhet->setActiveSheetIndex(0);
$activesheet = $spreadshhet->getActiveSheet();



if(mysqli_num_rows($result2) > 0){
$rowid = 2;

while($row = mysqli_fetch_assoc($result2)){
    // echo $quizformdata['quiztype'];
    // echo "<br>";
    // var_dump($row);

//code descriping the cells title
$activesheet->setCellValue('A1','#');
$activesheet->setCellValue('B1','Student ID');
$activesheet->setCellValue('C1','Student Fullname');
$activesheet->setCellValue('D1','Wrong Answers');
$activesheet->setCellValue('E1','Correct Answers');
$activesheet->setCellValue('F1','Total Question');
$activesheet->setCellValue('G1','Total Marks');
$activesheet->setCellValue('H1','Quiz Taken Date');
// code descriping the cells content
$activesheet->setCellValue('A'.$rowid,$rowid);
$activesheet->setCellValue('B'.$rowid,$row['stdid']);
$activesheet->setCellValue('C'.$rowid,$row['stdfullname']);
$activesheet->setCellValue('D'.$rowid,$row['wrong_count']);
$activesheet->setCellValue('E'.$rowid,$row['correct_count']);
$activesheet->setCellValue('F'.$rowid,$row['number_of_questions']);
$activesheet->setCellValue('G'.$rowid,$row['quizmarks']);
$activesheet->setCellValue('H'.$rowid,date('M-j-Y ', strtotime($row['quiz_taken_date'])));

//   echo $row['stdid'];
$rowid++;



}
// if($rowid === mysqli_num_rows($result2)){

// generate filename
$filename = 'quizformdata.xlsx';

ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.urlencode($filename).'"');
$excelwrite->save('php://output');
// }



}
else{
    header('location:../viewquizform.php?dbqueryfailed');
exit();
}
}
    
  
}
else{
    header('location:../viewquizform.php?redirect');
    exit();
}