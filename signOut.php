<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $_SESSION = [];
    session_destroy();
    header("Location: index.php?type=success&message=You Have Logged Out Successfully!!!");
    exit();
}
?>