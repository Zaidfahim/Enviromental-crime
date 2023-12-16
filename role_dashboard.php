<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: role_login.html");
    exit(); // Make sure that the script stops here
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Dashboard</title>
    
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
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        /* Styles for select dropdowns */
        select {
            width: 30%; /* Adjust the width as needed */
            padding: 8px;
            margin-bottom: 5px; /* Adjust the margin as needed */
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        /* Styles for the button container */
        .button-container {
            text-align: center;
        }

        /* Styles for the search button */
        button {
            margin-top: 10px; /* Adjust the margin as needed */
            padding: 10px 15px;
            cursor: pointer;
            border: 1px solid #4CAF50;
            border-radius: 3px;
            background-color: #4CAF50;
            color: white;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
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

        .action-button {
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
            border: 1px solid #4CAF50;
            border-radius: 3px;
            background-color: #4CAF50;
            color: white;
            transition: background-color 0.3s;
        }

        .action-button:hover {
            background-color: #45a049;
        }

        .dashboard-container {
            max-width: 1500px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        img {
            max-width: 120%;
            height: auto;
        }
    </style>

    <!-- Include html2pdf library -->
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <p>Role: <?php echo $_SESSION['role']; ?></p>
        <p>Province: <?php echo $_SESSION['province']; ?></p>
        <p>District: <?php echo $_SESSION['district']; ?></p>
        <form action="role_logout.php" method="post">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </header>

    <div class="dashboard-container">
        <h2>Complaints Records</h2>
        <div>
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

            <div class="button-container">
            <button onclick="searchComplaints()">Search</button>
            <button onclick="generatePDF()" class="action-button">Generate PDF</button>
            </div>
           

        </div>

        <div id="complaintsTable"></div>
        
        
        <script>
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
            }

            function searchComplaints() {
                var selectedProvince = document.getElementById('province').value;
                var selectedDistrict = document.getElementById('district').value;

                // Fetch complaints data using JavaScript
                fetch('complaint_report.php?province=' + selectedProvince + '&district=' + selectedDistrict)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(data => {
                        document.getElementById('complaintsTable').innerHTML = data;
                    })
                    .catch(error => console.error('Fetch error:', error));
            }
            
            function cancelComplaintConfirmation(complaintId) {
                var confirmCancel = confirm('Are you sure you want to cancel this complaint?');

                if (confirmCancel) {
                    fetch('complaint_report.php?id=' + complaintId + '&action=cancel')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.text();
                        })
                        .then(data => {
                            alert(data); // Display the response (customize based on your needs)
                            searchComplaints(); // Refresh the complaints table after the action
                        })
                        .catch(error => console.error('Fetch error:', error));
                }
            }
            function handleAction(complaintId, action) {
                fetch('complaint_report.php?id=' + complaintId + '&action=' + action)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(data => {
                        alert(data); // Display the response (customize based on your needs)
                        searchComplaints(); // Refresh the complaints table after the action
                    })
                    .catch(error => console.error('Fetch error:', error));
            }

            function generatePDF() {
                    // Check if complaintsTable has content
                    var complaintsTable = document.getElementById('complaintsTable');
                    if (complaintsTable.innerHTML.trim() === '') {
                        alert('Please perform a search before generating PDF.');
                        return;
                    }

                    var confirmGeneratePDF = confirm('Do you want to generate the PDF?');

                    if (confirmGeneratePDF) {
                        console.log('Generating PDF...');

                        var selectedProvince = document.getElementById('province').value;
                        var selectedDistrict = document.getElementById('district').value;
                        var element = document.getElementById('complaintsTable');

                        // Specify the options (optional)
                        var options = {
                            margin: 10,
                            filename: 'complaints_records_' + selectedProvince + '_' + selectedDistrict + '.pdf',
                            image: { type: 'jpeg', quality: 0.98 },
                            html2canvas: { scale: 2 },
                            jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' } 
                        };

                        // Use html2pdf to generate the PDF
                        html2pdf().from(element).set(options).save();
                    } else {
                        console.log('PDF generation canceled.');
                    }
                }

            // Initial update of districts
            updateDistricts();
        </script>
    </div>
</body>
</html>


