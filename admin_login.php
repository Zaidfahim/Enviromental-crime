<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'environment';

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
    }

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $stmt = $conn->prepare("SELECT id, password FROM admin_login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();

        // Use password_hash to hash the entered password for comparison
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username;
            echo json_encode(["message" => "Login successful"]);
            exit();
        } else {
            echo json_encode(["message" => "Incorrect password"]);
        }
    } else {
        echo json_encode(["message" => "Incorrect username"]);
    }

    $stmt->close();
    $conn->close();
}
?>
