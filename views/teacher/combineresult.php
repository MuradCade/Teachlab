<?php
include('../../model/dbcon.php');
include('slices/studentcreationvalidation.php');
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




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - Combine Result</title>
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
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-00CYL9RWEC"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-00CYL9RWEC');
</script>
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
                <h1 class="h2" style='font-size:15px;'>Report / <span>Combine Result</span></h1>
                </div>

                <div class="row">
                    <div class="col-12 col-md-8 col-xl-8 mb-4">
                        <div class="card p-2 rounded" style='border:none !important; background-color:#f8f9fa !important;'>
                            <h4 class="card-title px-3 fw-bold mt-2 mb-0" style='font-size:17px !important;'>
                                Combine Result
                            </h4>
                            <div class="card-body">
                            <?php if (coursenames($_SESSION['userid'],$connection) == false) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>New course should be added in order to display comibined result of assignment , attandence and quiz</p>
                                <?php } ?>
                            <?php if (isset($_GET['emptdb'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>There is no data to be converted , please choose proper coursename.</p>
                                <?php } ?>
                                <form method='post'>
                                <div class="form-group">
                                    <label class="form-label">Course Name</label>
                                    <select class='form-select' name='coursename'>
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
                                    <p style='font-size:14px;font-weight:500;' class='mt-2'>It will generate combined data from attendance and assignment, that you can easily export it as excel file .</p>
                                    <button class="btn btn-primary btn-sm mt-2 fw-bold <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled' : ''?>" name='submit'>Submit</button>
                                        </form>
                                </div>
                            </div>
                          
                        </div>
                    </div>

                </div>
                <?php if(isset($_POST['submit'])){
                    $coursename = $_POST['coursename'];
                   
                    // echo $coursename;
                
                    ?>
                              <div class="row mt-2">
                                <div class="col-12 col-md-12 col-xl-10 mb-4">
                                <div class=''>
                                <div>
                                <a href="slices/exportexcelcombined_data.slice.php?coursename=<?php echo $coursename;?>" class="btn btn-secondary">Convert To Excel</a>
                                
                            </div>
                            <div>
                                <input  style="width:400px !important;" type='text' class='form-control mt-2 mb-1' id='myInput' placeholder="Search by id or name" onkeyup="searchTable()"  <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled':''?> <?php echo isset($_POST['coursename']) ? '' : 'readonly'?>/>                       
                            </div>
                            </div>
                                   
                                <div class="card mt-2 table-responsive" style='border:none !important; background-color:#f8f9fa !important;'>
                                    <table class="table table-bordered table-hover" id="myTable">
                                        <tr>
                                            <td>#</td>
                                            <td>Studentid</td>
                                            <td>Studentname</td>
                                            <td>Coursename</td>
                                            <td>Total_Attandence_Marks</td>
                                            <td>Total_Assignment_Marks</td>
                                            <td>Total_Quiz_Marks</td>
                                        </tr>


                                        <?php 
                                  
                                        //step1 get the formid of the quizform
                                        $sqlmain = "select * from quizform where teacherid = '$teacherid' and coursename ='$coursename'";
                                        $resultmain = mysqli_query($connection,$sqlmain);
                                        $rows = mysqli_fetch_assoc($resultmain);
                                        $formid =  $rows['quizformid']??'';
                                        // echo "<pre>";
                                        // var_dump($formid);
                                        // die();
                                        // echo "</pre>";
                                        // die();
                                            $sql2 = "SELECT 
                                                        markattendence.stdid, 
                                                        markattendence.stdfullname, 
                                                        markattendence.coursename, 
                                                        SUM(markattendence.attendedmarks) AS attandancemarks, 
                                                        COALESCE(assignment_totals.assignmentmarks, 0) AS assignmentmarks,
                                                        COALESCE(quiz_totals.quizmarks, 0) AS quizmarks
                                                     FROM 
                                                        markattendence 
                                                     LEFT JOIN 
                                                        (SELECT stdid,formid, SUM(marks) AS assignmentmarks FROM assignmententries GROUP BY stdid) AS assignment_totals 
                                                     ON 
                                                        markattendence.stdid = assignment_totals.stdid 
                                                     LEFT JOIN 
                                                        (SELECT stdid, quizmarks,quizformid  FROM studentquiz where  quizformid = '$formid' GROUP BY stdid) AS quiz_totals 
                                                     ON 
                                                        markattendence.stdid = quiz_totals.stdid 
                                                     WHERE 
                                                        teacherid = '$teacherid' and
                                                       coursename = '$coursename'  
                                                       
                                                       
                                                     GROUP BY 
                                                        assignment_totals.formid, 
                                                        markattendence.stdfullname,markattendence.coursename ORDER BY markattendence.stdid;";
                                                        

                                            $result2 = mysqli_query($connection,$sql2);
                                            $rowsid =1;
                                            if(mysqli_num_rows($result2) == 0){
                                                echo "<span style='font-size:15px;'>There’s currently no data to show.</span>";
                                            }else{
                                                while($rows = mysqli_fetch_assoc($result2)){?> 
                                            
                                                    <tr>
                                                        <td><?php echo $rowsid?></td>
                                                        <td><?php echo $rows['stdid']?></td>
                                                        <td><?php echo $rows['stdfullname']?></td>
                                                        <td><?php echo $rows['coursename']?></td>
                                                        <td><?php echo $rows['attandancemarks']?></td>
                                                        <td><?php echo $rows['assignmentmarks']?></td>
                                                        <td><?php echo $rows['quizmarks']?></td>
                                                    </tr>
                                                
                                                
                                                
                                                <?php $rowsid++;}} ?>
                                            
                                           
                                    </table>
                               </div>
                                </div>
                              </div>
                            <?php } ?>
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