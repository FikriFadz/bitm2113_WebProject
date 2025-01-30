<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'webassgn');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cleanerName = $_SESSION['name'];
$cleanerId = $_SESSION['id'];
$sql = "SELECT * FROM cleanerdata WHERE name = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $cleanerName);
$stmt->execute();
$stmt->bind_result($id, $name, $block, $level);
if ($stmt->fetch()) {
    echo "Cleaner ID: $id, Name: $name, Block: $block, Level: $level";
} else {
    echo "No cleaner found with name: $cleanerName";
}
$stmt->close();

date_default_timezone_set('Asia/Singapore');
$assignedDate = date("d-m-Y");
$assignedTime = date("H:i");

if (isset($_POST['submitChecklist'])) {
    $cleanToilet = isset($_POST['cleanToilet']) ? 'done' : 'undone';
    $cleanSink = isset($_POST['cleanSink']) ? 'done' : 'undone';
    $cleanShower = isset($_POST['cleanShower']) ? 'done' : 'undone';
    $wipeMirrors = isset($_POST['wipeMirrors']) ? 'done' : 'undone';
    $cleanFloor = isset($_POST['cleanFloor']) ? 'done' : 'undone';

    $insertSql = "INSERT INTO cleanerreport (cleanerId, name, date, time, cleanToilet, cleanSink, cleanShower, wipeMirrors, cleanFloor, block, level) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssssss", $cleanerId, $cleanerName, $assignedDate, $assignedTime, $cleanToilet, $cleanSink, $cleanShower, $wipeMirrors, $cleanFloor, $block, $level);
    
    if ($stmt->execute()) {
        echo "<script>alert('Checklist submitted successfully!');</script>";
    } else {
        echo "Error submitting checklist: " . $conn->error;
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleaner Schedule</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center;">
            <h1>HostelLog - Cleaner Schedule</h1>
        </div>
    </header>
    <!-- Sidebar -->
    <div class="sidebar">
        <input type="hidden" id="studentName" name="studentName">
        <img src="logoutem.png" alt="UTeM Logo">
        <h3 id="userName">Loading...</h3>
        <p id="userEmail">Loading...</p>
        <a href="schedulecleaner.php" class="active">Schedule</a>
        <a href="taskcleaner.php">Cleaner Task</a>
        <a href="taskhistory.php">Task History</a>
        <button id="logoutButton">Logout</button>
    </div>

    <div class="main-content">
        <div class="form-container">
            <form method="POST">
                <h2>Assigned Schedule</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Block</th>
                            <th>Level</th>
                            <th>Assigned Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($block); ?></td>
                            <td><?php echo htmlspecialchars($level); ?></td>
                            <td><?php echo $assignedDate; ?></td>
                        </tr>
                    </tbody>
                </table>

                <h2>Daily Cleaning Checklist</h2>
                <table class="checklist">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Clean toilet bowl</td>
                            <td><input type="checkbox" name="cleanToilet" value="done"></td>
                        </tr>
                        <tr>
                            <td>Clean sink</td>
                            <td><input type="checkbox" name="cleanSink" value="done"></td>
                        </tr>
                        <tr>
                            <td>Clean shower room</td>
                            <td><input type="checkbox" name="cleanShower" value="done"></td>
                        </tr>
                        <tr>
                            <td>Wipe mirrors</td>
                            <td><input type="checkbox" name="wipeMirrors" value="done"></td>
                        </tr>
                        <tr>
                            <td>Clean floor</td>
                            <td><input type="checkbox" name="cleanFloor" value="done"></td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" name="submitChecklist" class="submit-button">Submit Checklist</button>
            </form>
        </div>
    </div>

    <footer>
        <p>© 2024 Group 4 - Web Development</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>
