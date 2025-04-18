<?php

include('../../model/dbcon.php');



// this function is reponsible to fecth studentfullname
function getstudentfullname($connection, $studentid)
{
    $sql = "select stdfullname from students where stdid = '$studentid'";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) == 0) {
        return 'N/A';
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row['stdfullname'];
    }
}

// this function is reponsible to fecth studentfullname
function getsteacherfullname($connection, $teacherid)
{
    $sql = "select fullname from users where userid = '$teacherid'";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) == 0) {
        return 'N/A';
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row['fullname'];
    }
}


// get total marks of  attandece of single student
function gettotalmarksofattandence($connection, $studentid)
{
    $sql = "select sum(attendedmarks) as totalattandencemarks from markattendence  where stdid = '$studentid' group by stdid";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) == 0) {
        return 'N/A';
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row['totalattandencemarks'];
    }
}

// get total marks of  assignment of single student
function gettotalmarksofassignment($connection, $studentid)
{
    $sql = "select sum(marks) as totalassignmentmarks from assignmententries  where stdid = '$studentid' group by stdid";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) == 0) {
        return 'N/A';
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row['totalassignmentmarks'];
    }
}


// get total marks of  quiz of single student
function gettotalmarksofquiz($connection, $studentid)
{
    $sql = "select quizmarks from studentquiz  where stdid = '$studentid' group by stdid";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) == 0) {
        return 'N/A';
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row['quizmarks'];
    }
}

