<?php
include('../../model/dbcon.php');
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//check if session exist , if it exist prevent user from seeing login page
if (!isset($_SESSION['userid'])) {
    header('location:../login.php');
    exit();
}

// $adminid = $_SESSION['userid'];
//totalcourses
function totalusers($connection){
    $sql = "select * from users";
    $result = mysqli_query($connection,$sql);
    if($result){
       $row = mysqli_num_rows($result);
       

            return $row;
       
        
    } 
}
//total Number of students
function totalstudents($connection){
    $sql = "select * from students";
    $result = mysqli_query($connection,$sql);
    if($result){
       $row = mysqli_num_rows($result);
       

            return $row;
       
        
    } 
}
//totalassignment
function totalassignment($connection){
    $sql = "select * from assignmentform";
    $result = mysqli_query($connection,$sql);
    if($result){
       $row = mysqli_num_rows($result);
       

            return $row;
       
        
    } 
}


//totalaquiz
function totalquiz($connection){
    $sql = "select * from quizform";
    $result = mysqli_query($connection,$sql);
    if($result){
       $row = mysqli_num_rows($result);
       

            return $row;
       
        
    } 
}

function totalexam($connection){
    $sql = "select * from examform";
    $result = mysqli_query($connection,$sql);
    if($result){
       $row = mysqli_num_rows($result);
       

            return $row;
       
        
    } 
}


function totalcourses($connection){
    $sql = "select * from course";
    $result = mysqli_query($connection,$sql);
    if($result){
       $row = mysqli_num_rows($result);
       

            return $row;
       
        
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - Dashboard</title>
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
                    <h1 class="h2" style='font-size:17px;'>Admin Dashboard</h1>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                            <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Total Users</h5>
                                <p class="card-text"><?php echo totalusers($connection); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                        <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Total Number Courses</h5>
                                <p class="card-text"><?php echo totalcourses($connection) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                        <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Total Number of Students</h5>
                                <p class="card-text"><?php echo totalstudents($connection) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-dark" style='background-color:#f8f9fa !important;'>
                        <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Total Number Assignment Form</h5>
                                <p class="card-text"><?php echo totalassignment($connection)?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-dark" style='background-color:#f8f9fa !important;'>
                        <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Total Number Quiz Form</h5>
                                <p class="card-text"><?php echo totalquiz($connection)?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-dark" style='background-color:#f8f9fa !important;'>
                        <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Total Number Exam Form</h5>
                                <p class="card-text"><?php echo totalexam($connection)?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- More Content Here -->
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
