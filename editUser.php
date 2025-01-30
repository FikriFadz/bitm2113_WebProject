<?php
$conn = new mysqli('localhost', 'root', '', 'webassgn');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = isset($_GET['id']) ? $_GET['id'] : '';
$userData = null;

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $userId);

    if ($stmt->execute()) {
        header("Location: managestaff.php?status=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} elseif ($userId) {
    // Fetch user data based on userId from URL
    $sql = "SELECT id, name, email, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Details</title>
    <style>
       /* General Body Styles */
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f9f9f9;
    color: #333;
}

/* Edit User Container */
.edit-user-container {
    max-width: 600px;
    margin: 0 auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Page Title */
.edit-user-container h2 {
    text-align: center;
    color: #007bff;
    margin-bottom: 20px;
}

/* Table Styles */
table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
    border: 1px solid #ddd;
    background-color: #fefefe;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: white;
}

td:first-child {
    font-weight: bold;
    width: 30%;
}

/* Input Fields */
input[type="text"], input[type="email"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 14px;
    background-color: #f9f9f9;
    transition: background-color 0.3s, border-color 0.3s;
}

input[type="text"]:read-only, input[type="email"]:read-only {
    background-color: #e9ecef;
    color: #6c757d;
}

input[type="text"]:focus, input[type="email"]:focus {
    border-color: #007bff;
    outline: none;
    background-color: #fff;
}

/* Form Actions */
.form-actions {
    text-align: center;
    margin-top: 20px;
}

button {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    font-size: 14px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0056b3;
}

button:focus {
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Responsive Design */
@media (max-width: 768px) {
    table th, table td {
        padding: 8px;
        font-size: 14px;
    }

    .form-actions button {
        padding: 8px 15px;
        font-size: 13px;
    }
}

    </style>
</head>
<body>
<div class="edit-user-container">
    <h2>Edit User Details</h2>
    <form id="editUserForm" method="POST" action="">
        <!-- Hidden Input for User ID -->
        <input type="hidden" id="userId" name="userId" value="<?php echo htmlspecialchars($userId); ?>">
        <table>
            <tr>
                <td><strong>Role:</strong></td>
                <td><input type="text" id="role" name="role" readonly></td>
            </tr>
            <tr>
                <td><strong>Full Name:</strong></td>
                <td><input type="text" id="name" name="name"></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td><input type="email" id="email" name="email"></td>
            </tr>
        </table>
        <div class="form-actions">
            <button type="submit">Save Changes</button>
            <!-- Back Button -->
            <button type="button" onclick="window.history.back();">Back</button>
        </div>
    </form>
</div>


    <script>
        // Fetch user data from PHP
        const userData = <?php echo json_encode($userData); ?>;

        // Populate the form with user data
        if (userData) {
            document.getElementById("role").value = userData.role;
            document.getElementById("name").value = userData.name;
            document.getElementById("email").value = userData.email;
        }
    </script>
</body>
</html>