<?php
    include('../../model/dbcon.php');
    include('slices/studentcreationvalidation.php');
    include('slices/displayexamform.slice.php');
    include_once('slices/display_single_exam.php');
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

    $examtitle = mysqli_real_escape_string($connection, $_POST['examtitle']);
    $examdescription = mysqli_real_escape_string($connection , $_POST['examdescription']);
    $examstatus = mysqli_real_escape_string($connection , $_POST['examstatus']);
    $numberofquestion = mysqli_real_escape_string($connection , $_POST['numberofquestion']);
    $examformid = $_POST['examformid'];
    
    
    if(empty(trim($examtitle))){
        header('location:viewexamform.php?examformid='.base64_encode($examformid).'&emptyeaxmtitlefield');
        exit();
    }else if(empty(trim($examdescription))){
        header('location:viewexamform.php?examformid='.base64_encode($examformid).'&emptydescriptionfield');
        exit();
    }else if(empty(trim($examstatus))){
        header('location:viewexamform.php?examformid='.base64_encode($examformid).'&emptyexamstatusfield');
        exit();
    }else if(empty(trim($numberofquestion))){
        header('location:viewexamform.php?examformid='.base64_encode($examformid).'&emptynumberofquestionfield');
        exit();
    }else{
        
        $sql = "update examform set examtitle = '{$examtitle}', examdesc = '{$examdescription}', examstatus = '{$examstatus}', number_of_questions = '{$numberofquestion}'  where examformid = '{$examformid}' and teacherid = '{$teacherid}'";
        $result = mysqli_query($connection,$sql);
        if($result){
            header('location:viewexamform.php?examformupdatedsuccessfully');
            exit();
        }else{
            header('location:viewexamform.php?examformupdatefailed');
            exit();
        }
    } 
    }


    //delete quizform
    if(isset($_GET['delid'])){
        $delid = $_GET['delid'];
        // delete all options related to currently deleting quizform
        $sql1 = "delete from examoptions where examformid = '$delid'";
        $result1 = mysqli_query($connection,$sql1);
        if($result1){ 
            //delete student related to this quizform
            $sql2 = "delete from studentexam where examformid = '$delid'";
            $result2 = mysqli_query($connection,$sql2);
            if($result2){
                // delte all questions related to quizform
                $sql3 = "delete from examquestions where examformid = '$delid'";
                $result3 = mysqli_query($connection,$sql3);
                if($result3){
                    // now delete the quizform
                    $sql4 = "delete from examform where examformid = '$delid'";
                    $result4 = mysqli_query($connection,$sql4);
                    if($result4){
                         // now delete the quizform
                    $sql5 = "delete from examtrue_false_options where examformid = '$delid'";
                    $result5 = mysqli_query($connection,$sql5);
                        header('location:viewexamform.php?delsuccess');
                        exit();
                    }
                }
            }
        
        }else{
            header('location:viewexamform.php?delfailed');
            exit();
        }
    }





    // update quiz entry quiz marks
    if(isset($_POST['updatequizentries']) && isset($_GET['studentid']) ){
        if(isset($_GET['entries'])){
            $examformid = $_GET['entries'];
            $studentid = $_GET['studentid'];
            $exammarks = $_POST['exammarks'];
            $sql = "update studentexam set exammarks = '$exammarks' where stdid = '$studentid' and examformid = '$examformid'";
            $result = mysqli_query($connection,$sql);
            if($result){
                header('location:viewexamform.php?entries='.$examformid.'&quizmarksupdatedsuccessfully');
                exit();
            }else{
                header('location:viewexamform.php?entries='.$examformid.'&quizmarksupdatefailed');
                exit();
            }
        }
    }


    // delete quiz entry
    if(isset($_GET['delstudentid'])){
        if(isset($_GET['entries'])){
            $examformid = $_GET['entries'];
            $delid = $_GET['delstudentid'];
            $sql = "delete from studentexam where stdid = '$delid'";
            $result = mysqli_query($connection,$sql);
            if($result){
            header('location:viewexamform.php?entries='.$examformid.'&studentdeletedsuccessfully');
                exit();
            }else{
                header('location:viewexamform.php?entries='.$examformid.'&studentdeletionfailed');
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
        <title>TeachLab - View Exam Form</title>
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
                        <h1 class=" fw-medium" style='font-size:16px'>Exam / <span>View Exam Form</span></h1>
                    </div>

                    <!-- Quiz List Table -->
                    <div class="row mt-4">
                        <div class="col-lg-12 col-md-6 col-xl-12 mb-4">
                            <?php if(isset($_GET['examformid'])){
                                $examformid = base64_decode($_GET['examformid']);
                                $sql = "select * from examform where examformid = '$examformid' and teacherid = '$teacherid' order by exam_created_date ";
                                $result = mysqli_query($connection,$sql);
                                if(mysqli_num_rows($result) == 0){?>
                                    <div class="container">
                                    <p class="bg-danger text-white fw-bold p-2" role="alert">
                                                Exam id not valid
                                </p>
                                    <a href="viewexamform.php" class="btn btn-primary btn-sm fw-bold">Go Back</a>
                                    </div>
                                <?php }else{ 
                                    
                                    while($examformdata = mysqli_fetch_assoc($result)){?>
                                    <div class="card" style="border:none !important; background-color:#f8f9fa !important;">
                                <h4 class="card-title mt-4 px-3 fw-bold" style='font-size:17px !important;'>
                                    Update Exam Form
                                </h4>
                              
                                <!-- display errors to the user -->
                                    <?php if(isset($_GET['emptyeaxmtitlefield'])){ ?>
                                        <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Empty Exam Title Field</p>
                                    <?php }?>
                                    <?php if(isset($_GET['emptydescriptionfield'])){ ?>
                                        <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Empty Exam Description Field</p>
                                    <?php }?>
                                    
                                    <?php if(isset($_GET['emptyexamstatusfield'])){ ?>
                                        <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Empty Exam Status Field</p>
                                    <?php }?>   
                                    <?php if(isset($_GET['emptynumberofquestionfield'])){ ?>
                                        <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Empty Number of Question Field</p>
                                    <?php }?>       
                                <div class="card-body">
                                <form method="POST">
                                <div class="mb-3">
                                
                                    <input type="hidden" class="form-control"  name="examformid"  
                                    value="<?php echo $examformdata['examformid']?>">
                                </div>
                                <div class="mb-3">
                                    <label for="quizTitle" class="form-label">Exam Title</label>
                                    <input type="text" class="form-control"  name="examtitle"  placeholder="Enter Quiz Title"
                                    value="<?php echo $examformdata['examtitle']?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="quizDescription" class="form-label">Exam Description</label>
                                    <textarea class="form-control"  name="examdescription" rows="4"  placeholder="Enter Quiz Description"><?php echo $examformdata['examdesc']?></textarea>
                                </div>
                                
                              
                                
                                <div class="mb-3">
                                    <label for="quizStatus" class="form-label">Exam Status</label>
                                    <select class="form-select" name="examstatus">
                                        <option value="active" <?php  echo $examformdata['examstatus'] == 'active' ? 'selected' : '';?>>Active</option>
                                        <option value="disable" <?php  echo $examformdata['examstatus'] == 'disable' ? 'selected' : '';?>>Disable</option>
                                        <option value="draft" <?php  echo $examformdata['examstatus'] == 'draft' ? 'selected' : '';?>>Draft</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="numerofquestions" class="form-label">Number of Questions</label>
                                <select name="numberofquestion" class="form-select">
                                    <option value="5" <?php  echo $examformdata['number_of_questions'] == '5' ? 'selected' : '';?>>5</option>
                                    <option value="10" <?php  echo $examformdata['number_of_questions'] == '10' ? 'selected' : '';?>>10</option>
                                    <option value="20" <?php  echo $examformdata['number_of_questions'] == '20' ? 'selected' : '';?>>20</option>
                                    <option value="30" <?php  echo $examformdata['number_of_questions'] == '30' ? 'selected' : '';?>>30</option>
                                </select>
                                </div>
                               
                                <button type="submit" class="btn btn-primary  fw-bold btn-sm" name='update'>Update</button>
                                <a href='viewexamform.php'  class="btn btn-secondary  fw-bold btn-sm">Cancel</a>
                            </form>
                                </div>
                            </div>
                                <?php } } }else if(!isset($_GET['entries'])){ ?>

                                   
                            <div class="card p-2" style="border:none !important; background-color:#f8f9fa !important;">
                                
                                    <h4 class="card-title mb-0 px-2 mt-1 fw-bold" style='font-size:17px !important;'>
                                        Exam Form List
                                    </h4>

                                    <?php if(isset($_GET['examformupdatedsuccessfully'])){ ?>
                                        <p class='bg-success p-2  mt-3 fw-bold text-break text-white'>Exam Form Updated Successfully</p>
                                    <?php }?>
                                    <?php if(isset($_GET['examformupdatefailed'])){ ?>
                                        <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Exam Form Update Failed</p>
                                    <?php }?>
                                    <?php if(isset($_GET['delsuccess'])){ ?>
                                        <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Exam Form Data Deleted Successfully</p>
                                    <?php }?>
                                    <?php if(isset($_GET['delfailed'])){ ?>
                                        <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Failed To Delete Exam Form</p>
                                    <?php }?>
                                
                                        
                                <div class="card-body table-responsive">

                                        <table class="table table-bordered table-hover table-sm">
                                        
                                                <tr>
                                                    <td>#</td>
                                                    <td>Exam_Title</td>
                                                    <td>Exam_Type</td>
                                                    <td>Number_of_Questions</td>
                                                    <td>Course_Name</td>
                                                    <td>Exam_Form_Created_Date</td>
                                                    <td>Exam_Form_Status</td>
                                                    <td>Actions</td>
                                                </tr>
                                    
                                        <?php 
                                            $examform = displayexamform($connection,$teacherid);
                                            $rowid = 1;
                                            if($examform == ''){
                                                echo "<p>There is currently no data to be shown</p>";
                                            }else{
                                            foreach($examform as $exam){ ?>
                                            <tr>
                                                <td><?php echo $rowid?></td>
                                                <td><?php echo $exam['examtitle']?></td>
                                                <td class='text-break'><?php echo $exam['examtype']?></td>
                                                <td><?php echo $exam['number_of_questions']?></td>
                                                <td><?php echo $exam['coursename']?></td>
                                                <td><?php echo date('M-j-Y ', strtotime($exam['exam_created_date']));?></td>
                                                <td><p style='text-transform:capitalize;' class="<?php echo $exam['examstatus'] == 'active'?'text-success fw-bold':'text-danger fw-bold'?>"><?php echo $exam['examstatus'] ?></p></td>
                                                <td>
                                                    <form method='GET'>
                                                    <button   name='details' class='btn btn-secondary btn-sm mb-1 d-inline-block fw-bold' data-bs-toggle="modal" data-bs-target="#examformdetails"  value='<?php echo base64_encode($exam['examformid']);?>'>Details</button>
                                                    </form>
                                                    <a href="viewexamform.php?examformid=<?php echo base64_encode($exam['examformid'])?>" class="btn btn-primary mb-1 fw-bold btn-sm">Update</a>
                                                    <a href="create_examquestions.php?examformid=<?php echo $exam['examformid']?>" target="_blank" class="btn btn-info text-white fw-bold mb-1 btn-sm">Add Questions</a>
                                                    <a href="../exam/take_exam.php?examformid=<?php echo base64_encode($exam['examformid']);?>" target="_blank" class="btn btn-dark text-white mb-1 fw-bold btn-sm">View_Exam_As_Student</a>
                                                    <a href="../exam/take_exam.php?examformid=<?php echo base64_encode($exam['examformid']);?>" target="_blank" class="btn btn-warning text-black mb-1 fw-bold btn-sm" onclick="copyToClipboard(event,this)">Share_Exam_Link</a>
                                                    <a href="viewexamform.php?entries=<?php echo $exam['examformid'];?>" class="btn btn-secondary mb-1 fw-bold btn-sm">Entries</a>
                                                    <a href="viewexamform.php?delid=<?php echo $exam['examformid'];?>" class="btn btn-danger fw-bold btn-sm">Delete</a>
                                                </td>
                                            </tr>

                                            <!-- quiz model that displays complete information starts here-->
                                        <?php if(isset($_GET['details'])){?>    
                                        <!-- Modal -->
                                        <div class="modal " id="examformdetails"  style="display:block;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Exam Form Details</h1>
                                            </div>
                                            <div class="modal-body">
                                                <?php $singlequizinformation =  displaysingle_examform($connection,$_GET['details']);

                                                    foreach ($singlequizinformation as $singlequizformdata) {?>
                                                        
                                                        <p>Exam Title : <?= $singlequizformdata['examtitle']?></p>
                                                        <p>Exam Description : <?= $singlequizformdata['examdesc']?></p>
                                                        <p>Coursename : <?= $singlequizformdata['coursename']?></p>
                                                        <p>Created_Date : <?= date('M-j-Y ', strtotime($singlequizformdata['exam_created_date']))?></p>
                                                        <p>Exam_Type : <?= $singlequizformdata['examtype'] == 'singlechoicequestion' ?'Single Choice Question':'True And False Questions'?></p>
                                                        <p>Number_of_questions : <?= $singlequizformdata['number_of_questions']?></p>
                                                        <p>Exam_status : <span class="<?= $singlequizformdata['examstatus'] == 'active'?'text-success':'text-danger'?>"><?= $singlequizformdata['examstatus']?></span></p>

                                                  <?php }?>
                                            </div>
                                            <div class="modal-footer">
                                                <a type="button" class="btn btn-secondary btn-sm fw-bold" href='viewexamform.php'>Close</a>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                         
                                        <?php }?>
                                            <!-- quiz model ends here-->
                                            <?php $rowid++; }} ?>
                                        </table>
                                <?php }else if(isset($_GET['entries'])){ ?>
                                    
                                <div class="card p-2" style='font-size:17px !important;'>
                                    <h4 class="card-title">
                                        Exam Entries
                                    </h4>
                                    <?php if(isset($_GET['quizmarksupdatedsuccessfully'])){ ?>
                                        <p class='bg-success p-2  mt-3 fw-bold text-break text-white'>Exam Marks Updated Successfully</p>
                                    <?php }?>
                                    <?php if(isset($_GET['quizmarksupdatefailed'])){ ?>
                                        <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Failed To Update Exam Marks</p>
                                    <?php }?>
                                    <?php if(isset($_GET['studentdeletedsuccessfully'])){ ?>
                                        <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Student Data Deleted Successfully</p>
                                    <?php }?>
                                    <?php if(isset($_GET['studentdeletionfailed'])){ ?>
                                        <p class='bg-danger p-2  mt-3 fw-bold text-break text-white'>Failed To Delete Student Data</p>
                                    <?php }?>
                                    <div class="card-body">
                                        <table class="table table-bordered table-hover table-sm" id='myTable'>
                                          
                                            <!-- card that shows total student enteries in quiz-->
                                            <div class="col-lg-3 col-md-4 col-sm-12 mb-4 ">
                                                <div class="card border-primary h-100 shadow-sm">
                                                    <div class="card-body text-center">
                                                   
                                                    <h6 class="card-title text-uppercase text-muted mb-2">Total Student Entries</h6>
                                                    <h2 class="card-text fw-bold text-primary mb-0">
                                                        <?php 
                                                            $examformid1 = $_GET['entries'];
                                                            $count_query = "select count(DISTINCT stdid) as total from studentexam where examformid = '$examformid1'";
                                                            $count_result = mysqli_query($connection, $count_query);
                                                            $count_data = mysqli_fetch_assoc($count_result);
                                                            echo $count_data['total'];
                                                        ?>
                                                    </h2>
                                                </div>
                                                <div class="card-footer bg-primary bg-opacity-10 border-0 text-center">
                                                    <small class="text-muted">Exam Participants</small>
                                                </div>
                                            </div>
                                        </div> 
                                            <!-- end here-->
                                            <div class="col-lg-7 mb-2">
                                              <input type="text" placeholder="Search By student id or name" class='form-control mb-2' id='myInput' onkeyup="searchTable()">
                                                <div>
                                                <a href="viewexamform.php" class="btn btn-primary mb-1 fw-bold btn-sm">Go Back</a>
                                                <a href="slices/exportexamformdata.slices.php?examformid=<?php echo $examformid1;?>" class="btn btn-secondary mb-1 fw-bold btn-sm">Convert To Excel</a>
                                                   
                                                </div>
                                          </div>
                                            <tr>
                                                <td>#</td>
                                                <td>Student_ID</td>
                                                <td>Student_Name</td>
                                                <td>Wrong_Questions</td>
                                                <td>Correct_Questions</td>
                                                <td>Total_Questions</td>
                                                <td>Exam_Date</td>
                                                <td>Exam_Marks</td>
                                                <td>Actions</td>
                                            </tr>
                                            <?php 
                                             $examformid = $_GET['entries'];
                                            // read quizform and see what is the type of this quizform
                                            $sqlmain = "select examtype from examform where examformid = '$examformid'";
                                            $resultmain = mysqli_query($connection,$sqlmain);
                                            $examformdata = mysqli_fetch_assoc($resultmain);
                                            // if the quiztype is trueandfalse then read from trueandfalse table
                                           if(!empty($examformdata) && $examformdata['examtype'] == 'trueandfalse'){

                                               $sql = "SELECT
                                            sq.stdfullname,
                                            sq.stdid,
                                            sq.exammarks,
                                            sq.exam_taken_date,
                                            qf.number_of_questions,
                                            COUNT(sq.question_id) AS total_questions,
                                            COUNT(
                                                CASE WHEN sq.selected_option = o.is_correct_option THEN 1 ELSE NULL END
                                                            ) AS correct_count,
                                                            COUNT(
                                                                CASE WHEN sq.selected_option <> o.is_correct_option THEN 1 ELSE NULL END
                                                            ) AS wrong_count
                                                        FROM
                                                            studentexam sq
                                                        JOIN examtrue_false_options o
                                                        ON
                                                            sq.question_id = o.questionid 
                                                            AND sq.examformid = o.examformid
                                                        JOIN examform qf 
                                                        ON
                                                            sq.examformid = qf.examformid
                                                        WHERE
                                                            qf.teacherid = '$teacherid'
                                                            AND sq.examformid = '$examformid'
                                                        GROUP BY
                                                            sq.stdfullname,
                                                            sq.stdid,
                                                            sq.exammarks,
                                                            sq.exam_taken_date,
                                                            qf.number_of_questions
                                                            ORDER BY
                                                              sq.stdfullname ASC,  -- Alphabetical order
                                                                sq.stdid ASC   -- Alphabetical order
                                                            ; ";

                                            $result = mysqli_query($connection,$sql);
                                            if(mysqli_num_rows($result) < 0){
                                                echo "<p>There is currently no data to be shown</p>";
                                                // exit();
                                            }else{
                                                $rowid = 1;
                                                while($row = mysqli_fetch_assoc($result)){?>
                                                <form method='POST' action="viewexamform.php?studentid=<?php echo $row['stdid'] ?>&entries=<?php echo $examformid ?>">
                                                    <tr>
                                                        <td><?php echo $rowid?></td>
                                                        <td><a href="../exam/showteacherstudententries.php?stdid=<?php echo $row['stdid']?>&examformid=<?php echo $examformid; ?>" target="_blank"><?php echo $row['stdid']?></a></td>
                                                        <td><?php echo $row['stdfullname']?></td>
                                                        <td><?php echo $row['wrong_count']?></td>
                                                        <td><?php echo $row['correct_count']?></td>
                                                        <td><?php echo $row['number_of_questions']?></td>
                                                        <td><?php echo date('M-j-Y ', strtotime($row['exam_taken_date']))?></td>
                                                        <td><input name='exammarks' value="<?php echo $row['exammarks']?>" style='width:60px !important;'/></td>
                                                        <td>
                                                            <button type='submit' class='btn btn-primary fw-bold btn-sm sm-mb-2' name='updatequizentries'>Update</button>
                                                            <a href="viewexamform.php?entries=<?php echo $examformid ?>&delstudentid=<?php echo $row['stdid'] ?>" class='btn btn-danger fw-bold btn-sm'>Delete</a>
                                                        </td>
                                                    </tr>   
                                                    </form>
                                                <?php $rowid++; }}
                                            }
                                            // if the quiztype is singlechoiceanswer read from options table
                                            $sql = "SELECT sq.stdfullname,sq.stdid,sq.exammarks,sq.exam_taken_date,number_of_questions, COUNT(CASE WHEN sq.selected_option = o.is_correct_option THEN 1 END) AS correct_count, COUNT(CASE WHEN sq.selected_option <> o.is_correct_option THEN 1 END) AS wrong_count, qf.number_of_questions FROM studentexam sq JOIN examoptions o ON sq.question_id = o.questionid AND sq.examformid = o.examformid JOIN examform qf ON sq.examformid = qf.examformid WHERE qf.teacherid = '$teacherid' and sq.examformid = '$examformid' GROUP BY sq.stdfullname, sq.stdid, sq.exammarks, qf.number_of_questions ORDER BY
                                                              sq.stdfullname ASC,  -- Alphabetical order
                                                                sq.stdid ASC   -- Alphabetical order ";
                                            $result = mysqli_query($connection,$sql);
                                            if(mysqli_num_rows($result) == 0){
                                                echo "<p>There is currently no data to be shown</p>";
                                            }else{
                                                $rowid = 1;
                                                while($row = mysqli_fetch_assoc($result)){?>
                                                <form method='POST' action="viewexamform.php?studentid=<?php echo $row['stdid'] ?>&entries=<?php echo $examformid ?>">
                                                    <tr>
                                                        <td><?php echo $rowid?></td>
                                                        <td><a href="../exam/showteacherstudententries.php?stdid=<?php echo $row['stdid']?>&examformid=<?php echo $examformid; ?>" target="_blank"><?php echo $row['stdid']?></a></td>
                                                        <td><?php echo $row['stdfullname']?></td>
                                                        <td><?php echo $row['wrong_count']?></td>
                                                        <td><?php echo $row['correct_count']?></td>
                                                        <td><?php echo $row['number_of_questions']?></td>
                                                        <td><?php echo date('M-j-Y ', strtotime($row['exam_taken_date']))?></td>
                                                        <td><input name='exammarks' value="<?php echo $row['exammarks']?>" style='width:80px !important;'/></td>
                                                        <td>
                                                            <button type='submit' class='btn btn-primary fw-bold btn-sm sm-mb-2' name='updatequizentries'>Update</button>
                                                            <a href="viewexamform.php?entries=<?php echo $examformid ?>&delstudentid=<?php echo $row['stdid'] ?>" class='btn btn-danger fw-bold btn-sm'>Delete</a>
                                                        </td>
                                                    </tr>   
                                                    </form>
                                                <?php $rowid++; }}
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

                 //search table by id
       function searchTable() {
    var input, filter, found, table, tr, td, i, j;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                found = true;
            }
        }
        if (found) {
            tr[i].style.display = "";
            found = false;
        } else {
            tr[i].style.display = "none";
        }
    }
}

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
