<?php
include('config.php');
session_start();

$deviceID = $_GET['id'];
$user_id = $_SESSION['user_id'];

$resultOrders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND id = $deviceID") or die('query failed');
$resultUser = mysqli_query($conn, "SELECT * FROM `customer` WHERE cust_id = '$user_id'") or die('query failed');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vtech - delivery</title>
    <link rel="icon" href="icon/icon.png">
    <link rel="stylesheet" href="delivery-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity=" sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin=" anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
        integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div id="preloader"></div>
    <header>
            <div class="header-dummy">
                <a href="main.php" class="logo" style="text-decoration: none;" ><img src="icon/icon.png" alt="" style="height: 55px;" ><span>VTech</span></a>
            </div>
    </header>

    <main>
        <h2>Order Details</h2>
        <div class="order-detail">
            <?php
                $row = $resultOrders -> fetch_assoc();
                echo'
                <div class="device">
                    <img src="'. $row['image'] .'" alt="" style="height: 150px;">
                    <div class="device-desc">
                        <h4>'. $row['name'] .'</h4>
                        <p>Color: '. $row['color'] .'</p>
                        <p>Capacity: '. $row['capacity'] .'</p>
                        <p>Waranty: '. $row['waranty'] .' Month</p>
                    </div>
                    <button class="cancel-order" onclick="toggleCancel()">
                        Cancel Order
                    </button>
                    <div class="cancel">
                        <div class="cancel-feed">
                            <h2>Tell us why</h2>
                            <form action="">
                                <input type="text">
                                <button onclick="toggleCancel()">cancel</button>
                                <button>submit</button>
                            </form>
                            <p>P.S: we will review your reason</p>
                        </div>
                    </div>
                </div>';
            mysqli_data_seek($resultOrders,0);
            ?>
            <div class="delivery-status">
                <?php
                $row = $resultOrders -> fetch_assoc();
                echo '<p>order id: #'. $row['user_id'] . $row['id'] . $row['device_id'] .'2525</p>';
                ?>
                <div class="delivery-tracker">

                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <ul class="text-center" id="progressbar">
                                <!-- delete active if not active -->
                                <li class="active step0"></li>
                                <li class="step0"></li>
                                <li class="step0"></li>
                                <li class="step0"></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row justify-content-between top">
                        <div class="row d-flex icon-content">
                            <img src="https://img.icons8.com/ios/100/create-order--v1.png" alt="create-order--v1" alt=""
                                class="icon">
                            <div class="d-flex flex-column">
                                <p class="font-weight-bold">Order <br />Processed</p>
                            </div>
                        </div>
                        <div class="row d-flex icon-content">
                            <img src="https://img.icons8.com/wired/64/move-stock.png" alt="move-stock" alt=""
                                class="icon">
                            <div class="d-flex flex-column">
                                <p class="font-weight-bold">Order <br />Shipped</p>
                            </div>
                        </div>
                        <div class="row d-flex icon-content">
                            <img src="https://img.icons8.com/wired/64/in-transit.png" alt="in-transit" alt=""
                                class="icon">
                            <div class="d-flex flex-column">
                                <p class="font-weight-bold">Order <br />En Route</p>
                            </div>
                        </div>
                        <div class="row d-flex icon-content">
                            <img src="https://img.icons8.com/wired/64/mailbox-opened-flag-down.png"
                                alt="mailbox-opened-flag-down" alt="" class="icon">
                            <div class="d-flex flex-column">
                                <p class="font-weight-bold">Order <br />Delivered</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="customer-info">
                <h4>Payment Info</h4>
                <?php
                $fetchUser = $resultUser -> fetch_assoc();
                $total = 0;
                $resultOrders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' AND id = $deviceID") or die('query failed');
                while ($fetchOrder = $resultOrders -> fetch_assoc()) {
                    $total += (int)$fetchOrder['price'];
                }
                echo '
                <p>Subtotal: RM '. $total .'.00</p>
                <p>Payment Method: Razer Pay</p>
                <br>
                <h4>Address Info</h4>
                <p>Name: '. $fetchUser['cust_name'] .' </p>
                <p>Phone Number: '. $fetchUser['cust_phone'] .'</p>
                <p>Address:</p>
                <p> '. $fetchUser['cust_add'] .'</p>';
                ?>
            </div>
        </div>

    </main>
    <script src="script.js"></script>
    <script>
        var cancelOrder = document.querySelector('.cancel');
        function toggleCancel() {
            cancelOrder.classList.toggle('active');
        }
    </script>
</body>

</html>