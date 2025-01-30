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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentName = $conn->real_escape_string($_POST['studentName']);
    $cleanliness = $conn->real_escape_string($_POST['cleanliness']);
    $hygiene = $conn->real_escape_string($_POST['hygiene']);
    $comments = $conn->real_escape_string($_POST['comments']);
    $checkInOrOut = $conn->real_escape_string($_POST['checklistType']);

    $sql = "INSERT INTO cleanliness (studentName, roomNumber, roomCleanliness, roomHygiene, additionalComments, checkInOrOut) 
            VALUES ('$studentName', '$roomNumber', '$cleanliness', '$hygiene', '$comments', '$checkInOrOut')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Checklist submitted successfully'); window.location.href='checkliststudent.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>