<?php
session_start();
// check if logged in 
if (!isset($_SESSION['user_id'])) {
    header("Location: signIn.php?type=warning&message=PleaseLogin");
    exit;
}
// create new course post request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST["title"];
    $category = (int) $_POST["category_id"];
    $teacherId = (int) $_SESSION["user_id"];
    $desc = $_POST["desc"];
    $duration = $_POST["duration"];
    $cost = (float)$_POST["cost"];
    $location = $_POST["location"];
    $img = $_POST["image"];
    $impactScore = (float) $_POST["impact"];

    $mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $sql = "INSERT INTO `session`
            (`title`, `categoryId`, `teacherId`, `desc`, `duration`, `cost`, `location`, `img`, `impactScore`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param(
        "siissdssd",
        $title,
        $category,
        $teacherId,
        $desc,
        $duration,
        $cost,
        $location,
        $img,
        $impactScore
    );

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();
    $mysqli->close();

    header("Location: teacheradmin.php?type=success&message=New Session Created!!!");
    exit();
}

// get categories for the form 
$mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

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
        <h1 class="title">Create New Course</h1>
        <form method="POST" action="courseCreate.php" class="signup-form">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <label for="category">Category</label>
            <select name="category_id" id="category" class="form-select form-control" required>
                <option value="">-- Select a category --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id'] ?>">
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
                <input type="text" class="form-control" id="duration" name="duration" required>
            </div>
            <div class="form-group">
                <label for="cost">Cost</label>
                <input type="text" class="form-control" id="cost" name="cost" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="url" class="form-control" id="image" name="image" required value="https://images.unsplash.com/photo-1594072282386-915fadf1a2d6?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cGx1bWJpbmd8ZW58MHwyfDB8fHwy">
            </div>
            <div class="form-group">
                <label for="image">Impact score</label>
                <input type="text" class="form-control" id="impact" name="impact" required value="5" hidden>
            </div>
            <div class="form-group">
                <label for="image">Description</label>
                <textarea class="form-control" id="desc" name="desc" required
                    placeholder="Describe what the course will offer."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Course</button>
        </form>
        <script src="utils/js/signUp.js"></script>
        <?php include("./partials/footer.php"); ?>
</body>