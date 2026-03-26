<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mysqli = new mysqli("localhost", "root", "", "skillshareLocal");
  if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = $_POST['mode'] ?? '';
    if ($mode === 'user') {
      $sql = "SELECT id, username, password FROM `user` WHERE username = ?";
      $stmt = $mysqli->prepare($sql);

      if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
      }

      $stmt->bind_param("s", $username);
      $stmt->execute();

      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
          session_start();
          session_regenerate_id(true);
          $_SESSION['user_id'] = $row['id'];
          $_SESSION['username'] = $row['username'];
          $_SESSION['role'] = $mode;

          header("Location: index.php?type=success&message=You Have Logged In Successfully!!!");
          exit();
        } else {
          header("Location: signIn.php?type=danger&message=Incorrect Username or Password!!!");
          exit();
        }
      } else {
        header("Location: signUp.php?type=danger&message=Account Doesnt Exist!!");
        exit();
      }
    } elseif ($mode === 'teacher') {
      $sql = "SELECT id, username, password FROM `teacher` WHERE username = ?";
      $stmt = $mysqli->prepare($sql);

      if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
      }

      $stmt->bind_param("s", $username);
      $stmt->execute();

      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
          session_start();
          session_regenerate_id(true);
          $_SESSION['user_id'] = $row['id'];
          $_SESSION['username'] = $row['username'];
          $_SESSION['role'] = $mode;
          header("Location: teacheradmin.php?type=success&message=You Have Logged In Successfully!!!");
          exit();
        } else {
          header("Location: signIn.php?type=danger&message=Incorrect Username or Password!!!");
          exit();
        }
      } else {
        header("Location: signUp.php?type=danger&message=Account Doesnt Exist!!");
        exit();
      }
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
    <h1 class="title">Sign In</h1>
    <div class="select-mode">
      <div class="user-mode selection">
        User
      </div>
      <div class="teacher-mode selection">
        Teacher
      </div>
      <div class="selector"></div>
    </div>
    <form method="POST" action="signIn.php" class="signup-form">
      <input type="hidden" id="mode" name="mode" value="user">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
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