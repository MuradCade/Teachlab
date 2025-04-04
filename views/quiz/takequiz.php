<?php
include('../../model/dbcon.php');
if(session_status() == PHP_SESSION_NONE){
  session_start();
}



if(isset($_GET['quizformid']) && !empty($_GET['quizformid'])){
  $quizformid = base64_decode($_GET['quizformid']);
  $sql = "select * from quizform where quizformid = '$quizformid'";
  $result = mysqli_query($connection,$sql);
 

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TeachLab - Take Quiz</title>
    <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <!-- this file is responsible for displaying the quiz to the student -->
     <?php if($result){
  while($row = mysqli_fetch_assoc($result)){
    // for security purpose we will check the quiz status is active or not
    if($row['quizstatus'] == 'disable' || $row['quizstatus'] == 'draft'){?>
      <div class="container">
      <div class="row">
        <div class="col-5 col-md-6 col-sm-12 mx-auto ">
        <div class="card p-2 mt-5">
          <h4 class="card-header">
            Quiz Form
          </h4>
          <div class="card-body">
          <p class="bg-danger text-white fw-bold p-2">This quiz form is no longer accepting submissions.</p>
          </div>
        </div>
        </div>
      </div>
      </div>
       <p class='text-center mt-2'>This Form Is powered by <a href='https://teachlabs.unaux.com/'>TeachLab</a></p>
   <?php }else if(!isset($_SESSION['studentid']) && $row['quizstatus'] == 'active'){?>
  
      <div class="container" id='quizstudentintro' s>
        <div class="row">
          <div class="col-lg-8 col-md-8 col-sm-12 mx-auto mt-5">
            <div class="card">
              <?php if(isset($_GET['success'])){?>
                <p class='bg-success text-white p-2'>Thank You , Quiz Submitted Successfully</p>
              <?php } ?>  
              <?php if(isset($_GET['emptyanswersfields'])){?>
                <p class='bg-danger text-white p-2'>Please Fill The Form In Order To Begin Taking The Quiz</p>
              <?php } ?>  
              <?php if(isset($_GET['alreadyattempted'])){?>
                <p class='bg-danger text-white p-2'>Student with specified ID has already taken this quiz</p>
              <?php } ?>  
              <div class="card-header">
                <p class='card-title fw-bold'><?php echo strtoupper($row['quiztitle']); ?></p>
                <p class='text-truncate text-break'><?php echo $row['quizdesc'] ?></p>
              </div>  
              <div class="card-body">
              <form method="post">
              <div class="form-group">
                  <input type="hidden" class='form-control' name='quiztitle'  value="<?php echo $row['quiztitle'] ?>" >
                  <input type="hidden" class='form-control' name='quizdesc'  value="<?php echo $row['quizdesc'] ?>" >
                  <input type="hidden" class='form-control' name='quizformid'  value="<?php echo $row['quizformid'] ?>" >
                </div>
              <div class="form-group">
                  <label class='form-label'>Studen ID</label>
                  <input type="text" class='form-control' name='studentid'  placeholder='Enter Student ID'>
                </div>
                <div class="form-group mb-2 mt-3">
                  <label class='form-label'>Studen Name</label>
                  <input type="text" class='form-control' name='studentname' placeholder='Enter Student Name'>
                </div>
                <button type='submit' class='btn btn-primary btn-sm mt-2 fw-bold' name='takequiz'>Take The Quiz</button>
              </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center" id='water-mark'>
        <p class='mt-2'>This Form Is Powered By <a href="https://teachlabs.unaux.com/">TeachLab</a></p>
        </div>
     
   <?php }?>
   
  <?php }} ?>
 
 <!-- dipslay success and failed message -->

   
    <!-- when take quiz button is clicked  we show the quiz questions -->
     <!-- we should create session and store the quizformid,quiztitle,quizdesc,studentid,studentname -->
     <?php if(isset($_POST['takequiz'])){
      $quizformid = $_POST['quizformid']; 
      $quiztitle = $_POST['quiztitle'];
      $quizdesc = $_POST['quizdesc'];
      $studentid = $_POST['studentid'];
      $studentname = $_POST['studentname'];
      if(empty($studentid) || empty($studentname)){
        header('location:takequiz.php?quizformid='.base64_encode($quizformid).'&emptyanswersfields');
        exit();
      }else{
        $sql = "select * from studentquiz where quizformid = '$quizformid' and stdid = '$studentid'";
        $result = mysqli_query($connection,$sql);
        if(mysqli_num_rows($result) > 0){
          header('location:takequiz.php?quizformid='.base64_encode($quizformid).'&alreadyattempted');
          exit();
        }else{
      $_SESSION['quizformid'] = $quizformid;
      $_SESSION['quiztitle'] = $quiztitle;
      $_SESSION['quizdesc'] = $quizdesc;
      $_SESSION['studentid'] = $studentid;
      $_SESSION['studentname'] = $studentname;
        }
      
      }
     

      ?>
      
     
      <?php }?>
<!-- show the quiz questions card-->
      <?php if(isset($_SESSION['studentid'])){
          $existingQuestions = [];
          $existingoptions = [];
          // get all the questions
          $sql = "SELECT * FROM questions WHERE quizformid = '{$_SESSION['quizformid']}' ORDER BY questionid";
          $result = mysqli_query($connection, $sql);
          
          while ($question = mysqli_fetch_assoc($result)) {
              $existingQuestions[$question['questionid']] = $question;
              // Fetch corresponding options
              $optionsQuery = "SELECT * FROM options WHERE questionid = '{$question['questionid']}' AND quizformid = '{$_SESSION['quizformid']}'";
              $optionsResult = mysqli_query($connection, $optionsQuery);
              $existingOptions[$question['questionid']] = mysqli_fetch_assoc($optionsResult);
             
          }
        ?>
        <style>
        #quizstudentintro{
          display:none;
        }
        #water-mark{
          display:none;
        }
        </style>
        <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-12 col-sm-5 mx-auto mt-3">
            
            <div class="card">
              <div class="card-header">
                <p class='card-title fw-bold'><?php echo strtoupper($_SESSION['quiztitle']); ?></p>
                <p class='text-break'><?php echo $_SESSION['quizdesc'] ?></p>
              </div>
              <div class="card-body">
        <!--display message to notify the student that the quiz is not ready -->
              <?php 
            if(mysqli_num_rows($result) == 0){?>
             <p class='alert alert-danger p-2 '>Dear student, the quiz hasn’t been created yet. Please check back later.</p>
            <?php }?>
                <?php if(isset($_GET['emptyanswersfields'])){ ?>
                  <p class='bg-danger text-white p-2'>Please select at least one answer</p>
                <?php } ?>
              <form method="post" action='savestudentquiz.php'>
              <?php 
             
              $sql01 = "select quiztype from quizform where quizformid = '$quizformid'";
              $result01 = mysqli_query($connection,$sql01);
              $quiztyperow = mysqli_fetch_assoc($result01);
              if($quiztyperow['quiztype'] === "singlechoicequestion"){
              foreach($existingQuestions as $question): 
                // first check if the question tyoe is 
                
                // var_dump($quiztyperow);
                                // Fetch options for current question
                                $optionsQuery = "SELECT * FROM options WHERE questionid = '{$question['questionid']}' AND quizformid = '$quizformid'";
                                $optionsResult = mysqli_query($connection, $optionsQuery);
                                $options = mysqli_fetch_assoc($optionsResult);
                            ?>
                            <div class="mb-4">
                                <p class='form-label mb-3' style='line-height:1.5em !important;'>
                                    <strong><?php echo $question['questionid'].' ) '?></strong> 
                                    <?php echo htmlspecialchars($question['questiontext']);?>
                                </p>
                                
                                <div class="ms-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="answers[<?php echo $question['questionid']; ?>][]" 
                                               value="option_one" >
                                        <label class="form-check-label">
                                            A- <?php echo htmlspecialchars($options['option_one']); ?>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="answers[<?php echo $question['questionid']; ?>][]" 
                                               value="option_two">
                                        <label class="form-check-label">
                                            B- <?php echo htmlspecialchars($options['option_two']); ?>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="answers[<?php echo $question['questionid']; ?>][]" 
                                               value="option_three">
                                        <label class="form-check-label">
                                            C- <?php echo htmlspecialchars($options['option_three']); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php }else if ($quiztyperow['quiztype'] === "trueandfalse"){
                             
                              foreach($existingQuestions as $question): 
                                // first check if the question tyoe is 
                                
                                // var_dump($quiztyperow);
                                                // Fetch options for current question
                                                $optionsQuery = "SELECT * FROM true_false_options WHERE questionid = '{$question['questionid']}' AND quizformid = '$quizformid'";
                                                $optionsResult = mysqli_query($connection, $optionsQuery);
                                                $options = mysqli_fetch_assoc($optionsResult);
                              ?>
                 
                            
                            <div class="mb-4">
                                <p class='form-label mb-3' style='line-height:1.5em !important;'>
                                    <strong><?php echo $question['questionid'].' ) '?></strong> 
                                    <?php echo htmlspecialchars($question['questiontext']);?>
                                </p>
                                
                                <div class="ms-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="answers[<?php echo $question['questionid']; ?>][]" 
                                               value="option_one" >
                                        <label class="form-check-label">
                                            A- <?php echo htmlspecialchars($options['option_one']); ?>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="answers[<?php echo $question['questionid']; ?>][]" 
                                               value="option_two">
                                        <label class="form-check-label">
                                            B- <?php echo htmlspecialchars($options['option_two']); ?>
                                        </label>
                                    </div>
                                   
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php } ?> 
              
                             <!-- if there are no questions hide the submit button-->
                            <?php 
            if(mysqli_num_rows($result) > 0){?>
            <button type='submit' class='btn btn-primary btn-sm mt-2 fw-bold' name='submitquiz'>Submit Quiz</button>
            <?php } ?>
               


              </form>
              </div>
            </div>
          </div>
        </div>
      </div>
        <?php }?>


       

      <!-- the end of the quiz question form-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
<!-- if there is no quizformid then redirect to the teacher viewquizform page -->
<?php }else{ ?>


<?php } ?>