<?php session_start();

$mysqli = new mysqli("localhost", "root", "", "skillshareLocal");

$userId = (int)$_SESSION["user_id"];
$reviewId = (int)$_GET["reviewid"];

$sql = "DELETE FROM `review`
        WHERE id = ?
          AND userId = ?";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("ii", $reviewId, $userId);
$stmt->execute();

header("Location: courseShow.php?courseid=".$_GET["courseid"]."&type=success&message=Review Deleted Sucessfully!");
?>