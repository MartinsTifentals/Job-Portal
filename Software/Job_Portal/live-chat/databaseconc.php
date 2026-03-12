<?php

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "job_portal";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
  http_response_code(500);
  die("DB connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
