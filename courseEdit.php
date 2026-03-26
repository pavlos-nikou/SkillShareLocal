<?php session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php?type=warning&message=Please Login First!!!");
    exit;
}

// edit course post request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $title = $_POST["title"];
    $category = (int) $_POST["category_id"];
    $teacherId = (int) $_SESSION["user_id"];
    $desc = $_POST["desc"];
    $duration = $_POST["duration"];
    $cost = (float) $_POST["cost"];
    $location = $_POST["location"];
    $img = $_POST["image"];
    $impactScore = (float) $_POST["impact"];

    $mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $sql = "UPDATE `session`
SET
  title = ?,
  categoryId = ?,
  `desc` = ?,
  duration = ?,
  cost = ?,
  `location` = ?,
  img = ?,
  impactScore = ?
WHERE id = ? AND teacherId = ?";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param(
    "sissdssdii",
    $title,
    $category,
    $desc,
    $duration,
    $cost,
    $location,
    $img,
    $impactScore,
    $id,
    $teacherId
);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();
    $mysqli->close();


    header("Location: teacheradmin.php?type=success&message=Changes Were Made Succesfully!!!");
    exit();
}


#get course data
$mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT * FROM `session` WHERE id = ?";


$stmt = $mysqli->prepare($sql);
$courseId = (int) $_GET["courseid"];
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();
#check if user is owner
if ($course["teacherId"] != $_SESSION["user_id"]) {
    header("Location: signIp.php?type=danger&message=This course does not belong to you pls sign into the correct account to access this course!!!");
}

$mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
#get categories for form
$sql = "SELECT id, categoryName FROM category ORDER BY categoryName ASC";
$result = $mysqli->query($sql);

$categories = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

</html>

<?php include("./partials/header.php"); ?>

<body>
    <?php include("./partials/navbar.php"); ?>
    <div class="form-container">
        <h1 class="title">Edit Course Data</h1>
        <form method="POST" action="courseEdit.php" class="signup-form">
            <input type="hidden" name="id" value="<?= $course['id'] ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required
                    value=" <?= $course["title"] ?>">
            </div>
            <label for="category">Category</label>
            <select name="category_id" id="category" class="form-select form-control" required>
                <option value="">-- Select a category --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id'] ?>" <?= ($cat['id'] == $course['categoryId']) ? 'selected' : '' ?>>
                        <?php echo $cat['categoryName'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="form-group">
                <label for="tutor">Tutor</label>
                <input type="text" class="form-control" id="tutor" name="tutor" required disabled
                    value="<?php echo $_SESSION["username"] ?>">
            </div>
            <div class="form-group">
                <label for="duration">Duration</label>
                <input type="text" class="form-control" id="duration" name="duration" required
                    value="<?= $course["duration"] ?>">
            </div>
            <div class="form-group">
                <label for="cost">Cost</label>
                <input type="text" class="form-control" id="cost" name="cost" required value=" <?= $course["cost"] ?>">
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" required
                    value="<?= $course["location"] ?>">
            </div>
            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="url" class="form-control" id="image" name="image" required value="<?= $course["img"] ?>">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="impact" name="impact" required hidden
                    value="<?= $course["impactScore"] ?>">
            </div>
            <div class="form-group">
                <label for="image">Description</label>
                <textarea class="form-control" id="desc" name="desc" required
                    placeholder=""><?= $course["desc"] ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="submit" class="btn btn btn-secondary">Back</button>
        </form>
        <?php include("./partials/footer.php"); ?>
</body>