<?php
include('./../../model/dbcon.php');
include('slices/studentcreationvalidation.php');
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

///check if session exist , if it exist prevent user from seeing login page
if (!isset($_SESSION['userid'])) {
    header('location:../login.php');
    exit();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - Create Assignment Form</title>
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
            <?php include('include/sidebar.php'); ?>


            <!-- Main Content -->
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4">
                <!-- Navbar for Mobile View -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light d-md-none">
                    <button id='hambergermenu' class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bi bi-list"></i> Menu
                    </button>
                </nav>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" style='font-size:15px;'>Assignment / <span>Create Assignment Form</span></h1>
                </div>

                <div class="row">
                    <div class="col-12 col-md-8 col-xl-5 mb-4">
                        <div class="card p-2 rounded" style='border:none !important; background-color:#f8f9fa !important;'>
                            <h4 class="card-title px-3 fw-bold mt-2 mb-0" style='font-size:17px !important;'>
                                Create Assignment Form
                            </h4>
                            <div class="card-body">
                                <?php if (isset($_GET['emptyfields'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Please fill all the fields to create assignment form</p>
                                <?php } ?>
                                <?php if (isset($_GET['queryfailed'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Failed to create assignment form</p>
                                <?php } ?>
                                <?php if (isset($_GET['formcreated'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Assignment form created successfully</p>
                                <?php } ?>
                                <?php if (coursenames($_SESSION['userid'],$connection) == false) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Please add new course name before adding new student!</p>
                                <?php } ?>
                               
                                
                                <form method='post' action='slices/createassignment.slice.php'>
                                    <div class="form-group mb-3">
                                        <label class='form-label'>Form Title</label>
                                        <input type="text" name='formtitle' class='form-control' placeholder="Enter Form Title"/>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class='form-label'>Form Description</label>
                                        <textarea name="formdesc" rows="5" col="5" class='form-control' placeholder="Enter Form Description"></textarea>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class='form-label'>Course Name</label>
                                        <select class="form-select" name='coursename'>
                                           
                                        <?php  
                                        // if teacher tries to create student before creating course show him none in course selection
                                           if(coursenames($_SESSION['userid'],$connection) == false){
                                            echo '<option>none</option>';
                                           }else{
                                           
                                            foreach (coursenames($_SESSION['userid'],$connection) as $data) {?>
                                                <option value='<?php echo $data['coursename']?>'><?php echo $data['coursename']?></option>
                                            <?php }
                                           }

                                        
                                        ?>
                                        </select>
                                        <div class="form-group mt-3">
                                            <label class='form-label'>Template</label>
                                            <select name='template' class='form-select'>
                                                <option value="default_template">Default Template</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label class='form-label'>Upload File Type</label>
                                            <select name='uploadfiletype' class='form-select'>
                                                <option value="word_document">Word Document</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label class="form-label">Assignment Marks</label>
                                            <input type="text" class="form-control" name='marks' placeholder="Enter Assignment Makrs">
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-sm mt-2 fw-bold  <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled' : ''?>" name='submit'>Submit</button>
                                </form>
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