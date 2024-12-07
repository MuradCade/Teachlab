<?php
include('../../model/dbcon.php');
// include('include/checksubscriptionstatus.php');

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
//totalcourses
function totalcourse($connection ,$teacherid){
    $sql = "select * from course where teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    if($result){
       $row = mysqli_num_rows($result);
       

            return $row;
       
        
    } 
}
//total number of students
function totalstudents($connection ,$teacherid){
    $sql = "select * from students where teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    if($result){
       $row = mysqli_num_rows($result);
       

            return $row;
       
        
    } 
}
//totalassignment
function totalassignment($connection ,$teacherid){
    $sql = "select * from assignmentform where teacherid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    if($result){
       $row = mysqli_num_rows($result);
       

            return $row;
       
        
    } 
}

// display subscription status
function displaysubscriptionstatus($connection,$teacherid){
    $sql = "select subsatus from subscription where userid = '$teacherid'";
    $result = mysqli_query($connection,$sql);
    if($result){
        // $row = ;
        while($row = mysqli_fetch_assoc($result)){

        // echo $row['subsatus'];
        $checksubs = $row['subsatus'] =="active"?"<span class='text-success fw-bold'>Active</span>":($row['subsatus'] =="expire"?"<span class='text-danger fw-bold'>Expire</span>":"<span class='text-success fw-bold'>Life Time</span>");
        return $checksubs;
        }
      
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
                    <h1 class="h2">Dashboard</h1>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                            <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Total Courses</h5>
                                <p class="card-text"><?php echo totalcourse($connection,$teacherid);?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                        <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Total number of Students</h5>
                                <p class="card-text"><?php echo totalstudents($connection,$teacherid);?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-dark" style='background-color:#f8f9fa !important;'>
                        <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Total Assignment Form</h5>
                                <p class="card-text"><?php echo totalassignment($connection,$teacherid);?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                        <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Subscription Status</h5>
                                <p class="card-text" style='font-size:14px;'><?php echo displaysubscriptionstatus($connection,$teacherid);?></p>
                            </div>
                        </div>
                    </div>

                      <?php if(checksubscriptionstatus($connection,$teacherid,'subsatus') == 'active' && checksubscriptionstatus($connection,$teacherid,'subplan') == 'free'){?>
                        <div class='col-12 col-md-6 col-xl-6 mb-4'>
                         <div class='card p-2' style='background-color:#f8f9fa !important;'>
                            <p class='fw-medium' style='line-height:30px;'>Dear <strong><?php echo $_SESSION['fullname']?></strong>, your are on free plan. and your are allowed to create only 1 course, 1 assignment form and 1 quiz form.
                            <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>1- Maximum 1 Course Creation</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>2- Maximum 1 Assignment Form</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>3- Maximum 1 Quiz Form</li>
                           <div class='container'>

                        
<button class='btn btn-primary w-100' data-bs-toggle="modal" data-bs-target="#upgradeModal">Upgrade to Pro Plan</button>

<!-- Add this modal right after the button -->
<div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="upgradeModalLabel">Upgrade to Pro Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-4">
          <i class="bi bi-arrow-up-circle-fill text-primary" style="font-size: 3rem;"></i>
        </div>
        <p class="text-center">To upgrade to our Pro Plan, please follow these steps:</p>
        <div class="card bg-light p-3 mb-3">
          <ol class="mb-0">
            <li>Send $10 via Zaad service</li>
            <li>Payment Number: <strong>063-3558027</strong></li>
            <li>After payment, please contact support with your transaction details</li>
          </ol>
        </div>
        <p class="text-muted small text-center">Your account will be upgraded within 24 hours after payment verification.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="copyNumber()">Copy Number</button>
      </div>
    </div>
  </div>
</div>






                           </div>
                        </ul>
                                </p>
                            <?php }else if(checksubscriptionstatus($connection,$teacherid,'subplan') == 'pro' && checksubscriptionstatus($connection,$_SESSION['userid'],'subsatus') == 'expire'){?>
                           <div class='col-12 col-md-6 col-xl-6 mb-4'>
                           <div class='card p-2' style='background-color:#f8f9fa !important;'>
                            <p class=' mt-2'>Dear <strong><?php echo $_SESSION['fullname'];?></strong> ,   Your subscription is expired. Please renew your subscription to continue using TeachLab. </p>
                            <button class='btn btn-primary w-100' data-bs-toggle="modal" data-bs-target="#upgradeModal">Upgrade to Pro Plan</button>
                        </div>
                           </div>
                        </div>
                        </div>
                    <?php }else if(checksubscriptionstatus($connection,$teacherid,'subplan') == 'pro' && checksubscriptionstatus($connection,$_SESSION['userid'],'subsatus') == 'active'){?>
                        <div class='col-12 col-md-6 col-xl-6 mb-4'>
                           <div class='card p-2' style='background-color:#f8f9fa !important;'>
                            <p class=' mt-2'>Dear <strong><?php echo $_SESSION['fullname'];?></strong>  Thank you for purchasing the lifetime plan! You now  access to all features of TeachLab. Enjoy your experience!</p>
                            <p class='text-muted small'>Note: Your subscription will expire after <?php echo checksubscriptionstatus($connection,$teacherid,'subamount').' Days '?>.</p>
                            </div>
                           </div>
                        </div>
                    <?php }?>
                </div>
              <!-- Add this modal right after the button -->
<div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="upgradeModalLabel">Upgrade to Pro Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-4">
          <i class="bi bi-arrow-up-circle-fill text-primary" style="font-size: 3rem;"></i>
        </div>
        <p class="text-center">To upgrade to our Pro Plan, please follow these steps:</p>
        <div class="card bg-light p-3 mb-3">
          <ol class="mb-0">
            <li>Send $10 via Zaad service</li>
            <li>Payment Number: <strong>063-3558027</strong></li>
            <li>After payment, please contact support with your transaction details</li>
          </ol>
        </div>
        <p class="text-muted small text-center">Your account will be upgraded within 24 hours after payment verification.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="copyNumber()">Copy Number</button>
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

        // Add function to copy payment number
    function copyNumber() {
        navigator.clipboard.writeText('063-3558027')
            .then(() => {
                alert('Payment number copied to clipboard!');
            })
            .catch(err => {
                console.error('Failed to copy number:', err);
            });
    }
    </script>
</body>
</html>
