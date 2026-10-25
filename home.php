<?php
session_start();
$title = "Home";
require "./templates/header.php";
require "database.php";

?>

<h2>Welcome, <?= $_SESSION["username"] ?>!</h2>
<form action="home.php" method="post">
    <label id="newage">New Age:</label>
    <input type="number" id="newage" name="newage" placeholder=<?= $_SESSION["age"] ?? 20 ?> />
    <br />
    <input type="submit" name="changeage" value="Change" />
</form>
<br />
<form action="logout.php" method="post">
    <input type="submit" name="logout" value="Logout" />
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["changeage"])) {
    $age = $_POST["newage"] ?? "";
    if (!isset($age)) {
        echo "The age must be a number between 1 and 99<br/>";
    } else {
        try {
            $username = $_SESSION["username"] ?? "";
            $sql = "UPDATE users SET age = ? WHERE user = ?;";
            $stmt = mysqli_prepare($connection, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "is", $age, $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION["age"] = $age;
                header("Location: home.php");
                mysqli_close($connection);
                exit();
            }
        } catch (mysqli_sql_exception $e) {
            echo "Update failed: " . $e->getMessage();
        }
    }
}
?>

<?php
require "./templates/footer.php";
mysqli_close($connection);
