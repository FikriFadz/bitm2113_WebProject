<?php
$conn = new mysqli('localhost', 'root', '', 'webassgn');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    if (preg_match('/^[a-zA-Z0-9]+$/', $deleteId)) {
        $sqlDelete = "UPDATE users SET visibility='No' WHERE id=?";
        $stmt = $conn->prepare($sqlDelete);
        
        if ($stmt) {
            $stmt->bind_param("s", $deleteId);
            if ($stmt->execute()) {
                echo "<script>console.log('User visibility updated successfully for ID: $deleteId');</script>";
                header("Location: managestaff.php?status=success");
                exit;
            } else {
                echo "<script>console.error('Error updating user visibility: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>console.error('Failed to prepare SQL statement');</script>";
        }
    } else {
        echo "<script>console.error('Invalid user ID');</script>";
    }
}

$sql = "SELECT id, name, email, role FROM users WHERE visibility='Yes'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .search-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .search-section input {
            padding: 10px 15px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border 0.3s;
        }

        .search-section input:focus {
            border-color: #0078D7;
            outline: none;
        }

        .search-section button {
            padding: 10px 15px;
            background-color: #0078D7;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-section button:hover {
            background-color: #005fa3;
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
            <h1>HostelLog - Manage Staff</h1>
        </div>
    </header>
    <!-- Sidebar -->
    <div class="sidebar">
        <input type="hidden" id="studentName" name="studentName">
        <img src="logoutem.png" alt="UTeM Logo">
        <h3 id="userName">Loading...</h3>
        <p id="userEmail">Loading...</p>
        <!-- <a href="maintenancestaff.php">Maintenance Request</a> -->
        <a href="managestaff.php" class="active">Manage User</a>
        <a href="pending.php">Assign Task</a>
        <a href="datareportstaff.php">Data Report</a>
        <button id="logoutButton">Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
    <h2>Manage User</h2>
    <div class="table-container">
        <div class="search-section">
            <input type="text" id="searchInput" placeholder="Search User">
            <button id="searchButton">Search</button>
        </div>
        <table id="userTable">
            <thead>
                <tr>
                    <th onclick="sortTable(0)">Name</th>
                    <th onclick="sortTable(1)">Email</th>
                    <th onclick="sortTable(2)">Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['role']); ?></td>
                <td>
                    <div class="action-buttons">
                        <button class="edit" onclick="location.href='editUser.php?id=<?php echo $row['id']; ?>';">Edit</button>
                        <button class="pending" onclick="deleteUser('<?php echo $row['id']; ?>')">Delete</button>
                    </div>
                </td>
            </tr>
        <?php
    }
} else {
    ?>
    <tr><td colspan="4">No users found.</td></tr>
    <?php
}

$conn->close();
?>
            </tbody>
        </table>
    </div>
</div>

    <script src="script.js"></script>
    <script>
        function editUser(userId) {
            window.location.href = `edituser.php?id=${userId}`;
        }

        function deleteUser(userId) {
            console.log(`Deleting user with ID: ${userId}`);
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = `managestaff.php?delete_id=${userId}`;
            }
        }

        function sortTable(columnIndex) {
            const table = document.querySelector("table tbody");
            const rows = Array.from(table.querySelectorAll("tr"));

            rows.sort((a, b) => {
                const aText = a.cells[columnIndex].innerText.trim().toLowerCase();
                const bText = b.cells[columnIndex].innerText.trim().toLowerCase();
                return aText.localeCompare(bText);
            });

            rows.forEach(row => table.appendChild(row));
        }

        document.getElementById("searchButton").addEventListener("click", function () {
        const query = document.getElementById("searchInput").value.toLowerCase();
        const rows = document.querySelectorAll("#userTable tbody tr");
    
        rows.forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            const role = row.cells[2].textContent.toLowerCase();
        
        if (name.includes(query) || email.includes(query) || role.includes(query)) {
            row.style.display = ""; // Show row
        } else {
            row.style.display = "none"; // Hide row
        }
    });
});
    </script>
</body>
</html>
