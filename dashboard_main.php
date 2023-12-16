<?php
// Start the session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to the login page
    header("Location: User_login_and_regisster.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4CAF50;
            padding: 1em;
            color: white;
            text-align: center;
        }
        
        #userInfo {
            position: absolute;
            top: 0px;
            left: 10px;
            color: black;
            font-weight: bold;
        }
        #logoutButton {
            position: absolute;
            top: 5px;
            right: 10px;
        }
        #logoutButton button {
            background-color: lightgreen ;
            color: black;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
         }

        #logoutButton button:hover {
            background-color: red;
        }

        main {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 20px;
        }

        section {
            border: 1px solid #ddd;
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        footer {
            background-color: #4CAF50;
            padding: 1em;
            color: white;
            text-align: center;
        }

        #complaintForm {
    max-width: 400px; 
    margin: 20px auto; 
}

#complaintForm form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    font-size: 14px; 
}
    </style>
</head>
<body>

<header>
    <h1>Environmental Complaint System</h1>
    <div id="userInfo"><?php echo "<p>User: " . $_SESSION['user_email'] . "</p>"; ?>
    </div>
    <div id="logoutButton" ><button onclick="window.location.href='logout.php'">Logout</button>
    </div>
</header>

<main>
    <section id="complaintForm">
        <h2>Submit a Complaint</h2>
        <form id="complaintForm" action="complaint_form.php" method="post" enctype="multipart/form-data">

            <div id="clockDisplay"></div>
            <input type="hidden" id="dateTime" name="dateTime">

            <label for="incidentType">Incident Type:</label>
            <select id="incidentType" name="incidentType" required>
                <option value="" disabled selected>Select Incident Type</option>
                <option value="wildlife">Wildlife </option>
                <option value="forest">Forest</option>
            </select>

            <label for="location">Province:</label>
            <select id="province" name="province" onchange="updateDistricts()" required>
                <option value="" disabled selected>Select Province</option>
                <option value="western">Western</option>
                <option value="southern">Southern</option>
                <option value="eastern">Eastern</option>
                <option value="uva">Uva</option>
                <option value="central">Central</option>
                <option value="northcentral">North Central</option>
                <option value="sabaragamuwa">Sabaragamuwa</option>
                <option value="northern">Northern</option>
                <option value="northwestern">North Western</option>
            </select>
            <label for="location">District:</label>
            <select id="district" name="district" required>
                <option value="" disabled selected>Select District</option>
            </select>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="evidence">Upload Evidence:</label>
            <input type="file" id="evidence" name="evidence" accept="image/*" required>
            
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" cols="50" required></textarea>

            <button type="submit">Submit Complaint</button>
        </form>
    </section>
</main>

<footer>
    <p>&copy; 2023 Environmental Complaint System</p>
</footer>

<script>
    
    // Detect browser back button click
    window.addEventListener('popstate', function (event) {
        // Log out the user by redirecting to logout.php
        window.location.href = 'logout.php';
    });

    // Push a new state to the history to handle the initial back button click
    history.pushState(null, null, null);
    
    function updateDistricts() {
        var provinceDropdown = document.getElementById("province");
        var districtDropdown = document.getElementById("district");
        var selectedProvince = provinceDropdown.value;

        // Remove existing options
        districtDropdown.innerHTML = '<option value="" disabled selected>Select District</option>';

        // Add new options based on the selected province
        if (selectedProvince === "western") {
            districtDropdown.innerHTML += '<option value="colombo">Colombo</option>';
            districtDropdown.innerHTML += '<option value="gampaha">Gampaha</option>';
            districtDropdown.innerHTML += '<option value="kalutara">Kalutara</option>';
        }
        if (selectedProvince === "southern") {
            districtDropdown.innerHTML += '<option value="gall">Gall</option>';
            districtDropdown.innerHTML += '<option value="mathara">Mathara</option>';
            districtDropdown.innerHTML += '<option value="hambanthota">Hambanthota</option>';
        }
        if (selectedProvince === "eastern") {
            districtDropdown.innerHTML += '<option value="trincomalee">Trincomalee</option>';
            districtDropdown.innerHTML += '<option value="ampara">Ampara</option>';
            districtDropdown.innerHTML += '<option value="batticaloa">Batticaloa</option>';
        }
        if (selectedProvince === "uva") {
            districtDropdown.innerHTML += '<option value="badulla">Badulla</option>';
            districtDropdown.innerHTML += '<option value="monaragala">Monaragala</option>';
           
        }
        if (selectedProvince === "central") {
            districtDropdown.innerHTML += '<option value="kandy">Kandy</option>';
            districtDropdown.innerHTML += '<option value="nuwaraeliya">Nuwara-Eliya</option>';
            districtDropdown.innerHTML += '<option value="matale">Matale</option>';
        }
        if (selectedProvince === "northcentral") {
            districtDropdown.innerHTML += '<option value="anuradhapura">Anuradhapura</option>';
            districtDropdown.innerHTML += '<option value="polonnaruwa">Polonnaruwa</option>';
        }
        if (selectedProvince === "sabaragamuwa") {
            districtDropdown.innerHTML += '<option value="ratnapura">Ratnapura</option>';
            districtDropdown.innerHTML += '<option value="kegalle">Kegalle</option>';
        }
        if (selectedProvince === "northern") {
            districtDropdown.innerHTML += '<option value="jaffna">Jaffna</option>';
            districtDropdown.innerHTML += '<option value="mannar">Mannar</option>';
            districtDropdown.innerHTML += '<option value="vavuniya">Vavuniya</option>';
            districtDropdown.innerHTML += '<option value="kilinochchi">Kilinochchi</option>';
            districtDropdown.innerHTML += '<option value="mullaitivu">Mullaitivu</option>';
        }
        if (selectedProvince === "northwestern") {
            districtDropdown.innerHTML += '<option value="Kurunagala">Kurunagala</option>';
            districtDropdown.innerHTML += '<option value="Puttalam">Puttalam</option>';
        }
        districtDropdown.value = '';
    }
    // It Update the clock every second
    setInterval(updateClock, 1000);

    // Initial clock update
    updateClock();
</script>
</body>
</html>
