-- =================================================================
-- MEAT SUPPLY CHAIN MANAGEMENT DATABASE
-- Generated: August 30, 2025
-- Database: meat_supply_chain_db
-- =================================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS meat_supply_chain_db;
USE meat_supply_chain_db;

-- =================================================================
-- TABLE #1: DETAILED MEAT PRODUCT DATA
-- =================================================================
CREATE TABLE meat_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meat_type VARCHAR(50) NOT NULL,
    breed_source VARCHAR(100) NOT NULL,
    avg_weight_kg VARCHAR(20) NOT NULL,
    feed_conversion_ratio VARCHAR(20) NOT NULL,
    rearing_period_months VARCHAR(20) NOT NULL,
    production_volume_tons INT NOT NULL,
    current_price_usd DECIMAL(10,2) NOT NULL,
    previous_year_price_usd DECIMAL(10,2),
    badge_color VARCHAR(20) DEFAULT 'bg-primary',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Sample Data for Meat Products
INSERT INTO meat_products (meat_type, breed_source, avg_weight_kg, feed_conversion_ratio, rearing_period_months, production_volume_tons, current_price_usd, previous_year_price_usd, badge_color) VALUES
('Beef', 'Angus, Holstein', '450-650', '6.5:1', '18-24', 890000, 35.99, 34.20, 'bg-danger'),
('Chicken', 'Broiler, Free-range', '2.2-3.5', '1.8:1', '1.5-2', 1200000, 12.99, 12.01, 'bg-warning'),
('Pork', 'Yorkshire, Duroc', '110-140', '3.5:1', '5-6', 420000, 18.75, 19.20, 'bg-info'),
('Lamb', 'Merino, Suffolk', '40-50', '4.5:1', '6-8', 85000, 42.00, 37.35, 'bg-success'),
('Fish', 'Salmon, Tuna', '3-8', '1.2:1', '12-18', 250000, 28.50, 24.65, 'bg-primary'),
('Turkey', 'Bronze, White Broad', '12-18', '2.3:1', '4-5', 95000, 22.80, 21.45, 'bg-secondary'),
('Duck', 'Pekin, Muscovy', '2.8-4.2', '2.8:1', '2-3', 45000, 26.90, 25.10, 'bg-dark'),
('Goat', 'Boer, Nubian', '35-65', '5.2:1', '8-12', 65000, 38.50, 36.80, 'bg-warning');

-- =================================================================
-- TABLE #2: PRODUCTION RECORDS BY DISTRICT/DIVISION
-- =================================================================
CREATE TABLE production_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    district_division VARCHAR(100) NOT NULL,
    livestock_count INT NOT NULL,
    slaughter_rate_percent DECIMAL(5,2) NOT NULL,
    meat_yield_tons DECIMAL(10,2) NOT NULL,
    production_volume_tons DECIMAL(10,2) NOT NULL,
    period VARCHAR(20) NOT NULL,
    year INT NOT NULL DEFAULT 2024,
    quarter INT NOT NULL DEFAULT 4,
    meat_type VARCHAR(50) DEFAULT 'Mixed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Data for Production Records
INSERT INTO production_records (district_division, livestock_count, slaughter_rate_percent, meat_yield_tons, production_volume_tons, period, year, quarter, meat_type) VALUES
('Dhaka', 125000, 15.2, 2850.00, 3200.00, '2024-Q4', 2024, 4, 'Mixed'),
('Chittagong', 98000, 12.8, 2240.00, 2680.00, '2024-Q4', 2024, 4, 'Mixed'),
('Sylhet', 67000, 14.1, 1520.00, 1890.00, '2024-Q4', 2024, 4, 'Mixed'),
('Rajshahi', 89000, 13.7, 1980.00, 2350.00, '2024-Q4', 2024, 4, 'Mixed'),
('Khulna', 76000, 11.9, 1650.00, 1920.00, '2024-Q4', 2024, 4, 'Mixed'),
('Barisal', 54000, 10.5, 1180.00, 1350.00, '2024-Q4', 2024, 4, 'Mixed'),
('Rangpur', 82000, 13.2, 1740.00, 2080.00, '2024-Q4', 2024, 4, 'Mixed'),
('Mymensingh', 71000, 12.4, 1560.00, 1820.00, '2024-Q4', 2024, 4, 'Mixed'),
-- Historical data for trend analysis
('Dhaka', 118000, 14.8, 2650.00, 2950.00, '2024-Q3', 2024, 3, 'Mixed'),
('Chittagong', 94000, 12.2, 2100.00, 2480.00, '2024-Q3', 2024, 3, 'Mixed'),
('Dhaka', 112000, 14.1, 2420.00, 2780.00, '2024-Q2', 2024, 2, 'Mixed'),
('Chittagong', 89000, 11.8, 1950.00, 2280.00, '2024-Q2', 2024, 2, 'Mixed');

