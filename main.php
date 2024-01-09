<?php

include('config.php');
session_start();

if(isset($_SESSION['user_id'])) {
    
    $user_id = $_SESSION['user_id'];
    
    $resultUser = mysqli_query($conn, "SELECT * FROM `customer` WHERE cust_id = '$user_id'") or die('query failed');

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vtech - home</title>
    <link rel="icon" href="icon/icon.png">
    <link rel="stylesheet" href="main-style.css?v=<?php echo time(); ?>">
</head>

<body id="body">
    <div id="preloader"></div>
    <header>
            
        <!--Search Box -->
        <div class="search-container" id="scontain">
            <form action="" id="searchForm" autocomplete="off" >
                <div class="search-box">
                    <input type="search" class="search" placeholder="Search" id="searchInput">
                    <img src="icon/icons8-close-100(1).png" id="close" alt="" onclick="toggleSearch()">
                </div>
            </form>

            <div class="search-result" id="sresult">
                <div id="no-input">
                    <h2>Search any iPhone you'll like, We'll wait.</h2>
                </div>
                <div class="result" id="result" ></div>
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav id="nav-bar">
            <div class="white-slide-down" id="wslide"> </div>
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
                <a class="search-logo" onclick="toggleSearch()"><img src="icon/icons8-search-96 (1).png" alt=""><img
                        src="icon/icons8-search-96.png" alt="" style="position: relative; right: 36px;"></a>
            <?php
            if (isset($_SESSION['user_id'])) {
                $countcart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = $user_id");
                if (mysqli_num_rows($countcart) > 0) {
                    echo '
                    <a onclick="viewCart()" style="cursor: pointer;"><img src="icon/icons8-cart-96 (3).png" alt=""><img
                            src="icon/icons8-cart-96 (2).png" alt="" style="position: relative; right: 36px;"></a>';
                }else{
                    echo '
                    <a onclick="viewCart()" style="cursor: pointer;"><img src="icon/icons8-cart-96 (1).png" alt=""><img
                            src="icon/icons8-cart-96.png" alt="" style="position: relative; right: 36px;"></a>';
                }
            }else{
                echo '
                <a onclick="viewCart()" style="cursor: pointer;"><img src="icon/icons8-cart-96 (1).png" alt=""><img
                        src="icon/icons8-cart-96.png" alt="" style="position: relative; right: 36px;"></a>';
            }
            ?>
                <a onclick="viewProfile()" style="cursor: pointer;"><img src="icon/icons8-account-96.png" alt=""><img
                        src="icon/icons8-account-96 (1).png" alt="" style="position: relative; right: 36px;"></a>
                
            </div>

        </nav>
        <div class="profile-box" id="prbox">
            <?php if(!isset($user_id)){
                echo '<h4><span style="color: red; font-size: 1.25em"></span></h4>';}
            else {
                echo '<h4><span style="color: darkslateblue; font-size: 1.25em">username: '.$_SESSION['user_name'].'</span></h4>';
            }
                    
            ?>
            <?php
            if (!isset($user_id)) {
                echo '<a href="login.php" class="view-profile">Login</a>';
            }else{
                $row = $resultUser->fetch_assoc();
                if ($row['user_type'] == 'user') {
                    echo '
                    <a href="profile.php" class="view-profile">View Profile</a>
                    <a href="logout.php" class="logout" style="color:tomato;" >Logout</a>';
                }else if($row['user_type'] == 'admin'){
                    echo '
                    <a href="admin.php" class="view-profile">View Profile</a>
                    <a href="logout.php" class="logout" style="color:tomato;" >Logout</a>';
                    
                }}
            ?>
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
        </div>

        <div class="view-blur" id="viewBlur">
            <div class="cart-sidebar" id="cart-sbar">
                <h2>Cart</h2>
                <div class="cart-item">
                    <?php
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
                    ?>
                </div>
                <div class="cart-link">
                    <a href="bag.php">View Cart</a>
                </div>
            </div>
        </div>

    </header>


    <main>
        <div class="banner">
            <div class="banner1">
                <div class="banner-1-title">
                    <p>Our Newest Arrival</p>
                    <h1>iPhone 14 Pro Max</h1>
                </div>

                <div class="banner-1-desc">
                    <p>Titanium. So strong. So light. So Pro. </p>
                    <a href="device-page.php?id=21"> shop now</a>
                </div>
            </div>
        </div>
        <div class="gurantee">

            <div>
                <img src="icon/icons8-box-128.png" alt="">
                <h3>Up To 12 Month Waranty</h3>
            </div>
            <div>
                <img src="icon/icons8-delivery-96.png" alt="">
                <h3>Free & Secure Delivery</h3>
            </div>
            <div>
                <img src="icon/icons8-euro-money-100.png" alt="">
                <h3>Up To 50% In Savings</h3>
            </div>

        </div>

        <div class="iPhone-promo" id="iPhoneX">
            <video autoplay muted loop id="iphoneXpromo">
                <source src="video/iPhone X Trailer Oficial.mp4" type="video/mp4">
            </video>
            <div class="iPhone-promo-desc">
                <h2>iPhone X</h2>
                <p>As low as RM799</p>
                <a href="view-device.php?series=iPhoneX">browse now</a>
            </div>
        </div>
        <hr style="margin: 30px 0;">
        <div class="iPhone-promo" id="iPhone11">
            <video autoplay muted loop id="iphoneXpromo">
                <source src="video/Iphone 11 Trailer.mp4" type="video/mp4">
            </video>
            <!--<img src="image/iphone-11 Promo.jpg" alt="">-->
            <div class="iPhone-promo-desc">
                <h2>iPhone 11</h2>
                <p>As low as RM1,199</p>
                <a href="view-device.php?series=iPhone11">browse now</a><br>
            </div>
        </div>
        <div class="view-all-title">
            <h3 class="all-cate">Devices</h3>
            <a href="view-device.php" class="view-more">view more</a>
        </div>

        <div class="view-all">
            <div class="arrow" id="arrow-left"><img width="50" height="50"
                    src="https://img.icons8.com/ios/50/left--v1.png" alt="back" /></div>
            <div class="arrow" id="arrow-right"><img width="50" height="50"
                    src="https://img.icons8.com/ios/50/right--v1.png" alt="forward--v1" /></div>
            
            <?php
            $selectDevice = mysqli_query($conn, "SELECT * FROM `device` ORDER BY deviceID DESC LIMIT 9");
            while ($row = $selectDevice -> fetch_assoc()) {
                echo'
                <div class="iPhone-box">
                    <div class="preview"><img src="uploaded_img/'. $row['image'] .'" alt=""></div>
                    <h2>'. $row['Name'] .'</h2>
                    <p>From RM'. $row['price'] .'.00</p>
                    <a href="device-page.php?id='. $row['deviceID'].'"">buy now ></a>
                </div>';
            }
            ?>
        </div>

        <div class="assurance">
            <div>
                <h2>Why Choose Second-Hand iPhone?</h2>
                <p>Well, why not? Not only by this you will save money but you will also save the environment, make the
                    world greener even. Won't you want that? Won't you?? Don't you love earth? Don't you want
                    to save it? Its your only planet after all. <br> <br>That's right, we guilting you. Buy from us and
                    the world
                    will be greener, I promise. Even better you will get <b>VTech guarantee!</b></p>
            </div>
            <div><img src="image/iphones IndyBest.jpg" alt=""></div>
        </div>
    </main>

    <footer>
        <div class="ftup">
            <div class="aboutft">
                <h4>About us</h4>
                <a href="about.php" class="ftlink">About VTech</a>
            </div>
            <div class="csft">
                <h4>Customer Service</h4>
                <p>Email: support@vtech.com</p>
            </div>
            <div class="contactft">
                <h4>Contact Us</h4>
                <a href=""><img src="icon/icons8-whatsapp-100.png" alt=""><img src="" alt=""></a>

            </div>
            <div class="flwft">
                <h4>Follow Us</h4>
                <a href=""><img src="icon/icons8-twitter-100.png" alt=""></a>
                <a href=""><img src="icon/icons8-instagram-100.png" alt=""></a>
            </div>

        </div>
        <hr>
        <p class="copyright">Copyright 2023 - VTech Studio</p>



    </footer>

    <script src="script.js"></script>
    <!-- js script for horizontal scroll button -->
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            // Get the arrow element and the view-all container
            const arrowLeft = document.getElementById('arrow-left');
            const arrowRight = document.getElementById('arrow-right');
            const viewAllContainer = document.querySelector('.view-all');
            arrowRight.addEventListener('click', function () {
                const currentScroll = viewAllContainer.scrollLeft;
                const targetScroll = currentScroll + 700;
                const duration = 500;
                const startTime = performance.now();

                function scrollAnimation(currentTime) {
                    const elapsedTime = currentTime - startTime;
                    const easedScroll = easeInOutQuad(elapsedTime, currentScroll, targetScroll - currentScroll, duration);

                    viewAllContainer.scrollLeft = easedScroll;

                    if (elapsedTime < duration) {
                        requestAnimationFrame(scrollAnimation);
                    }
                }
                arrowLeft.style.visibility = 'visible';

                if (currentScroll > 700) {
                    arrowRight.style.visibility = 'hidden';
                }

                requestAnimationFrame(scrollAnimation);
            })

            arrowLeft.addEventListener('click', function () {
                const currentScroll = viewAllContainer.scrollLeft;
                const targetScroll = currentScroll - 700;
                const duration = 500;
                const startTime = performance.now();

                function scrollAnimation(currentTime) {
                    const elapsedTime = currentTime - startTime;
                    const easedScroll = easeInOutQuad(elapsedTime, currentScroll, targetScroll - currentScroll, duration);

                    viewAllContainer.scrollLeft = easedScroll;

                    if (elapsedTime < duration) {
                        requestAnimationFrame(scrollAnimation);
                    }
                }
                if (currentScroll <= 500) {
                    arrowLeft.style.visibility = 'hidden';
                }
                if (currentScroll < 1200) {
                    arrowRight.style.visibility = 'visible';
                }

                requestAnimationFrame(scrollAnimation);
            });
            function easeInOutQuad(t, b, c, d) {
                t /= d / 2;
                if (t < 1) return c / 2 * t * t + b;
                t--;
                return -c / 2 * (t * (t - 2) - 1) + b;
            }
        });

    </script>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#searchInput').on('input', function() {
            var query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: 'search.php', // Update the path to your PHP file
                    type: 'POST',
                    data: { query: query },
                    success: function(response) {
                        $('#result').html(response); // Update the ID or class of the result container
                    }
                });
            } else {
                $('#result').html(''); // Clear the result container if the input is empty
            }
        });
    });
    </script>
</body>

</html>