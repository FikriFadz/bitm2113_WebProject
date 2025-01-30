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

$sessionId = $_SESSION['id'];
$sql = "SELECT roomNumber FROM studentData WHERE studentID = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $sessionId);
    $stmt->execute();
    $stmt->bind_result($roomNumber);
    if ($stmt->fetch()) {
        $localRoomNumber = $roomNumber;
    } else {
        echo "No user found with session ID: $sessionId";
    }
    $stmt->close();
} else {
    echo "Error preparing query: " . $conn->error;
}

$reportDate = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentName = $conn->real_escape_string($_POST['studentName']);
    $issue = $conn->real_escape_string($_POST['issue']);
    $description = $conn->real_escape_string($_POST['description']);

    $sql = "INSERT INTO reports (studentName, roomNumber, roomIssues, issuesDescription, dateReport) VALUES ('$studentName', '$roomNumber', '$issue', '$description', '$reportDate')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New record created successfully'); window.location.href='reportstudent.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>