<?php session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php?type=warning&message=Please Login!!");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


$sql = "SELECT 1
FROM `learning`
WHERE userId = ?
  AND sessionId = ?
LIMIT 1;";

$courseId = (int) $_GET["courseid"];
$userIdInt = (int) $_SESSION["user_id"];

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $userIdInt, $courseId);
$stmt->execute();


$stmt->store_result(); // REQUIRED

if ($stmt->num_rows === 0) {
    $sql = "INSERT INTO `learning`
    (`userId`, `sessionId`)
    VALUES (?, ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $userIdInt, $courseId);
    $stmt->execute();
    header("Location: courseShow.php?courseid=" . $_GET["courseid"] . "&type=success&message=Enrolled Successfully");
} else {
    header("Location: courseShow.php?courseid=" . $_GET["courseid"] . "&type=danger&message=Already Enrolled!!!");
}


?>