// get total marks of  exam of single student
function gettotalmarksofexam($connection, $studentid)
{
    $sql = "select exammarks from studentexam  where stdid = '$studentid' group by stdid";
    $result = mysqli_query($connection, $sql);
    if (mysqli_num_rows($result) == 0) {
        return 'N/A';
    } else {
        $row = mysqli_fetch_assoc($result);
        return $row['exammarks'];
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">

    <title>TeachLab | Student Report Preview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body style='overflow-x:hidden;'>

    <?php

    if (isset($_GET['reportid']) && !empty($_GET['reportid'])) {
        $reportid = mysqli_escape_string($connection,  base64_decode($_GET['reportid']));
        $sql = "select * from reportsharing where id = '$reportid'";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result) == 0) { ?>
            <!-- report not found error starts here -->


            <!-- Report Design Starts From Here-->
            <div class="row">
                <!-- column 1 starts here-->
                <div class="col-lg-6 col-md-8 col-sm-12 mx-auto mt-1 mb-2">
                    <div class="text-end mb-3">
                    </div>
                    <div class="card text-center">
                        <h4 class="card-header d-flex justify-content-between" style='font-size:14px;'>
                            Student Marks Report Preview
                        </h4>
                        <div class="card-bo">
                            <p class=' p-2 text-danger fw-bold' style='font-size:15px;'>Sorry Report Not Found </p>
                        </div>

                    </div>
                </div>
                <!-- column 1 ends here-->
            </div>

            <!-- report not found error end here -->




        <?php } else {

            $reportfetcheddata = $row = mysqli_fetch_assoc($result);
        ?>



            <style>
                .watermark-container {
                    position: relative;
                    width: 100%;
                    margin-top: 30px;
                    text-align: center;
                    page-break-inside: avoid;
                    /* Prevents the watermark from breaking across pages */
                }

                .watermark {
                    padding: 20px 0;
                    border-top: 2px solid #e74c3c;
                }

                .pdf-footer-text {
                    color: #e74c3c;
                    font-size: 14px;
                    font-weight: bold;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    padding: 10px;
                    background-color: #f8f9fa;
                    border-radius: 5px;
                    display: inline-block;
                }
            </style>

            <!-- Report Design Starts From Here-->
            <div class="row">
                <!-- column 1 starts here-->
                <div class="col-lg-6 col-md-8 col-sm-12 mx-auto mt-1 mb-2">
                    <div class="text-end mb-3">
                        <button class='btn btn-primary btn-sm fw-bold' onclick="downloadPDF()">Download PDF</button>
                    </div>
                    <div class="card" id="pdf-content">
                        <h4 class="card-header d-flex justify-content-between" style='font-size:14px;'>
                            <span>Student Marks Report Paper</span>
                            <span>Report ID: <?= $reportfetcheddata['id']; ?> </span>
                        </h4>
                        <div class="card-body" style="height:68vh">
                            <p class='mt-2'></p>
                            <h4 style='font-size:15px' class='mb-3'>Student ID : <?= ucwords($reportfetcheddata['studentid']) ?></h4>
                            <h4 style='font-size:15px' class='mb-3'>Student Fullname: <?= ucwords(getstudentfullname($connection, $reportfetcheddata['studentid'])) ?></h4>
                            <h4 style='font-size:15px' class='mb-3'>Coursename: <?= ucwords($reportfetcheddata['coursename']) ?></h4>
                            <h4 style='font-size:15px' class='mb-3'>Teacher Fullname: <?= ucwords(getsteacherfullname($connection, $reportfetcheddata['teacherid'])); ?></h4>
                            <h4 style='font-size:15px' class='mb-3'>Report Date: <?= date('M-j-Y ', strtotime($reportfetcheddata['created_date'])); ?></h4>

                            <hr>

                            <div class="row g-4">
                                <!-- attandence card starts here -->
                                <div class="col-lg-12 col-md-12 col-sm-12">

                                    <div class="card mb-4">
                                        <div class="card-header fw-bold" style='font-size:14px;'>
                                            Summary Of Course Marks
                                        </div>
                                        <div class="card-body">
                                            <table class='table table-bordered table-hover'>
                                                <tr>
                                                    <td style='font-size:14px'>Attandence Marks</td>
                                                    <td style='font-size:14px'>Assignment Marks</td>
                                                    <td style='font-size:14px'>Quiz Marks</td>
                                                    <td style='font-size:14px'>Exam Marks</td>
                                                </tr>

                                                <tr>
                                                    <td style='font-size:14px'><?= gettotalmarksofattandence($connection, $reportfetcheddata['studentid']) == 'N/A' ? 0 :  gettotalmarksofattandence($connection, $reportfetcheddata['studentid']); ?></td>
                                                    <td style='font-size:14px'><?= gettotalmarksofassignment($connection, $reportfetcheddata['studentid']) == 'N/A' ? 0 : gettotalmarksofassignment($connection, $reportfetcheddata['studentid']); ?></td>
                                                    <td style='font-size:14px'><?= gettotalmarksofquiz($connection, $reportfetcheddata['studentid'])  == 'N/A' ? 0 : gettotalmarksofquiz($connection, $reportfetcheddata['studentid']); ?></td>
                                                    <td style='font-size:14px'><?= gettotalmarksofexam($connection, $reportfetcheddata['studentid'])  == 'N/A' ? 0 : gettotalmarksofexam($connection, $reportfetcheddata['studentid']); ?></td>
                                                </tr>
                                                <!-- all marks -->
                                                <?php

                                                $attandencemarks = gettotalmarksofattandence($connection, $reportfetcheddata['studentid']) == 'N/A' ? 0 :  gettotalmarksofattandence($connection, $reportfetcheddata['studentid']);
                                                $assingmentmarks = gettotalmarksofassignment($connection, $reportfetcheddata['studentid']) == 'N/A' ? 0 : gettotalmarksofassignment($connection, $reportfetcheddata['studentid']);
                                                $quizmarks = gettotalmarksofquiz($connection, $reportfetcheddata['studentid'])  == 'N/A' ? 0 : gettotalmarksofquiz($connection, $reportfetcheddata['studentid']);
                                                $exammarks = gettotalmarksofexam($connection, $reportfetcheddata['studentid'])  == 'N/A' ? 0 : gettotalmarksofexam($connection, $reportfetcheddata['studentid']);


                                                ?>
                                                <td style='font-size:14px'>Total Marks : <?= $attandencemarks + $assingmentmarks + $quizmarks +  $exammarks; ?></td>

                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer watermark-container">
                            <div class="watermark">
                                <div class="pdf-footer-text">
                                    Generated by TeachLab - Your Digital Teaching Partner
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- column 1 ends here-->
            </div>
            <!-- Report Design Ends Here -->

        <?php  }

        // <?= date('M-j-Y ', strtotime($reportfetcheddata['created_date'])); 
        ?>

















    <?php } else { ?>
        <!-- report not found error starts here -->


        <!-- Report Design Starts From Here-->
        <div class="row">
            <!-- column 1 starts here-->
            <div class="col-lg-6 col-md-8 col-sm-12 mx-auto mt-1 mb-2">
                <div class="text-end mb-3">
                </div>
                <div class="card text-center">
                    <h4 class="card-header d-flex justify-content-between" style='font-size:14px;'>
                        Student Marks Report Preview
                    </h4>
                    <div class="card-bo">
                        <p class=' p-2 text-danger fw-bold' style='font-size:15px;'>Sorry , we are unable to show the report due to error, please check your url.</p>
                    </div>

                </div>
            </div>
            <!-- column 1 ends here-->
        </div>

        <!-- report not found error end here -->
    <?php }

    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('pdf-content');

            const opt = {
                margin: 0.5,
                filename: 'student-marks-report.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</body>

</html>