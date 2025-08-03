<?php
// Ensure we have a database connection and user is logged in
include_once('../../model/dbcon.php');
session_start();

if(isset($_POST['submitquiz'])) {

   
    $answers = $_POST['answers'];
    $option_one = 'a';
    $option_two = 'b';
    $option_three = 'c';
    // Optional: Display the processed answers
    // echo "<h3>Processed Answers:</h3>";
    // foreach($answers as $questionId => $selectedAnswer) {
       
    //     echo  $questionId . (is_array($selectedAnswer) ? implode(', ', $selectedAnswer) : $selectedAnswer) . "<br>";
if(empty($answers)){
    header('location:take_exam.php?examformid='.base64_encode($_SESSION['examformid']).'&emptyanswersfields');
}else{
    foreach($answers as $questionId => $selectedAnswer) {
        
        if(implode(',',$selectedAnswer) == 'option_one'){
         $selectedAnswer = $option_one;
        }elseif(implode(',',$selectedAnswer) == 'option_two'){
         $selectedAnswer = $option_two;
        }elseif(implode(',',$selectedAnswer) == 'option_three'){
         $selectedAnswer = $option_three;
        }
        
        $sql = "insert into studentexam (stdid, stdfullname, examformid, question_id, selected_option,exammarks) values('{$_SESSION['studentid']}','{$_SESSION['studentname']}','{$_SESSION['examformid']}','{$questionId}','{$selectedAnswer}','0')";
        $result = mysqli_query($connection,$sql);    
      
        
         // echo implode(',',$selectedAnswer);  
     }
     if($result){
         
         unset($_SESSION['examtitle']);
         unset($_SESSION['examdesc']);  
         unset($_SESSION['studentid']);
         unset($_SESSION['studentname']);
        header('location:take_exam.php?examformid='.base64_encode($_SESSION['examformid']).'&success');
        exit();
       }else{
        header('location:take_exam.php?examformid='.base64_encode($_SESSION['examformid']).'&failed');
        exit();
       }

}
    
    
}else{
 
 if(isset($_POST['quitexam'])){
     unset($_SESSION['examtitle']);
     unset($_SESSION['examdesc']);  
     unset($_SESSION['studentid']);
     unset($_SESSION['studentname']);
     header('location:take_exam.php?examformid='.base64_encode($_SESSION['examformid']).'&examquited');
     exit();
 }
}