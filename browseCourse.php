<?php session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php?type=warning&message=Please Login!!");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT 
    s.*,
    c.categoryName,
    t.username,
    COUNT(l.userId) AS participantCount
FROM `session` s
JOIN `category` c ON s.categoryId = c.id
JOIN `teacher` t ON t.id = s.teacherId
LEFT JOIN `learning` l ON l.sessionId = s.id
GROUP BY s.id
ORDER BY s.id DESC;";


$stmt = $mysqli->prepare($sql);


$stmt->execute();

$courses = $stmt->get_result();

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

</html>

<?php include("./partials/header.php"); ?>

<body>
    <?php include("./partials/navbar.php"); ?>
    <div class="course-container">
        <div class="course-header">
            <span>Browse Course</span>
        </div>
        <div class="course-list">
            <?php while ($course = $courses->fetch_assoc()): ?>
                <div class="course">
                    <div class="course-left">
                        <img src="<?= htmlspecialchars($course['img']) ?>"
                            alt="course image">
                    </div>
                    <div class="course-right">
                        <a href="courseShow.php?courseid=<?= htmlspecialchars($course['id']) ?>" ><h2 class="course-title"><?= htmlspecialchars($course['title']) ?></h2></a>
                        <h3>Description:</h3>
                        <p class="indent"><?= htmlspecialchars($course['desc']) ?></p>
                        <div class="mt-5"><img src="utils/icons/category-sold-full.svg" alt="category" class="icon"><?= htmlspecialchars($course['categoryName']) ?></div>
                        <div class="location"><img src="utils/icons/location-pin-solid-full.svg" alt="location-icon"
                                class="icon"><?= htmlspecialchars($course['location']) ?></div>
                        <span class="emmitions"><img src="utils/icons/user-solid-full.svg" alt="emmition-icon" class="icon">
                            <?= htmlspecialchars($course['participantCount']) ?></span>
                        <span class="cost"><img src="utils/icons/dollar-sign-solid-full.svg" alt="cost icon"
                                class="icon"><?= htmlspecialchars($course['cost']) ?></span>
                        <span class="teacher"><img src="utils/icons/chalkboard-user-solid-full.svg" alt="teacher-icon"
                                class="icon"><?= htmlspecialchars($course['username']) ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include("./partials/footer.php"); ?>
</body>