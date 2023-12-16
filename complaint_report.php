<?php
session_start();

// Function to establish a database connection
function connectToDatabase()
{
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'environment';

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to handle complaint cancellation
function cancelComplaint($conn, $complaintId)
{
    $sql = $conn->prepare("DELETE FROM complaint WHERE id = ?");
    $sql->bind_param("i", $complaintId);

    if ($sql->execute()) {
        return "Complaint canceled successfully";
    } else {
        return "Error canceling complaint: " . $conn->error;
    }
}

// Function to fetch and display complaints
function fetchAndDisplayComplaints($conn, $sql)
{
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result) {
        ?>
        <table>
            <tr>
                <th>ID</th>
                <th>User Email</th>
                <th>Incident Type</th>
                <th>Province</th>
                <th>District</th>
                <th>Location</th>
                <th>Evidence</th>
                <th>Message</th>
                <th>Date Time</th>
                <th>Action</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['user_email']}</td>";
                    echo "<td>{$row['incidentType']}</td>";
                    echo "<td>{$row['province']}</td>";
                    echo "<td>{$row['district']}</td>";
                    echo "<td>{$row['location']}</td>";

                    // Convert BLOB image data to base64
                    $imageData = base64_encode($row['evidence']);

                    // Display the image in the "evidence" column
                    echo "<td><img src='data:image/png;base64,{$imageData}' alt='Evidence' style='max-width: 100px; max-height: 100px;'></td>";

                    echo "<td>{$row['message']}</td>";
                    echo "<td>{$row['dateTime']}</td>";

                   
                    echo "<td><button class='action-button' onclick='cancelComplaintConfirmation({$row['id']})'>Cancel</button></td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No complaints found</td></tr>";
            }
            ?>

        </table>

        <?php
    } else {
        echo "Error: " . $conn->error;
    }
}

// Main logic

$conn = connectToDatabase();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id']) && isset($_GET['action'])) {
    $complaintId = $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'cancel') {
        $cancelMessage = cancelComplaint($conn, $complaintId);
        echo $cancelMessage;
        // Do not display the table row in case of canceling
        exit;
    } else {
        echo "Invalid action";
    }
} else {
    echo "";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $whereClause = "WHERE 1 ";

    if (isset($_GET['province'])) {
        $province = $conn->real_escape_string($_GET['province']);
        $whereClause .= "AND province = '$province' ";
    }

    if (isset($_GET['district'])) {
        $district = $conn->real_escape_string($_GET['district']);
        $whereClause .= "AND district = '$district' ";
    }

    $sql = "SELECT id, user_email, incidentType, province, district, location, evidence, message, dateTime FROM complaint $whereClause ORDER BY dateTime DESC";
} else {
    // Default SQL query without filters
    $sql = "SELECT id, user_email, incidentType, province, district, location, evidence, message, dateTime FROM complaint ORDER BY dateTime DESC";
}

fetchAndDisplayComplaints($conn, $sql);

// Close the database connection
$conn->close();
?>





