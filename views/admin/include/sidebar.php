<?php
include_once('include/activestate.php');
// find out the path to the current file:
 $url = $_SERVER['SCRIPT_NAME'];
 // display intials of user  - muradcade will be displayed as mc
function getSmartInitials($fullname) {
    $fullname = trim($fullname);

    // Case 1: If name has space
    if (strpos($fullname, ' ') !== false) {
        $words = explode(" ", $fullname);
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }
        return $initials;
    }

    // Case 2: One word, try to get uppercase pattern (like camelCase or PascalCase)
    preg_match_all('/[A-Z]/', $fullname, $matches);
    if (count($matches[0]) >= 2) {
        return strtoupper($matches[0][0] . $matches[0][1]);
    }

    // Case 3: Fallback → get first and middle character
    return strtoupper($fullname[0] . $fullname[1]);
}


 ?>
 <!-- Sidebar -->
 <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="overflow-y:auto;">
               
                <div class="position-sticky">
                              <img  class="mb-2 mt-2" width="180" src="data:image/png;base64,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"/>
                              <button id="closeSidebar" type="button" class="btn btn-secondary position-absolute top-0 end-0 mt-2 me-2" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
                
                <div class="d-flex align-items-center bg-white p-2 shadow-sm rounded-2" style="border: 1px solid #f9f9f9;">
                        <!-- Avatar -->
                        <div class="rounded-circle bg-primary border text-white fw-bold d-flex justify-content-center align-items-center me-3 "
                            style="width: 48px; height: 48px; font-size: 18px;">
                            <?php
                            // $intials =  getSmartInitials($_SESSION['fullname']);
                            $initials = strtoupper(substr(getSmartInitials(htmlspecialchars($_SESSION['fullname'])), 0, 2));
                            echo $initials;
                            ?>
                        </div>

                        <!-- Name and Title -->
                        <div class="d-flex flex-column">
                            <p class="mb-0 text-truncate fw-bold" style="max-width: 100px;">
                            <!-- display user intails-->
                            <?php echo htmlspecialchars($_SESSION['fullname']); ?>
                            </p>
                            <small class="text-muted fw-medium">Admin</small>
                        </div>
                        </div>
                    <ul class="nav flex-column">
                        <li class="nav-item mt-1 mt-3">
                            <a class="nav-link <?php checkactivesidebar($url,'admin'); checkactivesidebar($url,'index.php')?> fw-bold" href="index.php" style='font-size:15px;'>
                                <i class="bi bi-house-door me-2"></i> Home
                            </a>
                        </li>
                        <li class="nav-item dropdown mt-3">
                            <a class="nav-link dropdown-toggle  <?php checkactivesidebar($url,'viewusers.php'); checkactivesidebar($url,'userdetails.php'); ?> fw-bold" href="#" id="course" data-bs-toggle="dropdown" aria-expanded="false" style='font-size:15px;'>
                                <i class="bi bi-book me-1"></i> Users
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="course">
                                <li style="font-size:14px;"><a class="dropdown-item <?php checkactivesidebar($url,'viewusers.php');?>  fw-bold" href="viewusers.php">View Users</a></li>
                                <li style="font-size:14px;"><a class="dropdown-item <?php checkactivesidebar($url,'userdetails.php');?>  fw-bold" href="userdetails.php">User Details</a></li>
                                <!-- <li><a class="dropdown-item" href="#">Reports</a></li> -->
                            </ul>
                            
                        </li>
                        <li class="nav-item dropdown mt-3">
                            <a class="nav-link dropdown-toggle  <?php checkactivesidebar($url,'manage_subscription.php');  checkactivesidebar($url,'subscription_orders.php');?> fw-bold" href="#" id="dropdownAnalytics" data-bs-toggle="dropdown" aria-expanded="false" style='font-size:15px;'>
                                <i class="bi bi-person me-1"></i> Subscription
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownAnalytics">
                                <li style="font-size:14px;"><a class="dropdown-item <?php checkactivesidebar($url,'manage_subscription.php');?> fw-bold" href="manage_subscription.php">Change Days Left</a></li>
                                <li style="font-size:14px;"><a class="dropdown-item <?php checkactivesidebar($url,'subscription_orders.php');?> fw-bold" href="subscription_orders.php">Subscription Orders</a></li>
                                <!-- <li><a class="dropdown-item" href="#">Reports</a></li> -->
                            </ul>
                        </li>
                        
                        
                        
                       
                        
                        <!-- <li class="nav-item mt-3">
                            <a class="nav-link text-secondary" href="#" style='font-size:15px;'>
                            <i class="bi bi-clipboard-data"></i>  Generate Report
                            </a>
                        </li> -->
                        <!-- <li class="nav-item mt-3">
                            <a class="nav-link text-secondary" href="#" style='font-size:15px;'>
                            <i class="bi bi-coin"></i> Subscription
                            </a>
                        </li> -->
                    
                
                <!-- subscription-->


  <!-- subscription-->

                <div class="position-sticky  mt-2">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle  text-secondary fw-bold" href="#" id="dropdownAccount" data-bs-toggle="dropdown" aria-expanded="false" style='font-size:15px;'>
                            <i class="bi bi-person-circle me-1"></i> Account
                        </a>
                        <ul class="dropdown-menu " aria-labelledby="dropdownAccount">
                            <li style='font-size:14px;'><a class="dropdown-item text-secondary" href="../../include/logout.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
                </ul>
                <div>
            </nav>
