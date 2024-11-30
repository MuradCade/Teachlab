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


if(isset($_POST['update'])){
    $userid = $_POST['userid'];
    $subsatus = $_POST['substatus'];
    $subplan = $_POST['subplan'];
    $subamount = $_POST['subamount'];
    if(empty($userid)){
        header('location:manage_subscription.php?emptyuserid');
        exit();
    }else if(empty($subsatus)){
        header('location:manage_subscription.php?emptysubscriptionstatus');
        exit();
    }else if(empty($subplan)){
        header('location:manage_subscription.php?emptysubscriptionplan');
        exit();
    }else if(empty($subamount)){
        header('location:manage_subscription.php?emptysubscriptionamount');
        exit();
    }
    else{
       
            $sql = "update subscription set subsatus = '$subsatus', subplan = '$subplan' ,subamount = '$subamount' where userid = '$userid'";
            $result = mysqli_query($connection,$sql);
            if($result){
                $sql2 = "update users set subscription_status = '$subsatus' where userid = '$userid'";
                $result2 = mysqli_query($connection,$sql2);
                if($result2){
                    header('location:manage_subscription.php?q1updatesuccess');
                    exit();
                }
    
            }
       
        
    }
}


//delete
if(isset($_GET['userid'])){
    $userid = $_GET['userid'];
    $sql = "delete from users where userid = '$userid'";
    $result = mysqli_query($connection,$sql);
    if($result){
        header('location:viewusers.php?delsuccess');
                exit();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - Manage Subscription</title>
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
                    <h1 class="h2" style='font-size:17px;'>View Users</h1>
                </div>

                <div class="row">
                    <div class="col-8 col-md-6 col-xl-8 mb-4">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>#</td>
                                <td>userid</td>
                                <td>date</td>
                                <td>subscription_status</td>
                                <td>subscription_plan</td>
                                <td>subscription_days</td>
                                <td>Action</td>
                            </tr>

                            <?php
                                $sql = "select * from subscription";
                                $result = mysqli_query($connection,$sql);
                                if(mysqli_num_rows($result) == 0){
                                    echo 'there is no data to be shown';
                                }else{
                                    $rowid = 1;
                                    while($row = mysqli_fetch_assoc($result)){?>
                                    <form method="post">
                                        <tr>
                                            <td><?php echo $rowid;?></td>
                                            <td><input type='text' value="<?php echo $row['userid']?>" name='userid' readonly/></td>
                                            <td><?php echo date('M-j-Y ', strtotime($row['date']));?></td>
                                            <td><input type='text' value="<?php echo $row['subsatus']?>" name='substatus' /></td>
                                            <td><input type='text' value="<?php echo $row['subplan']?>" name='subplan'/></td>
                                            <td><input type='text' name='subamount' value="<?php echo $row['subamount'];?>"/></td>
                                            <td><button class='btn btn-primary btn-sm fw-bold' name='update'>Update</button></td>
                                        </tr>
                                    </form>
                                    <?php $rowid++;}
                                }
                            
                            ?>
                        </table>
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
