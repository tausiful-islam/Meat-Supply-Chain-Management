-- =================================================================
-- CLEAN SQL IMPORT FOR MEAT SUPPLY CHAIN DATABASE
-- Fixed version without Unicode characters
-- =================================================================

-- Create Database
DROP DATABASE IF EXISTS meat_supply_chain;
CREATE DATABASE meat_supply_chain;
USE meat_supply_chain;

-- =================================================================
-- TABLE #1: MEAT PRODUCTS (Feature #1)
-- =================================================================
CREATE TABLE meat_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meat_type VARCHAR(50) NOT NULL,
    breed_source VARCHAR(100) NOT NULL,
    avg_weight_kg DECIMAL(8,2) NOT NULL,
    feed_conversion_ratio DECIMAL(4,2) NOT NULL,
    rearing_period_months INT NOT NULL,
    production_volume_tons DECIMAL(10,2) NOT NULL,
    current_price_usd DECIMAL(8,2) NOT NULL,
    previous_year_price_usd DECIMAL(8,2),
    badge_color VARCHAR(20) DEFAULT 'primary',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_meat_type (meat_type),
    INDEX idx_production_volume (production_volume_tons)
);

-- Insert Sample Data for Meat Products
INSERT INTO meat_products (meat_type, breed_source, avg_weight_kg, feed_conversion_ratio, rearing_period_months, production_volume_tons, current_price_usd, previous_year_price_usd, badge_color) VALUES
('Beef', 'Holstein Friesian', 450.50, 6.50, 24, 125.8, 8.45, 7.89, 'danger'),
('Chicken', 'Broiler Hybrid', 2.20, 1.80, 2, 89.3, 3.25, 3.10, 'warning'),
('Pork', 'Yorkshire Cross', 110.75, 3.20, 6, 78.6, 5.60, 5.25, 'info'),
('Lamb', 'Dorper Sheep', 35.60, 4.80, 12, 45.2, 12.30, 11.85, 'success'),
('Turkey', 'Broad Breasted Bronze', 8.90, 2.40, 4, 23.7, 4.80, 4.65, 'primary'),
('Duck', 'Pekin Duck', 3.20, 2.90, 3, 15.4, 6.20, 5.95, 'secondary'),
('Goat', 'Boer Goat', 45.30, 5.20, 18, 32.1, 9.75, 9.20, 'dark'),
('Fish', 'Tilapia Farm', 0.85, 1.50, 1, 67.9, 7.85, 7.40, 'light');

-- =================================================================
-- TABLE #2: PRODUCTION RECORDS (Feature #2)
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
    quarter INT NOT NULL,
    meat_type VARCHAR(50) DEFAULT 'Mixed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_district (district_division),
    INDEX idx_year_quarter (year, quarter),
    INDEX idx_production (production_volume_tons)
);

-- Insert Sample Data for Production Records
INSERT INTO production_records (district_division, livestock_count, slaughter_rate_percent, meat_yield_tons, production_volume_tons, period, year, quarter, meat_type) VALUES
('Dhaka Division', 125000, 85.2, 45.8, 52.3, '2024-Q4', 2024, 4, 'Mixed'),
('Chittagong Division', 98000, 82.7, 38.2, 43.6, '2024-Q4', 2024, 4, 'Mixed'),
('Rajshahi Division', 87000, 79.3, 32.1, 36.8, '2024-Q4', 2024, 4, 'Mixed'),
('Khulna Division', 76000, 81.5, 28.9, 33.2, '2024-Q4', 2024, 4, 'Mixed'),
('Sylhet Division', 65000, 78.9, 24.2, 27.8, '2024-Q4', 2024, 4, 'Mixed'),
('Rangpur Division', 72000, 80.1, 26.8, 30.5, '2024-Q4', 2024, 4, 'Mixed'),
('Mymensingh Division', 68000, 77.6, 25.1, 28.9, '2024-Q4', 2024, 4, 'Mixed'),
('Barisal Division', 58000, 76.2, 21.3, 24.7, '2024-Q4', 2024, 4, 'Mixed');

-- =================================================================
-- TABLE #3: PRICE HISTORY (Feature #3)
-- =================================================================
CREATE TABLE price_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_type VARCHAR(50) NOT NULL,
    region VARCHAR(100) NOT NULL,
    period VARCHAR(20) NOT NULL,
    wholesale_price_usd DECIMAL(8,2) NOT NULL,
    retail_price_usd DECIMAL(8,2) NOT NULL,
    yoy_change_percent DECIMAL(5,2) NOT NULL,
    seasonal_trend VARCHAR(20) NOT NULL,
    year INT NOT NULL DEFAULT 2024,
    quarter INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_product_region (product_type, region),
    INDEX idx_year_quarter (year, quarter)
);

