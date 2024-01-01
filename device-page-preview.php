<?php
    include('config.php');

    session_start();

    $deviceID = $_GET['id'];
    
    $admin_id = $_SESSION['admin_id'];
    if(!isset($admin_id)){
       header('location:login.php');
    };

    $sql = "SELECT * From device WHERE DeviceID = '$deviceID'";
    $result = mysqli_query($conn, $sql) OR die('Query Failed');

    $sqlMulti = "SELECT * FROM deviceattributes WHERE DeviceID = '$deviceID'";
    $resultMulti = mysqli_query($conn, $sqlMulti) OR die('Query Failed');
    


if(isset($_POST['update_product'])){
   
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_dimension = $_POST['update_dimension'];
    $update_weight = $_POST['update_weight'];
    $update_build = $_POST['update_build'];
    $update_sim = $_POST['update_sim'];
    $update_os = $_POST['update_os'];
    $update_chipset = $_POST['update_chipset'];
    $update_cpu = $_POST['update_cpu'];
    $update_gpu = $_POST['update_gpu'];
    $update_rear_camera = $_POST['update_rear_camera'];
    $update_selfie_camera = $_POST['update_selfie_camera'];
    $update_box = $_POST['update_box'];
    $update_spline = $_POST['update_spline'];
    $update_price = $_POST['update_price'];
 
    mysqli_query($conn, "UPDATE `device` SET Name = '$update_name', 
    price = '$update_price' 
    , dimension = '$update_dimension', weight = '$update_weight', build = '$update_build', sim = '$update_sim'
    , os = '$update_os', chipset = '$update_chipset', cpu = '$update_cpu', gpu = '$update_gpu', rear = '$update_rear_camera'
    , selfie = '$update_selfie_camera', wintb = '$update_box', spline_url = '$update_spline' WHERE deviceID = '$update_p_id'") or die('query failed');
 
    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'uploaded_img/'.$update_image;
    $update_old_image = $_POST['update_old_image'];
 
    if(!empty($update_image)){
       if($update_image_size > 2000000){
          $message[] = 'image file size is too large';
       }else{
          mysqli_query($conn, "UPDATE `device` SET image = '$update_image' 
          WHERE deviceID = '$update_p_id'") or die('query failed');
          move_uploaded_file($update_image_tmp_name, $update_folder);
          unlink('uploaded_img/'.$update_old_image);
       }
    }
    
    for ($i=1; $i < 5; $i++) { 
        // Assuming form field names like att_id1, image_location1, color_name1, etc.
        $att_id = mysqli_real_escape_string($conn, $_POST['att_id' . $i]);
        $update_image = mysqli_real_escape_string($conn, $_POST['image_location' . $i]);
        $update_stat = mysqli_real_escape_string($conn, $_POST['color_name' . $i]);
        $update_color = mysqli_real_escape_string($conn, $_POST['color' . $i]);
        $update_capacity = mysqli_real_escape_string($conn, $_POST['capacity' . $i]);
    
        $selectMulti = mysqli_query($conn, "SELECT * FROM `deviceattributes` WHERE DeviceID = $update_p_id AND AttributesID = $att_id");
    
        if (mysqli_num_rows($selectMulti) > 0) { // If it has a value already
            mysqli_query($conn, "UPDATE `deviceattributes` SET ImageColor = '$update_image', DeviceColorText = '$update_stat', DeviceColor = '$update_color', 
            DeviceCapacity = '$update_capacity' WHERE DeviceID = $update_p_id AND AttributesID = $att_id");
        } else {
            mysqli_query($conn, "INSERT INTO `deviceattributes` (DeviceID, AttributesID, DeviceColorText, DeviceColor, DeviceCapacity, ImageColor)
                                VALUES ('$update_p_id', '$att_id','$update_stat','$update_color','$update_capacity', '$update_image')");
        }
    }

    $deleteempty = mysqli_query($conn, "DELETE FROM `deviceattributes` WHERE DeviceColorText = '' AND DeviceColor = ''");

    header('location:device-page-preview.php?id='. $update_p_id .'');
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
    .header-dummy{
        background-color: white;
        height: 70px;
        position: absolute;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .header-dummy>.logo{
        color: black;
        display: flex;
        align-items: center;
    }
    .logo>span{
        font-size: 20px;
        font-weight: 800;
        margin-left: 5px;
    }
</style>

<body id="body-pre" >
    <div id="preloader"></div>
    <header style="display: none;" >
    <?php
    include 'header.php';
    ?>
    </header>
    <div class="header-dummy">
        <a href="admin.php" class="logo" style="text-decoration: none;" ><img src="icon/icon.png" alt="" style="height: 55px;" ><span>VTech</span></a>
    </div>

    <main>
        <div class="edit-box">
        <h1 id="preview" >Preview</h1>
        <div id="update" onclick="update()" >Update / Add Variation</div>
        </div>
        <div class="device-preview">
            <div class="image">
            <?php
            if(mysqli_num_rows($resultMulti) <= 0){
                echo '<p style="border: 1px white solid; width: fit-content; height: fit-content; padding: 0 5px;" >insert image</p>';
            }else {
                $row = $resultMulti->fetch_assoc();
                echo '<img src="'. $row['ImageColor'] .'" alt="" id="img'. $row['DeviceColor'] .'" class="color-image" style="visibility: visible; opacity: 1">';
    
                while($row = $resultMulti->fetch_assoc()){
                    echo '<img src="'. $row['ImageColor'] .'" alt="" id="img'. $row['DeviceColor'] .'" class="color-image">';
                }
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
                        if(mysqli_num_rows($resultMulti) <= 0){
                                echo '<p style="border: 1px white solid; width: fit-content; padding: 0 5px;" >insert color variation</p>';
                        }else {
                            $row = $resultMulti->fetch_assoc();
                            echo '<div id="text'. $row['DeviceColor'] .'" class="color-text" style="visibility: visible;">
                                    <p>Color - ' . $row['DeviceColorText'] . '</p>
                                </div>';
    
                            while ($row = $resultMulti->fetch_assoc()) {
                                echo '<div id="text'. $row['DeviceColor'] .'" class="color-text">
                                <p>Color - ' . $row['DeviceColorText'] . '</p>
                                </div>';
                            }
                        }
                        mysqli_data_seek($resultMulti,0);
                        ?>
                    </div>
                    <br><br>
                    <div class="color-box">
                    <?php
                    if(mysqli_num_rows($resultMulti) <= 0){
                        echo '<p style="border: 1px white solid; width: fit-content; height: fit-content; padding: 0 5px;" >insert color box</p>';
                    }else {
                        $row = $resultMulti->fetch_assoc();
                        echo '<input type="radio" name="product_color" value="'. $row['DeviceColorText'] .'" onclick="visibility(\''. $row['DeviceColor'] .'\')"id="'. $row['DeviceColor'] .'" checked="checked" style="visibility: visible;">
                        <span></span> <!--separartion-->';
    
                        while($row = $resultMulti->fetch_assoc()){
                            echo '<input type="radio" name="product_color" value="'. $row['DeviceColorText'] .'" onclick="visibility(\''. $row['DeviceColor'] .'\')" id="'. $row['DeviceColor'] .'" style="visibility: visible;">
                            <span></span> <!--separartion-->';
                        }
                    }
                    mysqli_data_seek($resultMulti,0);
                    ?>
                    </div>
                    <br>
                    <p>Capacity: <span id="plusCapacityText" style="font-size: 18px;"></span></p>
                    <div class="spec">
                        <?php
                        if(mysqli_num_rows($resultMulti) <= 0){
                            echo '<p style="border: 1px white solid; width: fit-content; height: fit-content; padding: 0 5px;" >insert capacity</p>';
                        }else {
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
                    <p>Waranty: <span id="plusWarantyText" style="font-size: 18px;"></span></p>
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
                    <button type="submit" name="add_to_cart" class="bag">Add to Bag</button>
                    <button type="submit" class="checkout">Buy Now</button>
                </form>
            </div>
        </div>
        <div class="spline-preview">
            <h2>3D Preview</h2>
            <?php
            $row = $result->fetch_assoc();
            echo'
            <iframe src="'. $row['spline_url'] .'" frameborder="0" width="70%" ></iframe>';
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
        <div class="edit-form" id="edit-form">
        <div class="edit-product-form">
            <?php
                $update_query = mysqli_query($conn, "SELECT * FROM `device` WHERE DeviceID = '$deviceID'") or die('query failed');
                    while($fetch_update = mysqli_fetch_assoc($update_query)){
            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="edit-info">
                    <h4>Edit Info</h4>
                <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['deviceID']; ?>">
                <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                <span>Name:</span><input type="text" name="update_name" value="<?php echo $fetch_update['Name']; ?>" class="box" required placeholder="enter phone name">
                <span>Price:</span><input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter phone price">
                <span>Preview Image:</span><input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
                </div><hr>
                <div class="edit-desc">
                    <h4>Edit Description</h4>
                <span>Dimension:</span><input type="text" name="update_dimension" value="<?php echo $fetch_update['dimension']; ?>" class="box" required placeholder="enter phone dimension">
                <span>Weight:</span><input type="text" name="update_weight" value="<?php echo $fetch_update['weight']; ?>" class="box" required placeholder="enter phone weight">
                <br>
                <span>Build:</span><input type="text" name="update_build" value="<?php echo $fetch_update['build']; ?>" class="box" required placeholder="enter phone build">
                <span>Sim:</span><input type="text" name="update_sim" value="<?php echo $fetch_update['sim']; ?>" class="box" required placeholder="enter phone sim">
                <br>
                <span>OS:</span><input type="text" name="update_os" value="<?php echo $fetch_update['os']; ?>" class="box" required placeholder="enter phone os">
                <span>Chipset:</span><input type="text" name="update_chipset" value="<?php echo $fetch_update['chipset']; ?>" class="box" required placeholder="enter phone chipset">
                <br>
                <span>CPU:</span><input type="text" name="update_cpu" value="<?php echo $fetch_update['cpu']; ?>" class="box" required placeholder="enter phone cpu">
                <span>GPU:</span><input type="text" name="update_gpu" value="<?php echo $fetch_update['gpu']; ?>" class="box" required placeholder="enter phone gpu">
                <br>
                <span>Rear Camera:</span><input type="text" name="update_rear_camera" value="<?php echo $fetch_update['rear']; ?>" class="box" required placeholder="enter phone rear camera">
                <span>Selfie Camera:</span><input type="text" name="update_selfie_camera" value="<?php echo $fetch_update['selfie']; ?>" class="box" required placeholder="enter phone selfie camera">
                <br>
                <span>Box:</span><input type="text" name="update_box" value="<?php echo $fetch_update['wintb']; ?>" class="box" required placeholder="enter phone box">
                <span>Spline Url:</span><input type="text" name="update_spline" value="<?php echo $fetch_update['spline_url']; ?>" class="box" required placeholder="enter spline url">
                </div><hr>
                <h4>Add Variation</h4>
                <?php 
                mysqli_data_seek($resultMulti,0);
                $fetchMulti = $resultMulti -> fetch_assoc();
                ?>
                <div class="row">
                <label for="">row 1: </label>
                <input type="hidden" name="att_id1" value="1">
                <input type="text" name="color_name1" value="<?php echo $fetchMulti['DeviceColorText'] ?? ''; ?>" class="box" required placeholder="enter color name">
                <input type="text" name="color1" value="<?php echo $fetchMulti['DeviceColor'] ?? ''; ?>" class="box" required placeholder="enter color">
                <input type="text" name="capacity1" value="<?php echo $fetchMulti['DeviceCapacity'] ?? ''; ?>" class="box" required placeholder="enter capacity">
                <input type="text" name="image_location1" value="<?php echo $fetchMulti['ImageColor'] ?? ''; ?>" class="box" required placeholder="enter image location"><br>
                </div><hr>
                
                <?php $fetchMulti = $resultMulti -> fetch_assoc();?>
                <div class="row">
                <label for="">row 2: </label>
                <input type="hidden" name="att_id2" value="2">
                <input type="text" name="color_name2" value="<?php echo $fetchMulti['DeviceColorText'] ?? ''; ?>" class="box"  placeholder="enter color name">
                <input type="text" name="color2" value="<?php echo $fetchMulti['DeviceColor'] ?? ''; ?>" class="box"  placeholder="enter color">
                <input type="text" name="capacity2" value="<?php echo $fetchMulti['DeviceCapacity'] ?? ''; ?>" class="box"  placeholder="enter capacity">
                <input type="text" name="image_location2" value="<?php echo $fetchMulti['ImageColor'] ?? ''; ?>" class="box" required placeholder="enter image location"><br>
                </div><hr>
                
                <?php $fetchMulti = $resultMulti -> fetch_assoc();?>
                <div class="row">
                <label for="">row 3: </label>
                <input type="hidden" name="att_id3" value="3">
                <input type="text" name="color_name3" value="<?php echo $fetchMulti['DeviceColorText'] ?? ''; ?>" class="box"  placeholder="enter color name">
                <input type="text" name="color3" value="<?php echo $fetchMulti['DeviceColor'] ?? ''; ?>" class="box"  placeholder="enter color">
                <input type="text" name="capacity3" value="<?php echo $fetchMulti['DeviceCapacity'] ?? ''; ?>" class="box"  placeholder="enter capacity">
                <input type="text" name="image_location3" value="<?php echo $fetchMulti['ImageColor'] ?? ''; ?>" class="box" required placeholder="enter image location"><br>
                </div><hr>
                
                <?php $fetchMulti = $resultMulti -> fetch_assoc();?>
                <div class="row">
                <label for="">row 4: </label>
                <input type="hidden" name="att_id4" value="4">
                <input type="text" name="color_name4" value="<?php echo $fetchMulti['DeviceColorText'] ?? ''; ?>" class="box"  placeholder="enter color name">
                <input type="text" name="color4" value="<?php echo $fetchMulti['DeviceColor'] ?? ''; ?>" class="box"  placeholder="enter color">
                <input type="text" name="capacity4" value="<?php echo $fetchMulti['DeviceCapacity'] ?? ''; ?>" class="box"  placeholder="enter capacity">
                <input type="text" name="image_location4" value="<?php echo $fetchMulti['ImageColor'] ?? ''; ?>" class="box"  placeholder="enter image location"><br>
                </div>
                <hr>
                <div class="btn">
                <input type="submit" value="Update" name="update_product" class="btn">
                <div id="cancel" onclick="update()" >Cancel</div>
                </div>
            </form>
            <?php
                }
            ?>
        </div>
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
    <script>
        let editForm =document.getElementById('edit-form');
        let main = document.getElementById('body-pre');

        function update() {
            editForm.classList.toggle('active');
            main.style.overflow = main.style.overflow === 'hidden' ? 'visible' : 'hidden';
        }
    </script>
</body>

</html>