-- =================================================================
-- TABLE #3: HISTORICAL PRICE DATA & TREND ANALYSIS
-- =================================================================
CREATE TABLE price_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_type VARCHAR(50) NOT NULL,
    region VARCHAR(100) NOT NULL,
    period VARCHAR(20) NOT NULL,
    wholesale_price_usd DECIMAL(10,2) NOT NULL,
    retail_price_usd DECIMAL(10,2) NOT NULL,
    yoy_change_percent VARCHAR(10) NOT NULL,
    seasonal_trend ENUM('Rising', 'Stable', 'Declining') NOT NULL,
    year INT NOT NULL,
    quarter INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Data for Price History
INSERT INTO price_history (product_type, region, period, wholesale_price_usd, retail_price_usd, yoy_change_percent, seasonal_trend, year, quarter) VALUES
('Beef', 'Northern Districts', 'Q4 2024', 8.45, 12.80, '+12.3%', 'Rising', 2024, 4),
('Lamb', 'Southern Districts', 'Q4 2024', 9.20, 14.50, '+8.7%', 'Stable', 2024, 4),
('Fish', 'Eastern Districts', 'Q4 2024', 7.80, 11.20, '+15.6%', 'Rising', 2024, 4),
('Pork', 'Western Districts', 'Q4 2024', 6.50, 9.80, '-2.3%', 'Declining', 2024, 4),
('Chicken', 'All Regions', 'Q4 2024', 4.20, 6.90, '+5.1%', 'Stable', 2024, 4),
('Beef', 'Northern Districts', 'Q3 2024', 8.15, 12.20, '+10.8%', 'Rising', 2024, 3),
('Lamb', 'Southern Districts', 'Q3 2024', 8.90, 14.10, '+7.2%', 'Stable', 2024, 3),
('Fish', 'Eastern Districts', 'Q3 2024', 7.50, 10.80, '+13.1%', 'Rising', 2024, 3),
('Pork', 'Western Districts', 'Q3 2024', 6.80, 10.20, '-1.8%', 'Declining', 2024, 3),
('Chicken', 'All Regions', 'Q3 2024', 4.10, 6.70, '+4.5%', 'Stable', 2024, 3);

-- =================================================================
-- TABLE #4: REGIONAL CONSUMPTION PATTERNS & DEMOGRAPHICS
-- =================================================================
CREATE TABLE consumption_patterns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    region VARCHAR(100) NOT NULL,
    meat_type VARCHAR(50) NOT NULL,
    per_capita_consumption_kg DECIMAL(8,2) NOT NULL,
    population INT NOT NULL,
    demographic_group VARCHAR(50) NOT NULL,
    nutritional_intake_calories INT NOT NULL,
    dietary_impact_score DECIMAL(3,1) NOT NULL,
    period VARCHAR(20) NOT NULL,
    year INT NOT NULL DEFAULT 2024,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Data for Consumption Patterns
INSERT INTO consumption_patterns (region, meat_type, per_capita_consumption_kg, population, demographic_group, nutritional_intake_calories, dietary_impact_score, period, year) VALUES
('Northern Districts', 'Beef', 28.5, 25000000, 'Urban Adult', 2850, 8.2, '2024-Q4', 2024),
('Northern Districts', 'Chicken', 35.2, 25000000, 'Urban Adult', 1760, 7.8, '2024-Q4', 2024),
('Southern Districts', 'Fish', 42.8, 18000000, 'Coastal Community', 1990, 9.1, '2024-Q4', 2024),
('Eastern Districts', 'Chicken', 32.1, 22000000, 'Rural Family', 1610, 7.5, '2024-Q4', 2024),
('Western Districts', 'Pork', 18.9, 15000000, 'Urban Adult', 1890, 6.8, '2024-Q4', 2024),
('All Regions', 'Lamb', 12.4, 80000000, 'Premium Consumer', 1240, 8.5, '2024-Q4', 2024),
('Northern Districts', 'Turkey', 8.7, 25000000, 'Holiday Consumer', 870, 7.2, '2024-Q4', 2024),
('Southern Districts', 'Duck', 6.3, 18000000, 'Traditional Family', 630, 6.9, '2024-Q4', 2024);

