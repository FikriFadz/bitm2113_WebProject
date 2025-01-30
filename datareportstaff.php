<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Report - Room Issues & Cleanliness</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .table-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #0078D7;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            color: #fff;
            transition: background-color 0.3s;
        }

        .completed {
            background-color: #28a745;
        }

        .completed:hover {
            background-color: #218838;
        }

        .pending {
            background-color: #dc3545;
        }

        .pending:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table th, table td {
                padding: 8px 10px;
                font-size: 14px;
            }

            .action-buttons button {
                font-size: 12px;
                padding: 6px 10px;
            }
        }

        @media (max-width: 480px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            table th {
                display: none;
            }

            table td {
                display: flex;
                justify-content: space-between;
                padding: 10px 0;
                border: none;
                border-bottom: 1px solid #ddd;
            }

            table td:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center;">
            <h1>HostelLog - Data Report</h1>
        </div>
    </header>
    <div class="sidebar">
        <input type="hidden" id="studentName" name="studentName">
        <img src="logoutem.png" alt="UTeM Logo">
        <h3 id="userName">Loading...</h3>
        <p id="userEmail">Loading...</p>
        <!-- <a href="maintenancestaff.php">Maintenance Request</a> -->
        <a href="managestaff.php">Manage User</a>
        <a href="pending.php">Assign Task</a>
        <a href="datareportstaff.php" class="active">Data Report</a>
        <button id="logoutButton">Logout</button>
    </div>
    <div class="main-content">
        <h2>Data Report Issues</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Technician Report ID</th>
                        <th>Technician Name</th>
                        <th>Room Number</th>
                        <th>Issue Description</th>
                        <th>Date Reported</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'webassgn');

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Fetch report data
                    $sql = "SELECT technicianTaskID, technicianname, roomNumber, roomIssues, dateReport, status FROM techniciantask";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['technicianTaskID'] . "</td>";
                            echo "<td>" . $row['technicianname'] . "</td>";
                            echo "<td>" . $row['roomNumber'] . "</td>";
                            echo "<td>" . $row['roomIssues'] . "</td>";
                            echo "<td>" . $row['dateReport'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No data available.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

        <h2>Cleanliness Data Report</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Cleaner Report ID</th>
                        <th>Cleaner Name</th>
                        <th>Room Number</th>
                        <th>Block</th>
                        <th>Level</th>
                        <th>Date Reported</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Reconnect to database to fetch cleanliness data
                    $conn = new mysqli('localhost', 'root', '', 'webassgn');

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Fetch cleanliness data
                    $sql_cleanliness = "SELECT cleanerTaskID, name, roomNumber, block, level, status, dateReport FROM cleanertask";
                    $result_cleanliness = $conn->query($sql_cleanliness);

                    if ($result_cleanliness->num_rows > 0) {
                        while ($row = $result_cleanliness->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['cleanerTaskID'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['roomNumber'] . "</td>";
                            echo "<td>" . $row['block'] . "</td>";
                            echo "<td>" . $row['level'] . "</td>";
                            echo "<td>" . $row['dateReport'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No data available.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        <p>© 2024 Group 4 - Web Development</p>
    </footer>

    <script src="script.js"></script>
    <script>
        // Handle Logout
        document.getElementById("logoutButton").addEventListener("click", function () {
            window.location.href = "home.html";
        });
    </script>
</body>
</html>
