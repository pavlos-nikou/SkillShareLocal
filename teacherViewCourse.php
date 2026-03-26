<?php session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php?type=warning&message=Please Login!!");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT s.*, c.categoryName, t.username
FROM `session` s
JOIN `category` c ON s.categoryId = c.id
JOIN `teacher` t ON t.id = s.teacherId
WHERE s.id = ?";

$stmt = $mysqli->prepare($sql);
$courseId = (int) $_GET["courseid"];
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();



$sql = "SELECT 
            u.id,
            u.username,
            u.email
        FROM `learning` l
        JOIN `user` u ON u.id = l.userId
        WHERE l.sessionId = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $courseId);
$stmt->execute();

$result = $stmt->get_result();
$participants = $result->fetch_all(MYSQLI_ASSOC);
$participantCount = count($participants);


$sql = "SELECT 
            r.id,
            r.review,
            r.sessionId,
            u.id   AS userId,
            u.name AS userName
        FROM `review` r
        JOIN `user` u ON u.id = r.userId
        WHERE r.sessionId = ?
        ORDER BY r.id DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $courseId);
$stmt->execute();

$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);

$teacherId = (int) $_SESSION['user_id'];

if ((int) $course['teacherId'] !== $teacherId) {
    header("Location: signIn.php?type=danger&message=Access Denied");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

</html>

<?php include("./partials/header.php"); ?>

<body>
    <?php include("./partials/navbar.php"); ?>
    <div class="course-show">
        <div class="card course-details">
            <img src="<?= $course["img"] ?>" class="card-img-top" alt="...">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <h2><?= $course["title"] ?></h2>
                    </li>
                    <li class="list-group-item">
                        <img src="utils/icons/clock-solid-full.svg" alt="duration"
                            class="icon"><?= $course["duration"] ?>
                        <img src="utils/icons/dollar-sign-solid-full.svg" alt="cost" class="icon"><?= $course["cost"] ?>
                        <img src="utils/icons/leaf-solid-full.svg" alt="duration"
                            class="icon"><span class="impactScore"><?= $participantCount ?></span>
                    </li>
                    <li class="list-group-item"><img src="utils/icons/category-sold-full.svg" alt="category"
                            class="icon"><span class="category"><?= $course["categoryName"] ?></span></li>
                    <li class="list-group-item"><img src="utils/icons/location-pin-solid-full.svg" alt="category"
                            class="icon"><?= $course["location"] ?></li>
                    <li class="list-group-item"><img src="utils/icons/chalkboard-user-solid-full.svg" alt="category"
                            class="icon"><?= $course["username"] ?></li>
                    <li class="list-group-item">
                        <h4>Description</h4><?= $course["desc"] ?>
                    </li>
                </ul>
                <div class="edit-delete-container">
                    <a href="courseEdit.php?courseid=<?= htmlspecialchars($course['id']) ?>"><img
                            src="utils/icons/edit-solid.svg" class="edit-icon icon" alt="edit"></a>
                    <a href="courseDelete.php?courseid=<?= htmlspecialchars($course['id']) ?>"><img
                            src="utils/icons/trash-solid-full.svg" class="edit-icon icon" alt="edit"></a>
                </div>
            </div>
        </div>
        <div class="course-reviews">
            <div class="enrolled-student-container">
                <h1>Enrolled Students</h1>
                <?php if (!empty($participants)): ?>
                    <ul class="list-group">
                        <?php foreach ($participants as $user): ?>
                            <li class="list-group-item">
                                <?= htmlspecialchars($user['username']) ?>
                                (<?= htmlspecialchars($user['email']) ?>)
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No users enrolled yet.</p>
                <?php endif; ?>
            </div>
            <h1 id="review-head">Reviews</h1>
            <div class="review-container">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <h2><img src="utils/icons/chalkboard-user-solid-full.svg" alt="user icon"
                                    class="icon"><?= htmlspecialchars($review['userName']) ?></strong>
                                <p class="indent"><?= nl2br(htmlspecialchars($review['review'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet.</p>
                <?php endif; ?>
            </div>
        </div>
        <script src="utils/js/impactScore.js"></script>
        <?php include("./partials/footer.php"); ?>
</body>