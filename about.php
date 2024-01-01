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
    <title>Vtech - about</title>
    <link rel="icon" href="icon/icon.png">
    <link rel="stylesheet" href="about-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="nav-footer-style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div id="preloader"></div>
    <header>
        <?php include 'header.php'; ?>
    </header>
    <main>
        <div class="intro">
            <h1>ABOUT US</h1>
            <p> VTech Studio specializes in selling iPhones X and beyond. VTech Studio didn't just resell iPhones; they
                made dreams come true, connecting people to the cutting-edge technology of Apple's mobile devices. They
                took the complexity out of buying an iPhone and made it a delightful experience. <br><br>And so, VTech
                Studio's story was one of passion, technology, and making life better one iPhone at a time. They
                continued to be the go-to place for those who wanted the best from Apple's iPhone lineup.
            </p>
        </div>
        <div class="vis-and-mis">
            <div class="vision">
                <h2>VISION</h2>
                <p>To be the premier destination for individuals seeking reliable, sustainable, and affordable pre-owned
                    iPhones.</p>
            </div>
            <div class="mission">
                <h2>MISSION</h2>
                <p>Improving connectivity while redefining value through a focus on quality and affordability.</p>

            </div>
        </div>
    </main>
    <footer>
        <div class="ftup">
            <div class="aboutft">
                <h4>About us</h4>
                <a href="about.html" class="ftlink">about VTech</a>
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