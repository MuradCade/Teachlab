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


if (isset($_POST['update'])) {
    $userid = $_POST['userid'];
    $fullname = mysqli_escape_string($connection, $_POST['fullname']);
    $email = mysqli_escape_string($connection, $_POST['email']);
    $role = mysqli_escape_string($connection, $_POST['role']);
    $vstatus = mysqli_escape_string($connection, $_POST['v_status']);
    $v_statuschecked = strtolower($_POST['v_status']) == 'yes' ? '1' : '0';


    if (empty($userid)) {
        header('location:viewusers.php?emptyuserid');
        exit();
    } else if (empty($fullname)) {
        header('location:viewusers.php?emptyfullname');
        exit();
    } else if (empty($email)) {
        header('location:viewusers.php?emptyemail');
        exit();
    } else if (empty($vstatus)) {
        header('location:viewusers.php?vstatus');
        exit();
    } else if (empty($role)) {
        header('location:viewusers.php?emptyrole');
        exit();
    } else {

        $sql = "update users set fullname = '$fullname', role = '$role' ,verified_status = '$v_statuschecked', email ='$email'  where userid = '$userid'";
        $result = mysqli_query($connection, $sql);
        if ($result) {
            $_SESSION['fullname'] = $fullname;
            header('location:viewusers.php?updatesuccess');
            exit();
        } else {
            header('location:viewusers.php?updatefailed');
            exit();
        }
    }
}



//delete
if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];
    $sql = "delete from users where userid = '$userid'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        header('location:viewusers.php?delsuccess');
        exit();
    }
}

// get subscription info of each user
function getsubscriptioninfo($userid, $params, $connection)
{
    $sql = "select *  from subscription where userid = '$userid'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            return $row[$params];
        }
    }
}

