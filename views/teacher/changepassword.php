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

$teacherid = $_SESSION['userid'];

//change password
if(isset($_POST['savepwd']) && isset($teacherid)){
    $pwd = mysqli_real_escape_string($connection ,$_POST['pwd']);
    if(empty($pwd)){
        header('location:changepassword.php?emptypwd');
        exit();
    }else{
        //hash the new password
    $hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);

        $sql = "update users set pwd = '$hashedpwd' where userid = '$teacherid'";
        $result = mysqli_query($connection,$sql);
        if($result){
            header('location:changepassword.php?pwdsuccess');
            exit();
        }else{
            header('location:changepassword.php?pwdfailed');
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
    <title>TeachLab - Change Password</title>
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
                    <h1 class=" fw-medium" style='font-size:16px'>Setting / <span>Change Password</span></h1>
                </div>

                <div class="row">
                <div class="row">
                <div class="col-4">
                    <div id="list-example" class="list-group">
                    <a class="list-group-item list-group-item-action" href='setting.php'>Update Your Password</a>
                    <a class="list-group-item list-group-item-action bg-primary text-white fw-bold" href="#">Change Password</a>
                    
                    </div>
                </div>
                <div class="col-6">
                    <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" class="scrollspy-example" tabindex="0">
                            
                    <div class="card p-3"  style='border:none !important; background-color:#f8f9fa !important;'>
                        <h4  class='card-title fw-bold px-3' style='font-size:16px;'>Change Password</h4>
                        <div class="card-body">
                        <?php if(isset($_GET['emptypwd'])){?>
                            <p class='bg-danger text-white p-2'>Empty Password Field</p>
                        <?php }?>
                        <?php if(isset($_GET['pwdfailed'])){?>
                            <p class='bg-danger text-white p-2'>Sorry, we failed to update your password</p>
                        <?php }?>
                        <?php if(isset($_GET['pwdsuccess'])){?>
                            <p class='bg-success text-white p-2'>Password Updated Successfully</p>
                        <?php }?>
                            <form method='post'>
                            <div class="form-group mb-2">
                                <label class='form-label'>New Password</label>
                                <input type="password" class="form-control" placeholder="Enter New Password" name='pwd'>
                            </div>
                            <button class='btn btn-primary btn-sm mt-2 fw-bold' name='savepwd'>Update</button>
                            </form>
                        </div>
                        
                    </div>
                 
                    
                   
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
