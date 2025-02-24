<?php
include('../../model/dbcon.php');
include('slices/studentcreationvalidation.php');
include('include/restrictions.php');
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
  
}

//check if session exist , if it exist prevent user from seeing login page
if (!isset($_SESSION['userid'])) {
    header('location:../login.php');
    exit();
}


// generating random quiz id
$quizid = rand(1000, 9999);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - Create Quiz Form</title>
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
                    <h1 class=" fw-medium" style='font-size:16px'>Quiz / <span>Create Quiz Form</span></h1>
                </div>

               

                <div class="row">
                    <div class="col-12 col-md-6 col-xl-6 mb-4">
                        <div class="card p-2" style="border:none !important; background-color:#f8f9fa !important;">
                            
                            <?php if (coursenames($_SESSION['userid'],$connection) == false) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Please add new course name before Creating new QuizForm</p>
                                <?php } ?>
                                <?php if (isset($_GET['emptyquizid'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Empty Quiz id field</p>
                                <?php } ?>
                                <?php if (isset($_GET['emptyquiztitle'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Empty Quiz title field</p>
                                <?php } ?>
                                <?php if (isset($_GET['emptyquizdescription'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Empty Quiz description field</p>
                                <?php } ?>
                                <?php if (isset($_GET['emptycoursename'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Empty Course name field</p>
                                <?php } ?>
                                <?php if (isset($_GET['emptyquizstatus'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Empty Quiz status field</p>
                                <?php } ?>
                                <?php if (isset($_GET['emptynumberofquestion'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Empty Number of question field</p>
                                <?php } ?>
                                <?php if (isset($_GET['success'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Quiz form created successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['error'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Failed to create  quiz form</p>
                                <?php } ?>
                                <?php if (isset($_GET['emptyquiztype'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Empty Quiz type field</p>
                                <?php } ?>
                                <?php if (checkquizamount($connection,$_SESSION['userid'],'free')) { ?>
                                    <p class='alert alert-danger p-2'>Dear User, You have reached the maximum number of courses for your free plan. Please upgrade to a paid plan to create more courses.
                                        in order to upgrade go to home and click on upgrade button.
                                    </p>
                                <?php } ?>
                                <h4 class="card-title fw-bold mb-0 px-3 mt-1" style='font-size:17px !important;'>
                                    Create Quiz Form
                                </h4>
                            
                                    <div class="card-body">
                                    <form method="POST" action="slices/createquizform.slice.php">
                                    <div class="mb-3">
                                        <label for="quizId" class="form-label">Quiz ID</label>
                                        <input type="text" class="form-control"name="quizid"  placeholder="Enter Quiz ID" value="<?php echo $quizid;?>" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="quizTitle" class="form-label">Quiz Title</label>
                                        <input type="text" class="form-control"  name="quiztitle"  placeholder="Enter Quiz Title">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="quizDescription" class="form-label">Quiz Description</label>
                                        <textarea class="form-control"  name="quizdescription" rows="4"  placeholder="Enter Quiz Description"></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="courseName" class="form-label">Course Name</label>
                                        <select class="form-select"  name="coursename">
                                            <option value="">Select Course</option>
                                            <?php
                                            $teacherid = $_SESSION['userid']??'';
                                            // Fetch courses from database
                                            $sql = "SELECT * FROM course where teacherid =  '$teacherid'";
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
                                            <option value="">Select Status</option>
                                            <option value="active">Active</option>
                                            <option value="disable">Disable</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="quizStatus" class="form-label">Quiz Type</label>
                                        <select class="form-select" name="quiztype">
                                            <option value="">Select Quiz Type</option>
                                            <option value="trueandfalse">True And False</option>
                                            <option value="singlechoicequestion">Single Choice Questions</option>
                                            
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="numerofquestions" class="form-label">Number of Questions</label>
                                     <select name="numberofquestion" class="form-select">
                                        <option value="">Select Number of Questions</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                     </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary  fw-bold <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled' : ''?><?php echo checkquizamount($connection,$_SESSION['userid'],'free') ? 'disabled' : ''?>" name='submit'>Submit</button>
                                </form>
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
    </script>
</body>
</html>
