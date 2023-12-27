<?php

// Simulate a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "review";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Function to validate JSON input
function validateInput($data)
{
	$message = [];
	$status = true;
	if (!isset($data['product_id']) || !ctype_digit((string)$data['product_id'])) {
		$status = false;
		$message[] = "Invalid product_id. It must be an integer.";
	}

	if (!isset($data['user_id']) || !ctype_digit((string)$data['user_id'])) {
		$status = false;
		$message[] = "Invalid user_id. It must be an integer.";
	}

	if (!isset($data['review_text']) || !is_string($data['review_text'])) {
		$status = false;
		$message[] = "Invalid review_text. It must be a string.";
	}

	// You can add more validation as needed

	return [
		'status' => $status,
		'message' => $message,
	];
}

// Endpoint to handle the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$input_data["product_id"] = $_POST["product_id"] ?? null;
	$input_data["user_id"] = $_POST["user_id"] ?? null;
	$input_data["review_text"] = $_POST["review_text"] ?? null;

	// Validate input
	if (validateInput($input_data)['status']) {
		$product_id = $input_data['product_id'];
		$user_id = $input_data['user_id'];
		$review_text = $input_data['review_text'];

		// Insert the review into the database
		$sql = "INSERT INTO product_reviews (product_id, user_id, review_text) VALUES ('$product_id', '$user_id', '$review_text')";

		if ($conn->query($sql) === TRUE) {
			$response = array('status' => 'success', 'message' => 'Review submitted successfully');
			http_response_code(201); // Created
		} else {
			$response = array('status' => 'error', 'message' => 'Error submitting review: ' . $conn->error);
			http_response_code(500); // Internal Server Error
		}
	} else {
		$response = array('status' => 'error', 'message' => validateInput($input_data)['message']);
		http_response_code(400); // Bad Request
	}

	// Return JSON response
	header('Content-Type: application/json');
	echo json_encode($response);
}

// Close the database connection
$conn->close();

?>
