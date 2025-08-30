<?php
require 'config.php';

$db = new Database();
$conn = $db->getConnection();

if($conn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed!";
}
?>