-- Insert Sample Data for Price History
INSERT INTO price_history (product_type, region, period, wholesale_price_usd, retail_price_usd, yoy_change_percent, seasonal_trend, year, quarter) VALUES
('Beef', 'Northern Region', '2024-Q4', 7.85, 8.45, 7.1, 'Rising', 2024, 4),
('Chicken', 'Central Region', '2024-Q4', 2.95, 3.25, 4.8, 'Stable', 2024, 4),
('Pork', 'Southern Region', '2024-Q4', 5.20, 5.60, 6.7, 'Rising', 2024, 4),
('Lamb', 'Western Region', '2024-Q4', 11.50, 12.30, 3.8, 'Seasonal', 2024, 4),
('Turkey', 'Eastern Region', '2024-Q4', 4.40, 4.80, 3.2, 'Stable', 2024, 4),
('Duck', 'Central Region', '2024-Q4', 5.80, 6.20, 4.2, 'Rising', 2024, 4);

-- =================================================================
-- TABLE #4: CONSUMPTION PATTERNS (Feature #4)
-- =================================================================
CREATE TABLE consumption_patterns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    region VARCHAR(100) NOT NULL,
    meat_type VARCHAR(50) NOT NULL,
    per_capita_consumption_kg DECIMAL(6,2) NOT NULL,
    population INT NOT NULL,
    demographic_group VARCHAR(50) NOT NULL,
    nutritional_intake_calories INT NOT NULL,
    dietary_impact_score DECIMAL(3,1) NOT NULL,
    period VARCHAR(20) NOT NULL,
    year INT NOT NULL DEFAULT 2024,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_region_meat (region, meat_type),
    INDEX idx_consumption (per_capita_consumption_kg)
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
-- TABLE #5: PRICE ELASTICITY ANALYSIS (Feature #5)
-- =================================================================
CREATE TABLE price_elasticity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product VARCHAR(50) NOT NULL,
    elasticity_coefficient DECIMAL(6,3) NOT NULL,
    classification VARCHAR(20) NOT NULL,
    price_impact_percent DECIMAL(5,2) NOT NULL,
    optimal_price_usd DECIMAL(8,2) NOT NULL,
    revenue_potential_percent DECIMAL(5,2) NOT NULL,
    sensitivity_level VARCHAR(20) NOT NULL,
    cross_elasticity_substitute VARCHAR(50),
    cross_elasticity_value DECIMAL(6,3),
    period VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_product (product),
    INDEX idx_elasticity (elasticity_coefficient)
);

-- Insert Sample Data for Price Elasticity
INSERT INTO price_elasticity (product, elasticity_coefficient, classification, price_impact_percent, optimal_price_usd, revenue_potential_percent, sensitivity_level, cross_elasticity_substitute, cross_elasticity_value, period) VALUES
('Beef', -0.892, 'Inelastic', 15.2, 9.20, 12.8, 'Medium', 'Chicken', 0.456, '2024-Q4'),
('Chicken', -1.234, 'Elastic', 23.8, 3.45, 18.5, 'High', 'Turkey', 0.678, '2024-Q4'),
('Pork', -0.756, 'Inelastic', 12.9, 6.10, 9.7, 'Low', 'Beef', 0.234, '2024-Q4'),
('Lamb', -0.623, 'Inelastic', 8.4, 13.50, 6.2, 'Low', 'Goat', 0.345, '2024-Q4'),
('Turkey', -1.456, 'Elastic', 28.3, 5.20, 22.1, 'High', 'Chicken', 0.789, '2024-Q4'),
('Duck', -1.123, 'Elastic', 19.7, 6.80, 15.3, 'Medium', 'Chicken', 0.567, '2024-Q4'),
('Fish', -0.834, 'Inelastic', 11.6, 8.50, 8.9, 'Medium', 'Chicken', 0.123, '2024-Q4'),
('Goat', -0.712, 'Inelastic', 10.3, 10.75, 7.8, 'Low', 'Lamb', 0.298, '2024-Q4');

-- =================================================================
-- TABLE #6: SUPPLY VS DEMAND ANALYSIS (Feature #6)
-- =================================================================
CREATE TABLE supply_demand_analysis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product VARCHAR(50) NOT NULL,
    supply_tons DECIMAL(10,2) NOT NULL,
    demand_tons DECIMAL(10,2) NOT NULL,
    balance_tons DECIMAL(10,2) AS (supply_tons - demand_tons) STORED,
    market_status VARCHAR(20) AS (
        CASE 
            WHEN (supply_tons - demand_tons) > 5 THEN 'Surplus'
            WHEN (supply_tons - demand_tons) < -5 THEN 'Shortage'
            ELSE 'Balanced'
        END
    ) STORED,
    price_impact_percent DECIMAL(5,2) NOT NULL,
    business_action VARCHAR(100) NOT NULL,
    region VARCHAR(100) DEFAULT 'National',
    period VARCHAR(20) NOT NULL,
    year INT NOT NULL DEFAULT 2024,
    quarter INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_product (product),
    INDEX idx_market_status (market_status),
    INDEX idx_balance (balance_tons)
);

