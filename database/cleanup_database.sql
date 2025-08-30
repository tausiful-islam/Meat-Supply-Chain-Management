-- =====================================================
-- DATABASE CLEANUP SCRIPT
-- Use this to remove old test data before importing new schema
-- =====================================================

-- Disable foreign key checks to avoid constraint errors
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- OPTION 1: COMPLETE DATA CLEANUP (Recommended)
-- This removes all data but keeps table structure
-- =====================================================

-- Clear all tables in correct order (respecting dependencies)
TRUNCATE TABLE audit_logs;
TRUNCATE TABLE supply_demand_analysis;
TRUNCATE TABLE price_elasticity;
TRUNCATE TABLE consumption_patterns;
TRUNCATE TABLE price_history;
TRUNCATE TABLE production_records;
TRUNCATE TABLE meat_products;
TRUNCATE TABLE users;

-- Reset auto-increment counters to start from 1
ALTER TABLE meat_products AUTO_INCREMENT = 1;
ALTER TABLE production_records AUTO_INCREMENT = 1;
ALTER TABLE price_history AUTO_INCREMENT = 1;
ALTER TABLE consumption_patterns AUTO_INCREMENT = 1;
ALTER TABLE price_elasticity AUTO_INCREMENT = 1;
ALTER TABLE supply_demand_analysis AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE audit_logs AUTO_INCREMENT = 1;

-- =====================================================
-- OPTION 2: SELECTIVE CLEANUP
-- Remove only test/dummy data (uncomment if needed)
-- =====================================================

-- Remove test meat products
-- DELETE FROM meat_products WHERE meat_type LIKE '%test%' OR meat_type LIKE '%dummy%';

-- Remove test production records
-- DELETE FROM production_records WHERE district_division LIKE '%test%' OR district_division LIKE '%dummy%';

-- Remove test price history
-- DELETE FROM price_history WHERE product_type LIKE '%test%' OR product_type LIKE '%dummy%';

-- Remove test consumption data
-- DELETE FROM consumption_patterns WHERE region LIKE '%test%' OR region LIKE '%dummy%';

-- Remove test elasticity data
-- DELETE FROM price_elasticity WHERE product LIKE '%test%' OR product LIKE '%dummy%';

-- Remove test supply-demand data
-- DELETE FROM supply_demand_analysis WHERE product LIKE '%test%' OR product LIKE '%dummy%';

-- =====================================================
-- OPTION 3: DROP AND RECREATE TABLES (Nuclear option)
-- Use only if you want to completely start over
-- =====================================================

-- DROP TABLE IF EXISTS audit_logs;
-- DROP TABLE IF EXISTS supply_demand_analysis;
-- DROP TABLE IF EXISTS price_elasticity;
-- DROP TABLE IF EXISTS consumption_patterns;
-- DROP TABLE IF EXISTS price_history;
-- DROP TABLE IF EXISTS production_records;
-- DROP TABLE IF EXISTS meat_products;
-- DROP TABLE IF EXISTS users;

-- DROP VIEW IF EXISTS production_summary;
-- DROP VIEW IF EXISTS price_trends;
-- DROP VIEW IF EXISTS consumption_overview;

-- =====================================================
-- VERIFICATION QUERIES
-- Run these after cleanup to verify results
-- =====================================================

-- Check table counts (should be 0 after TRUNCATE)
SELECT 'meat_products' as table_name, COUNT(*) as record_count FROM meat_products
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
SELECT 'users', COUNT(*) FROM users
UNION ALL
SELECT 'audit_logs', COUNT(*) FROM audit_logs;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- INSTRUCTIONS FOR USE:
-- =====================================================
-- 1. Open phpMyAdmin: http://localhost/phpmyadmin
-- 2. Select your meat_supply_chain database
-- 3. Click "SQL" tab
-- 4. Copy and paste this entire script
-- 5. Click "Go" to execute
-- 6. After cleanup, import the fresh meat_supply_chain.sql file
-- =====================================================
