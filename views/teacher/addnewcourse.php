<?php
include_once('../../model/dbcon.php');
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//check if session exist , if it exist prevent user from seeing login page
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
    <title>TeachLab - Add New Course</title>
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
                    <h1 class="h2" style='font-size:15px;'>Course / <span>Add new course</span></h1>
                </div>

                <div class="row">
                    <div class="col-12 col-md-8 col-xl-5 mb-4">
                        <div class="card p-2 rounded" style='border:none !important; background-color:#f8f9fa !important;'>
                            <h4 class="card-title px-3 fw-bold mt-2 mb-0" style='font-size:17px !important;'>
                                Add New Course
                            </h4>
                            <div class="card-body">
                                <?php if (isset($_GET['emptycoursenamefield'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Empty Coursename Field</p>
                                <?php } ?>
                                <?php if (isset($_GET['success'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Course Information Saved Successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['failed'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>
                                        Failed to save coursename , please try again later
                                    </p>
                                <?php } ?>
                                <?php if (isset($_GET['exists'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>
                                    Sorry, a course with that name already exists.
                                    </p>
                                <?php } ?>

                                <?php if(checkcourseamount($connection,$_SESSION['userid'],'free')){?>
                                    <p class='alert alert-danger p-2'>Dear User, You have reached the maximum number of courses for your free plan. Please upgrade to a paid plan to create more courses.
                                        in order to upgrade go to home and click on upgrade button.
                                    </p>
                                <?php } ?>
                                    <form method='post' action='slices/addnewcourse.slice.php'>
                                    <div class="form-group">
                                        <label class='form-label'>Course name</label>
                                        <input type="text" name='coursename' class='form-control' placeholder="Enter Coursename" />
                                    </div>
                                    <button class="btn btn-primary btn-sm mt-2 fw-bold <?php echo checkcourseamount($connection,$_SESSION['userid'],'free') ? 'disabled' : ''?> " name='submit'>Submit</button>
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