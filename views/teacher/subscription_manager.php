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
                    <div class="col-lg-12 col-md-10 col-xl-11 mb-4">
                        <div class="card p-2 rounded" style='border:none !important; background-color:#f8f9fa !important;'>
                            <h4 class="card-title px-3 fw-bold mt-2 mb-0" style='font-size:17px !important;'>
                              Subscription Information
                            </h4>
                            <div class="card-body">
                              
                                <table class='table table-sm table-hover table-responsive table-bordered'>
                                    <tr>
                                        <td>#</td>
                                        <td>Subscription_Plan_Name</td>
                                        <td>Subscription_Started_Date</td>
                                        <td>Subscription_Expire_Date</td>
                                        <td>Days_Left_To_Expire</td>
                                        <td>Subscription_Status</td>
                                        <td>Action</td>
                                    </tr>
                                    <?php if (isset($teacherid)) {
                                        $rownumber = 1;
                                        $sql = "select * from usersubscription_manager where userid = '$teacherid'";
                                        $result = mysqli_query($connection, $sql);
                                        if (mysqli_num_rows($result) == 0) {
                                            echo "<span style='font-size:15px;'>There’s currently no data to be show, <br>
                                                </span>";
                                        } else {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                                <tr>
                                                    <td><?php echo $rownumber; ?></td>
                                                    <td><?php echo $row['subscription_plan']; ?></td>
                                                    <td><?php echo date('M-j-Y ', strtotime($row['started_date'])); ?></td>
                                                    <td><?php echo  date('M-j-Y ', strtotime($row['expire_date'])); ?></td>
                                                    <td><?php echo $row['days_left']; ?></td>
                                                    <td><p class="<?php echo $row['subscription_status'] == 'active'?'text-success fw-bold': 'text-danger fw-bold' ?>"><?php echo $row['subscription_status']; ?></p></td>
                                                    <td><a href="subscription_manager.php?detials=<?= base64_encode($row['id'])?>" class='btn btn-primary btn-sm fw-bold'>Details</a></td>
                                                </tr>
                                                   <!-- quiz model that displays complete information starts here-->
                                        <?php if(isset($_GET['detials'])){?>    
                                        <!-- Modal -->
                                        <div class="modal " id="quizformdetails"  style="display:block;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Subscription Details</h1>
                                            </div>
                                            <div class="modal-body">
                                                <?php 
                                                $id = base64_decode($_GET['detials']);
                                                $sql = "select * from usersubscription_manager where id = '$id'";
                                                $result = mysqli_query($connection,$sql);

                                                while($rowsdata = mysqli_fetch_assoc($result)):
                                                ?>  

                                                <p>Fullname: <?= $rowsdata['fullname']?></p>
                                                <p>Phone: <?= $rowsdata['number']?></p>
                                                <p>Subscription_Plan: <?= $rowsdata['subscription_plan']?></p>
                                                <p>Subscription_Amount: $<?= $rowsdata['amount']?></p>
                                                <p>Started_Date: <?= $rowsdata['started_date']?></p>
                                                <p>Expire_Date: <?= $rowsdata['expire_date']?></p>
                                                <p>Days_Left_to_expire: <?= $rowsdata['days_left']?> days</p>
                                                <p>Payment_Status: <?= $rowsdata['payment_status']?></p>
                                                <p style='text-transform:capitalize;'>Subscription_Status: <?= $rowsdata['subscription_status']?></p>

                                                <?php endwhile;?>
                                            </div>
                                            <div class="modal-footer">
                                                <a type="button" class="btn btn-secondary btn-sm fw-bold" href='subscription_manager.php'>Close</a>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                        <?php }?>
                                            <!-- quiz model ends here-->
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