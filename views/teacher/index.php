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
function totalcourse($connection, $teacherid)
{
    $sql = "select * from course where teacherid = '$teacherid'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $row = mysqli_num_rows($result);


        return $row;
    }
}
//total number of students
function totalstudents($connection, $teacherid)
{
    $sql = "select * from students where teacherid = '$teacherid'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $row = mysqli_num_rows($result);


        return $row;
    }
}
//totalassignment
function totalassignment($connection, $teacherid)
{
    $sql = "select * from assignmentform where teacherid = '$teacherid'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $row = mysqli_num_rows($result);


        return $row;
    }
}

// display subscription status
function displaysubscriptionstatus($connection, $teacherid)
{
    $sql = "select subsatus from subscription where userid = '$teacherid'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        // $row = ;
        while ($row = mysqli_fetch_assoc($result)) {

            // echo $row['subsatus'];
            $checksubs = $row['subsatus'] == "active" ? "<span class='text-success fw-bold'>Active</span>" : ($row['subsatus'] == "expire" ? "<span class='text-danger fw-bold'>Expire</span>" : "<span class='text-primary fw-bold'>Pending</span>");
            return $checksubs;
        }
    }
}
// display total quizform
function displaytotalquizform($connection, $teacherid)
{
    $sql = "select * from quizform where teacherid = '$teacherid'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $row = mysqli_num_rows($result);


        return $row;
    }
}
// display total examform
function displaytotalexamform($connection, $teacherid)
{
    $sql = "select * from examform where teacherid = '$teacherid'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $row = mysqli_num_rows($result);


        return $row;
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
                    <h5>Dashboard</h5>

                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                            <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:14px;'>Total Number Of Courses</h5>
                                <p class="card-text"><?php echo totalcourse($connection, $teacherid); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                            <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:14px;'>Total Number Of Students</h5>
                                <p class="card-text"><?php echo totalstudents($connection, $teacherid); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-dark" style='background-color:#f8f9fa !important;'>
                            <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:14px;'>Total Number Of Assignment Form</h5>
                                <p class="card-text"><?php echo totalassignment($connection, $teacherid); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                            <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:14px;'>Total Number Of Quiz Form</h5>
                                <p class="card-text" style='font-size:14px;'><?php echo displaytotalquizform($connection, $teacherid); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                            <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:14px;'>Total Number Of Exam Form</h5>
                                <p class="card-text" style='font-size:14px;'><?php echo displaytotalexamform($connection, $teacherid); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-3 mb-4">
                        <div class="card  text-white" style='background-color:#f8f9fa !important;'>
                            <div class="card-body text-black">
                                <h5 class="card-title fw-bold" style='font-size:15px;'>Subscription Status</h5>
                                <p class="card-text" style='font-size:14px;'><?php echo displaysubscriptionstatus($connection, $teacherid); ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- 1- if user subscription plan is free display thid-->
                    <?php if (checksubscriptionstatus($connection, $teacherid, 'subsatus') == 'active' && checksubscriptionstatus($connection, $teacherid, 'subplan') == 'free') { ?>
                        <div class='col-12 col-md-6 col-xl-6 mb-4'>
                            <div class="card p-3" style="background-color: #f8f9fa !important;">
                                <p class="fw-normal" style="line-height: 30px;">
                                    Dear <strong><?php echo $_SESSION['fullname']; ?></strong>, you are currently subscribed to the <strong>Free Plan</strong>.
                                    With this plan, you have access to the following features:
                                </p>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Create up to <strong>1 course</strong></li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Generate <strong>1 assignment form</strong></li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Create <strong>1 quiz form</strong></li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Create <strong>1 exam form</strong></li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> share up to 1 result <strong>per student</strong></li>
                                </ul>

                                <div class="container">
                                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#upgradeModal">
                                        Upgrade to Pro Plan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 2 - if subscription is pro and the subamount is not 0 (that means user paid pro plan)-->
                    <?php } else if (checksubscriptionstatus($connection, $teacherid, 'subplan') == 'pro' && checksubscriptionstatus($connection, $_SESSION['userid'], 'subsatus') == 'active'  && checksubscriptionstatus($connection, $teacherid, 'subamount') != '0') { ?>
                        <div class='col-lg-12 col-md-12 col-xl-6 mb-4'>
                            <div class='card p-2' style='background-color:#f8f9fa !important;'>
                                <p class=' mt-2' style='font-size:14px;'>Dear <strong><?php echo $_SESSION['fullname']; ?></strong> Thank you for purchasing the pro plan! You now access to all features of TeachLab. Enjoy your experience!</p>
                                <p class='text-muted small'>Note: Your subscription will expire after <strong><?php echo checksubscriptionstatus($connection, $teacherid, 'subamount') . ' Days ' ?>.</strong></p>
                            </div>
                        </div>
                </div>
                <!-- if user is pro and subscription status says pending then we should let them know we processing the payment-->
            <?php } else if (checksubscriptionstatus($connection, $teacherid, 'subplan') == 'pro' || checksubscriptionstatus($connection, $teacherid, 'subplan') == 'free' && checksubscriptionstatus($connection, $_SESSION['userid'], 'subsatus') == 'pending'  && checksubscriptionstatus($connection, $teacherid, 'subamount') == '0') { ?>
                <div class='col-lg-12 col-md-12 col-xl-6 mb-4'>
                        <div class='card p-2' style='background-color:#f8f9fa !important;'>
                        <p class='mt-2' style='font-size:14px;'>
                        Dear <strong><?php echo $_SESSION['fullname']; ?></strong>, thank you for renewing your subscription. We’re currently verifying your payment. This process will take less than 24 hours, and once confirmed, you’ll regain access to your account. <br>
                        If it takes longer than expected, please feel free to contact us on WhatsApp: <a href='https://wa.me/+252633558027' target='_blank' style='color:green; text-decoration:underline; font-weight:bold;'>Contact Us On WhatsApp</a>.
                        </p>
                    </div>
                </div>
        </div>

        <!-- 3 - if subscription is pro and the subamount is 0 then we will force the user to pay money-->
    <?php } else if (checksubscriptionstatus($connection, $teacherid, 'subplan') == 'pro' && checksubscriptionstatus($connection, $_SESSION['userid'], 'subsatus') == 'active'  && checksubscriptionstatus($connection, $teacherid, 'subamount') == '0') { ?>
        <div class='col-lg-12 col-md-12 col-xl-6 mb-4 mb-4'>
            <div class="card p-3" style="background-color: #f8f9fa !important;">
                <p class="fw-normal" style="line-height: 30px; font-size:14px">
                    Dear <strong><?php echo $_SESSION['fullname']; ?></strong>, you are currently subscribing to the <strong>Pro Plan</strong>,
                    To use this software, you will need to complete the payment for the Pro Subscription Plan, which costs $10.
                    To proceed with the payment, please click the 'Proceed with Payment' button.
                </p>



                <div class="container">
                    <button class="btn btn-primary w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#upgradeModal">
                        Prceed With payment
                    </button>
                </div>
            </div>

            <!-- 4- if subscription is pro and the subamount is 0 and the status of the plan is expire-->
        <?php } else if (checksubscriptionstatus($connection, $teacherid, 'subplan') == 'pro' && checksubscriptionstatus($connection, $_SESSION['userid'], 'subsatus') == 'expire'  && checksubscriptionstatus($connection, $teacherid, 'subamount') == '0') { ?>
            <div class='col-lg-12 col-md-12 col-xl-6  mb-4'>
                <div class="card p-3" style="background-color: #f8f9fa !important;">
                    <p class="fw-normal" style="line-height: 30px; font-size:14px">
                        Dear <strong><?php echo $_SESSION['fullname']; ?></strong>, your subscription has expired. To continue using our software, you will need to renew your subscription.
                        Please click the 'Renew Subscription' button to proceed
                    </p>



                    <div class="container">
                        <button class="btn btn-primary w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#upgradeModal">
                            Renew Subscription
                        </button>
                    </div>
                </div>
                <!-- 5- if user subsciption plan is one-time-purches and subamount is 0  that means user didn't complete one time purches-->
            <?php } else if (checksubscriptionstatus($connection, $teacherid, 'subplan') == 'one-time-purches' && checksubscriptionstatus($connection, $_SESSION['userid'], 'subsatus') == 'active'  && checksubscriptionstatus($connection, $teacherid, 'subamount') == '0') { ?>
                <div class='col-lg-12 col-md-12 col-xl-6 mb-4'>
                    <div class="card p-3" style="background-color: #f8f9fa !important;">
                        <p class="fw-normal" style="line-height: 30px; font-size:14px">
                            Dear <strong><?php echo $_SESSION['fullname']; ?></strong>, you are currently subscribing to the <strong>One Time Purches Plan</strong>,
                            To use this software, you will need to complete the payment for theOne Time Purches Plan, which costs $10.
                            To proceed with the payment, please click the 'Proceed with Payment' button.
                        </p>



                        <div class="container">
                            <button class="btn btn-primary w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#upgradeModal">
                                Prceed With payment
                            </button>
                        </div>
                    </div>
                    <!-- 6 - if user subscription plan is one time purches and subamount is not 0 that means user completed and can use thus
                                software life long(his entire life) -->
                <?php } else if (checksubscriptionstatus($connection, $teacherid, 'subplan') == 'one-time-purches' && checksubscriptionstatus($connection, $_SESSION['userid'], 'subsatus') == 'active'  && checksubscriptionstatus($connection, $teacherid, 'subamount') == 'infinite') { ?>
                    <div class='col-lg-12 col-md-12 col-xl-6 mb-4'>
                        <div class='card p-2' style='background-color:#f8f9fa !important;'>
                            <p class=' mt-2' style='font-size:14px;'>Dear <strong><?php echo $_SESSION['fullname']; ?></strong>, you have successfully purchased the One-Time Plan.
                                With this plan, you now have lifetime access to our software. No further payments are required.</p>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>

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
                        <!-- Step 1: Instructions -->
                        <div id="step1" class="step">
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

                        <!-- Step 2: Transaction Details -->
                        <div id="step2" class="step d-none">
                            <p class="text-center">Please provide your payment transaction details below:</p>
                            <form id="paymentForm" method='POST'>
                                <div class="mb-3">
                                    <label for="transactionId" class="form-label">Fullname</label>
                                    <input type="text" class="form-control" id="fullname" placeholder="Enter Fullname">
                                </div>
                                <div class="mb-3">
                                    <label for="transactionId" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="number" placeholder="Enter Phone Number">
                                </div>
                                <div class="mb-3">
                                    <label for="amountPaid" class="form-label">Subscription Plan</label>
                                    <select class="form-select" id="amountPaid">
                                        <option value="pro">Pro Plan ($10)</option>
                                        <option value="one-time-purches">One Time Purches ($50)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="paymentDate" class="form-label">Payment Date</label>
                                    <input type="date" class="form-control" id="paymentDate">
                                </div>
                            </form>
                        </div>

                        <!-- Step 3: Confirmation -->
                        <div id="step3" class="step d-none">
                            <p class="text-center" style='font-size:14px !important;'>To finalize your subscription, please click the 'Complete' button. Our support team will verify your subscription, and you will receive a confirmation message within 24 hours.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" id="prevBtn" onclick="previousStep()">Previous</button>
                        <button type="button" class="btn btn-primary btn-sm" id="nextBtn" onclick="nextStep()">Next</button>
                        <button type="button" class="btn btn-primary btn-sm" id="submitBtn" onclick="submitForm()" style="display:none;">Complete</button>
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



    <!-- making subscription form accessible -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        let currentStep = 1;

        function showStep(step) {

            const steps = document.querySelectorAll('.step');
            steps.forEach((stepElem, index) => {
                if (index === step - 1) {
                    stepElem.classList.remove('d-none');
                } else {
                    stepElem.classList.add('d-none');
                }
            });
        }

        function nextStep() {

            const fullname = $('#fullname').val();
            const number = $('#number').val();
            const amount = $('#amountPaid').val();
            const paymentdate = $('#paymentDate').val();

            if (currentStep === 1) {

                showStep(2);
                currentStep++;
                document.getElementById('prevBtn').style.display = 'inline-block';
                document.getElementById('nextBtn').style.display = 'inline-block';
                document.getElementById('submitBtn').style.display = 'none';
            } else if (currentStep === 2) {

                $.ajax({
                    url: 'slices/subscription_manager.slice.php',
                    method: 'POST',
                    data: {
                        fullname: fullname,
                        number: number,
                        amount: amount,
                        paymentdate: paymentdate
                    },
                    success: function(response) {
                        if (response == 'emptyfullname') {
                            alert('empty fullname feild');
                        } else if (response == 'emptynumber') {
                            alert('empty phone number feild');

                        } else if (response == 'emptypaymentdate') {
                            alert('empty payment date feild');

                        } else if (response == 'subscriptionisexsit') {
                            alert("dear user there is currently active subscription , you can't renew your subscription if there is active one ")
                        } else if (response == 'success') {
                            showStep(3);
                            currentStep++;
                            document.getElementById('nextBtn').style.display = 'none';
                            document.getElementById('submitBtn').style.display = 'inline-block';
                        } else if (response == 'failed') {
                            alert('sorry , something went wrong , please try again later')
                        } else {
                            console.log(response);

                        }

                    }
                })


            }
        }

        function previousStep() {
            if (currentStep === 2) {
                showStep(1);
                currentStep--;
                document.getElementById('prevBtn').style.display = 'none';
            } else if (currentStep === 3) {
                showStep(2);
                currentStep--;
                document.getElementById('nextBtn').style.display = 'inline-block';
                document.getElementById('submitBtn').style.display = 'none';
            }
        }

        function submitForm() {
            // Submit form logic here, e.g., send data to the server







            // Here you can use AJAX to send the form data to the server
            // For example:
            // fetch('/submit-payment', {
            //   method: 'POST',
            //   body: formData
            // });

            alert('Your payment details have been submitted successfully!');
            window.location.href='index.php';

            // Reset the form and go back to step 1
            form.reset();
            showStep(1);
            currentStep = 1;
            document.getElementById('prevBtn').style.display = 'none';
            document.getElementById('nextBtn').style.display = 'inline-block';
            document.getElementById('submitBtn').style.display = 'none';
        }

        // Initialize the modal on load
        showStep(currentStep);
    </script>



</body>

</html>