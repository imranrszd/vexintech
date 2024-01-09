<?php
include('config.php');

if(isset($_SESSION['user_id'])) {
    
    $user_id = $_SESSION['user_id'];
    
    $resultUser = mysqli_query($conn, "SELECT * FROM `customer` WHERE cust_id = '$user_id'") or die('query failed');
    
}

if(isset($message)){
    foreach($message as $message){
       echo '
          <div class="message">
             <span>' .$message. '</span>
             <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
          </div>
       ';
    }
 }

echo '
        <!-- Search Box -->
        <div class="search-container" id="scontain">
            <form action="" id="searchForm" autocomplete="off" >
                <div class="search-box">
                    <input type="search" class="search" placeholder="Search" id="searchInput">
                    <img src="icon/icons8-close-100(1).png" id="close" alt="" onclick="toggleSearch()">
                </div>
            </form>

            <div class="search-result" id="sresult">
                <div id="no-input">
                    <h2>Search any iPhone you\'ll like, We\'ll wait.</h2>
                </div>
                <div class="result" id="result" ></div>
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav id="nav-bar">
            <!-- Home -->
            <div class="header-dummy">
                <a href="main.php" class="logo" style="text-decoration: none;" ><img src="icon/icon.png" alt="" style="height: 55px;" ><span>VTech</span></a>
            </div>

            <!-- Device Category -->
            <div class="center">
                <div class="view-iPhone" onclick="viewiPhone()">
                    View iPhone
                </div>
            </div>

            <!-- Right Icon -->
            <div class="right">
                <a class="search-logo" onclick="toggleSearch()"><img src="icon/icons8-search-96.png" alt=""
                        style="position: relative; right: 36px;"></a>';
                        
                    if (isset($_SESSION['user_id'])) {
                        $countcart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = $user_id");
                        if (mysqli_num_rows($countcart) > 0) {
                            echo '
                            <a onclick="viewCart()" style="cursor: pointer;"><img src="icon/icons8-cart-96 (2).png" alt="" style="position: relative; right: 36px;"></a>';
                        }else{
                            echo '
                            <a onclick="viewCart()" style="cursor: pointer;"><img src="icon/icons8-cart-96.png" alt="" style="position: relative; right: 36px;"></a>';
                        }
                    }else {
                        echo '
                        <a onclick="viewCart()" style="cursor: pointer;"><img src="icon/icons8-cart-96.png" alt="" style="position: relative; right: 36px;"></a>';
                    }
                    
                        if (isset($_SESSION['user_id'])) {
                            $row = $resultUser->fetch_assoc();
    
                            if ($row['user_type'] == 'user') {
                                echo '
                                <a onclick="viewProfile()" style="cursor: pointer;"><img
                                        src="icon/icons8-account-96 (1).png" alt="" style="position: relative; right: 36px;"></a>';
                            }else if($row['user_type'] == 'admin'){
                                echo '
                                <a onclick="viewProfile()" style="cursor: pointer;"><img
                                        src="icon/icons8-account-96 (1).png" alt="" style="position: relative; right: 36px;"></a>';
                                
                            }
                            mysqli_data_seek($resultUser,0);
                        }else{
                            echo '
                            <a onclick="viewProfile()" style="cursor: pointer;"><img
                                    src="icon/icons8-account-96 (1).png" alt="" style="position: relative; right: 36px;"></a>';

                        }
            echo '
            </div>

        </nav>

        <div class="profile-box" id="prbox">';
        if (isset($_SESSION['user_id'])) {
            echo '<h4><span style="color: darkslateblue; font-size: 1.25em">username: '.$_SESSION['user_name'].'</span></h4>';
            
                $row = $resultUser->fetch_assoc();
                    if ($row['user_type'] == 'user') {
                        echo '
                        <a href="profile.php" class="view-profile">View Profile</a>
                        <a href="logout.php" class="logout" style="color: tomato;">Logout</a>';
                    }else if($row['user_type'] == 'admin'){
                        echo '
                        <a href="admin.php" class="view-profile">View Profile</a>
                        <a href="logout.php" class="logout" style="color: tomato;">Logout</a>';
                        
                    }
            }else {
                echo '
                <a href="login.php" class="view-profile">Login</a>';
            }
            echo '
        </div>

        <div class="view-blur" id="viewBlur" onclick="viewiPhone()">
            <div class="iPhone-container" id="view">
                <a href="view-device.php">View All</a>
                <a href="view-device.php?series=iPhone14">iPhone 14</a>
                <a href="view-device.php?series=iPhone13">iPhone 13</a>
                <a href="view-device.php?series=iPhone12">iPhone 12</a>
                <a href="view-device.php?series=iPhone11">iPhone 11</a>
                <a href="view-device.php?series=iPhoneX">iPhone X</a>
            </div>
        </div>';

        echo'
        <div class="view-blur" id="viewBlur">
        <div class="cart-sidebar" id="cart-sbar">
        <h2>Cart</h2>
        <div class="cart-item">';
            if (isset($user_id)) {
                $cartsb = mysqli_query($conn, "SELECT * FROM `cart` 
                WHERE user_id = '$user_id'") or die('query failed');

                if(mysqli_num_rows($cartsb) > 0){
                    while ($row = $cartsb->fetch_assoc()) {
                        echo 
                        '<a href="device-page.php?id='. $row['device_id'].'" class="item">
                            <img src="'. $row['image'] .'" alt="" style="height: 100px;" >
                            <div class="item-desc">
                                <h4>'. $row['name'] .'</h4>
                                <p>Color: '. $row['color'] .'</p>
                            </div>
                            <h3 class="item-price">RM '. $row['price'] .'.00</h3>
                        </a>';
                    }
                    mysqli_data_seek($cartsb,0);
                }else{
                    echo '<h4>There\'s nothing in here</h4>';
                }
            }else {
                echo '<h4>There\'s nothing in here</h4>';
            }
        echo'</div>
        <div class="cart-link">
            <a href="bag.php">View Cart</a>
        </div>
    </div>
        </div>';

?>