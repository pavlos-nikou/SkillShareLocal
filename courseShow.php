<?php session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php?type=warning&message=Please Login!!");
    exit;
}

#get course data
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


$stmt->store_result();

if ($stmt->num_rows > 0) {
    $isenrolled = true;
} else {
    $isenrolled = false;
}



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

$sql = "SELECT COUNT(*) AS participantCount
        FROM `learning`
        WHERE sessionId = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $courseId);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$participantCount = (int)$row['participantCount'];
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
                    <?php if (!$isenrolled): ?>
                        <li class="list-group-item"><a href="courseEnroll.php?courseid=<?= $course["id"] ?>"
                                class="btn btn-success">Enroll this Course</a></li>
                    <?php else: ?>
                        <li class="list-group-item"><a href="courseDrop.php?courseid=<?= $course["id"] ?>"
                                class="btn btn-danger">Drop Course</a></li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
        <div class="course-reviews">
            <?php if ($isenrolled): ?>
                <div class="new-review">
                    <form action="reviewCreate.php" method="POST">
                        <input type="text" name="courseId" value="<?= $_GET["courseid"] ?>" hidden>
                        <div class="form-group">
                            <label for="review" id="review-title">Post Review</label><br>
                            <textarea name="review" id="review" placeholder="I love this course!!!"></textarea>
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            <?php endif ?>
            <h1 id="review-head">Reviews</h1>
            <div class="review-container">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <h2><img src="utils/icons/chalkboard-user-solid-full.svg" alt="user icon"
                                    class="icon"><?= htmlspecialchars($review['userName']) ?></strong>
                                <p class="indent"><?= nl2br(htmlspecialchars($review['review'])) ?></p>
                                <?php if ($review['userId'] == $_SESSION['user_id']): ?>
                                    <a href="reviewDelete.php?courseid=<?= $courseId ?>&reviewid=<?= htmlspecialchars($review['id'])?>"><img src="utils/icons/trash-solid-full.svg" alt="delete icon" class="icon"></a>
                                <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="utils/js/impactScore.js"></script>
    <?php include("./partials/footer.php"); ?>
</body>