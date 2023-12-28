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
    <title>VTech - device</title>
    <link rel="icon" href="icon/icon.png">
    <link rel="stylesheet" href="device-page-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="nav-footer-style.css?v=<?php echo time(); ?>">
</head>

<body>
    <!--<div id="preloader"></div>-->

    <header>
        <?php include("header.php"); ?>
    </header>

    <main>
        <div class="big-title">All Series</div>

        </div>
        <div class="iPhone" id="iPhone14">
            <h1>iPhone 14 Series</h1>
            <div class="device-box">
            <?php
                    $querySelectName = 'SELECT * FROM device WHERE Name REGEXP BINARY "14"';
                    $result = mysqli_query($conn, $querySelectName) OR die('Query Failed');
                    foreach($result as $row){
                        include 'connection.php';
                        echo '<a href="device-page.php?id='. $row['deviceID'].'" class="X-box" style="text-decoration: none;">
                        <div class="preview">
                            <img src="uploaded_img/'. $row['image'] .'" alt="">
                            <div class="view-box">View</div>
                        </div>
                        
                        <div class="color-preview">';  
                        $queryColor = "SELECT DeviceColor FROM deviceattributes WHERE DeviceID =". $row['deviceID'];
                        $resultColor = mysqli_query($conn, $queryColor);
                            while($rowColor = $resultColor -> fetch_assoc()){
                                echo '<div style="background-color: '. $rowColor['DeviceColor'] .';">a</div>';
                            }  
                
                        echo '</div>
                        <h2>'. $row['Name'] . '</h2>
                        <p> RM '. $row['price'] . '</p>
                        </a>';
                    }
                ?>
            </div>
        </div>

        <div class="iPhone" id="iPhone13">
            <h1>iPhone 13 Series</h1>
            <div class="device-box">
            <?php
                    $querySelectName = 'SELECT * FROM device WHERE Name REGEXP BINARY "13"';
                    $result = mysqli_query($conn, $querySelectName) OR die('Query Failed');
                    foreach($result as $row){
                        include 'connection.php';
                        echo '<a href="device-page.php?id='. $row['deviceID'].'" class="X-box" style="text-decoration: none;">
                        <div class="preview">
                            <img src="uploaded_img/'. $row['image'] .'" alt="">
                            <div class="view-box">View</div>
                        </div>
                        
                        <div class="color-preview">';  
                        $queryColor = "SELECT DeviceColor FROM deviceattributes WHERE DeviceID =". $row['deviceID'];
                        $resultColor = mysqli_query($conn, $queryColor);
                            while($rowColor = $resultColor -> fetch_assoc()){
                                echo '<div style="background-color: '. $rowColor['DeviceColor'] .';">a</div>';
                            }  
                
                        echo '</div>
                        <h2>'. $row['Name'] . '</h2>
                        <p> RM '. $row['price'] . '</p>
                        </a>';
                    }
                ?>
            </div>
        </div>

        <div class="iPhone" id="iPhone12">
            <h1>iPhone 12 Series</h1>
            <div class="device-box">
            <?php
                    $querySelectName = 'SELECT * FROM device WHERE Name REGEXP BINARY "12"';
                    $result = mysqli_query($conn, $querySelectName) OR die('Query Failed');
                    foreach($result as $row){
                        include 'connection.php';
                        echo '<a href="device-page.php?id='. $row['deviceID'].'" class="X-box" style="text-decoration: none;">
                        <div class="preview">
                            <img src="uploaded_img/'. $row['image'] .'" alt="">
                            <div class="view-box">View</div>
                        </div>
                        
                        <div class="color-preview">';  
                        $queryColor = "SELECT DeviceColor FROM deviceattributes WHERE DeviceID =". $row['deviceID'];
                        $resultColor = mysqli_query($conn, $queryColor);
                            while($rowColor = $resultColor -> fetch_assoc()){
                                echo '<div style="background-color: '. $rowColor['DeviceColor'] .';">a</div>';
                            }  
                
                        echo '</div>
                        <h2>'. $row['Name'] . '</h2>
                        <p> RM '. $row['price'] . '</p>
                        </a>';
                    }
                ?>
            </div>
        </div>

        <div class="iPhone" id="iPhone11">
            <h1>iPhone 11 Series</h1>
            <div class="device-box">
            <?php
                    $querySelectName = 'SELECT * FROM device WHERE Name REGEXP BINARY "11"';
                    $result = mysqli_query($conn, $querySelectName) OR die('Query Failed');
                    foreach($result as $row){
                        include 'connection.php';
                        echo '<a href="device-page.php?id='. $row['deviceID'].'" class="X-box" style="text-decoration: none;">
                        <div class="preview">
                            <img src="uploaded_img/'. $row['image'] .'" alt="">
                            <div class="view-box">View</div>
                        </div>
                        
                        <div class="color-preview">';  
                        $queryColor = "SELECT DeviceColor FROM deviceattributes WHERE DeviceID =". $row['deviceID'];
                        $resultColor = mysqli_query($conn, $queryColor);
                            while($rowColor = $resultColor -> fetch_assoc()){
                                echo '<div style="background-color: '. $rowColor['DeviceColor'] .';">a</div>';
                            }  
                
                        echo '</div>
                        <h2>'. $row['Name'] . '</h2>
                        <p> RM '. $row['price'] . '</p>
                        </a>';
                    }
                ?>
            </div>
        </div>

        <div class="iPhone" id="iPhoneX">
            <h1>iPhone X Series</h1>
            <div class="device-box">
                <?php
                    $querySelectName = 'SELECT * FROM device WHERE Name REGEXP BINARY "X"';
                    $result = mysqli_query($conn, $querySelectName) OR die('Query Failed');
                    foreach($result as $row){
                        include 'connection.php';
                        echo '<a href="device-page.php?id='. $row['deviceID'].'" class="X-box" style="text-decoration: none;">
                        <div class="preview">
                            <img src="uploaded_img/'. $row['image'] .'" alt="">
                            <div class="view-box">View</div>
                        </div>
                        
                        <div class="color-preview">';  
                        $queryColor = "SELECT DeviceColor FROM deviceattributes WHERE DeviceID =". $row['deviceID'];
                        $resultColor = mysqli_query($conn, $queryColor);
                            while($rowColor = $resultColor -> fetch_assoc()){
                                echo '<div style="background-color: '. $rowColor['DeviceColor'] .';">a</div>';
                            }  
                
                        echo '</div>
                        <h2>'. $row['Name'] . '</h2>
                        <p> RM '. $row['price'] . '</p>
                        </a>';
                    }
                ?>
            </div>
        </div>
    </main>
    <footer>
        <div class="ftup">
            <div class="aboutft">
                <h4>About us</h4>
                <a href="about.php" class="ftlink">about VTech</a>
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
    <script src="script2.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Parse the query parameter to get the selected series
            const urlParams = new URLSearchParams(window.location.search);
            const selectedSeries = urlParams.get('series');

            // Get the corresponding iPhone section
            let selectedSection = document.getElementById(selectedSeries);

            // Display only the selected iPhone section
            if (selectedSection) {
                showOnlyiPhone(selectedSection);
            }
        });

        let iPhone14 = document.getElementById('iPhone14');
        let iPhone13 = document.getElementById('iPhone13');
        let iPhone12 = document.getElementById('iPhone12');
        let iPhone11 = document.getElementById('iPhone11');
        let iPhoneX = document.getElementById('iPhoneX');
        let bigTitle = document.querySelector('.big-title');

        function showOnlyiPhone(section) {
            // Set all iPhone sections to be initially hidden
            iPhone14.style.display = 'none';
            iPhone13.style.display = 'none';
            iPhone12.style.display = 'none';
            iPhone11.style.display = 'none';
            iPhoneX.style.display = 'none';
            bigTitle.style.display = 'none'

            // Display only the selected iPhone section
            section.style.display = 'block';

            // Move the selected section to the top of the page
            section.parentNode.insertBefore(section, section.parentNode.firstChild);

            // Move the box-title to the top
            boxTitle.parentNode.insertBefore(boxTitle, boxTitle.parentNode.firstChild);
        }
    </script>
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