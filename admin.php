<?php
$title = "Admin";
require "./templates/header.php";
require "database.php";

?>

<h2>Welcome, admin!</h2>

<div>

    <?php
    try {
        $sql = "SELECT * FROM users";
        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result)) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "ID: {$row["id"]}<br/>";
                echo "Name: {$row["user"]}<br/>";
                echo "Age: {$row["age"]}<br/>";
                echo "Hashed password: {$row["password"]}<br/>";
                echo "Account created: {$row["created_at"]}<br/><br/>";
            }
        } else {
            echo "No records found.";
        }
    } catch (mysqli_sql_exception $e) {
        echo "Database error: " . $e->getMessage();
    }
    ?>

</div>

<form action="admin.php" method="post">
    <input type="submit" name="logout" value="Logout" />
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
    header("Location: login.php");
}
?>

<?php
require "./templates/footer.php";
mysqli_close($connection);
