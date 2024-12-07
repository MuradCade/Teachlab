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



$teacherid = $_SESSION['userid'] ?? null;


// save marked attandence
if(isset($_POST['save'])){
    
    foreach($_POST['stdid'] as $key=>$data){
        $stdid = $data.'<br>';
        $stdfullname = $_POST['stdfullname'][$key];
        $coursename = $_POST['coursename'][$key];
        $attandencemarks = $_POST['totalattenddancemarks'][$key];
        $check = $_POST['check'][$key];
        $attended = 0;
        $absent = 0;

        // check absent and present
        $absentorpresent = $check == 'present' || $check=='excused'? $attended = 1 : $absent = 1;

            // if student is absent we don't give him the attandence marks
        $markscheck = $absentorpresent == $attended ? $attandencemarks : '0';
      
       $sql = "insert into markattendence(stdid,stdfullname,coursename,attendedmarks,present_column,absent_column,teacherid)
       values('$stdid','$stdfullname','$coursename','$markscheck','$attended','$absent','$teacherid')";
       $result = mysqli_query($connection,$sql);
       $redirect = true;
    }
       if($redirect){
        header('location:markattandence.php?attandencesaved');
        exit();
       }else{
        header('location:markattandence.php?attandencefailed');
        exit();
       }

    die();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - Mark Attedance</title>
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
                    <h1 class="h2" style='font-size:15px;'>Attendance / <span>Mark Students Attendance </span></h1>
                </div>
                
                <div class="row">
                    <div class="col-12 col-md-8 col-xl-5 mb-4">
                        <div class="card p-2 rounded" style='border:none !important; background-color:#f8f9fa !important;'>
                            <h4 class="card-title px-3 fw-bold mt-2 mb-0" style='font-size:17px !important;'>
                                Mark Students Attendance
                            </h4>
                            <div class="card-body">
                                <?php if (coursenames($_SESSION['userid'],$connection) == false) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Please add new course name before marking students attadence!</p>
                                <?php } ?>
                                <?php if (isset($_POST['attendancemarks']) &&$_POST['attendancemarks'] == '' ) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Empty Attendance Marks Field</p>
                                <?php } ?>
                                <?php if (isset($_GET['attandencesaved'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Attendance Marks Saved Successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['attandencefailed'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Failed To Save Attedance Marks,Please Try Again Later </p>
                                <?php } ?>
                                
                                <!-- emptymarkfield -->
                               
                                
                                <form method='post'>
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
                                        <p style='font-size:14px !important;' class='mt-2'><i class='bi bi-info-circle-fill text-danger me-1'></i><strong class='text-danger'>Important : </strong>To view student information, please provide the specific course name.</p>
                                           
                                    </div>

                                    <div class="form-group">
                                        <label class='form-label'>Attendance Marks</label>
                                        <input type='text' class='form-control' placeholder="Enter Attendance Marks" name='attendancemarks'/>
                                    </div>
                                    <!-- // if teacher tries to create student before creating course disable submit button -->

                                    <button class="btn btn-primary btn-sm mt-2 fw-bold <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled' : ''?>" name='submit'>Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- More Content Here -->
                 <div class="row">
                    <div class="col-lg-12 col-md-8 col-sm-12">
                          

                    <div class="card mt-2" style='border:none !important; background-color:#f8f9fa !important;'>
                    <table class='table table-hover table-bordered table-responsiv' id='myTable'>
                    <tr>
                        <td>#</td>
                        <td>Student ID</td>
                        <td>Student Name</td>
                        <td>Course Name</td>
                        <td>Attendance Marks</td>
                        <td>Present or Absent</td>
                        <!-- <td>Action</td> -->
                    </tr>

              
                    
                    <?php
                    if(isset($_POST['submit'])){
                        $coursename = $_POST['coursename'];
                        $totalmarks = $_POST['attendancemarks'];
                        if(empty($totalmarks)){
                            $totalmarks = '';
                           
                        }else{
                        $sql = "select * from students where coursename = '$coursename' and teacherid = '$teacherid'";
                        $result = mysqli_query($connection,$sql);
                        $rowid = 1;
                        
                        while($row = mysqli_fetch_assoc($result)){?>
                        <form method='post' action='markattandence.php'>
                        <tr>
                            <td><?php echo $rowid;?></td>
                            <td><input type='text' value='<?php echo $row['stdid'];?>' name='stdid[]' readonly/></td>
                            <td><input type='text' value='<?php echo $row['stdfullname'];?>' name='stdfullname[]' readonly/></td>
                            <td><input type='text' value='<?php echo $row['coursename'];?>' name='coursename[]' readonly/></td>
                            <td><input type='text' value='<?php echo $totalmarks;?>' name='totalattenddancemarks[]'/></td>
                            <td><select class='form-select' name='check[]'>
                                <option value='present'>Present</option>
                                <option value='absent'>Absent</option>
                                <option value='excused'>Excused</option>
                            </select></td>
                        </tr>
                       <?php }}?>
                       <td><button class='btn btn-primary btn-sm' name='save'>Save</button></td>
                        </form>
                        <?php }?>
                    </table>
                    </div>
                    </div>
                 </div>
                     
               
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