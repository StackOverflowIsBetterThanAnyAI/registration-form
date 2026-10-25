<?php
session_start();
$_SESSION["domain"] = "/registration-form";
$title = "Register";
require "./templates/header.php";
require "database.php";

?>

<a href=<?= $_SESSION["domain"] . "/login.php" ?>>Already have an account?</a>
<form action=<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?> method="post">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" />
    <br />
    <label for="password">Password</label>
    <input type="password" id="password" name="password" />
    <br />
    <label for="confirm">Confirm Password</label>
    <input type="password" id="confirm" name="confirm" />
    <br />
    <label for="age">Age</label>
    <input type="number" id="age" name="age" />
    <br />
    <input type="submit" name="submit" value="Register Account" />
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $username = trim($_POST["username"]) ?? "";
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm"] ?? "";
    $age = $_POST["age"] ?? "";
    $error = false;
    if (empty($username)) {
        echo "Username must not be empty<br/>";
        $error = true;
    } else {
        if (strlen($username) < 5 || strlen($username) > 24) {
            echo "The username must contain between 5 and 23 characters";
            $error = true;
        }
        $allowed_pattern = '/^[a-zA-Z0-9]+$/';
        if (!preg_match($allowed_pattern, $username)) {
            echo "The username can only contain Latin letters and Arabic numbers";
            $error = true;
        }
    }
    if (empty($password)) {
        echo "Password must not be empty<br/>";
        $error = true;
    } elseif (strlen($password) < 8) {
        echo "The password must contain at least 8 characters<br/>";
        $error = true;
    }
    if (empty($confirm)) {
        echo "Confirm password must not be empty<br/>";
        $error = true;
    } elseif (strlen($password) < 8) {
        echo "The confirm password must contain at least 8 characters<br/>";
        $error = true;
    }
    if (!empty($confirm) && $confirm !== $password) {
        echo "The passwords don't macht<br/>";
        $error = true;
    }
    if (empty($age)) {
        echo "Age must not be empty<br/>";
        $error = true;
    } elseif ($age < 1 || $age > 99) {
        echo "The age must be a number between 1 and 99<br/>";
        $error = true;
    }
    if (!$error) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (user, password, age) VALUES (?, ?, ?);";
            $stmt = mysqli_prepare($connection, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssi", $username, $hashed_password, $age);
                mysqli_stmt_execute($stmt);
                if ($username === "admin") {
                    header("Location: admin.php");
                } else {
                    $_SESSION["username"] = $username;
                    $_SESSION["age"] = $age;
                    header("Location: home.php");
                }
                mysqli_stmt_close($stmt);
                mysqli_close($connection);
                exit();
            }
        } catch (mysqli_sql_exception $e) {
            echo "Registration failed: " . $e->getMessage();
        }
    }
}

?>

<?php
require "./templates/footer.php";
