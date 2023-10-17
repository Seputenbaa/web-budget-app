<?php
require_once("../../config.php");

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    // Sanitize the input
    $recordId = intval($_POST['id']);

    // Use a prepared statement to fetch the record details
    $stmt = $conn->prepare("SELECT * FROM `running_balance` WHERE id = ?");
    $stmt->bind_param("i", $recordId);

    if ($stmt->execute()) {
        // Bind result variables
        $stmt->bind_result($id, $name, $remarks, $age, $sex, $address, $referred_to, $doctors, $disposition, $user_id);

        if ($stmt->fetch()) {
            // Store the record details in an associative array
            $recordDetails = [
                'id' => $id,
                'name' => $name,
                'remarks' => $remarks,
                'age' => $age,
                'sex' => $sex,
                'address' => $address,
                'referred_to' => $referred_to,
                'doctors' => $doctors,
                'disposition' => !empty($disposition) ? explode(', ', $disposition) : [],
                'user_id' => $user_id,
            ];

            // Return the record details as JSON
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'recordDetails' => $recordDetails]);
        } else {
            // Record not found
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }
    } else {
        // Database query error
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Invalid or missing 'id' parameter
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid or missing ID']);
}
?>
