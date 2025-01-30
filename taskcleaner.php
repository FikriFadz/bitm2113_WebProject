<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'webassgn');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cleanerId = $_SESSION['id'];

$cleanerTasks = [];
$stmt = $conn->prepare("SELECT cleanerTaskID, roomNumber, block, level, status, dateReport FROM cleanertask WHERE cleanerID = ? AND status = 'Pending'");
$stmt->bind_param("s", $cleanerId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cleanerTasks[] = $row;
    }
}
$stmt->close();

// Handle the "Mark as Done" functionality
if (isset($_POST['taskID'])) {
    $taskID = $_POST['taskID'];

    // Update the task status to 'Done'
    $stmt = $conn->prepare("UPDATE cleanertask SET status = ? WHERE cleanerTaskID = ?");
    $status = "Solved";
    $stmt->bind_param("si", $status, $taskID);

    if ($stmt->execute()) {
        echo "Task marked as done.";
    } else {
        echo "Error: Could not update task status.";
    }

    $stmt = $conn->prepare("UPDATE cleanliness SET status = ? WHERE cleanID = ?");
    $status = "Solved";
    $stmt->bind_param("si", $status, $taskID);

    if ($stmt->execute()) {
        echo "Task marked as done.";
    } else {
        echo "Error: Could not update task status.";
    }

    // Close the statement
    $stmt->close();
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Cleaner</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .main-content h2 {
            font-size: 28px;
            color: #003366;
            margin-bottom: 20px;
        }

        .main-content p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center;">
            <h1>HostelLog - Cleaner Task</h1>
        </div>
    </header>
    <!-- Sidebar -->
    <div class="sidebar">
        <input type="hidden" id="studentName" name="studentName">
        <img src="logoutem.png" alt="UTeM Logo">
        <h3 id="userName">Loading...</h3>
        <p id="userEmail">Loading...</p>
        <a href="schedulecleaner.php">Schedule</a>
        <a href="taskcleaner.php" class="active">Cleaner Task</a>
        <a href="taskhistory.php">Task History</a>
        <button id="logoutButton">Logout</button>
    </div>
    <div class="main-content">
        <h2>Cleaner Task</h2>
        <table>
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Room Number</th>
                    <th>Block</th>
                    <th>Level</th>
                    <th>Schedule Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($cleanerTasks)) {
                    echo "<tr><td colspan='6'>No data available.</td></tr>";
                } else {
                    foreach ($cleanerTasks as $task) {
                        echo "<tr data-id='" . htmlspecialchars($task['cleanerTaskID']) . "'>";
                        echo "<td>" . htmlspecialchars($task['cleanerTaskID']) . "</td>";
                        echo "<td>" . htmlspecialchars($task['roomNumber']) . "</td>";
                        echo "<td>" . htmlspecialchars($task['block']) . "</td>";
                        echo "<td>" . htmlspecialchars($task['level']) . "</td>";
                        echo "<td>" . htmlspecialchars($task['dateReport']) . "</td>";
                        echo "<td>";
                        echo "<div class='action-buttons'>";
                        echo "<button class='completed' onclick='markAsDone(" . htmlspecialchars($task['cleanerTaskID']) . ")'>Mark as Done</button>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>© 2024 Group 4 - Web Development</p>
    </footer>
    <script src="script.js"></script>

    <script>
        function markAsDone(taskID) {
            // Create a new AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true); // Posting to the same file
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Prepare the data to send (taskID to mark as done)
            var data = "taskID=" + taskID;

            // Send the request
            xhr.send(data);

            // Handle the response when the task is marked as done
            xhr.onload = function () {
                if (xhr.status == 200) {
                    // If the task was successfully marked as done, update the table
                    var row = document.querySelector("tr[data-id='" + taskID + "']");
                    row.remove();  // Remove the row

                    // Check if there are any tasks remaining
                    var rows = document.querySelectorAll("#taskTable tbody tr");
                    if (rows.length === 0) {
                        var noDataRow = document.createElement("tr");
                        var noDataCell = document.createElement("td");
                        noDataCell.colSpan = 6;
                        noDataCell.textContent = "No data available.";
                        noDataRow.appendChild(noDataCell);
                        document.querySelector("#taskTable tbody").appendChild(noDataRow);
                    }
                } else {
                    alert("Error: Could not mark task as done.");
                }
            };
        }
    </script>

</body>
</html>