<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    $stats = [];
    
    // Count records from each table
    $tables = ['meat_products', 'production_records', 'price_history', 'consumption_data', 'demand_elasticity', 'supply_demand_analysis'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM {$table}");
        $stmt->execute();
        $result = $stmt->fetch();
        $stats[$table] = $result['count'];
    }
    
    echo json_encode(['success' => true, 'stats' => $stats]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
