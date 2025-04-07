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
        $absentorpresent = $check == 'present'? $attended = 1 : $absent = 1;

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


if(isset($_GET['delid'])){
    $stdid = $_GET['delid'];
    $sql2= "delete from markattendence where stdid = '$stdid'";
    $result2 = mysqli_query($connection,$sql2);
    if($result2){
        header('location:viewattadencemarks.php?delsuccess');
        exit();
    }else{
        header('location:viewattadencemarks.php?delfailed');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - View Attendance Information</title>
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
                    <h1 class="h2" style='font-size:15px;'>Attendance / <span> View Attendance Information</span></h1>
                </div>
                
                <div class="row">
                    <div class="col-8 col-md-10 col-xl-5 col-sm-12 mb-4">
                        <div class="card p-2 rounded" style='border:none !important; background-color:#f8f9fa !important;'>
                            <h4 class="card-title px-3 fw-bold mt-2 mb-0" style='font-size:17px !important;'>
                            View Attendance Information
                            </h4>
                            <div class="card-body">
                                <?php if (coursenames($_SESSION['userid'],$connection) == false) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>New coursename should be added in order to see specific course attandance</p>
                                <?php } ?>
                                <?php if (isset($_GET['attendanceupdatedsuccessfully'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Attendance is updated successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['delsuccess'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Attendance data deleted successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['delfailed'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Attendance data failed to delete</p>
                                <?php } ?>
                                
                                
                                
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

                                  
                                    <!-- // if teacher tries to create student before creating course disable submit button -->

                                    <button class="btn btn-primary btn-sm mt-2 fw-bold <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled' : ''?>" name='submit'>Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- More Content Here -->
                 <div class="row">
                    <div class="col-lg-9 col-md-12 col-sm-12">
                          

                    <div class="card mt-2" style='border:none !important; background-color:#f8f9fa !important;'>
                        <div class='d-lg-flex item-center justify-content-start gap-2 d-sm-block '>
                        <input  style="width:400px !important;" type='text' class='form-control mt-2 mb-sm-1' id='myInput' placeholder="Search by id , studentname" onkeyup="searchTable()"  <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled':''?> <?php echo isset($_POST['coursename']) ? '' : 'readonly'?>/>                       
                    <?php 
                        if(isset($_POST['submit'])){
                            $coursename = $_POST['coursename'];?>
                                        <div class="mt-2">
                                        <a href="slices/exportattendance.slice.php?coursename=<?=  $coursename?>" class="btn btn-secondary btn-sm fw-bold mt-1">Convert To Excel</a>

                                        </div>
                        <?php }else{?>
                                <div class='mb-2 mt-2'>
                                <a href="slices/exportattendance.slice.php" class="btn btn-secondary btn-sm fw-bold  disabled" >Convert To Excel</a>

                                </div>
                        <?php }
                    ?>
                    </div>
                    <table class='table table-hover table-bordered table-responsiv mt-2' id='myTable'>
                    <tr>
                        <td>#</td>
                        <td>Student ID</td>
                        <td>Student Name</td>
                        <td>Course Name</td>
                        <td>Total Attandence Marks</td>
                        <td>Action</td>
                        <!-- <td>Action</td> -->
                    </tr>

              
                    
                    <?php
                    if(isset($_POST['submit'])){
                        $coursename = $_POST['coursename'];
                        // $totalmarks = $_POST['attendancemarks'];
                       
                        $sql = "select stdid,stdfullname,coursename,sum(attendedmarks) as totalmarks from markattendence where coursename = '$coursename' and teacherid = '$teacherid' group by stdid";
                        $result = mysqli_query($connection,$sql);
                        $rowid = 1;
                        if(mysqli_num_rows($result) == 0){
                            echo "<span style='font-size:15px;' class='mb-1 mt-2'>Sorry , There’s currently no data to show.</span>";
                        }else{
                        while($row = mysqli_fetch_assoc($result)){?>
                        <tr>
                            <td><?php echo $rowid;?></td>
                            <td><a href="viewattadencemarks.php?display=<?php echo $row['stdid'];?>"><?php echo $row['stdid'];?></a></td>
                            <td> <?php echo $row['stdfullname'];?></td>
                            <td><?php echo $row['coursename'];?></td>
                            <td><?php echo $row['totalmarks'];?></td>
                            <td><a href="viewattadencemarks.php?delid=<?php echo $row['stdid']?>" class='btn btn-danger btn-sm'>Delete</a></td>
                           
                        </tr>
                       <?php $rowid++;}}}?>
                    </table>
                    </div>
                    </div>
                 </div>
                     
                                   <!-- student update form -->
                                   <?php if(isset($_GET['display'])){
                                            $teacherid = $_SESSION['userid'];
                                                //get the student id to update its data
                                                $studentid = $_GET['display'];
                                                $sql2 = "select id, stdid,stdfullname,coursename,attendedmarks,date,present_column,absent_column,SUM(present_column) as totalpresent,SUM(absent_column) as totalabsent, sum(attendedmarks) as totalmarks from markattendence where stdid = '$studentid' and teacherid = '$teacherid' group by id";
                                                $result2 = mysqli_query($connection,$sql2);

                                                ?>
                    <div class="modal p-5" style='display:block !important; background-color:rgb(0, 0, 0,.86); '>
                     
                          <div class="card p-2 mt-5 " style='overflow-y: auto !important;'>
                            <h4 class="card-title">
                                
                            </h4>
                            <div class="card-body">
                                <table class='table table-bordered table-responsive table-hover '>
                                    <tr>
                                        <td>#</td>
                                        <td>Student_ID</td>
                                        <td>Student_Name</td>
                                        <td>Course_Name</td>
                                        <td>Attendance Marks</td>
                                        <td>present</td>
                                        <td>Absent</td>
                                        <td>Date</td>
                                        <td>Action</td>
                                    </tr>
                                    <?php
                                    $rownumber = 1;  
                                while($rows = mysqli_fetch_assoc($result2)){
                               
                                    
                                    ?>
                                    <form method='post' action="slices/updateattendancedata.slices.php?stdid=<?php  echo $rows['stdid']?>&columnid=<?php echo $rows['id']?>">
                                        <tr>
                                            <td><?php echo $rownumber;?></td>
                                            <td><?php echo $rows['stdid']?></td>
                                            <td><?php echo $rows['stdfullname']?></td>
                                            <td><?php echo $rows['coursename']?></td>
                                            <td><input type='text' value="<?php echo $rows['attendedmarks']?>" name='attendancemarks'/></td>
                                            <td><input type='text' value="<?php echo $rows['present_column'] == '1'? 'yes':'no'?>" name='present'/></td>
                                            <td><input type='text' value="<?php echo $rows['absent_column'] == '1'? 'yes':'no'?>" name='absent'/></td>
                                            <td><?php echo date('M-j-Y ', strtotime($rows['date']));?></td>
                                            <td><button class='btn btn-primary btn-sm fw-bold' name='updatesinglestudentattendance'>Update</button></td>
                                        </tr>
                                    </form>
                                    <?php  $rownumber++;} 
                                    // display total present days, total absent days , total attendance marks.
                                    $sql3 ="select  SUM(present_column) as totalpresent,SUM(absent_column) as totalabsent, sum(attendedmarks) as totalmarks from markattendence where stdid = '$studentid' group by stdid";
                                    $result3 = mysqli_query($connection,$sql3);
                                    $datarow = mysqli_fetch_assoc($result3); 
                                    ?>
                                    <td style="font-size:14px; font-weight:600;">Total Present Days : </td>
                                    <td><p  class='fw-bold'><?php echo $datarow['totalpresent'].' days';?></p></td>
                                    <td  style="font-size:14px;font-weight:600;">Total Absent Days : </td>
                                    <td><p  class='fw-bold'><?php echo $datarow['totalabsent'].' days';?></p></td>
                                    <td  style="font-size:14px;font-weight:600;">Total Attendance Marks : </td>
                                    <td><p  class='fw-bold'><?php echo $datarow['totalmarks'].' marks';?></p></td>
                                </table>
                                <a href='viewattadencemarks.php' class='btn btn-secondary btn-sm fw-bold'>Close</a>
                            </div>
                          </div>
                          
                        </div>
                            <?php } ?>
                  <!-- end of the stdudent update model -->
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

           //search table by id
           function searchTable() {
    var input, filter, found, table, tr, td, i, j;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                found = true;
            }
        }
        if (found) {
            tr[i].style.display = "";
            found = false;
        } else {
            tr[i].style.display = "none";
        }
    }
}
    
        
         
   
    
    </script>
</body>

</html>