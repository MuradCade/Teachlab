<?php 
include_once('../../model/dbcon.php');
if(isset($_GET['quizformid']) && !empty($_GET['quizformid'])){
    $quizformid = $_GET['quizformid'];

    $sql = "select * from quizform where quizformid = '$quizformid'";
    $result = mysqli_query($connection,$sql);
    $row = mysqli_fetch_assoc($result);

//    if the question form submitted
    if(isset($_POST['save'])){
        // how the logic work in the quiz is first we check if the quiz already exist inside the question table and option table
        // then we update it , if is not exist then we insert it in both tables
        $quiztype = $_POST['quiztype'];
        // save the quiz related to true and false questions
        if($quiztype == 'trueandfalse'){
            $questiontext = $_POST['questionstext'];
            $questionid = $_POST['questionid'];
            $option_a = $_POST['option_a'];
            $option_b = $_POST['option_b'];
            $correct_answer = $_POST['correct_answer'];
            $quiz_id = $_POST['quiz_id'];

        foreach($questionid as $key => $value){
            // step1 : check if the question already exist in the question table
            $questionssql = "select * from questions where questionid = '$questionid[$key]' and quizformid = '$quiz_id'";
            $questionsresult = mysqli_query($connection,$questionssql);
            if(mysqli_num_rows($questionsresult) > 0){
                // questions already exist update them
                $updatequestionssql = "update questions set questiontext = '$questiontext[$key]' where questionid = '$questionid[$key]' and quizformid = '$quiz_id'";
                $updatequestionsresult = mysqli_query($connection,$updatequestionssql);
                
                // echo 'from questions';
                //step2 : if the question id is found that means there is options also need to be updated
                if($updatequestionsresult){
                
                    //update existing options in the options table
                    $optionsql = "update true_false_options set option_one = '$option_a[$key]',option_two = '$option_b[$key]',is_correct_option = '$correct_answer[$key]' where questionid = '$questionid[$key]' and quizformid = '$quiz_id'";
                    $optionsresult = mysqli_query($connection,$optionsql);
                    $response = true;
                    
                }
            } else{
                //step3: if neither question nor options exist in the database then insert them
                $sql = "insert into questions(questionid,questiontext,quizformid) values('$questionid[$key]','$questiontext[$key]','$quiz_id')";
                $result = mysqli_query($connection,$sql);
                if($result){
                $sql2 = "insert into true_false_options(questionid,option_one,option_two,is_correct_option,quizformid) values('$questionid[$key]','$option_a[$key]','$option_b[$key]','$correct_answer[$key]','$quiz_id')";
                $result2 = mysqli_query($connection,$sql2);
                
                $response = true;
            }
        }
        }

        if(isset($response)){
                header('location:createquestions.php?quizformid='.$quiz_id.'&success');
                exit();
            }else{
                header('location:createquestions.php?quizformid='.$quiz_id.'&failed');
                exit();
            }
// save the quiz related to single choice questions
        }else if($quiztype =='singlechoicequestion'){
            $questiontext =  $_POST['questionstext'];
            $questionid = $_POST['questionid'];
            $option_a =  $_POST['option_a'];
            $option_b =  $_POST['option_b'];
            $option_c = $_POST['option_c'];
            $correct_answer = $_POST['correct_answer'];
            $quiz_id = $_POST['quiz_id'];
            // echo $quiztype;

            foreach($questionid as $key => $value){
                
                // var_dump($quizformid);
            // step1 : check if the question already exist in the question table
            $questionssql = "select * from questions where questionid = '$questionid[$key]' and quizformid = '$quiz_id'";
            $questionsresult = mysqli_query($connection,$questionssql);
            if(mysqli_num_rows($questionsresult) > 0){
                // questions already exist update them
                $updatequestionssql = "update questions set questiontext = '$questiontext[$key]' where questionid = '$questionid[$key]' and quizformid = '$quiz_id'";
                $updatequestionsresult = mysqli_query($connection,$updatequestionssql);
                
                //step2 : if the question id is found that means there is options also need to be updated
                if($updatequestionsresult){
                    //update existing options in the options table
                    $optionsql = "update options set option_one = '$option_a[$key]',option_two = '$option_b[$key]',option_three = '$option_c[$key]',is_correct_option = '$correct_answer[$key]' where questionid = '$questionid[$key]' and quizformid = '$quiz_id'";
                    $optionsresult = mysqli_query($connection,$optionsql);
                    $response = true;
                    
                }
            } else{
                //step3: if neither question nor options exist in the database then insert them
                $sql = "insert into questions(questionid,questiontext,quizformid) values('$questionid[$key]','$questiontext[$key]','$quiz_id')";
                $result = mysqli_query($connection,$sql);
                if($result){
                $sql2 = "insert into options(questionid,option_one,option_two,option_three,is_correct_option,quizformid) values('$questionid[$key]','$option_a[$key]','$option_b[$key]','$option_c[$key]','$correct_answer[$key]','$quiz_id')";
                $result2 = mysqli_query($connection,$sql2);
                
                $response = true;
            }
        }
        }

        if(isset($response)){
            header('location:createquestions.php?quizformid='.$quiz_id.'&success');
            exit();
        }else{
            header('location:createquestions.php?quizformid='.$quiz_id.'&failed');
            exit();
        }
        }

        
    }
    


// show the questions and the options if they exist in the same form that handls storing and updating them
 $existingQuestions = [];
$existingOptions = [];
if (isset($_GET['quizformid'])) {
    // get the quiz status
    $quizformid = $_GET['quizformid'];
    $quizformid;
    $sqlmain = "select quiztype from quizform where quizformid = '$quizformid'";
    $resultmain = mysqli_query($connection,$sqlmain);
    $quizrow = mysqli_fetch_assoc($resultmain);
    // echo $quizrow['quiztype'];

    // if quizform equals trueandfalse display the trueandfalase options table
    if($quizrow['quiztype'] == 'trueandfalse'){
        
            $questionsQuery = "SELECT * FROM questions WHERE quizformid = '$quizformid' ORDER BY questionid";
            $questionsResult = mysqli_query($connection, $questionsQuery);
            while ($question = mysqli_fetch_assoc($questionsResult)) {
                $existingQuestions[$question['questionid']] = $question;
                
                // Fetch corresponding options
                $optionsQuery = "SELECT * FROM true_false_options WHERE questionid = '{$question['questionid']}' AND quizformid = '$quizformid'";
                $optionsResult = mysqli_query($connection, $optionsQuery);
                $existingOptions[$question['questionid']] = mysqli_fetch_assoc($optionsResult);
            }
            
            // echo json_encode($existingQuestions);
            
        }else{
            $questionsQuery = "SELECT * FROM questions WHERE quizformid = '$quizformid' ORDER BY questionid";
                $questionsResult = mysqli_query($connection, $questionsQuery);
                while ($question = mysqli_fetch_assoc($questionsResult)) {
                    $existingQuestions[$question['questionid']] = $question;
                    
                    // Fetch corresponding options
                    $optionsQuery = "SELECT * FROM options WHERE questionid = '{$question['questionid']}' AND quizformid = '$quizformid'";
                    $optionsResult = mysqli_query($connection, $optionsQuery);
                    $existingOptions[$question['questionid']] = mysqli_fetch_assoc($optionsResult);
                }

                // var_dump($existingOptions);
                    }
        
}
    ?>

    <!-- quiz form status -->
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>TeachLab - Add Quiz Question</title>
            <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        </head>
        <body style='overflow-x:hidden'>
      <?php if($row['quizstatus'] == 'active' || $row['quizstatus'] == 'disable'){?>
    <div class="row">
        <div class="col-lg-7 col-md-10 col-sm-12 mx-auto mt-4">
            <div class="card">
                <div class="card-header">
                    <!--notify the teacher the status of the quizform 1 -->
                    <?php if($row['quizstatus'] == 'disable'){?>
                        <p class='alert alert-danger p-2 '>Dear Teacher, this quiz form is currently disabled, meaning new students cannot participate in the quiz.</p>
                        <?php }?>
                        <!-- end of disable status 1-->
            <!--notify the teacher the status of the quizform 2 -->
                        <?php if($row['quizstatus'] == 'active'){?>
                        <p class='alert alert-success p-2 '>Dear Teacher, the quiz is currently active and accepting student submissions.</p>
                        <?php }?>
                        <!--end active status 2 -->




                    <?php if(isset($_GET['success'])){ ?>
                        <div class="bg-success text-white p-2 fw-bold">Question Added/updated Successfully</div>
                    <?php }else if(isset($_GET['failed'])){?>
                        <div class="bg-danger text-white p-2 fw-bold">Failed to Add/update Question</div>
                    <?php }?>
                <h4 class='card-title fw-bold text-break'><?php echo $row['quiztitle']; ?></h4>
                <p class='text-break '><?php echo $row['quizdesc']; ?></p>
                </div>
                <div class="card-body">
                <form  method="POST" action="createquestions.php?quizformid=<?php echo $row['quizformid']; ?>">
                    <!-- quizformid -->
                <input type="hidden" name="quiz_id" value="<?php echo $row['quizformid']; ?>">
                <!-- check if quiztype is single choice questions and if its generate form accordingly -->
            <?php if($row['quiztype'] == 'singlechoicequestion'){?>
                <h4 style='font-size:14px;'class='mb-4'>Dear Teacher keep in mind your are creating single choice  Quiz Question </h4>
                 <!-- help us determine how to save the quiz by identifying what is the type-->
                <input type="hidden" name='quiztype' value="singlechoicequestion">
    <?php for($i = 1; $i <=$row['number_of_questions']; $i++): ?>
        <div class="question-container">
            <h3>Question <?php echo $i; ?></h3>
            <div class="form-group mb-3">
                <label  class='form-label'>Question Text:</label>
                <textarea name="questionstext[]" required class='form-control' placeholder='Enter Question Text Here'><?php echo $existingQuestions[$i]['questiontext'] ?? ''; ?></textarea>
                <input type="hidden" name="questionid[]" value="<?php echo $i; ?>">
            </div>

            <!-- Multiple Choice Options -->
            <div class="form-group mb-3">
                <label class='form-label'>Options:</label>
                <input type="text" name="option_a[]" placeholder="Option A" required class='form-control mb-2' value="<?php echo $existingOptions[$i]['option_one'] ?? ''; ?>">
                <input type="text" name="option_b[]" placeholder="Option B" required class='form-control mb-2' value="<?php echo $existingOptions[$i]['option_two'] ?? ''; ?>">
                <input type="text" name="option_c[]" placeholder="Option C" required class='form-control mb-2' value="<?php echo $existingOptions[$i]['option_three'] ?? ''; ?>">   
               
            </div>

            <div class="form-group mb-3">
                <label  class='form-label'>Correct Answer:</label>
                <select name="correct_answer[]" required class='form-control'>
                    <option value="a" <?php echo ($existingOptions[$i]['is_correct_option'] ?? '') == 'a' ? 'selected' : ''; ?>>Option A</option>
                    <option value="b" <?php echo ($existingOptions[$i]['is_correct_option'] ?? '') == 'b' ? 'selected' : ''; ?>>Option B</option>
                    <option value="c" <?php echo ($existingOptions[$i]['is_correct_option'] ?? '') == 'c' ? 'selected' : ''; ?>>Option C</option>
                </select>
            </div>
        </div>
    <?php endfor; ?>
                <!-- check if quiz type is true or false and if its make each question single choice is true and false-->
                        <?php }else if($row['quiztype'] == 'trueandfalse'){?>
                        <h4 style='font-size:14px;'class='mb-4'>Dear Teacher keep in mind your are creating True And False Quiz Question </h4>
       <!-- help us determine how to save the quiz by identifying what is the type-->
       <input type="hidden" name='quiztype' value="trueandfalse">
                        <?php for($i = 1; $i <=$row['number_of_questions']; $i++): ?>
        <div class="question-container">
            <h3>Question <?php echo $i; ?></h3>
            
            <div class="form-group mb-3">
                <label  class='form-label'>Question Text:</label>
                <textarea name="questionstext[]" required class='form-control' placeholder='Enter Question Text Here'><?php echo $existingQuestions[$i]['questiontext'] ?? ''; ?></textarea>
                <input type="hidden" name="questionid[]" value="<?php echo $i; ?>">
            </div>

            <!-- Multiple Choice Options -->
            <div class="form-group mb-3">
                <label class='form-label'>Options:</label>
                <input type="text" name="option_a[]" placeholder="Option A" required class='form-control mb-2' value="<?php echo $existingOptions[$i]['option_one'] ?? ''; ?>">
                <input type="text" name="option_b[]" placeholder="Option B" required class='form-control mb-2' value="<?php echo $existingOptions[$i]['option_two'] ?? ''; ?>">
               
            </div>

            <div class="form-group mb-3">
                <label  class='form-label'>Correct Answer:</label>
                <select name="correct_answer[]" required class='form-control'>
                    <option value="a" <?php echo ($existingOptions[$i]['is_correct_option'] ?? '') == 'a' ? 'selected' : ''; ?>>Option A</option>
                    <option value="b" <?php echo ($existingOptions[$i]['is_correct_option'] ?? '') == 'b' ? 'selected' : ''; ?>>Option B</option>
                </select>
            </div>
        </div>
    <?php endfor; ?>
                            <?php } ?>

    <button type="submit" class='btn btn-primary btn-sm fw-bold' name='save'>Save/update</button>
</form>
                </div>
            </div>
        </div>
    </div>

    <?php }else if($row['quizstatus'] == 'draft'){?>
        <div class="row">
        <div class="col-lg-8 col-md-10 col-sm-12 mx-auto mt-4">
            <div class="card">
                <div class="card-header">
                <h4 class='card-title fw-bold text-break'><?php echo $row['quiztitle']; ?></h4>
                <p class='text-break '><?php echo $row['quizdesc']; ?></p>
                </div>
                <div class="card-body">
        <div class="text-center text-danger p-2 fw-bold">Dear Teacher in order to edit/add this quizform question change the quizform status from draft to disable or active.</div>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

<?php }else{
    header('location:viewquizform.php?redirected');
    exit();
}?>

