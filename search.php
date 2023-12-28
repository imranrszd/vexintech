<?php
// search.php

include('config.php');

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($conn, $_POST['query']);
    
    // Implement your search logic here, for example, fetching data from the database
    $searchResult = mysqli_query($conn, "SELECT * FROM `device` WHERE Name LIKE '%$query%' LIMIT 6");
    
    // Display the search results
    if (mysqli_num_rows($searchResult) > 0) {
    while ($row = mysqli_fetch_assoc($searchResult)) {
        echo '<a href="device-page.php?id='. $row['deviceID'].'" class="search-item">' . $row['Name'] . '</a>';

        if ($row == null) {
            echo '<h2>Search any iPhone you\'ll like, We\'ll wait.</h2>';
        }
    }
    }else {
        echo '<h2 class="no-result" style="color: black;">Sorry we either don\'t have that or what you type is gibberish.</h2>';
    }
}
?>