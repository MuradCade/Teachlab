<!-- this page is to show the teacher student entries for the quiz form -->
<?php
include '../../model/dbcon.php';
if(isset($_GET['stdid']) && !empty($_GET['stdid'])){
    $stdid = $_GET['stdid'];
    $sql = "select * from studentquiz where stdid = '{$stdid}'";
    $result = mysqli_query($connection,$sql);
    if(mysqli_num_rows($result) == 0){
        echo "<p>There is currently no data to be shown</p>";
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
            
            // Get question text
            $sql2 = "SELECT * FROM questions WHERE questionid = '{$questionids}'";
            $result2 = mysqli_query($connection, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $questiontext = $row2['questiontext'];
            $existingquestions[] = $questiontext;
            
            // Get options and correct answer
            $sql3 = "SELECT * FROM options WHERE questionid = '{$questionids}'";
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
    <style>
        @media print {
            /* Add any PDF-specific styles here */
            .card {
                border: none !important;
            }
            /* etc */
        }
    </style>
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
                    <p class='card-title fw-bold'>Student Name: <?php echo $studentinfo[0]['stdfullname'] ?? 'N/A'; ?></p>
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
                    <p class='card-title fw-bold'>Course Name: <?php echo $rows['coursename']; ?></p>
                    <p class='card-title fw-bold'>Quiz Title: <?php echo $rows['quiztitle']; ?></p>
                    <?php } ?>
                  </div>
                  <div class="card-body">
                    <?php if(isset($_GET['emptyanswersfields'])){ ?>
                      <p class='bg-danger text-white p-2'>Please select at least one answer</p>
                    <?php } ?>
                  <form method="post" action='savestudentquiz.php'>
                  <?php 
                  $questioncount = 1;
                  $questionsymbold = ['0'=>'a','1'=>'b','2'=>'c','3'=>'d','4'=>'e'];
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
                                        <?php foreach(['option_one', 'option_two', 'option_three'] as $optionIndex => $option) { ?>
                                            <div class="form-check" style="padding: 8px; border-radius: 4px; 
                                                <?php 
                                                $isStudentAnswer = $existingoptions[$i][$option] === $existingAnswers[$i]['answer'];
                                                $isCorrectAnswer = $existingoptions[$i][$option] === $existingAnswers[$i]['correctAnswer'];
                                                
                                                if ($isStudentAnswer) {
                                                    echo 'border: 2px solid ' . ($isCorrectAnswer ? 'bg-success text-white' : 'bg-danger text-white') . ';';
                                                }
                                                if ($isCorrectAnswer) {
                                                    echo 'background-color: wheat; color:black; font-weight:bold;';
                                                }
                                                ?>">
                                                <input class="form-check-input" type="checkbox" 
                                                       value="<?php echo $existingoptions[$i][$option];?>" 
                                                       <?php echo $isStudentAnswer ? "checked=true" : '';?>
                                                       >
                                                <label class="form-check-label text-black " style="font-weight:600 !important;">
                                                    <?php echo $questionsymbold[$optionIndex] . '.'; ?>
                                                    <?php echo $existingoptions[$i][$option];?>
                                                    <?php if ($isStudentAnswer) { ?>
                                                        <span class="<?php echo $isCorrectAnswer ? 'text-success' : 'text-danger';?>">
                                                        (Correct Answer)
                                                        </span>
                                                    <?php } ?>
                                                    <?php if ($isCorrectAnswer) { ?>
                                                        <span class="text-success"> (Correct Answer)</span>
                                                    <?php } ?>
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
    // $quizformid = $_GET['entries'];

    $sql = "SELECT 
                sq.stdid,
                sq.stdfullname, 
                sq.quiz_taken_date, 
                SUM(CASE WHEN o.is_correct_option = sq.selected_option THEN 1 ELSE 0 END) as correct_answers, 
                SUM(CASE WHEN o.is_correct_option != sq.selected_option THEN 1 ELSE 0 END) as wrong_answers, 
                COUNT(*) as total_questions, 
                sq.quizmarks as quizmarks 
            FROM questions q 
            INNER JOIN options o ON q.questionid = o.questionid 
            INNER JOIN studentquiz sq ON q.questionid = sq.question_id 
            WHERE sq.stdid = '$stdid'
            GROUP BY sq.stdid";

    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {?>
            <div class="card-footer">

                <p>Correct Answers: <?php echo $row['correct_answers']?> </p>
                <p>Wrong Answers: <?php echo $row['wrong_answers']?> </p>
                <p>Total Questions: <?php echo $row['total_questions']?> </p>
                <p>Quiz Marks: <?php echo $row['quizmarks']?> </p>
            </div>
        <?php }
    } 
}

// ... existing code ...
    ?>
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
        // Get the element
        const element = document.getElementById('pdf-content');
        
        // Get student info for filename
        const studentName = '<?php echo $studentinfo[0]['stdfullname'] ?? "student"; ?>';
        const studentId = '<?php echo $studentinfo[0]['stdid'] ?? ""; ?>';
        const filename = `Quiz_Result_${studentName}_${studentId}.pdf`;

        // html2pdf options
        const opt = {
            margin: 1,
            filename: filename,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        // Promise-based
        html2pdf().set(opt).from(element).save().catch(error => {
            console.error('Error generating PDF:', error);
            alert('There was an error generating the PDF. Please try again.');
        });
    }
    </script>
</body>
</html> 
<?php }}else{
    header('location:../teacher/viewquizform.php');
    exit();
}   
?>