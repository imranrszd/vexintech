<?php

include('config.php');

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

$selectUser = mysqli_query($conn, "SELECT * FROM `customer` WHERE cust_id = '$admin_id'") or die('query failed');
$selectAdmin = mysqli_query($conn, "SELECT * FROM `employee` WHERE user_id = '$admin_id'") or die('query failed');


$selectAllOrder = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
$selectTransaction = mysqli_query($conn, "SELECT * FROM `transaction`") or die('query failed');


if(isset($_POST['approve'])){
    $order_id = $_POST['orderId'];
 
    mysqli_query($conn, "UPDATE `transaction` SET approval = 'Approve'
    WHERE order_id = '$order_id'") or die('query failed');
    header('location:admin.php');

}if(isset($_POST['disapprove'])){
    $order_id = $_POST['orderId'];
 
    mysqli_query($conn, "UPDATE `transaction` SET approval = 'Disapprove'
    WHERE order_id = '$order_id'") or die('query failed');


    mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$order_id'") 
    or die('query failed');
    header('location:admin.php');
}
$selectAllOrderApprove = mysqli_query($conn, "SELECT * FROM `transaction` WHERE approval = 'Approve'") or die('query failed');

/* product */
if(isset($_POST['add_product'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dimension = mysqli_real_escape_string($conn, $_POST['dimension']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $build = mysqli_real_escape_string($conn, $_POST['build']);
    $sim = mysqli_real_escape_string($conn, $_POST['sim']);
    $os = mysqli_real_escape_string($conn, $_POST['os']);
    $chipset = mysqli_real_escape_string($conn, $_POST['chipset']);
    $cpu = mysqli_real_escape_string($conn, $_POST['cpu']);
    $gpu = mysqli_real_escape_string($conn, $_POST['gpu']);
    $rear = mysqli_real_escape_string($conn, $_POST['rear_camera']);
    $selfie = mysqli_real_escape_string($conn, $_POST['selfie_camera']);
    $box = mysqli_real_escape_string($conn, $_POST['box']);
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;
 
    $select_product_name = mysqli_query($conn, "SELECT name FROM `device`
    WHERE name = '$name'") or die('query failed');
 
    if(mysqli_num_rows($select_product_name) > 0){
       $message[] = 'product name already added';
    }else{
       $add_product_query = mysqli_query($conn, "INSERT INTO 
       `device`(Name, price, image, dimension, weight, build, sim, os, chipset, cpu, gpu, rear, selfie, wintb) 
       VALUES ('$name', '$price', '$image', '$dimension', '$weight', '$build', '$sim', '$os', '$chipset', '$cpu', '$gpu', '$rear','$selfie', '$box')")
       or die('query failed');
 
       if($add_product_query){
          if($image_size > 2000000){
             $message[] = 'image size is too large';
          }else{
             move_uploaded_file($image_tmp_name, $image_folder);
             $message[] = 'product added successfully!';
          }
       }else{
          $message[] = 'product could not be added';
       }
    }
 }

 if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `device`
    WHERE deviceID = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('uploaded_img/'.$fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `device` WHERE deviceID = '$delete_id'") 
    or die('query failed');
    header('location:admin.php');
 }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width-device-width,initial-scale=1.0">
    <title>VTech - admin</title>
    <link rel="icon" href="icon/icon.png">
    <link rel="stylesheet" href="admin-style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div id="preloader"></div>
    <header>
    <div class="header-dummy">
        <a href="admin.php" class="logo" style="text-decoration: none;" ><img src="icon/icon.png" alt="" style="height: 55px;" ><span>VTech</span></a>
    </div>
        <h1>ADMIN SPACE</h1>
        <a href="logout.php" class="logout">Log out</a>
    </header>
    <main>
        <div class="admin-profile">
            <h2>Admin Profile</h2>
            <div class="admin-data">
                <?php
                    $fetchAdmin = $selectAdmin -> fetch_assoc();
                    $fetchUser = $selectUser -> fetch_assoc();
                echo '
                <div class="picture">
                    <img src="'. $fetchAdmin['emp_image'] .'" alt="">
                </div>
                <div class="data">
                    <p><b>Name:</b> '. $fetchAdmin['emp_name'] .'</p>
                    <p><b>Position:</b> '. $fetchAdmin['job'] .'</p>
                    <p><b>ID:</b> '. $fetchAdmin['matric_id'] .'</p>
                    <p><b>E-mail:</b> '. $fetchUser['cust_email'] .'</p>';
                    ?>
                </div>
            </div>
        </div>

        <div class="button-box">
            <div class="folder-button" id="fold-butt1"
                style="background: #aac3f0; color: #214280; border-color: #214280;">Sales</div>
            <div class="folder-button" id="fold-butt2" style="color: #23755d; border-color: #23755d;">Stock</div>
            <div class="folder-button" id="fold-butt3" style="color: #ba451a; border-color: #ba451a;">Transaction</div>
            <div class="folder-button" id="fold-butt4" style="color: #8e44ad; border-color: #8e44ad;">Product</div>
        </div>

        <div class="folder" id="folder1" style="visibility: visible;">
            <div class="sales-track">
                <h2>Monthly Sales Track</h2>
                <?php
                $orderCount = 0;
                $profit = 0;
                while ($fetchOrder = $selectAllOrderApprove -> fetch_assoc()) {
                    $orderCount++;
                    $profit += $fetchOrder['price'];
                }
                mysqli_data_seek($selectAllOrder,0);
                echo '
                <div class="device-sold">
                    <p><b>Device Sold:</b> '. $orderCount .' devices</p>
                </div>
                <hr>
                <div class="profit">
                    <p><b>Profit:</b> RM'. $profit .'.00</p>
                </div>';
                ?>
            </div>
        </div>
        <div class="folder" id="folder2">
            <div class="device-stock">
                <h2>Device Stock</h2>
                <table>
                    <!--iPhone 14-->
                    <tr class="head">
                        <th>iPhone 14</th>
                        <th>Stock</th>
                        <th>Set Price (min)</th>
                    </tr>
                    <?php
                    $stock14 = mysqli_query($conn,"SELECT * FROM `stock` WHERE ip_name LIKE '%14%' ORDER BY price_set DESC");
                    while ($stockrow = $stock14 -> fetch_assoc()) {
                        echo'
                        <tr>
                        <td>'. $stockrow['ip_name'] .'</td>
                        <td>'. $stockrow['stock'] .'</td>
                        <td>RM '. $stockrow['price_set'] .'</td>
                        </tr>';
                    };
                    ?>

                    <!--iPhone 13-->
                    <tr class="head">
                        <th>iPhone 13</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <?php
                    $stock14 = mysqli_query($conn,"SELECT * FROM `stock` WHERE ip_name LIKE '%13%' ORDER BY price_set DESC");
                    while ($stockrow = $stock14 -> fetch_assoc()) {
                        echo'
                        <tr>
                        <td>'. $stockrow['ip_name'] .'</td>
                        <td>'. $stockrow['stock'] .'</td>
                        <td>RM '. $stockrow['price_set'] .'</td>
                        </tr>';
                    };
                    ?>
                    <!--iPhone 12-->
                    <tr class="head">
                        <th>iPhone 12</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <?php
                    $stock14 = mysqli_query($conn,"SELECT * FROM `stock` WHERE ip_name LIKE '%12%' ORDER BY price_set DESC");
                    while ($stockrow = $stock14 -> fetch_assoc()) {
                        echo'
                        <tr>
                        <td>'. $stockrow['ip_name'] .'</td>
                        <td>'. $stockrow['stock'] .'</td>
                        <td>RM '. $stockrow['price_set'] .'</td>
                        </tr>';
                    };
                    ?>
                    <!--iPhone 11-->
                    <tr class="head">
                        <th>iPhone 11</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <?php
                    $stock14 = mysqli_query($conn,"SELECT * FROM `stock` WHERE ip_name LIKE '%11%' ORDER BY price_set DESC");
                    while ($stockrow = $stock14 -> fetch_assoc()) {
                        echo'
                        <tr>
                        <td>'. $stockrow['ip_name'] .'</td>
                        <td>'. $stockrow['stock'] .'</td>
                        <td>RM '. $stockrow['price_set'] .'</td>
                        </tr>';
                    };
                    ?>
                    <!--iPhone X-->
                    <tr class="head">
                        <th>iPhone X</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tbody>
                    <?php
                    $stock14 = mysqli_query($conn,"SELECT * FROM `stock` WHERE ip_name REGEXP BINARY 'X' ORDER BY price_set DESC");
                    while ($stockrow = $stock14 -> fetch_assoc()) {
                        echo'
                        <tr>
                        <td>'. $stockrow['ip_name'] .'</td>
                        <td>'. $stockrow['stock'] .'</td>
                        <td>RM '. $stockrow['price_set'] .'</td>
                        </tr>';
                    };
                    ?></tbody>
                </table>
            </div>
        </div>

        <div class="folder" id="folder3">
            <div class="transaction">
                <h2>Transaction History</h2>
                <table>
                    <thead>
                    <tr>
                        <th>User|Order ID</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Transaction Status</th>
                        <th>Approval</th>
                    </tr>
                    </thead>
                        <tbody>
                    <?php
                    while ($row = $selectTransaction -> fetch_assoc()) {
                        echo'
                        <tr class="table-list">
                            <td>'. $row['user_id'] .'|'. $row['order_id'] .'</td>
                            <td>RM '. $row['price'] .'.00</td>
                            <td>'. $row['placed_on'] .'</td>
                            <td>'. $row['payment_status'] .'</td>
                            <td>';
                            if ($row['approval'] == null) {
                                echo'
                                <form action="" method="post">
                                    <input type="hidden" name="orderId" value="' . $row['order_id'] . '">
                                    <input type="submit" name="approve" value="approve">
                                    <input type="submit" name="disapprove" value="disapprove">
                                </form>';
                            }else{
                                echo'
                                <p>'. $row['approval'] .'</p>';
                            }
                            echo'
                            </td>
                        </tr>';
                    }
                    ?></tbody>
                </table>
            </div>
        </div>

        <div class="folder" id="folder4">
            <div class="add-product">
                <h2>Add Product</h2>
                <form action="" method="post" enctype="multipart/form-data">
                    <h4>Device Info:</h4>
                    <div class="product-info">
                    <label for="name">Phone Name:</label>
                    <input type="text" name="name" class="box" placeholder="enter phone name" required>
                    <label for="price">Price:</label>
                    <input type="number" min="0" name="price" class="box" placeholder="enter phone price (number only)" required>
                    <label for="image-update" style="display: block;">Choose Preview:</label>
                    <input type="file" name="image" id="image-upload" accept="image/jpg, image/png, image/jpeg" class="box" style="margin: 10px 0;" required>
                    </div>
                        <h4>Device Description: </h4>
                    <div class="product-desc">
                    <input type="text" name="dimension" class="box" placeholder="enter phone dimension" required>
                    <input type="text" name="weight" class="box" placeholder="enter phone weight" required>
                    <input type="text" name="build" class="box" placeholder="enter phone build" required>
                    <input type="text" name="sim" class="box" placeholder="enter phone sim" required>
                    <input type="text" name="os" class="box" placeholder="enter phone os" required>
                    <input type="text" name="chipset" class="box" placeholder="enter phone chipset" required>
                    <input type="text" name="cpu" class="box" placeholder="enter phone cpu" required>
                    <input type="text" name="gpu" class="box" placeholder="enter phone gpu" required>
                    <input type="text" name="rear_camera" class="box" placeholder="enter phone rear camera" required>
                    <input type="text" name="selfie_camera" class="box" placeholder="enter phone selfie camera" required>
                    <input type="text" name="box" class="box" placeholder="enter phone box" required>
                    <input type="submit" value="add product" name="add_product" class="btn">
                    </div>
                </form>
            </div>

            <br>
            <div class="device">
                <?php
                echo '<div class="row">';
                $selectdevice = mysqli_query($conn, "SELECT * FROM device ORDER BY deviceID DESC");
                $counter = 0;

                while ($row = $selectdevice->fetch_assoc()) {
                    if ($counter % 4 == 0 && $counter > 0) {
                        echo '</div><div class="row">';
                    }

                    echo '
                    <div class="device-preview">
                        <a href="">
                            <div class="device-img"><img src="uploaded_img/' . $row['image'] . '" alt="" style="height: 180px;"></div>
                            <div class="device-brief-info">
                                <p>' . $row['Name'] . '</p>
                                <a href="device-page-preview.php?id='. $row['deviceID'].'" class="option-btn">Preview</a>
                                <a href="admin.php?delete=' . $row['deviceID'] . '" class="delete-btn" onclick="return confirm(\'delete this product?\')">Delete</a>
                            </div>
                        </a>
                    </div>';

                    $counter++;
                }

                echo '</div>';
                ?>
                </div>
            </div>
            
        </div>
    </main>
    <script src="script.js"></script>
    <script>
        let folder1 = document.getElementById('folder1');
        let button1 = document.getElementById('fold-butt1');
        button1.addEventListener('click', function () {
            button1.style.background = '#aac3f0';
            folder1.style.visibility = 'visible';

            folder2.style.visibility = 'collapse';
            button2.style.background = '#fff';
            folder3.style.visibility = 'collapse';
            button3.style.background = '#fff';
            folder4.style.visibility = 'collapse';
            button4.style.background = '#fff';

        })

        let folder2 = document.getElementById('folder2');
        let button2 = document.getElementById('fold-butt2');
        button2.addEventListener('click', function () {
            button2.style.background = '#c1f3e4';
            folder2.style.visibility = 'visible';

            folder1.style.visibility = 'collapse';
            button1.style.background = '#fff';
            folder3.style.visibility = 'collapse';
            button3.style.background = '#fff';
            folder4.style.visibility = 'collapse';
            button4.style.background = '#fff';
        })

        let folder3 = document.getElementById('folder3');
        let button3 = document.getElementById('fold-butt3');
        button3.addEventListener('click', function () {
            button3.style.background = '#f3dcc1';
            folder3.style.visibility = 'visible';

            folder1.style.visibility = 'collapse';
            button1.style.background = '#fff';
            folder2.style.visibility = 'collapse';
            button2.style.background = '#fff';
            folder4.style.visibility = 'collapse';
            button4.style.background = '#fff';
        })

        let folder4 = document.getElementById('folder4');
        let button4 = document.getElementById('fold-butt4');
        button4.addEventListener('click', function () {
            button4.style.background = '#eed0fb';
            folder4.style.visibility = 'visible';

            folder1.style.visibility = 'collapse';
            button1.style.background = '#fff';
            folder2.style.visibility = 'collapse';
            button2.style.background = '#fff';
            folder3.style.visibility = 'collapse';
            button3.style.background = '#fff';
        })
    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        var tbody = $('#folder3 table tbody');
        tbody.html($('tr',tbody).get().reverse());
    </script>
</body>

</html>