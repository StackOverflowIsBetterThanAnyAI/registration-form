<?php

$db_hostname = "localhost";
$db_username = "root";
$db_password = "";
$db_database = "registration_form_db";


try {
    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
} catch (mysqli_sql_exception $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
