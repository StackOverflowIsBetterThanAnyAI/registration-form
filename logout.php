<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
    header("Location: login.php");
    exit();
}
