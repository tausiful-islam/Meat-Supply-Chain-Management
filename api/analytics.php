<?php
/**
 * Analytics API Endpoints
 * Provides data for all 6 analytical features
 */

require_once 'config.php';
setCorsHeaders();

$database = new Database();
$db = $database->getConnection();

// Get the endpoint from URL
$endpoint = $_GET['endpoint'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

switch ($endpoint) {
    
    // =================================================================
    // FEATURE #1: DETAILED MEAT PRODUCT DATA
    // =================================================================
    case 'meat-products':
        if ($method === 'GET') {
            try {
                $query = "SELECT 
                    id, meat_type, breed_source, avg_weight_kg, 
                    feed_conversion_ratio, rearing_period_months, 
                    production_volume_tons, current_price_usd, 
                    previous_year_price_usd, badge_color,
                    CASE 
                        WHEN previous_year_price_usd IS NULL OR previous_year_price_usd = 0 THEN 'No Data'
                        WHEN current_price_usd > previous_year_price_usd THEN 'Rising'
                        WHEN current_price_usd < previous_year_price_usd THEN 'Falling'
                        ELSE 'Stable'
                    END as price_trend,
                    CASE 
                        WHEN previous_year_price_usd IS NULL OR previous_year_price_usd = 0 THEN 0
                        ELSE ROUND(((current_price_usd - previous_year_price_usd) / previous_year_price_usd) * 100, 2)
                    END as price_change_percent
                FROM meat_products 
                ORDER BY production_volume_tons DESC";
                
                $stmt = $db->prepare($query);
                $stmt->execute();
                $products = $stmt->fetchAll();
                
                ApiResponse::success($products, "Meat products retrieved successfully");
            } catch (Exception $e) {
                ApiResponse::error("Failed to retrieve meat products: " . $e->getMessage());
            }
        }
        
        elseif ($method === 'POST') {
            try {
                $data = json_decode(file_get_contents("php://input"), true);
                
                $query = "INSERT INTO meat_products 
                    (meat_type, breed_source, avg_weight_kg, feed_conversion_ratio, 
                     rearing_period_months, production_volume_tons, current_price_usd, 
                     previous_year_price_usd, badge_color) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $db->prepare($query);
                $stmt->execute([
                    $data['meat_type'], $data['breed_source'], $data['avg_weight_kg'],
                    $data['feed_conversion_ratio'], $data['rearing_period_months'],
                    $data['production_volume_tons'], $data['current_price_usd'],
                    $data['previous_year_price_usd'], $data['badge_color']
                ]);
                
                ApiResponse::success(['id' => $db->lastInsertId()], "Meat product added successfully");
            } catch (Exception $e) {
                ApiResponse::error("Failed to add meat product: " . $e->getMessage());
            }
        }
        break;

    // =================================================================
    // FEATURE #2: PRODUCTION RECORDS BY DISTRICT/DIVISION
    // =================================================================
    case 'production-records':
        if ($method === 'GET') {
            try {
                $district = $_GET['district'] ?? '';
                $year = $_GET['year'] ?? 2024;
                
                $query = "SELECT 
                    id, district_division, livestock_count, slaughter_rate_percent,
                    meat_yield_tons, production_volume_tons, period, year, quarter,
                    meat_type, created_at
                FROM production_records 
                WHERE year = ?";
                
                $params = [$year];
                
                if ($district) {
                    $query .= " AND district_division = ?";
                    $params[] = $district;
                }
                
                $query .= " ORDER BY production_volume_tons DESC";
                
                $stmt = $db->prepare($query);
                $stmt->execute($params);
                $records = $stmt->fetchAll();
                
                // Also get district summary for charts
                $summaryQuery = "SELECT 
                    district_division,
                    SUM(livestock_count) as total_livestock,
                    AVG(slaughter_rate_percent) as avg_slaughter_rate,
                    SUM(meat_yield_tons) as total_meat_yield,
                    SUM(production_volume_tons) as total_production
                FROM production_records 
                WHERE year = ? 
                GROUP BY district_division 
                ORDER BY total_production DESC";
                
                $summaryStmt = $db->prepare($summaryQuery);
                $summaryStmt->execute([$year]);
                $summary = $summaryStmt->fetchAll();
                
                ApiResponse::success([
                    'records' => $records,
                    'summary' => $summary
                ], "Production records retrieved successfully");
            } catch (Exception $e) {
                ApiResponse::error("Failed to retrieve production records: " . $e->getMessage());
            }
        }
        
        elseif ($method === 'POST') {
            try {
                $data = json_decode(file_get_contents("php://input"), true);
                
                $query = "INSERT INTO production_records 
                    (district_division, livestock_count, slaughter_rate_percent, 
                     meat_yield_tons, production_volume_tons, period, year, quarter, meat_type) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $db->prepare($query);
                $stmt->execute([
                    $data['district_division'], $data['livestock_count'], $data['slaughter_rate_percent'],
                    $data['meat_yield_tons'], $data['production_volume_tons'], $data['period'],
                    $data['year'], $data['quarter'], $data['meat_type'] ?? 'Mixed'
                ]);
                
                ApiResponse::success(['id' => $db->lastInsertId()], "Production record added successfully");
            } catch (Exception $e) {
                ApiResponse::error("Failed to add production record: " . $e->getMessage());
            }
        }
        break;

    // =================================================================
    // FEATURE #3: HISTORICAL PRICE DATA & TREND ANALYSIS
    // =================================================================
    case 'price-history':
        if ($method === 'GET') {
            try {
                $product = $_GET['product'] ?? '';
                $region = $_GET['region'] ?? '';
                
                $query = "SELECT 
                    id, product_type, region, period, wholesale_price_usd,
                    retail_price_usd, yoy_change_percent, seasonal_trend,
                    year, quarter, created_at
                FROM price_history 
                WHERE 1=1";
                
                $params = [];
                
                if ($product) {
                    $query .= " AND product_type = ?";
                    $params[] = $product;
                }
                
                if ($region) {
                    $query .= " AND region = ?";
                    $params[] = $region;
                }
                
                $query .= " ORDER BY year DESC, quarter DESC";
                
                $stmt = $db->prepare($query);
                $stmt->execute($params);
                $prices = $stmt->fetchAll();
                
                // Get price trends for charts
                $trendQuery = "SELECT 
                    product_type,
                    AVG(wholesale_price_usd) as avg_wholesale,
                    AVG(retail_price_usd) as avg_retail,
                    COUNT(*) as data_points
                FROM price_history 
                GROUP BY product_type 
                ORDER BY product_type";
                
                $trendStmt = $db->prepare($trendQuery);
                $trendStmt->execute();
                $trends = $trendStmt->fetchAll();
                
                ApiResponse::success([
                    'prices' => $prices,
                    'trends' => $trends
                ], "Price history retrieved successfully");
            } catch (Exception $e) {
                ApiResponse::error("Failed to retrieve price history: " . $e->getMessage());
            }
        }
        break;

    // =================================================================
    // FEATURE #4: REGIONAL CONSUMPTION PATTERNS
    // =================================================================
    case 'consumption-patterns':
        if ($method === 'GET') {
            try {
                $query = "SELECT 
                    id, region, meat_type, per_capita_consumption_kg,
                    population, demographic_group, nutritional_intake_calories,
                    dietary_impact_score, period, year
                FROM consumption_patterns 
                ORDER BY per_capita_consumption_kg DESC";
                
                $stmt = $db->prepare($query);
                $stmt->execute();
                $patterns = $stmt->fetchAll();
                
                // Get regional summary for charts
                $regionQuery = "SELECT 
                    region,
                    SUM(per_capita_consumption_kg * population) as total_consumption,
                    AVG(per_capita_consumption_kg) as avg_per_capita,
                    SUM(population) as total_population
                FROM consumption_patterns 
                GROUP BY region 
                ORDER BY total_consumption DESC";
                
                $regionStmt = $db->prepare($regionQuery);
                $regionStmt->execute();
                $regional = $regionStmt->fetchAll();
                
                ApiResponse::success([
                    'patterns' => $patterns,
                    'regional' => $regional
                ], "Consumption patterns retrieved successfully");
            } catch (Exception $e) {
                ApiResponse::error("Failed to retrieve consumption patterns: " . $e->getMessage());
            }
        }
        break;

    // =================================================================
    // FEATURE #5: PRICE ELASTICITY ANALYSIS
    // =================================================================
    case 'price-elasticity':
        if ($method === 'GET') {
            try {
                $query = "SELECT 
                    id, product, elasticity_coefficient, classification,
                    price_impact_percent, optimal_price_usd, revenue_potential_percent,
                    sensitivity_level, cross_elasticity_substitute, cross_elasticity_value,
                    period
                FROM price_elasticity 
                ORDER BY ABS(elasticity_coefficient) DESC";
                
                $stmt = $db->prepare($query);
                $stmt->execute();
                $elasticity = $stmt->fetchAll();
                
                ApiResponse::success($elasticity, "Price elasticity data retrieved successfully");
            } catch (Exception $e) {
                ApiResponse::error("Failed to retrieve price elasticity: " . $e->getMessage());
            }
        }
        break;

    // =================================================================
    // FEATURE #6: SUPPLY VS DEMAND ANALYSIS
    // =================================================================
    case 'supply-demand':
        if ($method === 'GET') {
            try {
                $query = "SELECT 
                    id, product, supply_tons, demand_tons, balance_tons,
                    market_status, price_impact_percent, business_action,
                    region, period, year, quarter
                FROM supply_demand_analysis 
                ORDER BY ABS(balance_tons) DESC";
                
                $stmt = $db->prepare($query);
                $stmt->execute();
                $analysis = $stmt->fetchAll();
                
                ApiResponse::success($analysis, "Supply-demand analysis retrieved successfully");
            } catch (Exception $e) {
                ApiResponse::error("Failed to retrieve supply-demand analysis: " . $e->getMessage());
            }
        }
        
        elseif ($method === 'POST') {
            try {
                $data = json_decode(file_get_contents("php://input"), true);
                
                $query = "INSERT INTO supply_demand_analysis 
                    (product, supply_tons, demand_tons, market_status, 
                     price_impact_percent, business_action, region, period, year, quarter) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $db->prepare($query);
                $stmt->execute([
                    $data['product'], $data['supply_tons'], $data['demand_tons'],
                    $data['market_status'], $data['price_impact_percent'], $data['business_action'],
                    $data['region'] ?? 'National', $data['period'], $data['year'], $data['quarter']
                ]);
                
                ApiResponse::success(['id' => $db->lastInsertId()], "Supply-demand record added successfully");
            } catch (Exception $e) {
                ApiResponse::error("Failed to add supply-demand record: " . $e->getMessage());
            }
        }
        break;

    // =================================================================
    // DASHBOARD SUMMARY
    // =================================================================
    case 'dashboard-summary':
        if ($method === 'GET') {
            try {
                // Get key metrics
                $metrics = [];
                
                // Total production
                $prodStmt = $db->query("SELECT SUM(production_volume_tons) as total FROM production_records WHERE year = 2024");
                $metrics['total_production'] = $prodStmt->fetch()['total'] ?? 0;
                
                // Average per capita consumption
                $consStmt = $db->query("SELECT AVG(per_capita_consumption_kg) as avg FROM consumption_patterns WHERE year = 2024");
                $metrics['avg_consumption'] = round($consStmt->fetch()['avg'] ?? 0, 1);
                
                // Average price index
                $priceStmt = $db->query("SELECT AVG(retail_price_usd) as avg FROM price_history WHERE year = 2024");
                $metrics['avg_price'] = round($priceStmt->fetch()['avg'] ?? 0, 2);
                
                // Supply-demand ratio
                $supplyStmt = $db->query("SELECT AVG(supply_tons/demand_tons) as ratio FROM supply_demand_analysis WHERE year = 2024");
                $metrics['supply_demand_ratio'] = round($supplyStmt->fetch()['ratio'] ?? 0, 2);
                
                ApiResponse::success($metrics, "Dashboard summary retrieved successfully");
            } catch (Exception $e) {
                ApiResponse::error("Failed to retrieve dashboard summary: " . $e->getMessage());
            }
        }
        break;

    default:
        ApiResponse::error("Invalid endpoint", 404);
        break;
}
?>
