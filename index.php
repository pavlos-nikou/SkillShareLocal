<?php session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: browseCourse.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include("./partials/header.php"); ?>

<body>
    <?php include("./partials/navbar.php"); ?>
    <img id="home-background"
        src="https://images.unsplash.com/photo-1487088678257-3a541e6e3922?w=900&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGJhY2tncm91bmQlMjBibHVlfGVufDB8MHwwfHx8Mg%3D%3D"
        alt="background image">
    <div class="intro">
        <h1>SKILLSHARE LOCAL</h1>
        <p>SkillShare Local is a community-driven platform where people share practical, eco-friendly skills through
            short workshops and webinars. <br>
            Discover local knowledge on sustainability, learn from real people, and take part in a growing community
            focused on positive environmental impact.<br>
            <br>
            Browse sessions, explore categories, and join workshops that help you live more sustainably.
        </p>
        <a class="a" id="signin-link" href="signIn.php">Sign-In</a>
        <a class="a" id="signup-link" href="signUp.php">Sign-Up</a>
    </div>
    <?php include("./partials/footer.php"); ?>
</body>

</html>