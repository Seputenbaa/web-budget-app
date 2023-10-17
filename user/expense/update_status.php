<?php
require_once("../../config.php");

// Get the record ID and action type from the AJAX request
$recordId = $_POST['id'];
$action = $_POST['action']; // This will be either "approve" or "reject"

// Initialize the response array
$response = [];

// Determine the SQL query based on the action type
if ($action === 'approve') {
    $sql = "UPDATE running_balance SET status = 'Claimed', date_claimed = NOW() WHERE id = ?";
} elseif ($action === 'reject') {
    $sql = "UPDATE running_balance SET status = 'Rejected' WHERE id = ?";
} else {
    // Invalid action type
    $response['success'] = false;
    $response['message'] = 'Invalid action type';
}

// If a valid action type is provided, execute the SQL query
if (isset($sql)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $recordId);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Status updated successfully";
    } else {
        $response['success'] = false;
        $response['message'] = "Status update failed";
    }

    // Close the prepared statement
    $stmt->close();
}

// Return the response to the client
header('Content-Type: application/json');
echo json_encode($response);


?>