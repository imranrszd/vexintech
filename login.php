<?php

include("config.php");
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $user_type = $_POST['user_type'];

   $select_users = mysqli_query($conn, "SELECT * FROM `customer` WHERE cust_email = '$email'
   AND cust_password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $row = mysqli_fetch_assoc($select_users);
      
      if($row['user_type'] == 'admin'){

         $_SESSION['admin_name'] = $row['cust_name'];
         $_SESSION['admin_email'] = $row['cust_email'];
         $_SESSION['admin_id'] = $row['cust_id'];
         header('location:admin.php');

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_name'] = $row['cust_name'];
         $_SESSION['user_email'] = $row['cust_email'];
         $_SESSION['user_id'] = $row['cust_id'];
         header('location:main.php');
      }
   }else{
      $message[] = 'incorrect email or password!';
   }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width-device-width,initial-scale=1.0">
    <title>VTech</title>
    <link rel="icon" href="Logo C.png">
    <link rel="stylesheet" href="login-style.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php
        if(isset($message)){
        foreach($message as $message){
            echo '
                <div class="message">
                    <span>' .$message. '</span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>
            ';
        }
        }
    ?>
    <div class="logo">
    <div class="wrapper">
        <div class="form-box login">

            <h2 id="login">Login</h2>

            <form method="post">
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>

                <!--<div class="remember-forgot">
                    <label>
                        <input type="checkbox">
                        Remember me</label>
                    <a href="#">Forgot Password?</a>
                </div>-->

                <input type="submit" name="submit" class="btn" value="Login">

                <div class="login-register">
                    <p>
                        Don't have an account? <a href="register.php" class="register-link">Register</a>
                    </p>
                </div>
            </form>

        </div>

    </div>
    <div class="logo-border">
        <img src="icon/icon.png" alt="">
        <h1>VTech</h1>
    </div>
    </div>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>