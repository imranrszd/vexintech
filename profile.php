<?php
include('config.php');
session_start();

$user_id = $_SESSION['user_id'];

$resultUser = mysqli_query($conn, "SELECT * FROM `customer` WHERE cust_id = '$user_id'") or die('query failed');

if(isset($_POST['save_profile'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
 
    mysqli_query($conn, "UPDATE `customer` SET cust_name = '$name', cust_email = '$email', cust_phone = '$phone'
    WHERE cust_id = '$user_id'") or die('query failed');
    
   header('location:profile.php');
}

if(isset($_POST['save_address'])){

    $address = $_POST['address'];
 
    mysqli_query($conn, "UPDATE `customer` SET cust_add = '$address'
    WHERE cust_id = '$user_id'") or die('query failed');
    
   header('location:profile.php');
}

$resultOrderHistory = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = $user_id");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vtech - profile</title>
    <link rel="icon" href="icon/icon.png">
    <link rel="stylesheet" href="profile-style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div id="preloader"></div>
    <header>
            <div class="header-dummy">
                <a href="main.php" class="logo" style="text-decoration: none;" ><img src="icon/icon.png" alt="" style="height: 55px;" ><span>VTech</span></a>
            </div>
        <a href="logout.php" class="logout">Log out</a>
    </header>
    <main>
        <h2>PROFILE
            <a style="cursor: pointer;" onclick="profileEdit()" ><img src="icon/icons8-edit-50.png" title="edit" height="18px"> </a>
        </h2>
        <div class="profile" >
            <div class="profile-view" id="profile-view" >
            <?php
            $row = $resultUser -> fetch_assoc();
            echo '
            <p>name: '. $row['cust_name'] .'</p>
            <p>e-mail: '. $row['cust_email'] .'</p>
            <p>phone number: '. $row['cust_phone'] .'</p>';
            ?>
            </div>
            <form action="" method="post" class="profile-edit"  id="profile-edit">
                <?php
                echo'
                <div>
                    <p id="name">name: <input type="text" value="'. $row['cust_name'] .'" name="name" ></p>
                    <p id="email">e-mail: <input type="text" value="'. $row['cust_email'] .'" name="email"></p>
                    <p id="pnumber">phone number: <input type="text" value="'. $row['cust_phone'] .'" name="phone"></p>
                </div>';
                ?>
                <input type="submit" id="save" name="save_profile" value="Save">
                <input type="button" id="cancel" value="Cancel" onclick="profileView()" >
            </form>
        </div>

        <h2 class="address">ADDRESS
            <a style="cursor: pointer;" onclick="addressView()" ><img src="icon/icons8-plus-24.png" title="add" height="18px"></a>
        </h2>
        <div class="address-list">
            <div id="address-view">
            <?php
                if ($row['cust_add'] == null) {
                    echo '<p id="no-address">- no adressess added -</p>';
                }else{
                    echo'
                    <p>default address:</p>
                    <div id="have-address">
                        <p>'. $row['cust_add'] .'</p>
                    </div>';
                };
            ?>
            </div>
            <form action="" method="post" class="address-edit" id="address-edit">
                <?php
                echo'
                <input type="text" value="'. $row['cust_add'] .'" name="address">';
                ?>
                <div>
                <input type="submit" value="Save" name="save_address">
                <input type="button" value="Cancel" onclick="addressEdit()">
                </div>
            </form>
        </div>

        <h2 class="order-history">ORDER HISTORY </h2>
        <div class="order-list">
            <?php
            if (mysqli_num_rows($resultOrderHistory) <= 0) {
                echo'<p id="no-order"> - you have yet not make any order - </p>';
            }else{
                echo'
                    <div class="title">
                    <p style="width: 40%;">Device</p>
                    <p style="width: 30%;">Date</p>
                    <p style="width: 20%;">Total</p>
                    <p style="width: 30%;">Status</p>
                </div>
                <div class="order-box">';
                
                while ($row = $resultOrderHistory -> fetch_assoc()) {
                    $name = $row['name'];
                    $order_id = $row['id'];
                    $resultDevice = mysqli_query($conn, "SELECT * FROM `device` WHERE Name = '$name'");
                    $resulttransact = mysqli_query($conn, "SELECT * FROM `transaction` WHERE order_id = '$order_id'");
                    $fetchDevice = $resultDevice ->fetch_assoc();
                    $fetchTransact = $resulttransact ->fetch_assoc();
                    echo '
                    <div class="have-order">
                        <div class="device" style="width: 40%;">
                            <a href="device-page.php?id='. $fetchDevice['deviceID'].'"">'. $row['name'] .'</a>
                        </div>
                        <div class="device" style="width: 30%;">
                            '. $fetchTransact['placed_on'].'
                        </div>
                        <div class="total" style="width: 20%;">
                            RM '. $row['price'] .'.00
                        </div>
                        <div class="status" style="width: 30%;">
                            <a href="delivery.php?id='. $order_id.'"">Order Processed</a>
                        </div>
                    </div>';
                }
                echo '</div>';
            }
            
            ?>
        </div>
    </main>
    <script src="script.js"></script>
    <script>
        let prView = document.getElementById('profile-view');
        let prEdit = document.getElementById('profile-edit');
        function profileEdit() {
            prView.style.visibility = 'hidden';
            prEdit.style.visibility = 'visible';
        }
        function profileView() {
            prView.style.visibility = 'visible';
            prEdit.style.visibility = 'hidden';
        }

        let addView = document.getElementById('address-view');
        let addHave = document.getElementById('have-address');
        let addEdit = document.getElementById('address-edit');
        function addressView() {
            addEdit.style.visibility = 'visible';
            addView.style.visibility = 'collapse';
            addHave.style.visibility = 'collapse';
        }
        function addressEdit() {
            addEdit.style.visibility = 'collapse';
            addView.style.visibility = 'visible';
            addHave.style.visibility = 'visible';
        }
    </script>
</body>

</html>