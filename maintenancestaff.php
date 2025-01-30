<?php
$conn = new mysqli('localhost', 'root', '', 'webassgn');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $reportID = $data['reportID'];

    if (!empty($reportID)) {
        // Update the status in the techniciantask table
        $sql = "UPDATE techniciantask SET status = 'Solved' WHERE technicianTaskID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reportID);

        if ($stmt->execute()) {
            // Update the status in the reports table
            $sql = "UPDATE reports SET status = 'Solved' WHERE reportID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $reportID);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status in reports table.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status in techniciantask table.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid report ID.']);
    }

    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Requests</title>
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
            <h1>HostelLog - Maintenance Requests</h1>
        </div>
    </header>
    <div class="sidebar">
    <input type="hidden" id="studentName" name="studentName">
        <img src="logoutem.png" alt="UTeM Logo">
        <h3 id="userName">Loading...</h3>
        <p id="userEmail">Loading...</p>
        <a href="maintenancestaff.html" class="active">Maintenance Request</a>
        <!-- <a href="managestaff.php">Manage User</a>
        <a href="assigntask.php">Assign Task</a>
        <a href="datareportstaff.php">Data Report</a> -->
        <button id="logoutButton">Logout</button>
    </div>
    <div class="main-content">
        <h2>Maintenance Task Records</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Student Name</th>
                        <th>Room Number</th>
                        <th>Issue</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="reportTable">
                    <?php
                    session_start();
                    $technicianID = $_SESSION['name'];
                    $sql = "SELECT technicianTaskID, studentName, roomNumber, roomIssues FROM techniciantask WHERE status = 'Pending' AND technicianname = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $technicianID);
                    $stmt->execute();
                    $result = $stmt->get_result();      

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr data-id='" . $row['technicianTaskID'] . "'>";
                            echo "<td>" . $row['technicianTaskID'] . "</td>";
                            echo "<td>" . $row['studentName'] . "</td>";
                            echo "<td>" . $row['roomNumber'] . "</td>";
                            echo "<td>" . $row['roomIssues'] . "</td>";
                            echo "<td>";
                            echo "<div class='action-buttons'>";
                            echo "<button class='completed' onclick='markAsCompleted(" . $row['technicianTaskID'] . ")'>Mark as Completed</button>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No maintenance requests found.</td></tr>";
                    }
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
        function markAsCompleted(reportID) {
            fetch('', { // Current file is the endpoint
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reportID: reportID })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully.');
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</body>
</html>
