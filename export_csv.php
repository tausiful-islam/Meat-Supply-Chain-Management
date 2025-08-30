<?php
require_once 'config.php';

if ($_POST && isset($_POST['table'])) {
    $table = $_POST['table'];
    
    // Validate table name
    $validTables = ['meat_products', 'production_records', 'price_history', 'consumption_data', 'demand_elasticity', 'supply_demand_analysis'];
    
    if (!in_array($table, $validTables)) {
        die('Invalid table name');
    }
    
    try {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $table . '_export_' . date('Y-m-d') . '.csv"');
        
        // Get all records
        $stmt = $pdo->query("SELECT * FROM `$table`");
        $records = $stmt->fetchAll();
        
        if (!empty($records)) {
            // Open output stream
            $output = fopen('php://output', 'w');
            
            // Write CSV headers
            fputcsv($output, array_keys($records[0]));
            
            // Write data rows
            foreach ($records as $record) {
                fputcsv($output, $record);
            }
            
            fclose($output);
        } else {
            echo "No data found in table: $table";
        }
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No table specified";
}
?>
