<?php
    include 'config.php';
    session_start();

    $deviceID = $_GET['id'];

    $sql = "SELECT * From device WHERE DeviceID = '$deviceID'";
    $result = mysqli_query($conn, $sql) OR die('Query Failed');

    $sqlMulti = "SELECT * FROM deviceattributes WHERE DeviceID = '$deviceID'";
    $resultMulti = mysqli_query($conn, $sqlMulti) OR die('Query Failed');

    if(isset($_SESSION['user_id'])) {
        
        $user_id = $_SESSION['user_id'];

        if(isset($_POST['add_to_cart'])){

            if (isset($_SESSION['user_id'])) {
                $product_name = $_POST['product_name'];
                $product_price = $_POST['product_price'];
                $product_color = $_POST['product_color'];
                $product_capacity = $_POST['product_capacity'];
                $plus_capacity = 0;
                if ($product_capacity == '64GB') {
                 $plus_capacity = 0;
                }else if ($product_capacity == '128GB') {
                 $plus_capacity = 30;
                }else if ($product_capacity == '256GB') {
                 $plus_capacity = 50;
                }
             
                $product_warranty = $_POST['waranty'];
                $plus_warranty = 0;
                if ($product_warranty == '6') {
                 $plus_warranty = 0;
                }else if ($product_warranty == '12') {
                 $plus_warranty = 20;
                }
             
                $product_price += $plus_capacity + $plus_warranty;
                
                $resultImage = mysqli_query($conn, "SELECT * From deviceattributes WHERE deviceID = '$deviceID'");
             
                $row = $resultImage->fetch_assoc();
                $product_image = $row['ImageColor'];
             
             
                $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` 
                WHERE name = '$product_name' and user_id = '$user_id'") or die('query failed');
             
                if(mysqli_num_rows($check_cart_numbers) > 0){
                   $message[] = 'already added to cart!';
                }else{
                   mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, quantity, image, color, capacity, waranty, device_id)
                   VALUES ('$user_id', '$product_name', '$product_price', 
                   '1', '$product_image', '$product_color', '$product_capacity', '$product_warranty','$deviceID')") or die('query failed');
                   $message[] = 'product added to cart!';
                }
            }
        
        }
    }

    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $row = $result->fetch_assoc();
    echo '<title>VTech - '. $row['Name'] .'</title>';
    mysqli_data_seek($result,0);
    ?>
    <link rel="icon" href="icon/icon.png">
    <link rel="stylesheet" href="device-page-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="nav-footer-style.css?v=<?php echo time(); ?>">
</head>

<style>
<?php
foreach($resultMulti as $row){
    echo "#". $row["DeviceColor"] ."[type='radio']::before {
        background-color: ". $row["DeviceColor"] .";
        }";
    }
mysqli_data_seek($resultMulti, 0);
 ?>
</style>

<body>
    <div id="preloader"></div>
    <header>
    <?php
    include 'header.php';
    ?>
    </header>

    <main>
        <div class="device-preview">
            <div class="image">
            <?php
            $row = $resultMulti->fetch_assoc();
            echo '<img src="'. $row['ImageColor'] .'" alt="" id="img'. $row['DeviceColor'] .'" class="color-image" style="visibility: visible; opacity: 1">';

            while($row = $resultMulti->fetch_assoc()){
                echo '<img src="'. $row['ImageColor'] .'" alt="" id="img'. $row['DeviceColor'] .'" class="color-image">';
            }
            mysqli_data_seek($resultMulti,0);
            ?>
            </div>

            <div class="description">
                <form action="" method="post" style="width: 100%;">
                    <?php
                    $row = $result->fetch_assoc();
                    echo '<h1 id="title">'. $row['Name'] .'</h1>';
                    mysqli_data_seek($result,0);
                    ?>
                    <div class="color-text">
                        <?php
                        $row = $resultMulti->fetch_assoc();
                        echo '<div id="text'. $row['DeviceColor'] .'" class="color-text" style="visibility: visible;">
                                <p>Color - ' . $row['DeviceColorText'] . '</p>
                            </div>';

                        while ($row = $resultMulti->fetch_assoc()) {
                            echo '<div id="text'. $row['DeviceColor'] .'" class="color-text">
                            <p>Color - ' . $row['DeviceColorText'] . '</p>
                            </div>';
                        }
                        mysqli_data_seek($resultMulti,0);
                        ?>
                    </div>
                    <br><br>
                    <div class="color-box">
                    <?php
                    $row = $resultMulti->fetch_assoc();
                    echo '<input type="radio" name="product_color" value="'. $row['DeviceColorText'] .'" onclick="visibility(\''. $row['DeviceColor'] .'\')"id="'. $row['DeviceColor'] .'" checked="checked" style="visibility: visible;">
                    <span></span> <!--separartion-->';

                    while($row = $resultMulti->fetch_assoc()){
                        echo '<input type="radio" name="product_color" value="'. $row['DeviceColorText'] .'" onclick="visibility(\''. $row['DeviceColor'] .'\')" id="'. $row['DeviceColor'] .'" style="visibility: visible;">
                        <span></span> <!--separartion-->';
                    }

                    mysqli_data_seek($resultMulti,0);
                    ?>
                    </div>
                    <br>
                    <p>Capacity: <span id="plusCapacityText" style="font-size: 18px;"></span></p>
                    <div class="spec">
                        <?php
                            $row = $resultMulti->fetch_assoc();
                            echo '<label class="capacity">
                            <input type="radio" name="product_capacity" value="'. $row['DeviceCapacity'] .'" id="'. $row['DeviceCapacity'] .'" onclick="calculate()" checked = checked >
                            '. $row['DeviceCapacity'] .'
                            </label>
                            <span class="divider"></span>';

                            while ($row = $resultMulti->fetch_assoc()) {
                                if ($row['DeviceCapacity'] != null) {
                                    echo '<label class="capacity">
                                    <input type="radio" name="product_capacity" value="'. $row['DeviceCapacity'] .'" id="'. $row['DeviceCapacity'] .'" onclick="calculate()">
                                    '. $row['DeviceCapacity'] .'
                                    </label>
                                    <span class="divider"></span>';
                                }
                            }
                            mysqli_data_seek($resultMulti,0);
                        ?>
                        <div class="dummy-spec" style="position: absolute; visibility: collapse; " >
                        <input type="radio" value="" id="64GB">
                        <input type="radio" value="" id="128GB">
                        <input type="radio" value="" id="256GB">
                        <input type="radio" value="" id="512GB">
                        </div>
                    </div>
                    <p>Warranty: <span id="plusWarantyText" style="font-size: 18px;"></span></p>
                    <div class="spec">
                        <label class="waranty">
                            <input type="radio" name="waranty" id="6" value="6" onclick="calculate()" checked = checked>
                            6 Months
                        </label>
                        <span class="divider"></span>
                        <label class="waranty">
                            <input type="radio" name="waranty" id="12" value="12" onclick="calculate()">
                            12 Months
                        </label>
                    </div>
                    <div class="total">
                        <?php
                        $row = $result->fetch_assoc();
                        echo '
                        <p id="output">Total: RM'. $row['price'] .'.00</p>
                        <input type="hidden" name="product_price" value="'.$row['price'].'">
                        ';

                        mysqli_data_seek($result,0);                        
                        ?>
                    </div>
                    <input type="hidden" name="product_name" value="<?php echo $row['Name']; ?>">
                    <?php $row = $resultMulti->fetch_assoc(); ?>
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '<button type="submit" name="add_to_cart" class="bag">Add to Bag</button>';
                    }else{
                        echo '<a href="login.php" class="bag">Add to Bag</a>';
                    }
                    ?>
                </form>
            </div>
        </div>
        <div class="spline-preview">
            <h2>3D Preview</h2>
            <?php
            $row = $result->fetch_assoc();
            echo'
            <iframe id="splineIframe" src="'. $row['spline_url'] .'" frameborder="0" width="70%" ></iframe>';
            mysqli_data_seek($result,0);
            ?>
        </div>
        <div class="spec-desc">
            <hr>
            <h1>Specification</h1>
            <div class="desc">
                <div class="body">
                    <h2>Body</h2>
                    <?php
                    $row = $result->fetch_assoc();
                    echo '<p><b>Dimensions:</b> '. $row['dimension'] .'<br>
                        <b>Weight:</b> '. $row['weight'] .'<br>
                        <b>Build:</b> Glass front (Gorilla Glass), glass back (Gorilla Glass), stainless steel frame
                        <br>
                        <b>SIM:</b> '. $row['sim'] .'
                        </p>';
                        mysqli_data_seek($result,0);
                    ?>
                </div>
                <div class="platform">
                    <h2>Platform</h2>
                    <?php
                    $row = $result->fetch_assoc();
                    echo '<p><b>OS:</b> '. $row['os'] .'<br>
                            <b>Chipset:</b> '. $row['chipset'] .'<br>
                            <b>CPU:</b> '. $row['cpu'] .'<br>
                            <b>GPU:</b> '. $row['gpu'] .'
                        </p>';
                        mysqli_data_seek($result,0);
                    ?>
                </div>
                <div class="camera">
                    <h2>Camera</h2>
                    <?php
                    $row = $result->fetch_assoc();
                    echo '<p><b>Rear Camera:</b> '. $row['rear'] .'<br>
                            <b>Selfie Camera:</b> '. $row['selfie'] .'
                        </p>';
                        mysqli_data_seek($result,0);
                    ?>
                </div>
            </div>
            <hr>
            <div class="in-box">
                <h1>What's in the box: </h1>
                <?php
                $row = $result->fetch_assoc();
                echo '<p>'. $row['wintb'] .'</p>';
                mysqli_data_seek($result,0);
                ?>
            </div>
            <hr>
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
    <script>
        
        function visibility(color) {
            // Hide all color boxes initially
            document.querySelectorAll('.color-image').forEach(function (box) {
                box.style.visibility = 'hidden';
                box.style.opacity = '0';
            });document.querySelectorAll('.color-text').forEach(function (box) {
                box.style.visibility = 'hidden';
            });

            // Show the selected color box
            let selectedImg = document.getElementById('img' + color);
            let selectedText = document.getElementById('text' + color);

            if (selectedImg && selectedText) {
                selectedImg.style.visibility = 'visible';
                selectedImg.style.opacity = '1';
                selectedText.style.visibility = 'visible';
            }
        }

        function calculate() {
            <?php
                $row = $result->fetch_assoc();
                echo 'let total = '. $row['price'] .';';
                mysqli_data_seek($result,0);
            ?>

            let capacity1 = document.getElementById('64GB');
            let capacity2 = document.getElementById('128GB');
            let capacity3 = document.getElementById('256GB');
            let capacity4 = document.getElementById('512GB');
            let plusCapacity = 0;

            if (capacity1.checked) {
                plusCapacity = 0;
                document.getElementById('plusCapacityText').innerHTML = '';
            } else if (capacity2.checked) {
                plusCapacity = 30;
                document.getElementById('plusCapacityText').innerHTML = '+RM' + plusCapacity;
            }else if (capacity3.checked) {
                plusCapacity = 50;
                document.getElementById('plusCapacityText').innerHTML = '+RM' + plusCapacity;
            }else if (capacity4.checked) {
                plusCapacity = 60;
                document.getElementById('plusCapacityText').innerHTML = '+RM' + plusCapacity;
            }
            total += plusCapacity;

            let waranty1 = document.getElementById('6');
            let waranty2 = document.getElementById('12');
            let plusWaranty = 0;

            if (waranty1.checked) {
                plusWaranty = 0;
                document.getElementById('plusWarantyText').innerHTML = '';
            } else if (waranty2.checked) {
                plusWaranty = 20;
                document.getElementById('plusWarantyText').innerHTML = '+RM' + plusWaranty;
            }
            total += plusWaranty;

            let output = document.getElementById('output');
            output.innerHTML = 'Total: RM' + total.toFixed(2);
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