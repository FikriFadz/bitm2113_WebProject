<?php
$conn = new mysqli('localhost', 'root', '', 'webassgn');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cleanId = isset($_GET['cleanId']) ? htmlspecialchars($_GET['cleanId']) : '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cleanId = $_POST['cleanId'];
    $cleanerId = $_POST['cleanerName'];
    $scheduleDate = $_POST['schedule'];
    $block = $_POST['kolej'];
    $level = $_POST['level'];

    // Retrieve cleaner name using cleaner ID
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("s", $cleanerId);
    $stmt->execute();
    $stmt->bind_result($cleanerName);
    $stmt->fetch();
    $stmt->close();

    // Retrieve cleanliness details from cleanliness table
    $stmt = $conn->prepare("SELECT roomNumber, status FROM cleanliness WHERE cleanID = ?");
    $stmt->bind_param("i", $cleanId);
    $stmt->execute();
    $stmt->bind_result($roomNumber, $status);
    $stmt->fetch();
    $stmt->close();

    // Insert into cleanerTask table
    $stmt = $conn->prepare("INSERT INTO cleanertask (cleanerTaskID, cleanerID, name, roomNumber,block, level, status, dateReport) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $cleanId, $cleanerId, $cleanerName, $roomNumber, $block, $level, $status, $scheduleDate);

    if ($stmt->execute()) {
        // Update visibility in reports table
        $updateStmt = $conn->prepare("UPDATE cleanliness SET visibility = 'No' WHERE cleanID = ?");
        $updateStmt->bind_param("i", $cleanId);
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

$cleaners = [];
$sqlCleaners = "SELECT id, name FROM users WHERE role = 'cleaner' AND visibility = 'Yes'";
$resultCleaners = $conn->query($sqlCleaners);
if ($resultCleaners->num_rows > 0) {
    while ($row = $resultCleaners->fetch_assoc()) {
        $cleaners[] = $row;
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
        .form-container input[readonly] {
            background-color: #e9ecef;
            color: #6c757d;
        }
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
        <h1>HostelLog - Assign Task</h1>
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
        <h2>Manage Cleaners</h2>

        <div class="form-container">
    <form id="cleanerForm" method="POST" action="">
        <label for="cleanId">Cleaner ID</label>
        <input type="text" id="cleanId" name="cleanId" value="<?php echo $cleanId; ?>" readonly required>

        <label for="cleanerName">Cleaner Name</label>
        <select id="cleanerName" name="cleanerName" required>
            <option value="">Select Cleaner</option>
            <?php
            foreach ($cleaners as $cleaner) {
                echo "<option value=\"" . htmlspecialchars($cleaner['id']) . "\">" . htmlspecialchars($cleaner['name']) . "</option>";
            }
            ?>
        </select>

        <label for="schedule">Schedule Date</label>
        <input type="date" id="schedule" name="schedule" required>

        <label for="kolej">Select Kolej Kediaman: </label>
        <select id="kolej" name="kolej" required>
            <option value="">Select Kolej Kediaman</option>
            <option value="Kolej Kediaman Tuah">Kolej Kediaman Tuah</option>
            <option value="Kolej Kediaman Jebat">Kolej Kediaman Jebat</option>
            <option value="Kolej Kediaman Kasturi">Kolej Kediaman Kasturi</option>
            <option value="Kolej Kediaman Lekir">Kolej Kediaman Lekir</option>
            <option value="Kolej Kediaman Lekiu">Kolej Kediaman Lekiu</option>
        </select>

        <label for="level">Select Level: </label>
        <select id="level" name="level" required>
            <option value="">Select Level</option>
            <option value="1">Level 1</option>
            <option value="2">Level 2</option>
            <option value="3">Level 3</option>
            <option value="4">Level 4</option>
            <option value="5">Level 5</option>
            <option value="6">Level 6</option>
            <option value="7">Level 7</option>
            <option value="8">Level 8</option>
            <option value="9">Level 9</option>
        </select>

        <button type="submit">Assign Cleaner</button>
    </form>

    <div class="button-container">
        <div class="back-button">
            <a href="pending.php">Back</a>
        </div>
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

        // Placeholder for form submissions
        // document.getElementById("cleanerForm").addEventListener("submit", function (e) {
        //     e.preventDefault();
        //     alert("Cleaner assigned successfully!");
        // });
    </script>
</body>
</html>