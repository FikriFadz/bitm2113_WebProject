<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'webassgn'; 

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cleanerId = $_SESSION['id'];

$sql = "SELECT * FROM cleanerreport WHERE cleanerId = '$cleanerId'";
$result = $conn->query($sql);
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

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #003366;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center;">
            <h1>HostelLog - Cleaner task history</h1>
        </div>
    </header>

   <!-- Sidebar -->
   <div class="sidebar">
        <input type="hidden" id="studentName" name="studentName">
        <img src="logoutem.png" alt="UTeM Logo">
        <h3 id="userName">Loading...</h3>
        <p id="userEmail">Loading...</p>
        <a href="schedulecleaner.php">Schedule</a>
        <a href="taskcleaner.php">Cleaner Task</a>
        <a href="taskhistory.php" class="active">Task History</a>
        <button id="logoutButton">Logout</button>
    </div>

    <div class="main-content">
        <div class="form-container">
            <form>
                <h2>Completed Tasks History</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Assigned Date</th>
                            <th>Block</th>
                            <th>Level</th>
                            <th>Toilet Bowl</th>
                            <th>Sink</th>
                            <th>Shower Room</th>
                            <th>Mirror</th>
                            <th>Floor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are any results
                        if ($result->num_rows > 0) {
                            // Output data for each row
                            $count = 1;
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$count}</td>
                                    <td>{$row['date']}</td>
                                    <td>{$row['block']}</td>
                                    <td>{$row['level']}</td>
                                    <td>" . ($row['cleanToilet'] == 'done' ? '✔' : '✘') . "</td>
                                    <td>" . ($row['cleanSink'] == 'done' ? '✔' : '✘') . "</td>
                                    <td>" . ($row['cleanShower'] == 'done' ? '✔' : '✘') . "</td>
                                    <td>" . ($row['wipeMirrors'] == 'done' ? '✔' : '✘') . "</td>
                                    <td>" . ($row['cleanFloor'] == 'done' ? '✔' : '✘') . "</td>
                                </tr>";
                                $count++;
                            }
                        } else {
                            echo "<tr><td colspan='10'>No completed tasks found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <footer>
        <p>© 2024 Group 4 - Web Development</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
