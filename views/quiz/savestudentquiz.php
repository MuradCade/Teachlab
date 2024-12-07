<?php
// Ensure we have a database connection and user is logged in
include_once('../../model/dbcon.php');
session_start();

if(isset($_POST['submitquiz'])) {
    // Add this line to see the submitted data
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    
    $answers = $_POST['answers'];
    $option_one = 'a';
    $option_two = 'b';
    $option_three = 'c';
    // Optional: Display the processed answers
    // echo "<h3>Processed Answers:</h3>";
    // foreach($answers as $questionId => $selectedAnswer) {
       
    //     echo  $questionId . (is_array($selectedAnswer) ? implode(', ', $selectedAnswer) : $selectedAnswer) . "<br>";
if(empty($answers)){
    header('location:takequiz.php?quizformid='.base64_encode($_SESSION['quizformid']).'&emptyanswersfields');
}else{
    foreach($answers as $questionId => $selectedAnswer) {
        
        if(implode(',',$selectedAnswer) == 'option_one'){
         $selectedAnswer = $option_one;
        }elseif(implode(',',$selectedAnswer) == 'option_two'){
         $selectedAnswer = $option_two;
        }elseif(implode(',',$selectedAnswer) == 'option_three'){
         $selectedAnswer = $option_three;
        }
        
        $sql = "insert into studentquiz (stdid, stdfullname, quizformid, question_id, selected_option,quizmarks) values('{$_SESSION['studentid']}','{$_SESSION['studentname']}','{$_SESSION['quizformid']}','{$questionId}','{$selectedAnswer}','0')";
        $result = mysqli_query($connection,$sql);    
      
        
         // echo implode(',',$selectedAnswer);  
     }
     if($result){
         
         unset($_SESSION['quiztitle']);
         unset($_SESSION['quizdesc']);  
         unset($_SESSION['studentid']);
         unset($_SESSION['studentname']);
        header('location:takequiz.php?quizformid='.base64_encode($_SESSION['quizformid']).'&success');
        exit();
       }else{
        header('location:takequiz.php?quizformid='.base64_encode($_SESSION['quizformid']).'&failed');
        exit();
       }

}
    
    
}