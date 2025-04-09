<?php
include('./../../model/dbcon.php');
include('slices/studentcreationvalidation.php');
include('slices/deleteassignmentfiles.php');
include('slices/deleteallfiles.php');
include_once('slices/display_single_assignmentformdata.php');
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

///check if session exist , if it exist prevent user from seeing login page
if (!isset($_SESSION['userid'])) {
    header('location:../login.php');
    exit();
}

$teacherid = $_SESSION['userid'];



//update assignment form
if(isset($_POST['update'])){
    $formid = mysqli_real_escape_string($connection ,$_POST['formid']);
    $formtitle = mysqli_real_escape_string($connection ,$_POST['formtitle']);
    $formdesc = mysqli_real_escape_string($connection ,$_POST['formdesc']);
    $formstatus = mysqli_real_escape_string($connection ,$_POST['formstatus']);
    $marks = mysqli_real_escape_string($connection ,$_POST['marks']);
    if(empty($formtitle) || empty($formdesc)){
        header('location:viewassignmentform.php?emptyfields&updateformid='.$formid);
        exit();
    }else{
        $sql = "update assignmentform set title='$formtitle', description = '$formdesc', marks = '$marks',
        formstatus = '$formstatus' where formid = '$formid'";
        $result = mysqli_query($connection,$sql);
        if($result){
            $sql2 = "update assignmententries set marks='$marks' where formid = '$formid'";
            $result2 = mysqli_query($connection,$sql2);
            if($result2){
                header('location:viewassignmentform.php?formupdatedsuccessfully');
                exit();

            }
        }else{
            header('location:viewassignmentform.php?formfailedtoupdate');
            exit();
        }
    }

}



//update assigment entreies



if(isset($_POST['updatentries'])){
if(isset($_GET['stdid'])){
    $stdid = $_GET['stdid'];
    $assignmentmarks = $_POST['assignmentmarks'];
    $sqlquery = "update assignmententries set marks ='$assignmentmarks' where stdid = '$stdid'";
    $resultquery = mysqli_query($connection,$sqlquery);
    if($resultquery){
        header('location:viewassignmentform.php?entriesupdated');
        exit();
    }else{
        header('location:viewassignmentform.php?failedquery');
        exit();
    }
}


}
// delete assignment entries
if(isset($_GET['deleteid'])){
    $delid = $_GET['deleteid'];
    //fetch all document related to this student before deleting it from the database
    $sql2 = "select * from assignmententries where stdid = '$delid'";
    $result2 = mysqli_query($connection,$sql2);
    
    if($result2){
        while($row = mysqli_fetch_assoc($result2)){
            $word_document =  'uploads/'.$row['uploadedfile'];
            $pdf_document =  'uploads/'.$row['pdf_file'];

            if(file_exists($word_document) && file_exists($pdf_document)){

                //delete the files that submitted by this specific student
            deleteassignmentdocuement($word_document,$pdf_document);
            }
        }
        //delete the student from the database
        $sqlquery2 = "delete from assignmententries  where stdid = '$delid'";
        $resultquery2 = mysqli_query($connection,$sqlquery2);
        if($sqlquery2){
            
            header('location:viewassignmentform.php?entriesdeleted');
            exit();
        }else{
         header('location:viewassignmentform.php?failedquery');
        exit();
        }
    }else{
        header('location:viewassignmentform.php?failedquery');
        exit();
    }
}


//delete assignment form

