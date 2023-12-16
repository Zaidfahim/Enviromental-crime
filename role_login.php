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

    $stmt = $conn->prepare("SELECT id, password, role, province, district FROM role_login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword, $role, $province, $district);
        $stmt->fetch();

        // Use password_verify to check if the entered password matches the hashed password
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            $_SESSION['province'] = $province;
            $_SESSION['district'] = $district;

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
