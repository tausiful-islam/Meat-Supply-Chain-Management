<?php
// Database configuration
$host = 'localhost';
$dbname = 'meat_production_course';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Helper function to execute queries safely
function executeQuery($pdo, $sql, $params = []) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        throw new Exception("Database error: " . $e->getMessage());
    }
}

// Helper function to get all records from a table
function getAllRecords($pdo, $table) {
    $sql = "SELECT * FROM {$table} ORDER BY created_at DESC";
    return executeQuery($pdo, $sql)->fetchAll();
}

// Helper function to delete record
function deleteRecord($pdo, $table, $id) {
    $sql = "DELETE FROM {$table} WHERE id = :id";
    return executeQuery($pdo, $sql, ['id' => $id]);
}

// Helper function to get record by ID
function getRecordById($pdo, $table, $id) {
    $sql = "SELECT * FROM {$table} WHERE id = :id";
    return executeQuery($pdo, $sql, ['id' => $id])->fetch();
}
?>