-- =================================================================
-- TABLE #5: PRICE ELASTICITY ANALYSIS
-- =================================================================
CREATE TABLE price_elasticity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product VARCHAR(50) NOT NULL,
    elasticity_coefficient DECIMAL(5,2) NOT NULL,
    classification ENUM('Elastic', 'Inelastic') NOT NULL,
    price_impact_percent VARCHAR(20) NOT NULL,
    optimal_price_usd DECIMAL(10,2) NOT NULL,
    revenue_potential_percent VARCHAR(10) NOT NULL,
    sensitivity_level ENUM('Low', 'Medium', 'High') NOT NULL,
    cross_elasticity_substitute VARCHAR(50),
    cross_elasticity_value DECIMAL(5,2),
    period VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Data for Price Elasticity
INSERT INTO price_elasticity (product, elasticity_coefficient, classification, price_impact_percent, optimal_price_usd, revenue_potential_percent, sensitivity_level, cross_elasticity_substitute, cross_elasticity_value, period) VALUES
('Beef', -0.42, 'Inelastic', '-4.2% demand', 38.50, '+12.5%', 'Low', 'Lamb', 0.23, '2024-Q4'),
('Lamb', -0.68, 'Inelastic', '-6.8% demand', 44.20, '+8.3%', 'Medium', 'Beef', 0.18, '2024-Q4'),
('Fish', -1.32, 'Elastic', '-13.2% demand', 26.80, '-2.1%', 'High', 'Chicken', 0.45, '2024-Q4'),
('Pork', -0.89, 'Inelastic', '-8.9% demand', 19.75, '+4.7%', 'Medium', 'Chicken', 0.31, '2024-Q4'),
('Chicken', -0.78, 'Inelastic', '-7.8% demand', 14.20, '+6.1%', 'Medium', 'Fish', 0.28, '2024-Q4'),
('Turkey', -0.95, 'Inelastic', '-9.5% demand', 24.50, '+3.8%', 'Medium', 'Chicken', 0.35, '2024-Q4'),
('Duck', -1.15, 'Elastic', '-11.5% demand', 28.90, '-1.2%', 'High', 'Chicken', 0.42, '2024-Q4');

-- =================================================================
-- TABLE #6: SUPPLY VS DEMAND COMPARATIVE ANALYSIS
-- =================================================================
CREATE TABLE supply_demand_analysis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product VARCHAR(50) NOT NULL,
    supply_tons INT NOT NULL,
    demand_tons INT NOT NULL,
    balance_tons INT AS (supply_tons - demand_tons) STORED,
    market_status ENUM('Under-supplied', 'Over-supplied', 'Balanced') NOT NULL,
    price_impact_percent VARCHAR(10) NOT NULL,
    business_action VARCHAR(200) NOT NULL,
    region VARCHAR(100) DEFAULT 'National',
    period VARCHAR(20) NOT NULL,
    year INT NOT NULL DEFAULT 2024,
    quarter INT NOT NULL DEFAULT 4,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Sample Data for Supply vs Demand
INSERT INTO supply_demand_analysis (product, supply_tons, demand_tons, market_status, price_impact_percent, business_action, region, period, year, quarter) VALUES
('Beef', 890000, 920000, 'Under-supplied', '+8.5%', 'Increase Production', 'National', '2024-Q4', 2024, 4),
('Chicken', 1250000, 1180000, 'Over-supplied', '-3.2%', 'Reduce Production', 'National', '2024-Q4', 2024, 4),
('Pork', 450000, 415000, 'Over-supplied', '-8.1%', 'Export Surplus', 'National', '2024-Q4', 2024, 4),
('Lamb', 85000, 70000, 'Over-supplied', '+2.5%', 'Premium Markets', 'National', '2024-Q4', 2024, 4),
('Fish', 250000, 290000, 'Under-supplied', '+15.2%', 'Expand Aquaculture', 'National', '2024-Q4', 2024, 4),
('Turkey', 95000, 88000, 'Over-supplied', '+1.8%', 'Seasonal Marketing', 'National', '2024-Q4', 2024, 4),
('Duck', 45000, 52000, 'Under-supplied', '+12.8%', 'Increase Production', 'National', '2024-Q4', 2024, 4);

