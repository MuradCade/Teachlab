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
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $pwd = $_POST['role'];
    $hashpwd = password_hash($pwd,PASSWORD_DEFAULT);
    $subscription_status  =$_POST['subscription_status'];
    $v_status = $_POST['v_status'];
    if(empty($userid)){
        header('location:viewusers.php?emptyuserid');
        exit();
    }else if(empty($fullname)){
        header('location:viewusers.php?emptyfullname');
        exit();
    }else if(empty($email)){
        header('location:viewusers.php?emptyemail');
        exit();
    }else if(empty($role)){
        header('location:viewusers.php?emptyrole');
        exit();
    }else if(empty($v_status)){
        header('location:viewusers.php?emptyv_status');
        exit();
    }
    else if(empty($subscription_status)){
        header('location:viewusers.php?emptysubscription');
        exit();
    }
    else{
        if(empty($pwd)){
            $sql = "update users set fullname = '$fullname', role = '$role' ,verified_status = '$v_status' , subscription_status = '$subscription_status' where userid = '$userid'";
            $result = mysqli_query($connection,$sql);
            if($result){
                header('location:viewusers.php?q1updatesuccess');
                exit();
            }
        }else{
            $sql2 = "update users set fullname = '$fullname', role = '$role', pwd = '$hashpwd', verified_status = '$v_status' ,subscription_status = '$subscription_status' where userid = '$userid' ";
            $result2 = mysqli_query($connection,$sql2);
            if($result2){
                header('location:viewusers.php?q2updatesuccess');
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
    <title>TeachLab - Manage Users</title>
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
                                <td>fullname</td>
                                <td>email</td>
                                <td>role</td>
                                <td>verified_status</td>
                                <td>subscription_status</td>
                                <td>update_password</td>
                                <td>Action</td>
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
                                            <td><input type='text' value="<?php echo $row['userid']?>" name='userid' readonly/></td>
                                            <td><input type='text' value="<?php echo $row['fullname']?>" name='fullname'/></td>
                                            <td><input type='text' value="<?php echo $row['email']?>" name='email' readonly/></td>
                                            <td><input type='text' value="<?php echo $row['role']?>" name='role'/></td>
                                            <td><input type='text' name='v_status' value="<?php echo $row['verified_status'];?>"/></td>
                                            <td><input type='text' name='subscription_status' value="<?php echo $row['subscription_status'];?>"/></td>
                                            <td><input type='text' name='pwd' placeholder="update password"/></td>
                                            <td><button class='btn btn-primary btn-sm fw-bold' name='update'>Update</button>&numsp; <a href="viewusers.php?userid=<?php echo $row['userid']?>" class='btn btn-danger btn-sm fw-bold'>Delete</a></td>
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
