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

//save updated student information
if(isset($_POST['update'])){
    $studentid = $_POST['studentid'];
    $studentname = $_POST['studentname'];
    $coursename = $_POST['coursename'];

    if(empty($studentid)){
        header('location:viewstudent.php?emptystudentid');
        exit();
    }else if(empty($studentname)){
        header('location:viewstudent.php?emptystudentname');
        exit();
    }else if(empty($coursename)){
        header('location:viewstudent.php?emptycoursename');
        exit();
    }else{
        $sql = "update students set stdfullname = '$studentname',coursename = '$coursename' where stdid = '$studentid'";
        $result = mysqli_query($connection,$sql);
        if($result){
            header('location:viewstudent.php?updatesuccess');
            exit();
        }else{
            header('location:viewstudent.php?updatequeryfailed');
            exit();
        }
    }
}


//delete specific student
if(isset($_GET['delid'])){
    $studentdelid = $_GET['delid'];
    $sql = "delete from students where stdid = '$studentdelid'";
    $result = mysqli_query($connection,$sql);
    if($result){
        header('location:viewstudent.php?delsuccess');
            exit();
    }else{
        header('location:viewstudent.php?delqueryfailed');
            exit();
    }
}
 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - View Students</title>
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
                    <h1 class="h2" style='font-size:15px;'>Students / <span> View Student Informations</span></h1>
                </div>
                
                <div class="row">
                    <div class="col-12 col-md-8 col-xl-5 mb-4">
                        <div class="card p-2 rounded" style='border:none !important; background-color:#f8f9fa !important;'>
                            <h4 class="card-title px-3 fw-bold mt-2 mb-0" style='font-size:17px !important;'>
                                View Student Informations
                            </h4>
                            <div class="card-body">
                                <?php if (isset($_GET['emptystudentid'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>
                                    The Studentid column is empty in the Excel file. Please ensure all columns have the correct data.
                                    </p>
                                <?php } ?>
                                <?php if (isset($_GET['emptystudentname'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>
                                    The Studentname column is empty in the Excel file. Please ensure all columns have the correct data.
                                    </p>
                                <?php } ?>
                                <?php if (isset($_GET['emptycoursename'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>
                                    The Coursename column is empty in the Excel file. Please ensure all columns have the correct data.
                                    </p>
                                <?php } ?>
                                <?php if (isset($_GET['coursenamenotexist'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>
                                    Sorry , upload excel file contains coursename that doesn't exist , please make make sure the coursename exist, before uploading the file
                                    </p>
                                <?php } ?>
                                <?php if (isset($_GET['updatesuccess'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; ' id='t1'>Student information updated successfully </p>
                                <?php } ?>
                                <?php if (coursenames($_SESSION['userid'],$connection) == false) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Please add new course name before adding new student!</p>
                                <?php } ?>
                                <?php if (isset($_GET['delsuccess'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Student information deleted successfully </p>
                                <?php } ?>
                                <?php if (isset($_GET['filenotsupported'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Sorry , uploaded file not supported, please upload only excel sheet files</p>
                                <?php } ?>
                                <?php if (isset($_GET['savedsuccessfully'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>File imported successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['dataexist'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:14px !important; '>
                                    The uploaded file contains data that already exists in the database, Please review the content before proceeding with the upload.
                                    </p>
                                <?php } ?>
                                <?php if (isset($_GET['filedtoimportfile'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Sorry,  import process failed.</p>
                                <?php } ?>
                                <?php if (isset($_GET['chosefile'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Please choose file to be imported.</p>
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
                    <div class="col-lg-8 col-md-8 col-sm-12">
                            <div style='display:flex !important; align-items:center; justify-content:space-evenly'>
                                <div>
                                <button style='flex-wrap:wrap !important;'class="btn btn-primary btn-sm fw-bold <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled':''?>" data-bs-toggle="modal" data-bs-target="#importasexcel">Upload Excel File</button>
                                <a href="slices/exportexcel.php?coursename=<?php echo isset($_POST['coursename']) ? $_POST['coursename'] :'';?>" class="btn btn-secondary btn-sm fw-bold <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled':''?> <?php echo isset($_POST['coursename']) ? '' : 'disabled'?>">Covert To Excel</a>
                                
                            </div>
                            <div>

                                <input  style="width:400px !important;" type='text' class='form-control mt-2 mb-1' id='myInput' placeholder="Search by id or name" onkeyup="searchTable()"  <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled':''?> <?php echo isset($_POST['coursename']) ? '' : 'readonly'?>/>                       
                            </div>
                            </div>

                    <div class="card mt-2" style='border:none !important; background-color:#f8f9fa !important;'>
                    <table class='table table-hover table-bordered table-responsive' id='myTable'>
                    <tr>
                        <td>#</td>
                        <td>Student ID</td>
                        <td>Student Name</td>
                        <td>Course Name</td>
                        <td>Action</td>
                    </tr>

                    <?php
                    
                    
                    if (isset($teacherid) && isset($_POST['submit'])) {
                                        $coursename = $_POST['coursename'];
                                        $rownumber = 1;
                                        $sql = "select * from students where coursename = '$coursename' and teacherid = '$teacherid'";
                                        $result = mysqli_query($connection, $sql);
                                        if (mysqli_num_rows($result) == 0) {
                                            echo "<span style='font-size:15px;'>There’s currently no data to show, <br>
                                                Please Assign students to this coursename.</span>";
                                        } else {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                                <tr>
                                                    <td><?php echo $rownumber; ?></td>
                                                    <td><?php echo $row['stdid']; ?></td>
                                                    <td><?php echo $row['stdfullname'] ?></td>
                                                    <td><?php echo $row['coursename'] ?></td>
                                                    <td><a href="viewstudent.php?updateid=<?php echo $row['stdid'] ?>" class='btn btn-primary btn-sm fw-bold '>Update</a>&numsp;<a href="viewstudent.php?delid=<?php echo $row['stdid']?>" class='btn btn-danger btn-sm fw-bold '>Delete</a></td>
                                                </tr>
                                    <?php $rownumber++;
                                            }
                                        }
                                    } else {
                                        echo 'There’s currently no data to show.';
                                    }  ?>
                    </table>
                    </div>
                    </div>
                 </div>
                                                  <!-- import as excel model -->
                      <div class="modal fade" id="importasexcel" tabindex="-1" aria-labelledby="importasexcel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fw-medium" id="exampleModalLabel" style='font-size:16px !important;'>Import As Excel Sheet</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action='slices/importexcel.php' enctype="multipart/form-data">
                                           <div class="form-group">
                                            <label class='form-label'>Select File</label>
                                            <input type="file" name='excelfile' class='form-control'/>
                                            <p style='font-size:15px !important; font-weight:500 !important;' class='mt-2'><i class='bi bi-info-circle-fill text-danger me-1'></i><strong class='text-danger'>Important : </strong>
                                            You can only upload Excel files, Other file types are not allowed.
                                                Please ensure your file is in Excel format.
                                            </p>
                                            <button class="btn btn-primary btn-sm mt-2 fw-bold" name='save'>submit</button>

                                           </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                            </div>
                            </div>
                        </div>
                        </div>

                  <!-- end of the import file model -->

                                             <!-- student update form -->
                                              <?php if(isset($_GET['updateid'])){
                                                //get the student id to update its data
                                                $studentid = $_GET['updateid'];
                                                $sql = "select * from students where stdid = '$studentid'";
                                                $result = mysqli_query($connection,$sql);

                                                ?>
                                             <div class="modal" style='display:block !important; background-color:rgb(0, 0, 0,.86); '>
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fw-medium" id="exampleModalLabel" style='font-size:16px !important;'>Update Student Information</h1>
                                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                            </div>
                            <div class="modal-body">
                            <form method='post' action='viewstudent.php'>
                                <?php  while($row = mysqli_fetch_assoc($result)){?>
                                <div class="form-group mb-3">
                                    <label class='form-label'>Student ID</label>
                                    <input type="text" name='studentid' class='form-control' value="<?php echo $row['stdid'];?>" readonly/>
                                </div>
                                <div class="form-group mb-3">
                                    <label class='form-label'>Student Name</label>
                                    <input type="text" name='studentname' class='form-control' placeholder="Enter Studentname"
                                    value="<?php echo $row['stdfullname'];?>" />
                                </div>
                                <div class="form-group mb-2">
                                    <label class='form-label'>Course Name</label>
                                    <select class="form-select" name='coursename'>
                                        
                                        <?php  
                                        // if teacher tries to create student before creating course show him none in course selection
                                      
                                            
                                            foreach (coursenames($_SESSION['userid'],$connection) as $data) {?>
                                                <option value='<?php echo $data['coursename']?>'><?php echo $data['coursename']?></option>
                                                <?php }
                                           
                                           
                                           
                                           ?>


                                        </select>
                                        </div>
                                        <!-- // if teacher tries to create student before creating course disable submit button -->

                                        <button class="btn btn-primary btn-sm mt-2 fw-bold" name='update'>Update</button>
                                        <?php  } ?>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <a href="viewstudent.php" type="button" class="btn btn-secondary" >Close</a>
                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                            </div>
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