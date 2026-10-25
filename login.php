<?php
session_start();
$title = "Login";
require "./templates/header.php";
require "database.php";

?>

<a href=<?= $_SESSION["domain"] . "/index.php" ?>>Already have an account?</a>
<form action=<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?> method="post">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" />
    <br />
    <label for="password">Password</label>
    <input type="password" id="password" name="password" />
    <br />
    <input type="submit" name="submit" value="Login" />
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $username = trim($_POST["username"]) ?? "";
    $password = $_POST["password"] ?? "";
    $error = false;
    if (empty($username)) {
        echo "Username must not be empty<br/>";
        $error = true;
    } else {
        if (strlen($username) < 5 || strlen($username) > 24) {
            echo "The username must contain between 5 and 23 characters<br/>";
            $error = true;
        }
        $allowed_pattern = '/^[a-zA-Z0-9]+$/';
        if (!preg_match($allowed_pattern, $username)) {
            echo "The username can only contain Latin letters and Arabic numbers<br/>";
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
    if (!$error) {
        try {
            $sql = "SELECT password FROM users WHERE user = ?;";
            $stmt = mysqli_prepare($connection, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result)) {
                    $row = mysqli_fetch_assoc($result);
                    $stored_password = $row["password"];
                    if (password_verify($password, $stored_password)) {
                        if ($username === "admin") {
                            header("Location: admin.php");
                        } else {
                            $_SESSION["username"] = $username;
                            header("Location: home.php");
                        }
                        mysqli_close($connection);
                        exit();
                    } else {
                        echo "Invalid credentials";
                    }
                } else {
                    echo "Invalid credentials";
                }

                mysqli_stmt_close($stmt);
            }
        } catch (mysqli_sql_exception $e) {
            echo "Login failed: " . $e->getMessage();
        }
    }
}

?>

<?php
require "./templates/footer.php";
