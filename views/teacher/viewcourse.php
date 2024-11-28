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


$teacherid = $_SESSION['userid'] ?? null;


// delete course information
if(isset($_GET['delid'])){
    $courseid = $_GET['delid'];
    // we delete course information and students related to that course with give course id at once 
    // first task
    // get the coursename with given courseid
    $sql = "select coursename from course where courseid = '$courseid' and teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    if($result){
        $row = mysqli_fetch_assoc($result);
        $coursename = $row['coursename'];
        // second task delete all the data of the  student associated with that coursename
        $sql2 = "delete from students where coursename = '$coursename' and teacherid ='$teacherid'";
        $result2 = mysqli_query($connection,$sql2);
        if($result2){
            // third task delete all attendance related to deleted course (attendence of students)
            $sql3 = "delete from markattendence where coursename = '$coursename' and teacherid = '$teacherid'";
            $result3 = mysqli_query($connection,$sql3);
           
            if($result3){
                 // four task delete course information related to given course id from the course table
                 $sql4 = "delete from course where courseid = '$courseid'";
                 $result4 = mysqli_query($connection,$sql4);
                if($result4){
                    header('location:viewcourse.php?delsuccess');
                     exit();
                }else{
                    header('location:viewcourse.php?delsuccess');
                     exit();
                }
            }else{
                header('location:viewcourse.php?delfailedq3');
                exit();
            }
        }else{
            header('location:viewcourse.php?delfailedq2');
            exit();
        }
    }else{
        header('location:viewcourse.php?delfailedq1');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - View Course Information</title>
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
                <h1 class="h2" style='font-size:15px;'>Course / <span>View Course Information</span></h1>
                </div>

                <div class="row">
                    <div class="col-12 col-md-8 col-xl-8 mb-4">
                        <div class="card p-2 rounded" style='border:none !important; background-color:#f8f9fa !important;'>
                            <h4 class="card-title px-3 fw-bold mt-2 mb-0" style='font-size:17px !important;'>
                                View Course Information
                            </h4>
                            <div class="card-body">
                                <?php if (isset($_GET['updatefailed'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Failed To Update Course Information , Please Try Again Later.</p>
                                <?php } ?>
                                <?php if (isset($_GET['updatesuccess'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Course Information Updated  Successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['delsuccess'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Course Information Deleted  Successfully</p>
                                <?php } ?>
                                <table class='table table-sm table-hover table-responsive table-bordered'>
                                    <tr>
                                        <td>#</td>
                                        <td>Coursename</td>
                                        <td>Created_At</td>
                                        <td>Action</td>
                                    </tr>
                                    <?php if (isset($teacherid)) {
                                        $rownumber = 1;
                                        $sql = "select * from course where teacherid = '$teacherid'";
                                        $result = mysqli_query($connection, $sql);
                                        if (mysqli_num_rows($result) == 0) {
                                            echo "<span style='font-size:15px;'>There’s currently no data to show, <br>
                                                Please add a new course to display it here.</span>";
                                        } else {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                                <tr>
                                                    <td><?php echo $rownumber; ?></td>
                                                    <td><?php echo $row['coursename']; ?></td>
                                                    <td><?php echo date('M-j-Y ', strtotime($row['date'])); ?></td>
                                                    <td><a href="updatecourse.php?courseid=<?php echo $row['courseid'] ?>" class='btn btn-primary btn-sm fw-bold '>Update</a>&numsp;<a href="viewcourse.php?delid=<?php echo $row['courseid']?>" class='btn btn-danger btn-sm fw-bold '>Delete</a></td>
                                                </tr>
                                    <?php $rownumber++;
                                            }
                                        }
                                    } else {
                                        echo 'sorry,failed to fetchdata';
                                    }  ?>
                                </table>
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