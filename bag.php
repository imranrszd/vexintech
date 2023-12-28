<?php

include('config.php');

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['update_cart'])){

   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];

   mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity'
   WHERE id = '$cart_id'") or die('query failed');
   
   $message[] = 'cart quantity updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") 
   or die('query failed');
   header('location:bag.php');
}
if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") 
   or die('query failed');
   header('location:bag.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTech - cart</title>
    <link rel="icon" href="icon/icon.png">
    <link rel="stylesheet" href="bag-style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="nav-footer-style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div id="preloader"></div>
    <header>
        <?php
        include 'header.php';
        ?>
    </header>
    <main>

    <div class="bag">
    <div class="title">
        <h1>CART</h1>
        <?php
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_cart) <= 0) {
        echo'
        <div class="bag-empty">
            <img src="icon/icons8-cart-96 (1).png" alt="">
            <h2>Your cart is currently empty</h2>
            <a href="main.php"><button>return to browse</button></a>
        </div>';
        }
        ?>

        <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_cart) > 0) {
        echo '<div class="bag-header">
                    <p style="width: 45%;">DEVICE</p>
                    <p style="width: 15%;">PRICE</p>
                    <p style="width: 15%;">QUANTITY</p>
                    <p style="width: 25%;">TOTAL</p>
                </div>
            </div>
            <hr>';

        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            echo '<div class="item">
                    <div class="device" style="width: 45%;">';
                    
                    $selectDeviceImg = mysqli_query($conn, "SELECT * FROM `deviceattributes` WHERE DeviceColorText = '" . mysqli_real_escape_string($conn, $fetch_cart['color']) . "'");

                    $rowDeviceImg = $selectDeviceImg -> fetch_assoc();
                        echo '<img src="' . $rowDeviceImg['ImageColor'] . '" alt="" style="height: 150px;">';
                     
                        
                        echo '
                        <div class="device-desc">
                            <a href="device-page.php?id='. $fetch_cart['device_id'].'"><h4>' . $fetch_cart['name'] . '</h4></a>
                            <p>Color: '.$fetch_cart['color'].'</p>
                            <p>Capacity: '.$fetch_cart['capacity'].'</p>
                            <p>Warranty: '.$fetch_cart['waranty'].' Month</p>
                        </div>
                    </div>
                    <div class="price" style="width: 15%;">
                        <p>RM ' . $fetch_cart['price'] . '</p>
                    </div>
                    <div class="quantity" style="width: 15%;">
                        <form action="" method="post">
                            <input type="hidden" name="cart_id" value="' . $fetch_cart['id'] . '">
                            <input type="number" min="1" id="cart_quantity" name="cart_quantity" value="' . $fetch_cart['quantity'] . '">
                            <input type="submit" id="cart_submit" name="update_cart" value="update" class="">
                        </form>
                    </div>
                    <div class="total" style="width: 25%;">
                        <p class="total-text">RM ' . ($sub_total = ($fetch_cart['quantity'] * $fetch_cart['price'])) . '.00</p>
                        <a href="bag.php?delete=' . $fetch_cart['id'] . '" 
                        class="remove-button" onclick="return confirm(\'Delete this from cart?\');" style="text-decoration: none" >Remove</a>
                    </div>
                </div>';
            $grand_total += $sub_total;
        }

        echo '<div class="cart-total">
                <p class="subtotal">Grand Total : <span>RM ' . $grand_total . '.00</span></p>  
                <div class="checkout">
                    <a href="checkout.php" class="checkout-button" ' . ($grand_total > 1 ? '' : 'disabled') . '>Checkout</a>
                    <a href="bag.php?delete_all" class="checkout-button ' . ($grand_total > 1 ? '' : 'disabled') . '"
                    onclick="return confirm(\'Delete all from cart?\'); ">Remove All</a>
                </div>
            </div>';
        }
        ?>


        

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