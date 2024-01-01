<?php

include("config.php");

if(isset($_POST['submit'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
 
    $select_users = mysqli_query($conn, "SELECT * FROM `customer` WHERE cust_email = '$email'
    AND cust_password = '$pass'") or die('query failed');
 
    if(mysqli_num_rows($select_users) > 0){
       $message[] = 'user already exist';
    }else{
       if($pass != $cpass){
          $message[] = 'confirm password not matched';
       }else{
          mysqli_query($conn, "INSERT INTO `customer` (cust_name, cust_email, cust_password, user_type)
          VALUES ('$name', '$email', '$pass', 'user')") or die('query failed');
          $message[] = 'registered successfully';
          header('location:login.php');
       }
    }
 }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width-device-width,initial-scale=1.0">
    <title>VTech</title>
    <link rel="icon" href="Logo C.png">
    <link rel="stylesheet" href="register-style.css?v=<?php echo time(); ?>">
    <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
        <div class="form-box register">

            <h2 id="register">Registration</h2>

            <form method="post" autocomplete="off" >
                <div class="up-side">
                    <div class="input-box">
                        <span class="icon"><ion-icon name="person"></ion-icon></span>
                        <input type="text" name="name" required>
                        <label>Username</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><ion-icon name="mail"></ion-icon></span>
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                </div>
                
                <div class="down-side">
                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>

                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                        <input type="password" name="cpassword" required>
                        <label>Confirm Password</label>
                    </div>
                </div>

                <div class="agree">
                    <label>
                        <input type="checkbox" required>
                        I agree to the terms & conditions
                    </label>
                </div>

                <input type="submit" name="submit" class="btn" value="Register">

                <div class="login-register">
                    <p>
                        Already have an account? <a href="login.php" class="login-link">Login</a>
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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>