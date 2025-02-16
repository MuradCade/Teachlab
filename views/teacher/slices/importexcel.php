<?php
include('../../../model/dbcon.php');
require '../../../vendor/autoload.php';


// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$teacherid = $_SESSION['userid'] ?? null;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_POST['save'])){
    $excelfile = $_FILES['excelfile']['name'];
    $typeoffile = $_FILES['excelfile']['type'];
    if(empty($excelfile)){
        header('location:../viewstudent.php?chosefile');
        exit();
    }else{
        $allowedfileextensions = ['xlsx','xls','xlt'];
        //get the uploaded file extension
        $extension = pathinfo($excelfile, PATHINFO_EXTENSION);
        // if file matches our allowed extensions
        if(in_array($extension,$allowedfileextensions)){
            $inputFileName = $_FILES['excelfile']['tmp_name'];
            /** Load $inputFileName to a Spreadsheet object **/
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
            $data =  $spreadsheet->getActiveSheet()->toArray();
            // count variable i used to prevent read each row title inside the excel file
            $count = 0;
           
            foreach($data as $row){
                //preventing to store the title of each row
                if($count > 0){
                    
                    $studentid =  $row['1'];
                    $studentname=  $row['2'];
                    $coursename =  $row['3'];
                  
                //    check if the excel file contains empty columns
                if(empty($studentid)){
                    header('location:../viewstudent.php?emptystudentid');
                    exit();
                }else if(empty($studentname)){
                    header('location:../viewstudent.php?emptystudentname');
                    exit();

                }else if(empty($coursename)){
                    header('location:../viewstudent.php?emptycoursename');
                    exit();
                }else{
                //    check if the student that been upload thier course name exist
                    $sqlmain = "select * from course where coursename = '$coursename'";
                    $resultmain = mysqli_query($connection,$sqlmain);
                    if(mysqli_num_rows($resultmain) == 0){
                        header('location:../viewstudent.php?coursenamenotexist');
                        exit();
                    }else{
                        $sql = "select stdid from students where stdid = '$studentid' and coursename = '$coursename'";
                        $result = mysqli_query($connection,$sql);
                        if(mysqli_num_rows($result) > 0){
                            // data already exist so update it
                            $update = "UPDATE students SET  stdid = '$studentid',stdfullname = '$studentname',coursename = '$coursename' WHERE stdid = $studentid";
                            $updateresult = mysqli_query($connection,$update);
                            //redirect variables help us to let us know if everything is going googd
                            $redirect = true;
                        }else{
    
                            try {
                                $sql2 = "insert into students(stdid,stdfullname,coursename,teacherid)values('$studentid','$studentname','$coursename','$teacherid')";
                                $result2 = mysqli_query($connection,$sql2);
                                
                                // echo 'success';
                                $redirect = 'true';
                                
                                 //data is new insert it into db 
                        // handling duplicate key entery(id alread use if tried to be stored again it will
                        // scream error in mysql database so am fixing that)
                            } catch (mysqli_sql_exception $e) {
                                // echo 'failed';
                                if ($e->getCode() == 1062) {
                                    // echo "Error: Duplicate entry found for ID $e.";
                                    $redirect = 'duplicate_entry';
                                    // $status = 'exist';
                                } else {
                                    echo "Error: " . $e->getMessage();
                                    $redirect = 'false';
                                }
                            }
                       
                             
                          
                        }
                    }
                }
                    
                }else{
                    $count = 1;
                }  
            }
          
            
                // check if sql query runs properly
                if(isset($redirect) && $redirect == 'true'){
                    header('location:../viewstudent.php?savedsuccessfully');
                    exit();
                }else if($redirect == 'duplicate_entry'){
                    header('location:../viewstudent.php?dataexist');
                    exit();
                }else{
                    header('location:../viewstudent.php?filedtoimportfile');
                    exit();
                }

        }else{
        header('location:../viewstudent.php?filenotsupported');
        exit();
        }
        
    }
    
    // allowed file extensions
    // ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

    

}
