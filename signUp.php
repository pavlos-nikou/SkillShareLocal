<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }

  $name = $_POST['name'] ?? '';
  $surname = $_POST['surname'] ?? '';
  $username = $_POST['username'] ?? '';
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  $hash = password_hash($password, PASSWORD_DEFAULT);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = $_POST['mode'] ?? '';
    if ($mode === 'user') {
      $sql = "SELECT id FROM `user` WHERE username = ? OR email = ?";
      $stmt = $mysqli->prepare($sql);

      if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
      }

      $stmt->bind_param("ss", $username, $email);
      $stmt->execute();

      $result = $stmt->get_result();
      $existingUser = $result->fetch_assoc();

      if ($existingUser) {
        // A user with this username OR email already exists
        // Do NOT insert
        header("Location: signIn.php?type=danger&message=Username or Email already exists!!!");
        exit;
      }
      $stmt->close();
      $sql = "INSERT INTO `user` (`username`, `name`, `surname`, `email`, `password`)
          VALUES (?, ?, ?, ?, ?)";

      $stmt = $mysqli->prepare($sql);
      if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
      }

      $stmt->bind_param("sssss", $username, $name, $surname, $email, $hash);

      if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
      }

      echo "Inserted OK. New user id: " . $stmt->insert_id;
      $stmt->close();
      header("Location: index.php?type=success&message=Account Created Successfully");
      exit;
    } elseif ($mode === 'teacher') {
      $sql = "SELECT id FROM `teacher` WHERE username = ? OR email = ?";
      $stmt = $mysqli->prepare($sql);

      if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
      }

      $stmt->bind_param("ss", $username, $email);
      $stmt->execute();

      $result = $stmt->get_result();
      $existingUser = $result->fetch_assoc();

      if ($existingUser) {
        header("Location: signIn.php?type=danger&message=Username or Email already exists!!!");
        exit;
      }
      $stmt->close();
      $sql = "INSERT INTO `teacher` (`username`, `name`, `surname`, `email`, `password`)
          VALUES (?, ?, ?, ?, ?)";

      $stmt = $mysqli->prepare($sql);
      if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
      }

      $stmt->bind_param("sssss", $username, $name, $surname, $email, $hash);

      if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
      }

      echo "Inserted OK. New user id: " . $stmt->insert_id;
      $stmt->close();
      header("Location: index.php?type=success&message=Account Created Successfully");
      exit;
    }
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
    <h1 class="title">Sign Up</h1>
    <div class="select-mode">
      <div class="user-mode selection">
        User
      </div>
      <div class="teacher-mode selection">
        Teacher
      </div>
      <div class="selector"></div>
    </div>
    <form method="POST" action="signUp.php" class="signup-form">
      <input type="hidden" id="mode" name="mode" value="user">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="name">First Name</label>
        <input type="text" class="form-control" id="name" name="name" required>

      </div>
      <div class="form-group">
        <label for="surname">Last Name</label>
        <input type="text" class="form-control" id="surname" name="surname" required>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password" id="password" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  <script src="utils/js/signUp.js"></script>
  <?php include("./partials/footer.php"); ?>
</body>