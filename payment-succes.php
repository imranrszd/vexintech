<?php
include('config.php');
session_start();

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTech - payment transaction</title>
    <link rel="icon" href="icon/icon.png">
    <link rel="stylesheet" href="payment-succes.css?v=<?php echo time(); ?>">
</head>
<body>
    <main>
        <h1>The Transaction is Complete</h1>
        <img src="image/image_processing20191203-32098-1cjk1ui.gif" alt="" height="450px">
        <h2>Redirect you to your profile...</h2>
    </main>
    <!-- You can add more content to the temporary page here -->
    <script>
        // Function to redirect after a certain time
        function redirectToAnotherPage() {
            window.location.href = 'profile.php';
        }

        // Set the timeout for redirection (in milliseconds)
        setTimeout(redirectToAnotherPage, 8000); // Redirect after 5 seconds
    </script>

</body>
</html>