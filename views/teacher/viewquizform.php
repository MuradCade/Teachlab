<?php
include('../../model/dbcon.php');
include('slices/studentcreationvalidation.php');
include('slices/displayquizform.slice.php');
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
  
}

//check if session exist , if it exist prevent user from seeing login page
if (!isset($_SESSION['userid'])) {
    header('location:../login.php');
    exit();
}




$teacherid = $_SESSION['userid']??'';

//update quizform information
if(isset($_POST['update'])){

$quiztitle = $_POST['quiztitle'];
$quizdescription = $_POST['quizdescription'];
$coursename = $_POST['coursename'];
$quizstatus = $_POST['quizstatus'];
$numberofquestion = $_POST['numberofquestion'];
$quizformid = $_POST['quizformid'];

if(empty($quiztitle)){
    header('location:viewquizform.php?quizformid='.$quizformid.'&emptyquiztitlefield');
    exit();
}else if(empty($quizdescription)){
    header('location:viewquizform.php?quizformid='.$quizformid.'&emptydescriptionfield');
    exit();
}else if(empty($coursename)){
    header('location:viewquizform.php?quizformid='.$quizformid.'&emptycoursenamefield');
    exit();
}else if(empty($quizstatus)){
    header('location:viewquizform.php?quizformid='.$quizformid.'&emptyquizstatusfield');
    exit();
}else if(empty($numberofquestion)){
    header('location:viewquizform.php?quizformid='.$quizformid.'&emptynumberofquestionfield');
    exit();
}else{
    $sql = "update quizform set quiztitle = '$quiztitle', quizdesc = '$quizdescription', coursename = '$coursename', quizstatus = '$quizstatus', number_of_questions = '$numberofquestion' where quizformid = '$quizformid' and teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    if($result){
        header('location:viewquizform.php?quizformupdatedsuccessfully');
        exit();
    }else{
        header('location:viewquizform.php?quizformupdatefailed');
        exit();
    }
} 
}


//delete quizform
if(isset($_GET['delid'])){
    $delid = $_GET['delid'];
    // delete all options related to currently deleting quizform
    $sql1 = "delete from options where quizformid = '$delid'";
    $result1 = mysqli_query($connection,$sql1);
    if($result1){ 
        //delete student related to this quizform
        $sql2 = "delete from studentquiz where quizformid = '$delid'";
        $result2 = mysqli_query($connection,$sql2);
        if($result2){
            // delte all questions related to quizform
            $sql3 = "delete from questions where quizformid = '$delid'";
            $result3 = mysqli_query($connection,$sql3);
            if($result3){
                // now delete the quizform
                $sql4 = "delete from quizform where quizformid = '$delid'";
                $result4 = mysqli_query($connection,$sql4);
                if($result4){
                     header('location:viewquizform.php?delsuccess');
                    exit();
                }
            }
        }
       
    }else{
        header('location:viewquizform.php?delfailed');
        exit();
    }
}





// update quiz entry quiz marks
if(isset($_GET['studentid']) && isset($_POST['updatequizentries'])){
    if(isset($_GET['entries'])){
        $quizformid = $_GET['entries'];
        $studentid = $_GET['studentid'];
        $quizmarks = $_POST['quizmarks'];
        $sql = "update studentquiz set quizmarks = '$quizmarks' where stdid = '$studentid'";
        $result = mysqli_query($connection,$sql);
        if($result){
            header('location:viewquizform.php?entries='.$quizformid.'&quizmarksupdatedsuccessfully');
            exit();
        }else{
            header('location:viewquizform.php?entries='.$quizformid.'&quizmarksupdatefailed');
            exit();
        }
    }
}