// display single user details
function singleuserdetails($userid, $connection)
{
    $sql = "select * from users where userid = '$userid'";
    $result = mysqli_query($connection, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}







?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - Manage Users</title>
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
                    <h1 class="h2" style='font-size:17px;'>View Users</h1>
                </div>

                <div class="row">
                    <div class="col-lg-10 col-md-10 col-xl-12 px-2 col-smp-12 mb-4">
                        <div class='table-responsvie'>
                            <?php if (isset($_GET['emptyfullname'])) { ?>
                                <p class='bg-danger p-2 text-white fw-bold'  style='font-size:14px;'>Empty Fullname Field</p>
                            <?php } ?>
                            <?php if (isset($_GET['vstatus'])) { ?>
                                <p class='bg-danger p-2 text-white fw-bold'  style='font-size:14px;'>Empty Verified Field</p>
                            <?php } ?>
                            <?php if (isset($_GET['emptyrole'])) { ?>
                                <p class='bg-danger p-2 text-white fw-bold'  style='font-size:14px;'>Empty Role Field</p>
                            <?php } ?>
                            <?php if (isset($_GET['emptyemail'])) { ?>
                                <p class='bg-danger p-2 text-white fw-bold'  style='font-size:14px;'>Empty Email Field</p>
                            <?php } ?>
                            <?php if (isset($_GET['updatesuccess'])) { ?>
                                <p class='bg-success p-2 text-white fw-bold'  style='font-size:14px;'>User Information Updated Successfully</p>
                            <?php } ?>
                            <?php if (isset($_GET['updatefailed'])) { ?>
                                <p class='bg-danger p-2 text-white fw-bold'  style='font-size:14px;'>Faild To Update User Information</p>
                            <?php } ?>


                            <!-- changepassword respone-->
                            <?php if (isset($_GET['emptyuserid'])) { ?>
                                <p class='bg-danger p-2 text-white fw-bold'>Sorry , Changepassword operation not available try again later</p>
                            <?php } ?>
                            <?php if (isset($_GET['emptychangepwdfield'])) { ?>
                                <p class='bg-danger p-2 text-white fw-bold' style='font-size:14px;'>Empty Changepassword Field</p>
                            <?php } ?>
                            <?php if (isset($_GET['pwdchangedsuccess'])) { ?>
                                <p class='bg-success p-2 text-white fw-bold'  style='font-size:14px;'>User Password Changed Successfully</p>
                            <?php } ?>
                            <?php if (isset($_GET['pwdfailed'])) { ?>
                                <p class='bg-danger p-2 text-white fw-bold'  style='font-size:14px;'>Faild To Change User Password</p>
                            <?php } ?>

                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td>#</td>
                                    <!-- <td>userid</td> -->
                                    <td>Fullname</td>
                                    <td>Email</td>
                                    <td>Role</td>
                                    <td>Verified</td>
                                    <td>Action</td>
                                </tr>

                                <?php
                                $sql = "select * from users order by userid";
                                $result = mysqli_query($connection, $sql);
                                if (mysqli_num_rows($result) == 0) {
                                    echo 'there is no data to be shown';
                                } else {
                                    $rowid = 1;
                                    while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <form method="POST">
                                            <tr>
                                                <td><?php echo $rowid; ?></td>
                                                <td><input type='text' value="<?php echo $row['fullname'] ?>" name='fullname' /></td>
                                                <input type='hidden' value="<?php echo $row['userid'] ?>" name='userid' />
                                                <td><input type='text' value="<?php echo $row['email'] ?>" name='email' /></td>
                                                <td><input type='text' value="<?php echo $row['role'] ?>" name='role' /></td>
                                                <td><input type='text' name='v_status' value="<?php echo $row['verified_status'] == '1' ? 'Yes' : 'No'; ?>" /> <?php echo getsubscriptioninfo($row['userid'], 'subplan', $connection) == "one-time-purches" ? "<i class='bi bi-check' style='font-size:18px;'></i>" :(getsubscriptioninfo($row['userid'], 'subplan', $connection) == "free" ? "<i class='bi bi-dash-square' style='font-size:18px;'></i>":"<i class='bi bi-send' style='font-size:18px;'></i>"); ?> </td>
                                                <td><a href="viewusers.php?details=<?= base64_encode($row['userid']) ?>" class='btn btn-secondary btn-sm fw-bold  m-2'>Details</a><button class='btn btn-primary btn-sm fw-bold ' name='update'>Update Info</button>
                                                    <a href="viewusers.php?changepwd=<?= base64_encode($row['userid']) ?>" class='btn btn-warning btn-sm text-black fw-bold'>Changepwd</a>
                                                    <a href="#" class='btn btn-info btn-sm text-black fw-bold'>Manage_Sub</a>
                                                    <a href="viewusers.php?userid=<?php echo $row['userid'] ?>" class='btn btn-danger btn-sm fw-bold m-2'>Delete</a>
                                                </td>
                                            </tr>
                                        </form>
                                <?php $rowid++;
                                    }
                                }
                                ?>
                                <!-- Singl User Details Form-->
                                <?php if (isset($_GET['details'])) {
                                    $userid = base64_decode($_GET['details']);
                                ?>
                                    <div class="modal" style="display:block;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title " id="exampleModalLabel" style='font-size:16px !important;'>Singl User Details</h1>
                                                </div>
                                                <div class="modal-body">
                                                    <?php $displaysingleuserdetails =  singleuserdetails($userid, $connection);

                                                    foreach ($displaysingleuserdetails as $singleuserdata) { ?>

                                                        <p>UserID : <?= $singleuserdata['userid'] ?></p>
                                                        <p>Fullname : <?= $singleuserdata['fullname'] ?></p>
                                                        <p>UserEmail: <?= $singleuserdata['email'] ?></p>
                                                        <p>UserRole: <?= ucwords($singleuserdata['role']) ?></p>
                                                        <p>User_Verified: <?= $singleuserdata['verified_status'] == '1' ? "<span class='text-success fw-bold'>Yes</span>" : "<span class='text-danger fw-bold'>No</span>" ?></p>
                                                        <p>Subscriptin_Started_Date: <?= date('M-j-Y ', strtotime(getsubscriptioninfo($userid, 'date', $connection))) ?></p>
                                                        <p>Subscriptin_Expire_Date: <?= date('M-j-Y ', strtotime(getsubscriptioninfo($userid, 'expire_date', $connection))) ?></p>
                                                        <p>Subscription_Days_Left : <?= getsubscriptioninfo($userid, 'subamount', $connection) . ' Days' ?></p>
                                                        <p>Subscription_Plan : <?= getsubscriptioninfo($userid, 'subplan', $connection) ?></p>

                                                    <?php } ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <a type="button" class="btn btn-secondary btn-sm fw-bold" href='viewusers.php'>Close</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
                                <!-- Singl User Details Form ends here-->


                                <!-- Change User Password Form-->
                                <?php if (isset($_GET['changepwd'])) {
                                    $userid = $_GET['changepwd'];
                                ?>
                                    <div class='modal' style="display:block;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title " id="exampleModalLabel" style='font-size:16px !important;'>Change User Password</h1>
                                                </div>
                                                <div class="modal-body">

                                                    <form method="POST" action="slices/changeuserpwd.php">
                                                        <div class="form-group">
                                                            <label for="changepwd" class='form-label'>ChangePassword</label>
                                                            <input type="password" class="form-control" id="changepwd" name='changepwd' placeholder='Enter Password'>
                                                            <input type="hidden" class="form-control" name='userid' value="<?= $userid; ?>">
                                                        </div>
                                                        <div class='mt-2'>
                                                            <button class='btn btn-primary btn-sm fw-bold' name='changepwdbtn'>Submit</button>
                                                        </div>

                                                    </form>

                                                </div>
                                                <div class="modal-footer">
                                                    <a type="button" class="btn btn-secondary btn-sm fw-bold" href='viewusers.php'>Close</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>

                                <!-- Change User Password Form Ends Here-->




                            </table>
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