if(isset($_GET['delassigmentform'])){

    $formid = $_GET['delassigmentform'];
    // 1- find all document in this assignment form , fetch all document related to this student before deleting it from the database
        $sql = "select * from assignmententries where formid = '$formid'";
        $result = mysqli_query($connection,$sql);
   
    if($result){
        while($row = mysqli_fetch_assoc($result)){
         
            
                //1.1- delete all the files relaed to this assignment form
                deleteallfiles('uploads/'.$row['uploadedfile'],'uploads/'.$row['pdf_file']);
            
        }

    //2- delete all student that submitted thier asssingment inside that form assignment
    $sql2 = "delete  from assignmententries where formid = '$formid'";
    $result2 = mysqli_query($connection,$sql2);
    if($result2){
        //3- delete the assignment form itself
        $formsql = "delete from assignmentform where formid = '$formid'";
        $formresult = mysqli_query($connection,$formsql);
    
        if($formresult){
            header('location:viewassignmentform.php?assignmentformsuccess');
            exit();
        }else{
              header('location:viewassignmentform.php?assignmentformdeletefailed');
            exit();
        }
    }
   
        
    }else{
        // header('location:viewassignmentform.php?assignmentformdeletefailed');
        // exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - View Assignment Form</title>
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
                    <h1 class="h2" style='font-size:15px;'>Assignment / <span>View Assignment Form</span></h1>
                </div>

                <div class="row">
                    <div class="col-12 col-md-12 col-xl-12 mb-4" id='assignmentformtable'>
                        <div class="card table-responsive p-2 rounded" style='border:none !important; background-color:#f8f9fa !important;'>
                            <h4 class="card-title px-3 fw-bold mt-2 mb-0" style='font-size:17px !important;'>
                                View Assignment Form
                            </h4>
                            <div class="card-body">
                                <?php if (isset($_GET['formfailedtoupdate'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Failed to update assignment form</p>
                                <?php } ?>
                                <?php if (isset($_GET['assignmentformsuccess'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Assignment form deleted successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['assignmentformdeletefailed'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Assignment form failed to delete</p>
                                <?php } ?>
                                <?php if (isset($_GET['failedquery'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Sorry , Failed To Perform Action , Please Try Again Later.</p>
                                <?php } ?>
                                <?php if (isset($_GET['entriesupdated'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Assignment Form Entries Updated Successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['entriesdeleted'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Assignment Form Entries Deleted Successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['formupdatedsuccessfully'])) { ?>
                                    <p class='bg-success p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Assignment form updated successfully</p>
                                <?php } ?>
                            
                               
                                <table class='table table-bordered table-hover table-responsive'>
                                    <tr>
                                        <td>#</td>
                                        <td>Form_Title</td>
                                        <td>Course_Name</td>
                                        <td>Upload_File_Type</td>
                                        <td>Assignment Marks</td>
                                        <td>Form_Status</td>
                                        <td>Created_Date</td>
                                        <td>Action</td>
                                    </tr>
                                    
                                    <?php 
                                    $sql = "select * from assignmentform where teacherid = '$teacherid' order by  date ";
                                    $result = mysqli_query($connection,$sql);
                                    if(mysqli_num_rows($result) == 0){
                                        echo "<span style='font-size:15px;'>There’s currently no data to show, <br>
                                        Please create new assignment form.</span>";
                                    }else{
                                        $rowid = 1;
                                        while($row = mysqli_fetch_assoc($result)){?>

                                        <tr style='font-size:15px;'>
                                            <td><?php echo $rowid;?></td>
                                            <td><?php echo $row['title'];?></td>
                                            <td><?php echo $row['coursename'];?></td>
                                            <td><?php echo $row['uploadedfilename'];?></td>
                                            <td><?php echo $row['marks'];?></td>
                                            <td><p class="<?php echo $row['formstatus'] == 'active' ? 'text-success fw-bold':'text-danger fw-bold'?>"><?php echo $row['formstatus'];?></p></td>
                                            <td><?php echo date('M-j-Y ', strtotime($row['date']));?></td>
                                            <td class=' sm-d-flex'>
                                            <a href="viewassignmentform.php?details=<?php echo $row['formid']?>" class="btn btn-secondary d-inline-block btn-sm fw-bold mb-2">Details</a>    
                                            <a href="viewassignmentform.php?updateformid=<?php echo base64_encode($row['formid'])?>" class="btn btn-primary btn-sm mb-1 fw-bold mb-2">Update</a>&numsp;<a href="viewassignmentform.php?entries=<?php echo $row['formid'];?>" class='btn btn-secondary btn-sm fw-bold mb-2'>Entries</a>&numsp;<a href="../clients/assignmentform.clients.php?formid=<?php echo base64_encode($row['formid'])?>"  target='a_blank' class='btn btn-info btn-sm text-black fw-bold mb-2'>View Assignment</a>&numsp;<a href="../clients/assignmentform.clients.php?formid=<?php echo base64_encode($row['formid'])?>" onclick="copyToClipboard(event,this)" class='btn btn-warning btn-sm fw-bold text-black mb-2'>Share Assignment </a>&numsp;<a href="viewassignmentform.php?delassigmentform=<?php echo $row['formid'];?>" class='btn btn-danger btn-sm fw-bold'>Delete</a> </td>
                                        </tr>

                                        
                                            <!-- quiz model that displays complete information starts here-->
                                            <?php if(isset($_GET['details'])){?>    
                                        <!-- Modal -->
                                        <div class="modal " id="quizformdetails"  style="display:block;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Assignment Form Details</h1>
                                            </div>
                                            <div class="modal-body">
                                                <?php $singlequizinformation =  displaysingleassignmentformdata($connection,$_GET['details']);

                                                    foreach ($singlequizinformation as $singlequizformdata) {?>
                                                        
                                                        <p>Assignment Form Title : <?= $singlequizformdata['title']?></p>
                                                        <p>Assignment Form  Description : <?= $singlequizformdata['description']?></p>
                                                        <p>Coursename : <?= $singlequizformdata['coursename']?></p>
                                                        <p>Created_Date : <?= date('M-j-Y ', strtotime($singlequizformdata['date']))?></p>
                                                        <p>Assignment Form Status  : <span class="<?= $singlequizformdata['formstatus'] == 'active'?'text-success fw-bold':'text-danger fw-bold'?>"><?= $singlequizformdata['formstatus']?></span></p>
                                                        <p>Assignment Form Marks : <?= $singlequizformdata['marks']?></p>

                                                  <?php  }
                                                
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <a type="button" class="btn btn-secondary btn-sm fw-bold" href='viewassignmentform.php'>Close</a>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                        <?php }?>
                                       <?php $rowid++;}
                                    }
                                    ?>

                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <?php if(isset($_GET['updateformid'])){
                    $formid = base64_decode($_GET['updateformid']);
                    $sql = "select * from assignmentform where formid = '$formid'";
                    $result = mysqli_query($connection,$sql);
                    while($rows = mysqli_fetch_assoc($result)){
                    ?>
                    <style>
                        #assignmentformtable{
                            display: none !important;
                        }
                    </style>
                <!-- update assignment form -->
                       <div class="row">
                        <div class="col-12 col-md-12 col-xl-12 mb-4">
                        <div class="card p-3 " style='border:none !important; background-color:#f8f9fa !important;'>
                                    <h4 class="card-title mt-1 mb-1 fw-bold" style='font-size:17px;margin-left:18px;'>
                                    Update Assignment Form
                                    </h4>
                                    <?php if (isset($_GET['emptyfields'])) { ?>
                                    <p class='bg-danger p-1 text-white fw-bold px-2' style='font-size:15px !important; '>Empty Fields</p>
                                      <?php } ?>  
                                    <div class="card-body">
                                    <form method='post'>
                                    <div class="form-group mb-3">
                                        <label class='form-label'>Form Title</label>
                                        <input type="text" name='formtitle' class='form-control' placeholder="Enter Form Title" value="<?php echo $rows['title']?>" />
                                        <input name='formid' value="<?php echo $rows['formid']?>" hidden/>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class='form-label'>Form Description</label>
                                        <textarea name="formdesc" rows="10" col="5" class='form-control' placeholder="Enter Form Description" ><?php echo $rows['description']?></textarea>
                                    </div>
                                        <div class="form-group mt-3">
                                            <label class='form-label'>Form Status</label>
                                         
                                            <select class="form-select" name="formstatus">
                                        <option value="active" <?php  echo $rows['formstatus'] == 'active' ? 'selected' : '';?>>Active</option>
                                        <option value="disable" <?php  echo $rows['formstatus'] == 'disable' ? 'selected' : '';?>>Disable</option>
                                    </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label class="form-label">Assignment Marks</label>
                                            <input type="text" class="form-control" name='marks' placeholder="Enter Assignment Makrs" value="<?php echo $rows['marks']?>" >
                                        </div>
                                    </div>
                                    <div class='mx-3  '>
                                    <button class="btn btn-primary btn-sm mt-2 fw-bold  <?php echo coursenames($_SESSION['userid'],$connection) == false ? 'disabled' : ''?>" name='update'>Update</button>
                                        <a href="viewassignmentform.php" class="btn btn-secondary btn-sm fw-bold d-iniline-block mt-2">Cancel</a>
                                    </div>
                                </form>
                                    </div>
                                    </div>
                        </div>
                       </div>
                       <?php }} ?>

                <!-- More Content Here -->


                
                 <!-- display assignment entries -->
                 <?php if(isset($_GET['entries'])){
                                                //get the student id to update its data
                                                $formid = $_GET['entries'];
                                                $sql2 = "select * from assignmententries where formid = '$formid'";
                                                $result2 = mysqli_query($connection,$sql2);

                                              
                                                ?>
                    <div class="modal p-5" style='display:block !important; background-color:rgb(0, 0, 0,.86); '>
                     
                          <div class="card  p-2 mt-5 " style='overflow-y: auto !important;'>
                            <h4 class="card-title">
                                
                            </h4>
                            <div class="card-body">
                          
                                            <!-- card that shows total student enteries in quiz-->
                                            <div class="col-lg-3 col-md-4 col-sm-12 mb-4 mt-3">
                                                <div class="card   border-primary h-100 shadow-sm">
                                                    <div class="card-body text-center">
                                                   
                                                    <h6 class="card-title text-uppercase text-muted mb-2">Total Assignment Submission</h6>
                                                    <h2 class="card-text fw-bold text-primary mb-0">
                                                        <?php 
                                                            $count_query = "select count(DISTINCT stdid) as total from assignmententries where formid = '$formid'";
                                                            $count_result = mysqli_query($connection, $count_query);
                                                            $count_data = mysqli_fetch_assoc($count_result);
                                                            echo $count_data['total'];
                                                        ?>
                                                    </h2>
                                                </div>
                                                <div class="card-footer bg-primary bg-opacity-10 border-0 text-center">
                                                    <small class="text-muted">Students Submited Assignment</small>
                                                </div>
                                            </div>
                                        </div> 
                                        <a href="slices/exportassignment.slices.php?assignmentformid=<?php echo $formid; ?>" class='btn btn-secondary btn-sm fw-bold'>Convert To Excel</a>
                                            <!-- end here-->
                            <input  style="width:400px !important;" type='text' class='form-control mt-2 mb-1' id='myInput' placeholder="Search by id" onkeyup="searchTable()"/>                       
                                <table class='table table-bordered  table-hover 'id='myTable'>
                                    <tr>
                                        <td>#</td>
                                        <td>Student_ID</td>
                                        <td>Student_Name</td>
                                        <td>Course_Name</td>
                                        <td>Assignment Marks</td>
                                        <td>Uploaded_Filename</td>
                                        <td>Uploaded_Filesize</td>
                                        <td>Date</td>
                                        <td>Action</td>
                                    </tr>
                                    <?php
                                    $rownumber = 1; 
                                    if(mysqli_num_rows($result2) == 0){?>
                                     <p>Sorry , There’s currently no data to show.</p> 
                               <?php }else{ while($rows = mysqli_fetch_assoc($result2)){
                               
                                    
                                    ?>
                                    <form method='post' action="viewassignmentform.php?stdid=<?php  echo $rows['stdid']?>&columnid=<?php echo $rows['formid']?>">
                                        <tr>
                                            <td><?php echo $rownumber;?></td>
                                            <td><?php echo $rows['stdid']?></td>
                                            <td><?php echo $rows['stdfullname']?></td>
                                            <td><?php echo $rows['coursename']?></td>
                                            <td><input type='text' value="<?php echo $rows['marks']?>" name='assignmentmarks' style='width:50px'/></td>
                                            <td><?php echo $rows['uploadedfile'];?></td>
                                            <td><?php $filesize = round($rows['filesize']); echo $filesize.' MB'; ?></td>
                                            <td><?php echo date('M-j-Y ', strtotime($rows['date']));?></td>
                                            <td><a href='uploads/<?= $rows['uploadedfile']?>' download='uploads/<?= $rows['uploadedfile']?>'class='btn btn-info btn-sm fw-bold text-white mt-2 mb-2'>Download Document</a>&numsp;<button class='btn btn-primary btn-sm fw-bold' name='updatentries'>Update</button>&numsp;<a href="viewassignmentform.php?deleteid=<?php echo $rows['stdid']?>" class='btn btn-danger text-white fw-bold'>
                                                Delete
                                            </a>&numsp;<a href="showassignmentdocument.php?docname=<?php echo $rows['pdf_file']?>" target="_blank" class='btn btn-secondary btn-sm fw-bold mt-1'>View Document</a></td>
                                        </tr>
                                    </form>
                                    <?php  $rownumber++;}}?>
                                </table>
                                <a href='viewassignmentform.php' class='btn btn-secondary btn-sm fw-bold'>Close</a>
                            </div>
                          </div>
                          
                        </div>
                            <?php } ?>
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
    
        // Function to copy the link to clipboard
        function copyToClipboard(event , element) {
            event.preventDefault();
            // Create a temporary input element to copy the link
            var tempInput = document.createElement("input");
            document.body.appendChild(tempInput);
            tempInput.value = element.href;  // Set input value to the href of the anchor
            tempInput.select();  // Select the text in the input
            document.execCommand("copy");  // Copy the selected text to clipboard
            document.body.removeChild(tempInput);  // Remove the temporary input element
            alert("Link copied to clipboard!");  
            // Optional: Show an alert message
        }

    </script>
</body>

</html>