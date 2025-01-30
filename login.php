<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webassgn";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, name, email FROM users WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);

$stmt->execute();

$stmt->store_result();

$stmt->bind_result($id, $name, $email);

if ($stmt->num_rows > 0) {
    $stmt->fetch();
    $_SESSION['id'] = $id;
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    
    // Determine the redirection URL based on the email domain
    $redirectUrl = '';
    if (strpos($email, '@student.') !== false) {
        $redirectUrl = 'dashboardstudent.html';
    } elseif (strpos($email, '@staff') !== false) {
        $redirectUrl = 'managestaff.php';
    } elseif (strpos($email, '@cleaner') !== false) {
        $redirectUrl = 'schedulecleaner.php';
    } elseif (strpos($email, '@technician') !== false) {
        $redirectUrl = 'maintenancestaff.php';
    }

    // Prepare response with dynamic redirect URL
    $response = [
        'success' => true,
        'redirect' => $redirectUrl
    ];
} else {
    // Failure: Return an error message
    $response = [
        'success' => false,
        'message' => 'Invalid email or password'
    ];
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return response as JSON
echo json_encode($response);
?>
