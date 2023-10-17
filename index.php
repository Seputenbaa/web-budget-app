<?php require_once('config.php');

// Start the session


// Check if a valid session exists
if (isset($_SESSION['login_type'])) {
    // Check the login type and redirect accordingly
    if ($_SESSION['login_type'] === 'admin') {
        // Redirect to the admin page
        header("Location: admin/index.php"); // Replace with the actual admin page URL
        exit();
    } elseif ($_SESSION['login_type'] === 'user') {
        // Redirect to the user page
        header("Location: user/index.php"); // Replace with the actual user page URL
        exit();
    }
}

// If no valid session or login type is set, redirect to the login page
header("Location: admin/login.php"); // Replace with the actual login page URL
exit();

?>
