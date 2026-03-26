<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php?type=warning&message=PleaseLogin");
    exit;
}
$courseId  = (int)$_GET['courseid'];
$teacherId = (int)$_SESSION['user_id'];

$mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$checkSql = "SELECT teacherId FROM `session` WHERE id = ?";
$checkStmt = $mysqli->prepare($checkSql);
$checkStmt->bind_param("i", $courseId);
$checkStmt->execute();
$res = $checkStmt->get_result();
$row = $res->fetch_assoc();
$checkStmt->close();

if (!$row) {
    header("Location: teacheradmin.php?type=danger&message=CourseNotFound");
    exit;
}

if ((int)$row['teacherId'] !== $teacherId) {
    header("Location: signIn.php?type=danger&message=Not Authorized");
    exit;
}

$delSql = "DELETE FROM `session` WHERE id = ?";
$delStmt = $mysqli->prepare($delSql);
$delStmt->bind_param("i", $courseId);
$delStmt->execute();

header("Location: teacheradmin.php?type=success&message=Course Deleted Sucessfully!!!");
?>