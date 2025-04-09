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



function totalstudentsforeachteacher($teacherid,$connection){
    $sql = "select * from students where teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    return mysqli_num_rows($result);
}

function totalcoursesforeachteacher($teacherid,$connection){
    $sql = "select * from course where teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    return mysqli_num_rows($result);
}
function totalassignmentformforeachteacher($teacherid,$connection){
    $sql = "select * from assignmentform where teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    return mysqli_num_rows($result);
}
function totalquizformforeachteacher($teacherid,$connection){
    $sql = "select * from quizform where teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    return mysqli_num_rows($result);
}
function totalexamformforeachteacher($teacherid,$connection){
    $sql = "select * from examform where teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    return mysqli_num_rows($result);
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - User Details</title>
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
                    <h1 class="h2" style='font-size:17px;'>Subscription / Subscription Orders</h1>
                </div>

                <div class="row">
                    <?php if(!isset($_GET['details'])){?>
                    <div class="col-lg-10 col-md-10 col-xl-12 mb-4">
                        


                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>#</td>
                                <td>userid</td>
                                <td>Fullname</td>
                                <td>Total Students</td>
                                <td>Total Courses</td>
                                <td>Total Assignment</td>
                                <td>Total Quiz</td>
                                <td>Total Exam</td>
                               
                            </tr>

                            <?php
                                $sql = "select * from users";
                                $result = mysqli_query($connection,$sql);
                                if(mysqli_num_rows($result) == 0){
                                    echo 'there is no data to be shown';
                                }else{
                                    $rowid = 1;
                                    while($row = mysqli_fetch_assoc($result)){?>
                                    <form method="post">
                                        <tr>
                                            <td><?php echo $rowid;?></td>
                                            <td><?php echo $row['userid']?></td>
                                            <td><?php echo $row['fullname']?></td>
                                            <td><?php echo totalstudentsforeachteacher($row['userid'],$connection);?></td>
                                            <td><?php echo totalcoursesforeachteacher($row['userid'],$connection);?></td>
                                            <td><?php echo totalassignmentformforeachteacher($row['userid'],$connection);?></td>
                                            <td><?php echo totalquizformforeachteacher($row['userid'],$connection);?></td>
                                            <td><?php echo totalexamformforeachteacher($row['userid'],$connection);?></td>
                                            
                                        </tr>
                                    </form>
                                    <?php $rowid++;}
                                }
                            
                            ?>
                        </table>
                    </div>
                    <?php } else if(isset($_GET['details'])){
                        $userid = base64_decode($_GET['details']);?>
                        <!-- update subscription amount (days left)-->
                        <?php if(isset($_GET['emptyuserid'])){?>
                            <p class="bg-danger p-2 text-white fw-bold" style='font-size:14px;'>Failed to update subscription info , userid not passed</p>
                            <?php } ?>
                        <?php if(isset($_GET['emptysubstatus'])){?>
                            <p class="bg-danger p-2 text-white fw-bold" style='font-size:14px;'>Empty subscription_status field</p>
                            <?php } ?>
                        <?php if(isset($_GET['subscriptionupdatesuccess'])){?>
                            <p class="bg-success p-2 text-white fw-bold" style='font-size:14px;'>Subscription Order  Updated Successfully</p>
                            <?php } ?>
                        <?php if(isset($_GET['delsuccess'])){?>
                            <p class="bg-success p-2 text-white fw-bold" style='font-size:14px;'>Subscription Order  Deleted Successfully</p>
                            <?php } ?>
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>#</td>
                                <td>userid</td>
                                <td>fullname</td>
                                <td>number</td>
                                <td>subscription_plan</td>
                                <td>amount</td>
                                <td>started_date</td>
                                <td>expire_date</td>
                                <td>Days_Left</td>
                                <td>subscription_status</td>
                                <td>payment_status</td>
                                <td>Action</td>
                            </tr>

                            <?php
                                $sql = "select * from usersubscription_manager where userid = '$userid' order by id";
                                $result = mysqli_query($connection,$sql);
                                if(mysqli_num_rows($result) == 0){
                                    echo 'there is no data to be shown';
                                }else{
                                    $rowid = 1;
                                    while($row = mysqli_fetch_assoc($result)){?>
                                    <form method="post">
                                        <tr>
                                            <td><?php echo $rowid;?></td>
                                            <td><?php echo $row['userid']?></td>
                                            <input type='hidden' value="<?php echo $row['id']?>" name='userid' readonly style='border:none;width:60px'/>
                                            <input type='hidden' value="<?php echo $row['userid']?>" name='mainid' readonly style='border:none;width:60px'/>
                                            <td><?php echo $row['fullname']?></td>
                                            <td><?php echo $row['number']?></td>
                                            <td><?php echo $row['subscription_plan']?></td>
                                            <td>$<?php echo $row['amount']?></td>
                                            <td><?= date('M-j-Y ', strtotime($row['started_date'])) ?></td>
                                            <td><?= date('M-j-Y ', strtotime($row['expire_date'])) ?></td>
                                            <td><?php echo $row['days_left'].' days'?></td>
                                            <td><input type='text' name='substatus' value="<?php echo $row['subscription_status'];?>"/></td>
                                            <td><?php echo $row['payment_status']?></td>
                                            <td><button class='btn btn-primary btn-sm fw-bold mb-1' name='update'>Update</button> <a href='subscription_orders.php?delid=<?= base64_encode($row['id'])?>&mainid=<?= base64_encode($row['userid'])?>' class='btn btn-danger btn-sm fw-bold'>Delete</a></td>
                                        </tr>
                                    </form>
                                    <?php $rowid++;}
                                }
                            
                            ?>
                        </table>
                        <?php } ?>
                    
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
