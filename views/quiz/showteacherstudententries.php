    <!-- this page is to show the teacher student entries for the quiz form -->
    <?php
    include '../../model/dbcon.php';
    if(isset($_GET['stdid']) && !empty($_GET['stdid'])){
        $quizformid = $_GET['quizformid'];
        $stdid = mysqli_real_escape_string($connection, $_GET['stdid']);
        $sql = "select * from studentquiz where stdid = '$stdid' and quizformid = '$quizformid'";
        $result = mysqli_query($connection,$sql);
        if(mysqli_num_rows($result) == 0){
            echo "<center><h1>Sorry Student with that id doesn't exist.</h1>
            <br>
            <a href='../teacher/viewquizform.php' style=''>Go Back</a> </center>";
        }else{
            $existingquestions = [];
            $existingoptions = [];
            $existingAnswers = [];
            $studentinfo= [];
            while($row = mysqli_fetch_assoc($result)){
                
                $questionids = $row['question_id'];
                $studentid = $row['stdid'];
                $quizformid = $row['quizformid'];
               
                $studentAnswer = $row['selected_option'];
                $studentinfo[] = $row;
                

                // check what type of quiz is this (true or false / single choice question)
                $sqlmain = "select quiztype from quizform where quizformid = '$quizformid'";
                $resultmain = mysqli_query($connection,$sqlmain);
                $quiztypedata = mysqli_fetch_assoc($resultmain); 
                if(empty($quiztypedata)){
                    die("<center><h2>Sorry Quizform Is Not Found , so we can't display student information</h2><br><a href='../teacher/viewquizform.php' style=''>Go Back</a></center>");

                }
                if($quiztypedata['quiztype'] == 'singlechoicequestion' ){

                    // Get question text
                    $sql2 = "SELECT * FROM questions WHERE questionid = '{$questionids}' and quizformid = '$quizformid'";
                    $result2 = mysqli_query($connection, $sql2);
                    $row2 = mysqli_fetch_assoc($result2);
                $questiontext = $row2['questiontext'];
                $existingquestions[] = $questiontext;
                // echo "<pre>";
                // var_dump($row);
                // echo "</pre>";
                
                // Get options and correct answer
                $sql3 = "SELECT * FROM options WHERE questionid = '{$questionids}'  and quizformid = '$quizformid'";
                $result3 = mysqli_query($connection, $sql3);
                $options = mysqli_fetch_assoc($result3);
                $existingoptions[] = $options;
                
                // Find the correct answer from options
                $correctAnswer = null;
                if ($options['is_correct_option'] == 'a') {
                    $correctAnswer = $options['option_one'];
                } elseif ($options['is_correct_option'] == 'b') {
                    $correctAnswer = $options['option_two'];
                } elseif ($options['is_correct_option'] == 'c') {
                    $correctAnswer = $options['option_three'];
                }
                
                $isCorrect = ($correctAnswer === $studentAnswer);
                $existingAnswers[] = [
                    'answer' => $studentAnswer, 
                    'isCorrect' => $isCorrect,
                    'correctAnswer' => $correctAnswer
                ];
            }else if($quiztypedata['quiztype'] == 'trueandfalse'){

                    // Get question text
                    $sql2 = "SELECT * FROM questions WHERE questionid = '{$questionids}' and quizformid = '$quizformid'";
                    $result2 = mysqli_query($connection, $sql2);
                    $row2 = mysqli_fetch_assoc($result2);
                $questiontext = $row2['questiontext'];
                $existingquestions[] = $questiontext;
                // echo "<pre>";
                // var_dump($row);
                // echo "</pre>";
                
                // Get options and correct answer
                $sql3 = "SELECT * FROM true_false_options WHERE questionid = '{$questionids}'  and quizformid = '$quizformid'";
                $result3 = mysqli_query($connection, $sql3);
                $options = mysqli_fetch_assoc($result3);
                $existingoptions[] = $options;
                
                // Find the correct answer from options
                $correctAnswer = null;
                if ($options['is_correct_option'] == 'a') {
                    $correctAnswer = $options['option_one'];
                } elseif ($options['is_correct_option'] == 'b') {
                    $correctAnswer = $options['option_two'];
                } 
                
                $isCorrect = ($correctAnswer === $studentAnswer);
                $existingAnswers[] = [
                    'answer' => $studentAnswer, 
                    'isCorrect' => $isCorrect,
                    'correctAnswer' => $correctAnswer
                ];
            }
        }
            ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TeachLab - View Quiz Form</title>
        <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap" rel="stylesheet">
        <style>
            @media print {
                /* Add any PDF-specific styles here */
                .card {
                    border: none !important;
                }
            }
            /* Apply Noto Sans to all text elements */
            #pdf-content {
                font-family: 'Noto Sans', sans-serif;
            }
            .watermark-container {
                position: relative;
                width: 100%;
                margin-top: 30px;
                text-align: center;
                page-break-inside: avoid; /* Prevents the watermark from breaking across pages */
            }
            
            .watermark {
                padding: 20px 0;
                border-top: 2px solid #e74c3c;
            }

            .pdf-footer-text {
                color: #e74c3c;
                font-size: 14px;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 1px;
                padding: 10px;
                background-color: #f8f9fa;
                border-radius: 5px;
                display: inline-block;
            }
        </style>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-00CYL9RWEC"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-00CYL9RWEC');
    </script>
    </head>
    <body>
    <div class="container">
            <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12 mx-auto mt-3">
                <div class="text-end mb-3">
                    <button onclick="generatePDF()" class="btn btn-primary">
                        <i class="bi bi-download"></i> Download PDF
                    </button>
                </div>
                <div id="pdf-content">
                    <div class="card">
                    <div class="card-header">
                        <p class='card-title fw-bold' style="text-transform: capitalize;">Student Name: <?php echo $studentinfo[0]['stdfullname'] ?? 'N/A'; ?></p>
                        <p class='card-title fw-bold'>Student ID: <?php echo $studentinfo[0]['stdid'] ?? 'N/A'; ?></p>
                        <p class='card-title fw-bold'>Quiz Taken Date: <?php echo date('F j, Y g:i A', strtotime($studentinfo[0]['quiz_taken_date'])) ?? 'N/A'; ?></p>
                        <?php 
                            $quizformid = $studentinfo[0]['quizformid'] ?? '';
                            $courseQuery = "SELECT *
                                        FROM quizform 
                                        WHERE quizformid = '$quizformid'";
                            $courseResult = mysqli_query($connection, $courseQuery);
                            while($rows = mysqli_fetch_assoc($courseResult)){
                                
                        
                        ?>
                        <p class='card-title fw-bold' style="text-transform: capitalize;">Course Name: <?php echo $rows['coursename']; ?></p>
                        <p class='card-title fw-bold' style="text-transform: capitalize;">Quiz Title: <?php echo $rows['quiztitle']; ?></p>
                        <p class='card-title fw-bold' style="text-transform: capitalize;">Quiz Type: <?php echo $rows['quiztype'] == 'trueandfalse'?'True And False Questions':'Single Choice Questions';


    ?></p>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <?php if(isset($_GET['emptyanswersfields'])){ ?>
                        <p class='bg-danger text-white p-2'>Please select at least one answer</p>
                        <?php } ?>
                    <form method="post" action='savestudentquiz.php'>
                    <?php 
                    $questioncount = 1;
                    $questionsymbold = ['0'=>'a','1'=>'b','2'=>'c'];
                    for($i = 0; $i <count($existingquestions); $i++){
                                    ?>
                                    <div class="mb-4">
                                        <p class='form-label mb-3' style='line-height:1.5em !important;'>
                                            <strong><?php echo $questioncount.') ';?></strong>   
                                            <?php echo $existingquestions[$i]?>
                                            <?php if (!$existingAnswers[$i]['isCorrect']) { ?>
                                            
                                            <?php } ?>
                                        </p>
                                        
                                        <div class="ms-4">
                                            <?php 
                                            // Determine options to show based on quiz type
                                            $optionsToShow = ($quiztypedata['quiztype'] == 'singlechoicequestion') ? 
                                                ['option_one', 'option_two', 'option_three'] : 
                                                ['option_one', 'option_two'];

                                            foreach($optionsToShow as $optionIndex => $option) { 
                                                // Skip if option doesn't exist (for true/false questions)
                                                if (!isset($existingoptions[$i][$option])) continue;
                                            ?>
                                                <div class="form-check" style="padding: 8px; border-radius: 4px; 
                                                    <?php 
                                                    $isStudentAnswer = $existingoptions[$i][$option] === $existingAnswers[$i]['answer'];
                                                    $isCorrectAnswer = $existingoptions[$i][$option] === $existingAnswers[$i]['correctAnswer'];
                                                    
                                                    if ($isStudentAnswer) {
                                                        echo 'border: 2px solid ' . ($isCorrectAnswer ? 'green' : 'red') . ';';
                                                    }
                                                    if ($isCorrectAnswer) {
                                                        echo 'background-color: wheat;';
                                                    }
                                                    ?>">
                                                    <input class="form-check-input" type="checkbox" 
                                                        value="<?php echo $existingoptions[$i][$option];?>" 
                                                        <?php echo $isStudentAnswer ? "checked" : '';?>
                                                        disabled>
                                                    <label class="form-check-label text-black" style="font-weight:600 !important;">
                                                        <?php echo $questionsymbold[$optionIndex] . '.'; ?>
                                                        <?php echo $existingoptions[$i][$option];?>
                                                        <?php 
                                                        // Show only one indicator for the answer status
                                                        if ($isStudentAnswer) {
                                                            if ($isCorrectAnswer) {
                                                                echo '<span class="text-success"> (Correct Answer)</span>';
                                                            } else {
                                                                echo '<span class="text-danger"> (Wrong Answer)</span>';
                                                            }
                                                        } else if ($isCorrectAnswer) {
                                                            echo '<span class="text-success"> (Correct Answer)</span>';
                                                        }
                                                        ?>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                            
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    Student Answer: <span class="<?php echo $existingAnswers[$i]['isCorrect'] ? 'text-success fw-bold' : 'text-danger fw-bold'; ?>">
                                                        <?php echo $existingAnswers[$i]['answer']; ?>
                                                    </span>
                                                    <?php if (!$existingAnswers[$i]['isCorrect']) { ?>
                                                        <br>Correct answer: <span class="text-success fw-bold"><?php echo $existingAnswers[$i]['correctAnswer']; ?></span>
                                                    <?php } ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $questioncount++; }?>

                </form>
                
        <?php 
        // ... existing code ...

    if (isset($_GET['stdid']) && !empty($_GET['stdid'])) {
        $stdid = $_GET['stdid'];
        
        // check if the quiz type is (true and false / single choice question) and then determine which option table we should read from
        // $quizformid = $_GET['entries'];
        if($quiztypedata['quiztype'] == 'singlechoicequestion'){
        $sql = "SELECT sq.quizmarks, COUNT(CASE WHEN sq.selected_option = o.is_correct_option THEN 1 END) AS correct_count, COUNT(CASE WHEN sq.selected_option <> o.is_correct_option THEN 1 END) AS wrong_count, quizform.number_of_questions FROM studentquiz sq JOIN options o ON sq.question_id = o.questionid AND sq.quizformid = o.quizformid join quizform on quizform.quizformid = '$quizformid' WHERE sq.stdid ='$stdid'; ";

        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {?>
                <div class="card-footer">

                    <p>Correct Answers: <?php echo $row['correct_count']?> </p>
                    <p>Wrong Answers: <?php echo $row['wrong_count']?> </p>
                    <p>Total Questions: <?php echo $row['number_of_questions']?> </p>
                    <p>Quiz Marks: <?php echo $row['quizmarks']?> </p>
                </div>
            <?php }
        } 
    }else if($quiztypedata['quiztype'] == 'trueandfalse'){
        $sql = "SELECT sq.quizmarks, COUNT(CASE WHEN sq.selected_option = o.is_correct_option THEN 1 END) AS correct_count, COUNT(CASE WHEN sq.selected_option <> o.is_correct_option THEN 1 END) AS wrong_count, quizform.number_of_questions FROM studentquiz sq JOIN true_false_options o ON sq.question_id = o.questionid AND sq.quizformid = o.quizformid join quizform on quizform.quizformid = '$quizformid' WHERE sq.stdid ='$stdid'; ";

        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {?>
                <div class="card-footer">

                    <p>Correct Answers: <?php echo $row['correct_count']?> </p>
                    <p>Wrong Answers: <?php echo $row['wrong_count']?> </p>
                    <p>Total Questions: <?php echo $row['number_of_questions']?> </p>
                    <p>Quiz Marks: <?php echo $row['quizmarks']?> </p>
                </div>
            <?php }
        } 
    }
    }

    // ... existing code ...
        ?>
                </div>
                <!-- Move watermark inside pdf-content -->
                <div class="watermark-container">
                    <div class="watermark">
                        <div class="pdf-footer-text">
                            Generated by TeachLab - Your Digital Teaching Partner
                        </div>
                    </div>
                </div>
                </div>
                </div>
            </div>
            </div>
        </div>




    <!-- end -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>    
        

        <script>
            // JavaScript to handle the sidebar close button functionality
            var closebtn = document.getElementById('closeSidebar');
        
        closebtn.addEventListener('click', function() {
                var sidebar = new bootstrap.Collapse(document.getElementById('sidebar'), {
                    
                    toggle: true,
                });  
            });
        </script>
        <script>
        function generatePDF() {
            const element = document.getElementById('pdf-content');
            const studentName = '<?php echo $studentinfo[0]['stdfullname'] ?? "student"; ?>';
            const studentId = '<?php echo $studentinfo[0]['stdid'] ?? ""; ?>';
            const filename = `Quiz_Result_${studentName}_${studentId}.pdf`;

            const opt = {
                margin: 10,
                filename: filename,
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { 
                    scale: 2,
                    useCORS: true
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'portrait'
                }
            };

            // Add a small delay to ensure watermark is rendered
            setTimeout(() => {
                html2pdf().set(opt).from(element).save();
            }, 100);
        }
        </script>
        
    </body>
    </html> 
    <?php }}else{
        header('location:../teacher/viewquizform.php');
        exit();
    }   
    ?>