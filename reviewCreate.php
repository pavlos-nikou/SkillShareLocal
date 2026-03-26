<?php session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $sql = "INSERT INTO `review` (`userId`, `sessionId`, `review`)
VALUES (?, ?, ?);";

    $courseId = (int) $_POST["courseId"];
    $userIdInt = (int) $_SESSION["user_id"];
    $review = $_POST["review"];

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iis", $userIdInt, $courseId, $review);

    $stmt->execute();

    header("Location: courseShow.php?courseid=" . $courseId . "&type=success&message=review submitted!");
    exit();
}
?>