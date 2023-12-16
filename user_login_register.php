<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "environment";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["registerEmail"])) {
        // Collect user registration data
        $name = $_POST["registerName"];
        $email = $_POST["registerEmail"];
        $password = password_hash($_POST["registerPassword"], PASSWORD_DEFAULT);
        $address = $_POST["registerAddress"];
        $road = $_POST["registerRoad"];
        $city = $_POST["registerCity"];
        $telephone = $_POST["registerTelephone"];

        // SQL query for user registration
        $registerQuery = "INSERT INTO users_register (name, email, password, address, road, city, telephone) 
                          VALUES ('$name', '$email', '$password', '$address', '$road', '$city', '$telephone')";

        if ($conn->query($registerQuery) === TRUE) {
            // Registration successful, insert data into login table
            $loginQuery = "INSERT INTO user_login (email, password) VALUES ('$email', '$password')";

            if ($conn->query($loginQuery) === TRUE) {
                echo json_encode(["message" => "Registration successful"]);
            } else {
                echo json_encode(["message" => "Error inserting data into login table: " . $conn->error]);
            }
        } else {
            echo json_encode(["message" => "Error registering user: " . $conn->error]);
        }
    } elseif (isset($_POST["loginEmail"])) {
        // Collect user login data
        $email = $_POST["loginEmail"];
        $enteredPassword = $_POST["loginPassword"];

        // SQL query to retrieve user data
        $getUserQuery = "SELECT * FROM user_login WHERE email = '$email'";
        $result = $conn->query($getUserQuery);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row["password"];

            // Verify the entered password
            if (password_verify($enteredPassword, $hashedPassword)) {
                // Start the session
                session_start();

                // Set session variable for user email
                $_SESSION['user_email'] = $email;

                echo json_encode(["message" => "Login successful"]);
            } else {
                echo json_encode(["message" => "Invalid email or password"]);
            }
        } else {
            echo json_encode(["message" => "Invalid email or password"]);
        }
    }
}

// Close the database connection
$conn->close();
?>
