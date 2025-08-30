<?php
require_once 'config.php';

// Set headers for file download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="meat_production_backup_' . date('Y-m-d_H-i-s') . '.sql"');
header('Cache-Control: must-revalidate');

try {
    // Get all tables
    $tables = ['meat_products', 'production_records', 'price_history', 'consumption_data', 'demand_elasticity', 'supply_demand_analysis'];
    
    echo "-- Meat Production Database Backup\n";
    echo "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
    echo "-- Database: meat_production_course\n\n";
    
    foreach ($tables as $table) {
        echo "\n-- Table structure for table `$table`\n";
        
        // Get table structure
        $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
        $row = $stmt->fetch();
        echo $row['Create Table'] . ";\n\n";
        
        // Get table data
        echo "-- Dumping data for table `$table`\n";
        $stmt = $pdo->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll();
        
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $values = array_map(function($value) use ($pdo) {
                    return $value === null ? 'NULL' : $pdo->quote($value);
                }, array_values($row));
                
                echo "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
            }
        }
        echo "\n";
    }
    
    echo "-- End of backup\n";
    
} catch (Exception $e) {
    echo "-- Error: " . $e->getMessage();
}
?>
