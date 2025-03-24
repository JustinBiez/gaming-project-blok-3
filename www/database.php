<?php

$host = "mariadb";
$username = "root";
$password = "password";
$database = "games";

$conn = mysqli_connect(hostname: $host,username: $username,password: $password,database: $database);

if (mysqli_connect_errno()) {
    echo "failed to connect to MYSQL: " . mysqli_connect_error();
    exit();
}