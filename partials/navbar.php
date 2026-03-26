<?php session_start(); ?>
<nav class="navbar">
    <div class="nav-link-container">
        <a href="index.php"><img src="./utils/icons/logo.png" alt="skillshareLocalLogo"></a>
        <?php if ($_SESSION["role"] == "teacher"): ?>
            <a href="teacheradmin.php">My Courses</a>
        <?php elseif ($_SESSION["role"] == "user"): ?>
            <a href="browseCourse.php" class="nav-link">Browse</a>
        <?php endif ?>
    </div>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="sign-links">
            <a class="a" id="signin-link" href="signIn.php">Sign-In</a>
            <a class="a" id="signup-link" href="signUp.php">Sign-Up</a>
        </div>
    <?php endif ?>
    <?php if (isset($_SESSION['user_id'])): ?>
        <span></span>
        <form action="signOut.php" method="POST" class="sign-links">
            <img src="utils/icons/user-solid-full-white.svg" alt="user icon" class icon><?= $_SESSION["username"] ?>
            <button class="a" id="signout-link">Log Out</button>
        </form>
    <?php endif ?>
</nav>
<?php if (isset($_GET['message']) && $_GET["type"] === "danger"): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_GET["message"]; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
    </div>
<?php endif; ?>
<?php if (isset($_GET['message']) && $_GET["type"] === "success"): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_GET["message"]; ?>
        <button type="button" class="close" id="alert-btn" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
    </div>
<?php endif; ?>