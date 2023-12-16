<?php
session_start();
date_default_timezone_set('Asia/Colombo');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $userEmail = $_SESSION['user_email'];
    $incidentType = $_POST["incidentType"];
    $province = $_POST["province"];
    $district = $_POST["district"];
    $location = $_POST["location"];
    $message = $_POST["message"];

    // Set the server's timestamp
    $dateTime = date('Y-m-d H:i:s');

    // Perform database connection (replace these values with your database credentials)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "environment";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle file upload
    $evidence = file_get_contents($_FILES["evidence"]["tmp_name"]);

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO complaint (user_email, incidentType, province, district, location, evidence, message, dateTime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $userEmail, $incidentType, $province, $district, $location, $evidence, $message, $dateTime);

    if ($stmt->execute()) {
        echo "<script>alert('Complaint submitted successfully!'); window.location.href = 'dashboard_main.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
