-- Create new database for meat production course
CREATE DATABASE IF NOT EXISTS meat_production_course;
USE meat_production_course;

-- 1. Meat Products Table
CREATE TABLE meat_products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    meat_type VARCHAR(100) NOT NULL,
    breed VARCHAR(100),
    average_weight_slaughter DECIMAL(8,2),
    feed_conversion_ratio DECIMAL(5,2),
    typical_rearing_period_days INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Production Records Table
CREATE TABLE production_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    district VARCHAR(100) NOT NULL,
    division VARCHAR(100) NOT NULL,
    livestock_count INT,
    slaughter_rate DECIMAL(5,2),
    meat_yield_kg DECIMAL(10,2),
    production_date DATE,
    meat_type VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Price History Table
CREATE TABLE price_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    meat_type VARCHAR(100) NOT NULL,
    wholesale_price DECIMAL(8,2),
    retail_price DECIMAL(8,2),
    district VARCHAR(100),
    division VARCHAR(100),
    price_date DATE,
    seasonal_factor VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Consumption Data Table
CREATE TABLE consumption_data (
    id INT PRIMARY KEY AUTO_INCREMENT,
    region VARCHAR(100) NOT NULL,
    demographic_group VARCHAR(100),
    per_capita_consumption_kg DECIMAL(8,2),
    nutritional_intake_protein_g DECIMAL(8,2),
    nutritional_intake_calories DECIMAL(8,2),
    survey_year YEAR,
    population_size INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. Demand Elasticity Table
CREATE TABLE demand_elasticity (
    id INT PRIMARY KEY AUTO_INCREMENT,
    meat_type VARCHAR(100) NOT NULL,
    price_change_percent DECIMAL(5,2),
    demand_change_percent DECIMAL(5,2),
    alternative_protein VARCHAR(100),
    cross_elasticity_value DECIMAL(6,3),
    analysis_period VARCHAR(100),
    region VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. Supply Demand Analysis Table
CREATE TABLE supply_demand_analysis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    region VARCHAR(100) NOT NULL,
    meat_type VARCHAR(100),
    supply_quantity_tons DECIMAL(10,2),
    demand_quantity_tons DECIMAL(10,2),
    surplus_deficit_tons DECIMAL(10,2),
    analysis_month DATE,
    policy_recommendation TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data for Meat Products
INSERT INTO meat_products (meat_type, breed, average_weight_slaughter, feed_conversion_ratio, typical_rearing_period_days) VALUES
('Beef', 'Holstein', 550.00, 6.5, 730),
('Chicken', 'Broiler', 2.5, 1.8, 42),
('Mutton', 'Katahdin', 45.00, 4.5, 365),
('Goat', 'Boer', 35.00, 4.2, 300),
('Duck', 'Pekin', 3.2, 2.8, 56),
('Beef', 'Angus', 600.00, 6.8, 750),
('Chicken', 'Layer', 1.8, 2.2, 365),
('Mutton', 'Dorper', 50.00, 4.8, 380);

-- Insert sample data for Production Records
INSERT INTO production_records (district, division, livestock_count, slaughter_rate, meat_yield_kg, production_date, meat_type) VALUES
('Dhaka', 'Dhaka', 15000, 12.5, 8750.00, '2024-01-15', 'Chicken'),
('Chittagong', 'Chittagong', 8500, 15.2, 12800.00, '2024-01-20', 'Beef'),
('Sylhet', 'Sylhet', 12000, 10.8, 5400.00, '2024-02-10', 'Mutton'),
('Rajshahi', 'Rajshahi', 9500, 14.5, 3325.00, '2024-02-15', 'Goat'),
('Khulna', 'Khulna', 7800, 18.2, 4524.00, '2024-03-05', 'Duck'),
('Barisal', 'Barisal', 11200, 13.8, 9240.00, '2024-03-10', 'Chicken'),
('Rangpur', 'Rangpur', 6900, 16.5, 10350.00, '2024-03-20', 'Beef'),
('Mymensingh', 'Mymensingh', 8700, 11.2, 4785.00, '2024-04-01', 'Mutton');

-- Insert sample data for Price History
INSERT INTO price_history (meat_type, wholesale_price, retail_price, district, division, price_date, seasonal_factor) VALUES
('Beef', 450.00, 520.00, 'Dhaka', 'Dhaka', '2024-01-15', 'winter'),
('Chicken', 180.00, 220.00, 'Chittagong', 'Chittagong', '2024-01-20', 'winter'),
('Mutton', 650.00, 750.00, 'Sylhet', 'Sylhet', '2024-02-10', 'winter'),
('Goat', 580.00, 680.00, 'Rajshahi', 'Rajshahi', '2024-02-15', 'summer'),
('Duck', 320.00, 380.00, 'Khulna', 'Khulna', '2024-03-05', 'summer'),
('Beef', 465.00, 540.00, 'Barisal', 'Barisal', '2024-03-10', 'summer'),
('Chicken', 175.00, 210.00, 'Rangpur', 'Rangpur', '2024-03-20', 'monsoon'),
('Mutton', 670.00, 780.00, 'Mymensingh', 'Mymensingh', '2024-04-01', 'monsoon');

-- Insert sample data for Consumption Data
INSERT INTO consumption_data (region, demographic_group, per_capita_consumption_kg, nutritional_intake_protein_g, nutritional_intake_calories, survey_year, population_size) VALUES
('Dhaka', 'urban', 25.5, 18.2, 425, 2024, 9500000),
('Chittagong', 'rural', 18.3, 12.8, 285, 2024, 8200000),
('Sylhet', 'urban', 22.1, 15.6, 380, 2024, 3800000),
('Rajshahi', 'rural', 16.8, 11.4, 260, 2024, 2900000),
('Khulna', 'income_bracket_high', 28.9, 20.3, 485, 2024, 2400000),
('Barisal', 'income_bracket_low', 12.5, 8.7, 195, 2024, 2350000),
('Rangpur', 'rural', 15.2, 10.1, 230, 2024, 1650000),
('Mymensingh', 'urban', 21.7, 14.9, 365, 2024, 1200000);

-- Insert sample data for Demand Elasticity
INSERT INTO demand_elasticity (meat_type, price_change_percent, demand_change_percent, alternative_protein, cross_elasticity_value, analysis_period, region) VALUES
('Beef', 10.5, -8.2, 'fish', 0.75, '2024-Q1', 'Dhaka'),
('Chicken', 15.2, -12.8, 'eggs', 0.85, '2024-Q1', 'Chittagong'),
('Mutton', 8.7, -6.5, 'dairy', 0.42, '2024-Q2', 'Sylhet'),
('Goat', 12.3, -9.8, 'plant-based', 0.68, '2024-Q2', 'Rajshahi'),
('Duck', 18.5, -15.2, 'fish', 0.92, '2024-Q3', 'Khulna'),
('Beef', 7.8, -5.9, 'eggs', 0.38, '2024-Q3', 'Barisal'),
('Chicken', 11.2, -8.9, 'dairy', 0.55, '2024-Q4', 'Rangpur'),
('Mutton', 14.6, -11.3, 'plant-based', 0.78, '2024-Q4', 'Mymensingh');

-- Insert sample data for Supply Demand Analysis
INSERT INTO supply_demand_analysis (region, meat_type, supply_quantity_tons, demand_quantity_tons, surplus_deficit_tons, analysis_month, policy_recommendation) VALUES
('Dhaka', 'Chicken', 1250.5, 1185.2, 65.3, '2024-01-01', 'Maintain current production levels'),
('Chittagong', 'Beef', 890.8, 945.6, -54.8, '2024-01-01', 'Increase production capacity'),
('Sylhet', 'Mutton', 425.3, 398.7, 26.6, '2024-02-01', 'Export surplus to nearby regions'),
('Rajshahi', 'Goat', 315.9, 342.1, -26.2, '2024-02-01', 'Import from surplus regions'),
('Khulna', 'Duck', 185.4, 165.8, 19.6, '2024-03-01', 'Maintain seasonal production'),
('Barisal', 'Chicken', 720.6, 755.2, -34.6, '2024-03-01', 'Increase local farming incentives'),
('Rangpur', 'Beef', 650.3, 598.9, 51.4, '2024-04-01', 'Develop processing facilities'),
('Mymensingh', 'Mutton', 385.7, 410.3, -24.6, '2024-04-01', 'Support local livestock development');
