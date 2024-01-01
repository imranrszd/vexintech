<?php
include('config.php');
session_start();

$user_id = $_SESSION['user_id'];

$resultCart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
$resultUser = mysqli_query($conn, "SELECT * FROM `customer` WHERE cust_id = '$user_id'") or die('query failed');

if (isset($_POST['pay'])) {
    while ($row = $resultCart -> fetch_assoc()) {
        $device_id = $row['device_id'];
        $minusStock = mysqli_query($conn, "SELECT * FROM `stock` WHERE device_id = '$device_id'") or die('query failed');

        $order = $row['id'];
        $name = $row['name'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $image = $row['image'];
        $color = $row['color'];
        $capacity = $row['capacity'];
        $waranty = $row['waranty'];

        mysqli_query($conn, "INSERT INTO `orders` (user_id, id, device_id, name, price, quantity, image, color, capacity, waranty)
                            VALUES ('$user_id', '$order', '$device_id', '$name', '$price', '$quantity', '$image', '$color', '$capacity', '$waranty') ") or die('query failed');

        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'");
        $date = date("d/m/Y");
        mysqli_query($conn, "INSERT INTO `transaction` (user_id, order_id, price, placed_on, payment_status)
                            VALUES ('$user_id', '$order', '$price', '$date', 'Completed') ") or die('query failed');
        $rowstock = $minusStock -> fetch_assoc();
        $minus = $rowstock['stock'] - (int)$quantity;
        mysqli_query($conn,"UPDATE `stock` SET stock = '$minus' WHERE device_id = '$device_id'");
    }

    header('location:payment-succes.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vtech - checkout</title>
    <link rel="icon" href="icon/icon.png">
    <link rel="stylesheet" href="checkout-style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div id="preloader"></div>
    <header>
        <a href="main.php" class="logo">
            <img src="icon/logo.png" alt="" style="height: 50px;">
        </a>
        <h2>CHECKOUT</h2>
        <a href="bag.php" class="bag"><img src="icon/icons8-cart-96 (1).png" alt="" style="height: 30px;"></a>
    </header>
    <main>
        <div class="total-info">
            <h1>Order Summary</h1>
            <?php
            $total = 0;
            while ($row = $resultCart -> fetch_assoc()) {
                echo'
                <div class="device">
                    <img src="'. $row['image'] .'" alt="" style="height: 160px; margin: 10px 30px 10px 10px;">
                    <div class="device-desc">
                        <h4>'. $row['name'] .'</h4>
                        <p>Color: '. $row['color'] .'</p>
                        <p>Capacity: '. $row['capacity'] .'</p>
                        <p>Waranty: '. $row['waranty'] .' Month</p>
                    </div>
                    <div class="quantity">
                        <h4>x'. $row['quantity'] .'</h4>
                        <br><br>
                        <h3>RM'. $row['price'] .'.00</h3>
                    </div>
                </div>';
                $total += $row['price'];
            }
            ?>
            <?php
            echo'
            <p>Subtotal: RM '. $total .'.00</p>
            <p>Shipping: Free</p>
            <h3>Total: RM '. $total .'.00</h3>';
            ?>
        </div>
        <div class="customer-info">
            <h1>Customer Info</h1>
            <?php
            $row = $resultUser->fetch_assoc();
            echo'
            <div class="account">
                <p>Name: <span > '. $row['cust_name'] .'</span></p>
                <p>Email: <span > '. $row['cust_email'] .'</span></p>
                <p>Phone Number:<span > '. $row['cust_phone'] .'</span></p>
            </div>
            <hr>
            <div class="address">
                <p>Address:</p>';
                if ($row['cust_add'] == null) {
                    echo '<p style="margin-top: 10px;">- address is empty - </p><a href="profile.php">Click here to add your address</a>';
                }else{
                    echo '<p style="margin-top: 10px;">'. $row['cust_add'] .'</p>';
                }
                echo'
            </div>';
            ?>
            <hr>
            <h1 id="payment">Payment Method</h1>
            <p>All transaction are secure and encrypt. You can trust us.</p>
            <div class="payment-razer">
                <p>Razer Merchant Services - Fast & Easy Payment </p>
                <hr>
                <div class="redirect">
                    <img src="icon/original.png" alt="razer payment" />
                    <p>After clicking “Pay now”, you will be redirected to Razer Merchant Services - Fast & Easy Payment
                        to complete your purchase securely.</p>
                </div>
            </div>
            <form action="" method="post" >
                <?php
                if ($row['cust_add'] == null or $row['cust_phone'] == null) {
                    echo '<input type="button" value="Pay now" class="pay-button" onclick="alertAdd()">';
                }else{
                    echo '<input type="submit" value="Pay now" class="pay-button" name="pay">';
                }
                ?>
            </form>
        </div>
    </main>
    <script src="script.js"></script>
    <script>
    function alertAdd() {
    alert("Address or Phone Number is empty, Please add them");
    }
    </script>
</body>

</html>