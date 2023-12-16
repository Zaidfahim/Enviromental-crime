<?php

// Replace the following placeholders with your actual database details
$host = "localhost";
$username = "root";
$password = "";
$database = "environment";

// Create a connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$conn) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form data
    $fullName = htmlspecialchars($_POST['fullName']);
    $addressNo = htmlspecialchars($_POST['addressNo']);
    $road = htmlspecialchars($_POST['road']);
    $city = htmlspecialchars($_POST['city']);
    $province = htmlspecialchars($_POST['province']);
    $district = htmlspecialchars($_POST['district']);
    $phoneNumber = htmlspecialchars($_POST['phoneNumber']);
    $username = htmlspecialchars($_POST['username']);
    $role = htmlspecialchars($_POST['role']);
    $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT); // Hash the password

    // Insert data into the role_register table using prepared statements
    $insertRegisterQuery = "INSERT INTO role_register (fullName, addressNo, road, city, province, district, phoneNumber, username, role, password) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $registerStatement = mysqli_prepare($conn, $insertRegisterQuery);
    mysqli_stmt_bind_param($registerStatement, "ssssssssss", $fullName, $addressNo, $road, $city, $province, $district, $phoneNumber, $username, $role, $password);

    // Perform the query
    $registerResult = mysqli_stmt_execute($registerStatement);

    // Insert data into the role_login table using prepared statements
    $insertLoginQuery = "INSERT INTO role_login (username, password, role, province, district) 
                         VALUES (?, ?, ?, ?, ?)";

    $loginStatement = mysqli_prepare($conn, $insertLoginQuery);
    mysqli_stmt_bind_param($loginStatement, "sssss", $username, $password, $role, $province, $district);

    // Perform the query
    $loginResult = mysqli_stmt_execute($loginStatement);

    // Check if both queries were successful
    if ($registerResult && $loginResult) {
        // If both queries are successful, send a JSON response with a success message
        echo json_encode(["status" => "success", "message" => "Record inserted successfully!"]);
    } else {
        // If there's an error in either query, send a JSON response with an error message
        http_response_code(500); // Internal Server Error
        echo json_encode(["status" => "error", "message" => "Error inserting records"]);
    }
}

// Close the connection
mysqli_close($conn);

?>
