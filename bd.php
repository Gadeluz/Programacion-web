<?php
$server = "localhost";
$user = "root";
$pass = "root";
$dbname = "tiendita";

$conn = new mysqli($server, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