// delete quiz entry
if(isset($_GET['delstudentid'])){
    if(isset($_GET['entries'])){
        $quizformid = $_GET['entries'];
        $delid = $_GET['delstudentid'];
        $sql = "delete from studentquiz where stdid = '$delid'";
        $result = mysqli_query($connection,$sql);
        if($result){
        header('location:viewquizform.php?entries='.$quizformid.'&studentdeletedsuccessfully');
            exit();
        }else{
            header('location:viewquizform.php?entries='.$quizformid.'&studentdeletionfailed');
            exit();
        }
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
    <style>
        #sidebar {
            height: 100vh;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 100;
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
        }
        
        #sidebar.show {
            transform: translateX(0);
        }
        
        @media (min-width: 768px) {
            #sidebar {
                transform: translateX(0);
                position: fixed;
            }
        }
        
        #closeSidebar {
            display: none;
        }
        
        @media (max-width: 767.98px) {
            #closeSidebar {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
         <!-- sidebar included -->
        <?php  include('include/sidebar.php');?>


            <!-- Main Content -->
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <!-- Navbar for Mobile View -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light d-md-none">
                    <button id='hambergermenu' class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bi bi-list"></i> Menu
                    </button>
                </nav>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class=" fw-medium" style='font-size:16px'>Quiz / <span>View Quiz Form</span></h1>
                </div>

                 <!-- Quiz List Table -->
                 <div class="row mt-4">
                     <div class="col-12 col-md-6 col-xl-12 mb-4">
                        <?php if(isset($_GET['quizformid'])){
                            $quizformid = $_GET['quizformid'];
                            $sql = "select * from quizform where quizformid = '$quizformid' and teacherid = '$teacherid'";
                            $result = mysqli_query($connection,$sql);
                            if(mysqli_num_rows($result) == 0){?>
                                  <div class="container">
                                  <p class="bg-danger text-white fw-bold p-2" role="alert">
                                            Quiz id not valid
                            </p>
                                <a href="viewquizform.php" class="btn btn-primary btn-sm fw-bold">Go Back</a>
                                  </div>
                            <?php }else{ 
                                
                                while($quizformdata = mysqli_fetch_assoc($result)){?>
                                <div class="card" style="border:none !important; background-color:#f8f9fa !important;">
                            <h4 class="card-title mt-4 px-3 fw-bold" style='font-size:17px !important;'>
                                Update Quiz Form
                            </h4>
                            <!-- display errors to the user -->
                                <?php if(isset($_GET['emptyquiztitlefield'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Empty Quiz Title Field</p>
                                <?php }?>
                                <?php if(isset($_GET['emptydescriptionfield'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Empty Quiz Description Field</p>
                                <?php }?>
                                <?php if(isset($_GET['emptycoursenamefield'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Empty Course Name Field</p>
                                <?php }?>
                                <?php if(isset($_GET['emptyquizstatusfield'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Empty Quiz Status Field</p>
                                <?php }?>   
                                <?php if(isset($_GET['emptynumberofquestionfield'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Empty Number of Question Field</p>
                                <?php }?>       
                            <div class="card-body">
                            <form method="POST">
                            <div class="mb-3">
                            
                                <input type="hidden" class="form-control"  name="quizformid"  
                                value="<?php echo $quizformdata['quizformid']?>">
                            </div>
                            <div class="mb-3">
                                <label for="quizTitle" class="form-label">Quiz Title</label>
                                <input type="text" class="form-control"  name="quiztitle"  placeholder="Enter Quiz Title"
                                value="<?php echo $quizformdata['quiztitle']?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="quizDescription" class="form-label">Quiz Description</label>
                                <textarea class="form-control"  name="quizdescription" rows="4"  placeholder="Enter Quiz Description"><?php echo $quizformdata['quizdesc']?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="courseName" class="form-label">Course Name</label>
                                <select class="form-select"  name="coursename">
                                    <option value="<?php echo $quizformdata['coursename']?>"><?php echo $quizformdata['coursename']?></option>
                                    <?php
                                    $teacherid = $_SESSION['userid']??'';
                                    // Fetch courses from database
                                    $sql = "SELECT * FROM course where teacherid =  '$teacherid' and not(coursename = '$quizformdata[coursename]')";
                                    $result = mysqli_query($connection, $sql);
                                    
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . $row['coursename'] . "'>" . $row['coursename'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="quizStatus" class="form-label">Quiz Status</label>
                                <select class="form-select" name="quizstatus">
                                    <option value="active" <?php  echo $quizformdata['quizstatus'] == 'active' ? 'selected' : '';?>>Active</option>
                                    <option value="disable" <?php  echo $quizformdata['quizstatus'] == 'disable' ? 'selected' : '';?>>Disable</option>
                                    <option value="draft" <?php  echo $quizformdata['quizstatus'] == 'draft' ? 'selected' : '';?>>Draft</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="numerofquestions" class="form-label">Number of Questions</label>
                             <select name="numberofquestion" class="form-select">
                                <option value="5" <?php  echo $quizformdata['number_of_questions'] == '5' ? 'selected' : '';?>>5</option>
                                <option value="10" <?php  echo $quizformdata['number_of_questions'] == '10' ? 'selected' : '';?>>10</option>
                                <option value="20" <?php  echo $quizformdata['number_of_questions'] == '20' ? 'selected' : '';?>>20</option>
                                <option value="30" <?php  echo $quizformdata['number_of_questions'] == '30' ? 'selected' : '';?>>30</option>
                             </select>
                            </div>
                            <button type="submit" class="btn btn-primary  fw-bold btn-sm" name='update'>Update</button>
                            <a href='viewquizform.php'  class="btn btn-secondary  fw-bold btn-sm">Cancel</a>
                        </form>
                            </div>
                           </div>
                            <?php } } }else if(!isset($_GET['entries'])){ ?>
                        <div class="card p-2" style="border:none !important; background-color:#f8f9fa !important;">
                            
                                <h4 class="card-title mb-0 px-2 mt-1 fw-bold" style='font-size:17px !important;'>
                                    Quiz List
                                </h4>

                                <?php if(isset($_GET['quizformupdatedsuccessfully'])){ ?>
                                    <p class='bg-success p-2  mt-3 fw-bold text-break text-white'>Quiz Form Updated Successfully</p>
                                <?php }?>
                                <?php if(isset($_GET['quizformupdatefailed'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Quiz Form Update Failed</p>
                                <?php }?>
                                <?php if(isset($_GET['delsuccess'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Quiz Form Data Deleted Successfully</p>
                                <?php }?>
                                <?php if(isset($_GET['delfailed'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Failed To Delete Quiz Form</p>
                                <?php }?>
                              
                                    
                            <div class="card-body">

                                    <table class="table table-bordered table-hover">
                                       
                                            <tr>
                                                <td>#</td>
                                                <td>Quiz_Title</td>
                                                <td>Quiz_Description</td>
                                                <td>Number_of_Questions</td>
                                                <td>Course_Name</td>
                                                <td>Quiz_Form_Created_Date</td>
                                                <td>Quiz_Form_Status</td>
                                                <td>Actions</td>
                                            </tr>
                                  
                                       <?php 
                                        $quizform = displayquizform($connection,$teacherid);
                                        $rowid = 1;
                                        if($quizform == ''){
                                            echo "<p>There is currently no data to be shown</p>";
                                        }else{
                                        foreach($quizform as $quiz){ ?>
                                        <tr>
                                            <td><?php echo $rowid?></td>
                                            <td><?php echo $quiz['quiztitle']?></td>
                                            <td class='text-break'><?php echo $quiz['quizdesc']?></td>
                                            <td><?php echo $quiz['number_of_questions']?></td>
                                            <td><?php echo $quiz['coursename']?></td>
                                            <td><?php echo date('M-j-Y ', strtotime($quiz['quiz_created_date']));?></td>
                                            <td><?php echo $quiz['quizstatus']?></td>
                                            <td>
                                                <a href="viewquizform.php?quizformid=<?php echo $quiz['quizformid']?>" class="btn btn-primary mb-1 fw-bold btn-sm">Update</a>
                                                <a href="createquestions.php?quizformid=<?php echo $quiz['quizformid']?>" target="_blank" class="btn btn-info text-white fw-bold mb-1 btn-sm">Add Questions</a>
                                                <a href="../quiz/takequiz.php?quizformid=<?php echo base64_encode($quiz['quizformid']);?>" target="_blank" class="btn btn-dark text-white mb-1 fw-bold btn-sm">View_Quiz_As_Student</a>
                                                <a href="../quiz/takequiz.php?quizformid=<?php echo base64_encode($quiz['quizformid']);?>" target="_blank" class="btn btn-warning text-white mb-1 fw-bold btn-sm" onclick="copyToClipboard(event,this)">Share_Quiz_Link</a>
                                                <a href="viewquizform.php?entries=<?php echo $quiz['quizformid'];?>" class="btn btn-secondary mb-1 fw-bold btn-sm">Entries</a>
                                                <a href="viewquizform.php?delid=<?php echo $quiz['quizformid'];?>" class="btn btn-danger fw-bold btn-sm">Delete</a>
                                            </td>
                                        </tr>
                                        <?php $rowid++; }} ?>
                                    </table>
                              <?php }else if(isset($_GET['entries'])){ ?>
                               <div class="card p-2" style='font-size:17px !important;'>
                                <h4 class="card-title">
                                    Quiz Entries
                                </h4>
                                <?php if(isset($_GET['quizmarksupdatedsuccessfully'])){ ?>
                                    <p class='bg-success p-2  mt-3 fw-bold text-break text-white'>Quiz Marks Updated Successfully</p>
                                <?php }?>
                                <?php if(isset($_GET['quizmarksupdatefailed'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Failed To Update Quiz Marks</p>
                                <?php }?>
                                <?php if(isset($_GET['studentdeletedsuccessfully'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Student Data Deleted Successfully</p>
                                <?php }?>
                                <?php if(isset($_GET['studentdeletionfailed'])){ ?>
                                    <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Failed To Delete Student Data</p>
                                <?php }?>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <a href="viewquizform.php" class="btn btn-primary mb-1 fw-bold btn-sm">Go Back</a>
                                        <tr>
                                            <td>#</td>
                                            <td>Student_ID</td>
                                            <td>Student_Name</td>
                                            <td>Wrong_Questions</td>
                                            <td>Correct_Questions</td>
                                            <td>Total_Questions</td>
                                            <td>Quiz_Marks</td>
                                            <td>Quiz_Date</td>
                                            <td>Actions</td>
                                        </tr>
                                        <?php 
                                        $quizformid = $_GET['entries'];
                                        $sql = "SELECT sq.stdfullname,sq.stdid,sq.quizmarks,sq.quiz_taken_date, COUNT(CASE WHEN sq.selected_option = o.is_correct_option THEN 1 END) AS correct_count, COUNT(CASE WHEN sq.selected_option <> o.is_correct_option THEN 1 END) AS wrong_count, qf.number_of_questions FROM studentquiz sq JOIN options o ON sq.question_id = o.questionid AND sq.quizformid = o.quizformid JOIN quizform qf ON sq.quizformid = qf.quizformid WHERE qf.teacherid = 20015 GROUP BY sq.stdfullname, sq.stdid, sq.quizmarks, qf.number_of_questions; ";
                                        $result = mysqli_query($connection,$sql);
                                        if(mysqli_num_rows($result) == 0){
                                            echo "<p>There is currently no data to be shown</p>";
                                        }else{
                                            $rowid = 1;
                                            while($row = mysqli_fetch_assoc($result)){?>
                                            <form method='POST' action="viewquizform.php?studentid=<?php echo $row['stdid'] ?>&entries=<?php echo $quizformid ?>">
                                                <tr>
                                                    <td><?php echo $rowid?></td>
                                                    <td><a href="../quiz/showteacherstudententries.php?stdid=<?php echo $row['stdid']?>" target="_blank"><?php echo $row['stdid']?></a></td>
                                                    <td><?php echo $row['stdfullname']?></td>
                                                    <td><?php echo $row['wrong_count']?></td>
                                                    <td><?php echo $row['correct_count']?></td>
                                                    <td><?php echo $row['quizmarks']?></td>
                                                    <td><?php echo date('M-j-Y ', strtotime($row['quiz_taken_date']))?></td>
                                                    <td><input name='quizmarks' value="<?php echo $row['quizmarks']?>" style='width:80px !important;'/></td>
                                                    <td>
                                                        <button type='submit' class='btn btn-primary fw-bold btn-sm' name='updatequizentries'>Update</button>
                                                        <a href="viewquizform.php?entries=<?php echo $quizformid ?>&delstudentid=<?php echo $row['stdid'] ?>" class='btn btn-danger fw-bold btn-sm'>Delete</a>
                                                    </td>
                                                </tr>   
                                                </form>
                                            <?php $rowid++; }
                                        }
                                        ?>
                                    </table>
                                </div>
                               </div>
                              <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

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


        // Function to copy the link to clipboard
        function copyToClipboard(event , element) {
            event.preventDefault();
            // Create a temporary input element to copy the link
            var tempInput = document.createElement("input");
            document.body.appendChild(tempInput);
            tempInput.value = element.href;  // Set input value to the href of the anchor
            tempInput.select();  // Select the text in the input
            document.execCommand("copy");  // Copy the selected text to clipboard
            document.body.removeChild(tempInput);  // Remove the temporary input element
            alert("Link copied to clipboard!");  
            // Optional: Show an alert message
        }
    </script>
</body>
</html>
