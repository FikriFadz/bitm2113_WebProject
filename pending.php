<?php
$conn = new mysqli('localhost', 'root', '', 'webassgn');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch maintenance reports from the reports table
$maintenanceReports = [];
$sqlReports = "SELECT reportID, roomNumber, issuesDescription, status FROM reports WHERE visibility ='Yes'";
$resultReports = $conn->query($sqlReports);
if ($resultReports->num_rows > 0) {
    while ($row = $resultReports->fetch_assoc()) {
        $maintenanceReports[] = $row;
    }
}

// Fetch cleaner reports from the cleanliness table
$cleanerReports = [];
$sqlCleanliness = "SELECT cleanID, roomNumber, additionalComments, status FROM cleanliness WHERE visibility ='Yes'";
$resultCleanliness = $conn->query($sqlCleanliness);
if ($resultCleanliness->num_rows > 0) {
    while ($row = $resultCleanliness->fetch_assoc()) {
        $cleanerReports[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Reports</title>
    <link rel="stylesheet" href="style.css">
    <style>
        button {
            padding: 8px 15px;
            background-color: #0078D7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #005fa3;
        }

        .status {
            color: #ff9800;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="logoutem.png" alt="UTeM Logo">
        <input type="hidden" id="studentName" name="studentName">
        <h3 id="userName">Loading...</h3>
        <p id="userEmail">Loading...</p>
        <a href="managestaff.php">Manage User</a>
        <a href="pending.php" class="active">Assign Task</a>
        <a href="datareportstaff.php">Data Report</a>
        <button id="logoutButton">Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>HostelLog - Pending Reports</h1>
        </header>
        <h2>Pending Reports</h2>
        <div class="table-container">
            <h3>Maintenance Reports</h3>
            <table>
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($maintenanceReports as $report) {
                        echo "<tr>
                            <td>" . htmlspecialchars($report['roomNumber']) . "</td>
                            <td>" . htmlspecialchars($report['issuesDescription']) . "</td>
                            <td class='status'>" . htmlspecialchars($report['status']) . "</td>
                            <td>
                                <button onclick=\"assignTask('" . htmlspecialchars($report['reportID']) . "')\">Assign Task</button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="table-container">
            <h3>Cleaner Reports</h3>
            <table>
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($cleanerReports as $cleaner) {
                        echo "<tr>
                            <td>" . htmlspecialchars($cleaner['roomNumber']) . "</td>
                            <td>" . htmlspecialchars($cleaner['additionalComments']) . "</td>
                            <td class='status'>" . htmlspecialchars($cleaner['status']) . "</td>
                            <td>
                                <button onclick=\"assignCleaner('" . htmlspecialchars($cleaner['cleanID']) . "')\">Assign Task</button>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <footer>
            <p>© 2024 Group 4 - Web Development</p>
        </footer>
    </div>

    <script src="script.js"></script>
    <script>
        // Handle Logout
        document.getElementById("logoutButton").addEventListener("click", function () {
            window.location.href = "home.html";
        });

        // Redirect to assign task page
        function assignTask(reportId) {
            window.location.href = `assigntask.php?reportId=${reportId}`;
        }

        function assignCleaner(cleanerId) {
            window.location.href = `assignCleaner.php?cleanId=${cleanerId}`;
        }
    </script>
</body>
</html>