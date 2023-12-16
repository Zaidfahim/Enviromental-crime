<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: admin_login.html");
    exit(); // Make sure that the script stops here
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #4CAF50;
            padding: 1em;
            color: white;
            text-align: center;
            position: relative;
        }

        .logout-button {
            background-color: lightgreen;
            color: black;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            transition: background-color 0.3s;
        }

        .logout-button:hover {
            background-color: #d9534f;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #4CAF50;
        }

        .registration-form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .registration-form label {
            display: block;
            margin-bottom: 8px;
        }

        .registration-form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        .registration-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        /* Add this CSS to your existing styles */
        .registration-form select {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #000; /* Black border */
            border-radius: 3px;
            background-color: #fff; /* White background */
            color: #000;
        }

        .registration-form button,
        .registration-form select {
            background-color: #4CAF50; /* Green button */
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .registration-form select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="%234CAF50" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position: right 8px center;
        }

        .registration-form select::-ms-expand {
            display: none;
        }

        .registration-form select:focus {
            outline: none;
            border-color: #000; /* Black border on focus */
        }

        /* Add this CSS to style the pop-up message */
        .popup-message {
            display: none;
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 999;
            background-color: #fff;
            color: #4CAF50;
        }

        .popup-message.error {
            background-color: #f2dede;
            color: #a94442;
        }


       
    </style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Function to handle the form submission
        function registerUser(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Your existing form submission logic here...
            var form = document.getElementById('registrationForm');
            var formData = new FormData(form);

            fetch("role_register.php", {
                method: "POST",
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    // Show success message
                    showPopupMessage("success", data.message);

                    // Optionally, clear the form on success
                    form.reset();
                } else {
                    // Show error message
                    showPopupMessage("error", data.message);
                }
            })
            .catch(error => {
                console.error("Fetch error:", error);
                alert("Error: " + error.message);
            });
        }

        // Function to display the pop-up message
        function showPopupMessage(type, message) {
            // Get the pop-up message container
            var popupMessage = document.getElementById("popupMessage");

            // Set the message and style based on the type
            popupMessage.textContent = message;
            popupMessage.className = "popup-message " + type;

            // Show the pop-up message
            popupMessage.style.display = "block";

            // Hide the pop-up message after 3 seconds
            setTimeout(function () {
                popupMessage.style.display = "none";
            }, 3000);
        }

        // Function to update districts based on the selected province
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
            } else if (selectedProvince === "southern") {
                districtDropdown.innerHTML += '<option value="gall">Gall</option>';
                districtDropdown.innerHTML += '<option value="matara">Matara</option>';
                districtDropdown.innerHTML += '<option value="hambantota">Hambantota</option>';
            } else if (selectedProvince === "eastern") {
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

        // Attach the registerUser function to the form submission event
        var registrationForm = document.getElementById("registrationForm");
        if (registrationForm) {
            registrationForm.addEventListener("submit", registerUser);
        }

        // Attach the updateDistricts function to the province dropdown change event
        var provinceDropdown = document.getElementById("province");
        if (provinceDropdown) {
            provinceDropdown.addEventListener("change", updateDistricts);
        }
    });
</script>

</head>
<body>

    <header>
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <form action="admin_logout.php" method="post">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </header>

    <div class="dashboard-container">
        <h2>Register new employees with their role </h2>
        <div class="registration-form">
          
            <h2>Registration Form</h2>
            
            <form id="registrationForm" action="role_register.php" method="post">
                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="fullName" required>

                <label for="addressNo">Address No:</label>
                <input type="text" id="addressNo" name="addressNo" required>

                <label for="road">Road:</label>
                <input type="text" id="road" name="road" required>

                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>

                <label for="role">Role:</label>
                <input type="text" id="role" name="role" required>

                <label for="province">Province:</label>
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

                <label for="district">District:</label>
                <select id="district" name="district" required>
                    <option value="" disabled selected>Select District</option>
                </select>                

                <label for="phoneNumber">Phone Number:</label>
                <input type="text" id="phoneNumber" name="phoneNumber" required pattern="\d{10}" title="Please enter a 10-digit phone number">

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Register</button>
            </form>
        </div>
    </div>
    <div id="popupMessage" class="popup-message"></div>
</body>
</html>
