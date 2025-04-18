<?php
include_once('../../model/dbcon.php');
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//check if session exist , if it exist prevent user from seeing login page
if (!isset($_SESSION['userid'])) {
    header('location:../login.php');
    exit();
}

//read all courses name related to this teacher
function getcoursename($connection, $teacherid)
{
    $sql = "select * from course where teacherid = '$teacherid'";
    $result = mysqli_query($connection, $sql);
    $coursename = array();
    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            $coursename[] = $row;
        }
    } else {
        echo 'empty';
    }
    return $coursename;
}

// delete single generated student report
if (isset($_GET['delid'])) {
    $delid = mysqli_escape_string($connection, trim($_GET['delid']));
    $sql = "delete from reportsharing where id = '$delid'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        header('location:reportsharing.php?delsuccess');
        exit();
    } else {
        header('location:reportsharing.php?delfailed');
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - Generate Student Report</title>
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

        function gtag() {
            dataLayer.push(arguments);
        }
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
                    <h1 class="h2" style='font-size:15px;'>Students / <span>Generate Student Report</span></h1>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xl-8 col-sm-12 mb-4">
                        <div class="card p-2 rounded" style='border:none !important; background-color:#f8f9fa !important;'>
                            <h4 class="card-title px-3 fw-bold mt-2 mb-0" style='font-size:14px !important;'>
                                Generate New Student Report
                            </h4>
                            <div class="card-body">

                                <?php if (isset($_GET['delsuccess'])) { ?>
                                    <p class='bg-success p-2 text-white fw-bold' id='msg2' style='font-size:14px !important;'>Student Report Deleted Successfully</p>
                                <?php } ?>
                                <?php if (isset($_GET['delfailed'])) { ?>
                                    <p class='bg-danger p-2 text-white fw-bold' id='msg2' style='font-size:14px !important;'>Failed To Delete Student Report</p>
                                <?php } ?>

                                <!-- this p tag i used by the jquery ajax to display error or successs-->
                                <p class='p-2 text-white fw-bold d-none' id='msg' style='font-size:14px !important;'></p>

                                <form method='post' id='reportform'>
                                    <div class="form-group mb-2 mt-2">
                                        <label class='form-label' style='font-size:15px !important;'>Cousename</label>
                                        <select name='coursename' id='coursename' class='form-control'>
                                            <option value="empty" style='font-size:15px !important;'>Select Coursename</option>
                                            <?php
                                            $coursesinfo = getcoursename($connection, $_SESSION['userid']);
                                            foreach ($coursesinfo as $coursename): ?>
                                                <option value="<?= $coursename['coursename'] ?>"><?= $coursename['coursename'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name='teacherid' id='teacherid' value='<?= $_SESSION['userid'] ?>'>
                                    </div>
                                    <div class="form-group mt-1 mb-2">
                                        <label class='form-label' style='font-size:15px !important;'>Student Information</label>
                                        <select name='studentinformation' id='studentinformation' class='form-control'>
                                            <!-- <option value="all_marks">All Students Of This Course</option> -->

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class='form-label' style='font-size:15px !important;'>Sharing Ability</label>
                                        <select name='sharingability' id='sharingability' class='form-control'>
                                            <option value="" style='font-size:15px !important;'>Select Sharing Ability</option>
                                            <!-- <option value="attandence_marks">Attandence Marks</option>
                                            <option value="assignment_marks">Assignment Marks</option>
                                            <option value="quiz_marks">Quiz Marks</option>
                                            <option value="exam_marks">Exam Marks</option> -->
                                            <option value="all_marks">All Marks</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary btn-sm mt-2 fw-bold" name='submit' id='submit'>Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- table content starts here-->

                    <div class="col-lg-12 col-md-12 col-xl-11 col-sm-12">
                        <div class="card">
                            <h4 class="card-header" style='font-size:15px;'>
                                View Generated Student Report
                            </h4>
                            <div class="card-body table-responsive">
                                <table class='table table-bordered table-hover'>

                                    <tr>
                                        <td>#</td>
                                        <td>Student_ID</td>
                                        <td>Student_Fullname</td>
                                        <td>Coursename</td>
                                        <td>Sharing_Ability</td>
                                        <td>Created_Date</td>
                                        <td>Action</td>
                                    </tr>

                                    <tbody id='reportinformation'>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- table content ends here-->

                </div>

                <!-- More Content Here -->
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>


    <script>
        // JavaScript to handle the sidebar close button functionality
        var closebtn = document.getElementById('closeSidebar');

        closebtn.addEventListener('click', function() {
            var sidebar = new bootstrap.Collapse(document.getElementById('sidebar'), {

                toggle: true,
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            //  we put message in select element as place holder 
            $('#studentinformation').append("<option value=''>Please select coursename before selecting student name</option>")
            studentdisplayallreportdata();



            // fetch and display all report generated in the ui
            function studentdisplayallreportdata() {
                $.ajax({
                    url: "slices/report_getstudentinfo_byspecificoursename.slices.php",
                    method: "GET",
                    data: {
                        teacherid: <?php echo json_encode($_SESSION['userid'] ?? ''); ?>,
                    },
                    dataType: 'json',

                    success: function(response) {

                        // now we need to check if the returning data is array (contains data we need to be displayed)

                        if (Array.isArray(response)) {

                            // const data = json.parse(response)
                            // console.log('content is array');
                            $('#reportinformation').html(''); // Clear table first
                            let rowid = 1;
                            response.forEach(studentinfo => {
                                // this function helps me to make the date understandable
                                function formatDate(dateString) {
                                    // Parse the date string (it should be in 'YYYY-MM-DD HH:MM:SS' format from PHP)
                                    return new Date(dateString).toLocaleDateString('en-US', {
                                        month: 'long',
                                        day: 'numeric',
                                        year: 'numeric'
                                    });
                                }
                                console.log();

                                $('#reportinformation').append(`
                                    
                                    <tr>
                                    <td>${rowid}</td>
                                    <td>${studentinfo.reportdata.studentid}</td>
                                    <td>${studentinfo.getstudentname[0].stdfullname|| "N/A" }</td>
                                    <td>${studentinfo.reportdata.coursename}</td>
                                    <td>${studentinfo.reportdata.sharing_ability}</td>
                                    <td>${formatDate(studentinfo.reportdata.created_date)}</td>
                                    <td class='d-sm-flex gap-1'><a href="../shareable_report/shareablestudentreport.php?reportid=${btoa(studentinfo.reportdata.id)}" onclick="copyToClipboard(event,this)" class='btn btn-warning btn-sm text-black fw-bold  me-2'>Share Report</a><a href='reportsharing.php?delid=${studentinfo.reportdata.id}' class='btn btn-danger btn-sm fw-bold' >Delete</a></td>
                                    </tr>
                                    
                                    

                                `);

                                rowid++
                            });

                            // now we need to check if the returning data is string (contains errors that we ned to be displayed) 
                        } else if (typeof response === 'string') {
                            // console.log(response);
                            if (response === 'dbempty') {
                                $('#reportinformation').html("<p style='width:300px;'>there is nothing to be shown</p>");

                            } else if (response === 'teacheridnotfound') {
                                $('#reportinformation').empty();
                                $('#reportinformation').append("<p style='width:300px;'>sorry we failed to display generated report data , please try again later.<p>");
                            } else {
                                console.log(response);

                            }


                        }




                    }
                })
            }






            // checking when option is selected inside the select and we are reading what that data is
            $('#coursename').on('change', function() {
                let teacherid = $('#teacherid').val();
                let value = $(this).val();
                let text = $('option:selected', this).text();
                // if the select element is still no option be chosing we this play the blow message
                if (value == 'empty') {
                    $('#msg2').addClass('d-none');
                    // console.log('empty choice')
                    $('#studentinformation').empty()
                    $('#studentinformation').append("<option value=''>Please select coursename before selecting student name</option>")

                } else {
                    // sending data to the intended php file to process the information
                    $.ajax({
                        url: 'slices/report_getstudentinfo_byspecificoursename.slices.php',
                        method: 'post',
                        data: {
                            teacherid: teacherid,
                            coursename: value
                        },
                        success: function(response) {
                            let data = JSON.parse(response);
                            if (data == 'teacheridnotfound') {
                                $('#msg2').addClass('d-none');
                                $('#studentinformation').empty()
                                $('#studentinformation').append("<option value=''>Sorry , we failed to display students information reated to chosing coursename, try again later.</option>")
                            }
                            if (data == 'empty') {
                                $('#msg2').addClass('d-none');
                                $('#studentinformation').empty()
                                $('#studentinformation').append("<option value=''>No student found in the speicified coursename</option>")

                            } else if (data != 'empty' && data != 'teacheridnotfound') {
                                $('#msg2').addClass('d-none');
                                $('#studentinformation').empty()
                                data.forEach(studentdata => {
                                    // console.log(studentdata.stdid);
                                    $('#studentinformation').append(`<option value='${studentdata.stdid}'>${studentdata.stdid} | ${studentdata.stdfullname}</option>`)

                                });
                            } else {
                                console.log(data);

                            }


                        }

                    })

                }
            });


            // this section of the code will handle storing and validate form content when form is submitted
            $('#reportform').submit(function(event) {
                event.preventDefault();
                $('#submit').html('submitting...');
                $.ajax({
                    url: "slices/savegeneratedreport.slices.php",
                    method: 'post',
                    data: $('#reportform').serialize(),
                    success: function(response) {
                        // $('#msg').removeClass('d-none');
                        // $('#msg').addClass('bg-danger');
                        // $('#msg').html('Please Select Coursename');
                        if (response == 'emptycoursenamefield') {
                            $('#msg2').addClass('d-none');
                            $('#msg').removeClass('d-none');
                            $('#msg').removeClass('bg-success');
                            $('#msg').addClass('bg-danger');
                            $('#msg').html('Empty Coursename Field ');

                        } else if (response == 'emptystudentinformationfield') {
                            $('#msg2').addClass('d-none');
                            $('#msg').removeClass('d-none');
                            $('#msg').removeClass('bg-success');
                            $('#msg').addClass('bg-danger');
                            $('#msg').html('Sorry , we failed to display student information related to the chosing coursename, try again later.');


                        } else if (response == 'emptysharingabilityfield') {
                            $('#msg2').addClass('d-none');
                            $('#msg').removeClass('d-none');
                            $('#msg').removeClass('bg-success');
                            $('#msg').addClass('bg-danger');
                            $('#msg').html('Empty Sharing Ability Field');

                        } else if (response == 'emptysharingstatusfield') {
                            $('#msg2').addClass('d-none');
                            $('#msg').removeClass('d-none');
                            $('#msg').removeClass('bg-success');
                            $('#msg').addClass('bg-danger');
                            $('#msg').html('Empty Sharing Status Field');

                        } else if (response == 'emptyteacherid') {
                            $('#msg2').addClass('d-none');
                            $('#msg').removeClass('d-none');
                            $('#msg').addClass('bg-danger');
                            $('#msg').html('Sorry Error Occur While Generating Report , Please Try Again Later.');
                        } else if (response == 'failedtosave') {
                            $('#msg2').addClass('d-none');
                            $('#msg').removeClass('d-none');
                            $('#msg').removeClass('bg-success');
                            $('#msg').addClass('bg-danger');
                            $('#msg').html('Dear teacher you already generated report for this particular student ,please check the table below to see all generated reports.');
                        } else if (response == 'failed') {
                            $('#msg2').addClass('d-none');
                            $('#msg').removeClass('d-none');
                            $('#msg').removeClass('bg-success');
                            $('#msg').addClass('bg-danger');
                            $('#msg').html('Sorry Error Occur Saving Report Information , Please Try Again Later.');
                        } else if (response == 'success') {
                            $('#msg2').addClass('d-none');
                            studentdisplayallreportdata();
                            $('#msg').removeClass('d-none');
                            $('#msg').removeClass('bg-danger');
                            $('#msg').addClass('bg-success');
                            $('#msg').html('Student Report Generated Successfully.');
                        } else {
                            $('#msg2').addClass('d-none');
                            $('#msg').addClass('d-none');
                            $('#msg').removeClass('bg-danger');
                            $('#msg').removeClass('bg-success');
                            console.log(response);
                        }
                    },
                    complete: function(response) {
                        $('#submit').html('submit');
                    }

                })
            })
        });


        // Function to copy the link to clipboard
        function copyToClipboard(event, element) {
            event.preventDefault();
            // Create a temporary input element to copy the link
            var tempInput = document.createElement("input");
            document.body.appendChild(tempInput);
            tempInput.value = element.href; // Set input value to the href of the anchor
            tempInput.select(); // Select the text in the input
            document.execCommand("copy"); // Copy the selected text to clipboard
            document.body.removeChild(tempInput); // Remove the temporary input element
            alert("Link copied to clipboard!");
            // Optional: Show an alert message
        }
    </script>
</body>

</html>