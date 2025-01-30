<?php
$conn = new mysqli('localhost', 'root', '', 'webassgn');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reportId = isset($_GET['reportId']) ? htmlspecialchars($_GET['reportId']) : '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reportId = $_POST['reportId'];
    $technicianId = $_POST['maintenanceName'];
    $scheduleDate = $_POST['schedule'];

    // Retrieve technician name using technician ID
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("s", $technicianId);
    $stmt->execute();
    $stmt->bind_result($technicianName);
    $stmt->fetch();
    $stmt->close();

    // Retrieve report details from reports table
    $stmt = $conn->prepare("SELECT studentName, roomNumber, roomIssues, issuesDescription, status FROM reports WHERE reportID = ?");
    $stmt->bind_param("i", $reportId);
    $stmt->execute();
    $stmt->bind_result($studentName, $roomNumber, $roomIssues, $issueDescription, $status);
    $stmt->fetch();
    $stmt->close();

    // Insert into techniciantask table
    $stmt = $conn->prepare("INSERT INTO techniciantask (technicianTaskID, technicianID, technicianname, studentName, roomNumber, roomIssues, issuesDescription, status, dateReport) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $reportId, $technicianId, $technicianName, $studentName, $roomNumber, $roomIssues, $issueDescription, $status, $scheduleDate);

    if ($stmt->execute()) {
        // Update visibility in reports table
        $updateStmt = $conn->prepare("UPDATE reports SET visibility = 'No' WHERE reportID = ?");
        $updateStmt->bind_param("i", $reportId);
        if ($updateStmt->execute()) {
            echo "Maintenance task assigned and report visibility updated successfully!";
            header("Location: pending.php");
            exit();
        } else {
            echo "Maintenance task assigned, but failed to update report visibility: " . $updateStmt->error;
        }
        $updateStmt->close();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$technicians = [];
$sqlTechnicians = "SELECT id, name FROM users WHERE role = 'technician' AND visibility = 'Yes'";
$resultTechnicians = $conn->query($sqlTechnicians);
if ($resultTechnicians->num_rows > 0) {
    while ($row = $resultTechnicians->fetch_assoc()) {
        $technicians[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Task</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-container h3 {
            color: #003366;
            margin-bottom: 15px;
        }

        .form-container label {
            font-size: 16px;
            color: #003366;
            display: block;
            margin-bottom: 8px;
        }

        .form-container select, .form-container input, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background-color: #fff;
        }

        .form-container input[readonly] {
            background-color: #e9ecef;
            color: #6c757d;
        }

        .form-container button {
            padding: 12px 20px;
            background-color: #0078D7;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #005fa3;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .back-button {
            text-align: center;
            margin-top: 20px;
        }

        .back-button a {
            margin-bottom: 20px;
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #0078D7;
            padding: 12px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-button a:hover {
            background-color: #005fa3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                grid-template-columns: 1fr;
            }

            .sidebar {
                flex-direction: row;
                justify-content: space-around;
                align-items: center;
                padding: 10px;
            }

            .sidebar img {
                width: 50px;
            }

            .sidebar h3, .sidebar p {
                display: none;
            }

            .form-container {
                max-width: 100%;
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center;">
            <h1>HostelLog - Assign Task</h1>
        </div>
    </header>
    <div class="sidebar">
        <input type="hidden" id="studentName" name="studentName">
        <img src="logoutem.png" alt="UTeM Logo">
        <h3 id="userName">Loading...</h3>
        <p id="userEmail">Loading...</p>
        <a href="managestaff.php">Manage User</a>
        <a href="pending.php" class="active">Assign Task</a>
        <a href="datareportstaff.php">Data Report</a>
        <button id="logoutButton">Logout</button>
    </div>
    
    <div class="main-content">
        <h2>Assign Maintenance Task</h2>

        <div class="form-container">
            <form id="maintenanceForm" method="POST" action="">
                <label for="reportId">Report ID</label>
                <input type="text" id="reportId" name="reportId" value="<?php echo $reportId; ?>" readonly required>

                <label for="maintenanceName">Maintenance Staff Name</label>
                <select id="maintenanceName" name="maintenanceName" required>
                    <option value="">Select Technician</option>
                    <?php
                    foreach ($technicians as $technician) {
                        echo "<option value=\"" . htmlspecialchars($technician['id']) . "\">" . htmlspecialchars($technician['name']) . "</option>";
                    }
                    ?>
                </select>

                <label for="schedule">Schedule Date</label>
                <input type="date" id="schedule" name="schedule" required>

                <button type="submit">Assign Task</button>
            </form>

            <div class="button-container">
                <div class="back-button">
                    <a href="pending.php">Back</a>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>© 2024 Group 4 - Web Development</p>
    </footer>

    <script>
        // Handle Logout
        document.getElementById("logoutButton").addEventListener("click", function () {
            window.location.href = "home.html";
        });
    </script>

    <script src="script.js"></script>
    <!-- <script src="validations.js"></script> -->
</body>
</html>