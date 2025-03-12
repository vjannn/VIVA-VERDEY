<?php
// Enable error reporting to help with debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require 'config.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch the data from the database
    $query = "SELECT donation_center, category, outfit_type, amount_needed FROM clothing_needs";
    $result = $conn->query($query);

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'center' => $row['donation_center'],
                'category' => $row['category'],
                'outfit_type' => $row['outfit_type'],
                'amount' => $row['amount_needed']
            ];
        }
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Error fetching data from the database"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the POST request
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    // Check if the received data is valid JSON
    if (!$data) {
        echo json_encode(["error" => "Invalid JSON received"]);
        exit;
    }

    // Extract the donation data
    $centerName = $data['centerName'];
    $category = $data['category'];
    $outfitType = $data['outfitType'];
    $clothingNeeds = $data['clothingNeeds'];

    // Check if required fields are missing
    if (!$centerName || !$category || !$outfitType || !$clothingNeeds) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    // Insert clothing needs into the database
    foreach ($clothingNeeds as $clothing) {
        $amountNeeded = $clothing['amount'];

        // Prepare SQL statement to insert data
        $stmt = $conn->prepare("INSERT INTO clothing_needs (donation_center, category, outfit_type, amount_needed) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $centerName, $category, $outfitType, $amountNeeded);

        if (!$stmt->execute()) {
            echo json_encode(["error" => "Database insert failed: " . $stmt->error]);
            exit;
        }
    }

    echo json_encode(["message" => "Clothing needs added successfully"]);
}
?>
