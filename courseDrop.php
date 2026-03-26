<?php session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php?type=warning&message=Please Login!!");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$courseId = (int)$_GET["courseid"];
$userIdInt = (int)$_SESSION["user_id"];

$sql = "DELETE FROM `learning`
        WHERE userId = ?
          AND sessionId = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $userIdInt, $courseId);
$stmt->execute();
header("Location: courseShow.php?courseid=".$courseId."&type=success&message=Course Droped. We hate to see you leave.");
?>