-- =================================================================
-- ADDITIONAL TABLES FOR ENHANCED ANALYTICS
-- =================================================================

-- Users Table for Authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'analyst', 'customer') DEFAULT 'customer',
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Insert Default Admin User (password: admin123)
INSERT INTO users (username, email, password_hash, role, first_name, last_name) VALUES
('admin', 'admin@meatchain.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System', 'Administrator'),
('manager', 'manager@meatchain.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager', 'John', 'Manager'),
('analyst', 'analyst@meatchain.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'analyst', 'Jane', 'Analyst');

-- Inventory Table
CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(50) UNIQUE NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    category ENUM('Beef', 'Pork', 'Chicken', 'Lamb', 'Fish', 'Processed') NOT NULL,
    supplier VARCHAR(100) NOT NULL,
    quantity_kg DECIMAL(10,2) NOT NULL,
    unit_price_usd DECIMAL(10,2) NOT NULL,
    expiry_date DATE NOT NULL,
    status ENUM('Fresh', 'Expiring Soon', 'Expired') DEFAULT 'Fresh',
    location VARCHAR(100) NOT NULL,
    batch_number VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Sample Inventory Data
INSERT INTO inventory (product_id, product_name, category, supplier, quantity_kg, unit_price_usd, expiry_date, status, location, batch_number) VALUES
('BEEF001', 'Premium Angus Steaks', 'Beef', 'Northern Farms Ltd', 500.00, 45.99, '2025-01-15', 'Fresh', 'Cold Storage A1', 'BF240830001'),
('CHKN001', 'Free Range Chicken', 'Chicken', 'Green Valley Poultry', 750.00, 18.50, '2024-09-15', 'Expiring Soon', 'Cold Storage B2', 'CK240830001'),
('PORK001', 'Organic Pork Chops', 'Pork', 'Heritage Farms', 300.00, 22.75, '2024-12-20', 'Fresh', 'Cold Storage A2', 'PK240830001'),
('LAMB001', 'Grass Fed Lamb', 'Lamb', 'Highland Ranches', 200.00, 52.00, '2025-02-10', 'Fresh', 'Cold Storage C1', 'LB240830001'),
('FISH001', 'Atlantic Salmon', 'Fish', 'Ocean Fresh Ltd', 400.00, 32.80, '2024-09-05', 'Expiring Soon', 'Frozen Storage F1', 'FS240830001');

-- =================================================================
-- VIEWS FOR QUICK ANALYTICS
-- =================================================================

-- View: Current Production Summary
CREATE VIEW production_summary AS
SELECT 
    district_division,
    SUM(livestock_count) as total_livestock,
    AVG(slaughter_rate_percent) as avg_slaughter_rate,
    SUM(meat_yield_tons) as total_meat_yield,
    SUM(production_volume_tons) as total_production
FROM production_records 
WHERE year = 2024 AND quarter = 4
GROUP BY district_division
ORDER BY total_production DESC;

-- View: Price Trend Analysis
CREATE VIEW price_trends AS
SELECT 
    product_type,
    region,
    AVG(wholesale_price_usd) as avg_wholesale,
    AVG(retail_price_usd) as avg_retail,
    COUNT(*) as data_points
FROM price_history 
WHERE year = 2024
GROUP BY product_type, region
ORDER BY product_type, region;

-- View: Market Balance Overview
CREATE VIEW market_balance AS
SELECT 
    product,
    supply_tons,
    demand_tons,
    balance_tons,
    market_status,
    ROUND((balance_tons / demand_tons) * 100, 2) as balance_percentage
FROM supply_demand_analysis 
WHERE year = 2024 AND quarter = 4
ORDER BY ABS(balance_tons) DESC;

-- =================================================================
-- STORED PROCEDURES FOR DATA OPERATIONS
-- =================================================================

DELIMITER //

-- Procedure: Calculate Price Trend
CREATE PROCEDURE CalculatePriceTrend(
    IN product_name VARCHAR(50),
    IN current_price DECIMAL(10,2),
    IN previous_price DECIMAL(10,2),
    OUT trend_direction VARCHAR(20),
    OUT trend_percentage DECIMAL(5,2)
)
BEGIN
    IF previous_price IS NULL OR previous_price = 0 THEN
        SET trend_direction = 'No Data';
        SET trend_percentage = 0;
    ELSE
        SET trend_percentage = ((current_price - previous_price) / previous_price) * 100;
        IF trend_percentage > 0 THEN
            SET trend_direction = 'Rising';
        ELSEIF trend_percentage < 0 THEN
            SET trend_direction = 'Falling';
        ELSE
            SET trend_direction = 'Stable';
        END IF;
    END IF;
END//

-- Procedure: Update Market Status
CREATE PROCEDURE UpdateMarketStatus()
BEGIN
    UPDATE supply_demand_analysis 
    SET market_status = CASE
        WHEN balance_tons > 50000 THEN 'Over-supplied'
        WHEN balance_tons < -50000 THEN 'Under-supplied'
        ELSE 'Balanced'
    END
    WHERE year = 2024 AND quarter = 4;
END//

DELIMITER ;

-- =================================================================
-- INDEXES FOR PERFORMANCE
-- =================================================================

-- Indexes for faster queries
CREATE INDEX idx_meat_products_type ON meat_products(meat_type);
CREATE INDEX idx_production_district ON production_records(district_division);
CREATE INDEX idx_production_period ON production_records(year, quarter);
CREATE INDEX idx_price_history_product ON price_history(product_type);
CREATE INDEX idx_price_history_period ON price_history(year, quarter);
CREATE INDEX idx_consumption_region ON consumption_patterns(region);
CREATE INDEX idx_elasticity_product ON price_elasticity(product);
CREATE INDEX idx_supply_demand_product ON supply_demand_analysis(product);
CREATE INDEX idx_inventory_category ON inventory(category);
CREATE INDEX idx_inventory_status ON inventory(status);

-- =================================================================
-- SAMPLE ANALYTICS QUERIES
-- =================================================================

-- Query 1: Top producing districts
SELECT district_division, SUM(production_volume_tons) as total_production
FROM production_records 
WHERE year = 2024 
GROUP BY district_division 
ORDER BY total_production DESC 
LIMIT 5;

-- Query 2: Price volatility by product
SELECT 
    product_type,
    STDDEV(wholesale_price_usd) as price_volatility,
    AVG(wholesale_price_usd) as avg_price
FROM price_history 
GROUP BY product_type 
ORDER BY price_volatility DESC;

-- Query 3: Regional consumption leaders
SELECT 
    region,
    meat_type,
    SUM(per_capita_consumption_kg * population) as total_consumption
FROM consumption_patterns 
GROUP BY region, meat_type 
ORDER BY total_consumption DESC;

-- =================================================================
-- DATA VALIDATION TRIGGERS
-- =================================================================

DELIMITER //

-- Trigger: Validate production data
CREATE TRIGGER validate_production_data
BEFORE INSERT ON production_records
FOR EACH ROW
BEGIN
    IF NEW.slaughter_rate_percent < 0 OR NEW.slaughter_rate_percent > 100 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Slaughter rate must be between 0 and 100 percent';
    END IF;
    
    IF NEW.livestock_count <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Livestock count must be positive';
    END IF;
END//

-- Trigger: Update inventory status based on expiry
CREATE TRIGGER update_inventory_status
BEFORE UPDATE ON inventory
FOR EACH ROW
BEGIN
    IF NEW.expiry_date <= CURDATE() THEN
        SET NEW.status = 'Expired';
    ELSEIF NEW.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN
        SET NEW.status = 'Expiring Soon';
    ELSE
        SET NEW.status = 'Fresh';
    END IF;
END//

DELIMITER ;

-- =================================================================
-- FINAL SETUP COMMANDS
-- =================================================================

-- Grant permissions (adjust as needed)
-- GRANT ALL PRIVILEGES ON meat_supply_chain_db.* TO 'meatchain_user'@'localhost';
-- FLUSH PRIVILEGES;

-- Display table information
SHOW TABLES;

-- Display row counts
SELECT 
    'meat_products' as table_name, COUNT(*) as row_count FROM meat_products
UNION ALL
SELECT 'production_records', COUNT(*) FROM production_records
UNION ALL
SELECT 'price_history', COUNT(*) FROM price_history
UNION ALL
SELECT 'consumption_patterns', COUNT(*) FROM consumption_patterns
UNION ALL
SELECT 'price_elasticity', COUNT(*) FROM price_elasticity
UNION ALL
SELECT 'supply_demand_analysis', COUNT(*) FROM supply_demand_analysis
UNION ALL
SELECT 'inventory', COUNT(*) FROM inventory
UNION ALL
SELECT 'users', COUNT(*) FROM users;

-- =================================================================
-- END OF SQL SCRIPT
-- =================================================================