-- Insert Sample Data for Supply-Demand Analysis
INSERT INTO supply_demand_analysis (product, supply_tons, demand_tons, price_impact_percent, business_action, region, period, year, quarter) VALUES
('Beef', 125.8, 142.3, -8.5, 'Increase Production', 'National', '2024-Q4', 2024, 4),
('Chicken', 89.3, 76.8, 12.2, 'Expand Markets', 'National', '2024-Q4', 2024, 4),
('Pork', 78.6, 82.1, -4.8, 'Optimize Supply Chain', 'National', '2024-Q4', 2024, 4),
('Lamb', 45.2, 39.7, 15.8, 'Premium Positioning', 'National', '2024-Q4', 2024, 4),
('Turkey', 23.7, 28.9, -18.2, 'Increase Production', 'National', '2024-Q4', 2024, 4),
('Duck', 15.4, 12.8, 22.5, 'Export Opportunities', 'National', '2024-Q4', 2024, 4),
('Fish', 67.9, 95.2, -22.8, 'Import Supplement', 'National', '2024-Q4', 2024, 4),
('Goat', 32.1, 28.6, 13.7, 'Market Expansion', 'National', '2024-Q4', 2024, 4);

-- =================================================================
-- ADDITIONAL TABLES
-- =================================================================

-- Users table for authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Audit logs for tracking changes
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(50) NOT NULL,
    operation VARCHAR(10) NOT NULL,
    record_id INT NOT NULL,
    old_values JSON,
    new_values JSON,
    user_id INT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- =================================================================
-- VIEWS FOR DASHBOARD OPTIMIZATION
-- =================================================================

-- Production Summary View
CREATE VIEW production_summary AS
SELECT 
    district_division,
    SUM(livestock_count) as total_livestock,
    AVG(slaughter_rate_percent) as avg_slaughter_rate,
    SUM(meat_yield_tons) as total_meat_yield,
    SUM(production_volume_tons) as total_production,
    year,
    quarter
FROM production_records 
GROUP BY district_division, year, quarter;

-- Price Trends View
CREATE VIEW price_trends AS
SELECT 
    product_type,
    region,
    AVG(wholesale_price_usd) as avg_wholesale,
    AVG(retail_price_usd) as avg_retail,
    AVG(yoy_change_percent) as avg_change,
    year,
    quarter
FROM price_history 
GROUP BY product_type, region, year, quarter;

-- Consumption Overview View
CREATE VIEW consumption_overview AS
SELECT 
    region,
    meat_type,
    SUM(per_capita_consumption_kg * population) as total_consumption,
    AVG(per_capita_consumption_kg) as avg_per_capita,
    SUM(population) as total_population,
    AVG(dietary_impact_score) as avg_impact_score
FROM consumption_patterns 
GROUP BY region, meat_type;

-- =================================================================
-- SAMPLE USERS
-- =================================================================
INSERT INTO users (username, password_hash, role, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@meatchain.com'),
('analyst', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Data Analyst', 'analyst@meatchain.com'),
('manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Supply Manager', 'manager@meatchain.com');

-- =================================================================
-- INDEXES FOR PERFORMANCE
-- =================================================================
CREATE INDEX idx_meat_products_price ON meat_products(current_price_usd);
CREATE INDEX idx_production_efficiency ON production_records(slaughter_rate_percent);
CREATE INDEX idx_price_trends ON price_history(yoy_change_percent);
CREATE INDEX idx_consumption_demographic ON consumption_patterns(demographic_group);
CREATE INDEX idx_elasticity_classification ON price_elasticity(classification);
CREATE INDEX idx_supply_demand_balance ON supply_demand_analysis(balance_tons);

-- =================================================================
-- VERIFICATION QUERIES
-- =================================================================
-- Uncomment these to verify data after import

-- SELECT 'meat_products' as table_name, COUNT(*) as records FROM meat_products
-- UNION ALL SELECT 'production_records', COUNT(*) FROM production_records
-- UNION ALL SELECT 'price_history', COUNT(*) FROM price_history
-- UNION ALL SELECT 'consumption_patterns', COUNT(*) FROM consumption_patterns
-- UNION ALL SELECT 'price_elasticity', COUNT(*) FROM price_elasticity
-- UNION ALL SELECT 'supply_demand_analysis', COUNT(*) FROM supply_demand_analysis
-- UNION ALL SELECT 'users', COUNT(*) FROM users;

-- =================================================================
-- IMPORT COMPLETE
-- =================================================================
