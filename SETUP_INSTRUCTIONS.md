# XAMPP Database Setup Instructions

## Prerequisites
- XAMPP installed on your system
- Web browser for accessing phpMyAdmin

## Setup Steps

### 1. Start XAMPP Services
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** services
3. Verify both services are running (green status)

### 2. Database Creation
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "New" to create a new database
3. Enter database name: `meat_supply_chain`
4. Click "Create"

### 3. Import Database Schema
1. Select the `meat_supply_chain` database
2. Click on "Import" tab
3. Click "Choose File" and select: `database/meat_supply_chain.sql`
4. Click "Go" to import all tables and sample data

**What happens during import:**
- Creates 8 main tables with all analytics features
- Inserts realistic sample data for immediate testing
- Sets up 3 optimized views for fast queries
- Establishes database relationships and indexes
- Takes about 10-30 seconds depending on your system

### 4. Verify Database Structure
After import, you should see these 8 tables:
- `meat_products` (Feature #1)
- `production_records` (Feature #2)  
- `price_history` (Feature #3)
- `consumption_patterns` (Feature #4)
- `price_elasticity` (Feature #5)
- `supply_demand_analysis` (Feature #6)
- `users` (Authentication)
- `audit_logs` (System tracking)

Plus 3 views:
- `production_summary`
- `price_trends` 
- `consumption_overview`

### 5. Configure Project Files
1. Copy entire project to XAMPP's `htdocs` folder:

   **Windows:**
   ```
   C:\xampp\htdocs\meat-supply-chain\
   ```
   
   **macOS:**
   ```
   /Applications/XAMPP/htdocs/meat-supply-chain/
   ```
   
   **Linux:**
   ```
   /opt/lampp/htdocs/meat-supply-chain/
   ```
   
   **Alternative Linux locations:**
   ```
   /var/www/html/meat-supply-chain/  (if using separate Apache)
   ~/xampp/htdocs/meat-supply-chain/  (portable XAMPP)
   ```

2. **How to find your htdocs folder:**
   - **Windows**: Open XAMPP Control Panel → Click "Explorer" button → Navigate to "htdocs" folder
   - **macOS**: Open Finder → Go to Applications → XAMPP → htdocs
   - **Linux**: Open terminal and run: `find / -name "htdocs" 2>/dev/null` or check `/opt/lampp/htdocs`
   - **All Systems**: In XAMPP Control Panel, click "Explorer" or "Open" next to Apache

3. Verify API configuration in `api/config.php`:
   ```php
   $host = 'localhost';
   $dbname = 'meat_supply_chain';
   $username = 'root';
   $password = '';  // Default XAMPP MySQL password
   ```

**What this does:**
- Ensures your PHP scripts can connect to the MySQL database
- Uses default XAMPP settings (username: root, no password)
- Points to the database you created in step 2
- Required for all analytics features to work properly

### 6. Test Database Connection
1. Open browser and go to: `http://localhost/meat-supply-chain/`
2. Navigate to analytics page
3. Charts should load with real database data
4. Check browser console for any API errors

## Database Features

### Sample Data Included
- 8 meat products with realistic production data
- Production records across 8 districts for 2024
- Historical price data for trend analysis
- Regional consumption patterns
- Price elasticity calculations
- Supply-demand analysis with market insights

### Performance Optimizations
- Indexed columns for fast queries
- Optimized views for dashboard summaries
- Stored procedures for complex calculations

### Data Relationships
- Foreign key constraints ensure data integrity
- Normalized structure prevents data duplication
- Audit trails track all data changes

## API Endpoints

### Available Endpoints
All accessible via `api/analytics.php?endpoint=`:

1. **meat-products** - Feature #1 data
2. **production-records** - Feature #2 data
3. **price-history** - Feature #3 data  
4. **consumption-patterns** - Feature #4 data
5. **price-elasticity** - Feature #5 data
6. **supply-demand** - Feature #6 data
7. **dashboard-summary** - Key metrics

### Testing APIs
Test individual endpoints:
```
http://localhost/meat-supply-chain/api/analytics.php?endpoint=meat-products
http://localhost/meat-supply-chain/api/analytics.php?endpoint=production-records
```

**✅ SUCCESS! If you see this response:**
```json
{
  "status": "success",
  "message": "Meat products retrieved successfully", 
  "data": [],
  "timestamp": "2025-08-30 13:42:51"
}
```

**This means:**
- ✅ API is working correctly
- ✅ Database connection is successful
- ✅ PHP scripts are functioning
- ⚠️ Database tables are empty OR database name mismatch

**If you already imported clean_import.sql:**

**Check database name mismatch:**
1. Your `clean_import.sql` creates database: `meat_supply_chain` 
2. Your `api/config.php` should point to: `meat_supply_chain`
3. **Verify in phpMyAdmin**: Which database name do you see?

**Quick verification steps:**
1. Go to phpMyAdmin: `http://localhost/phpmyadmin`
2. Check if you see database: `meat_supply_chain`
3. Click on it and verify these tables exist with data:
   - `meat_products` (should have 8 records)
   - `production_records` (should have 8 records)
   - `price_history` (should have 6 records)

**If tables are empty after import:**
```sql
-- Run this in phpMyAdmin SQL tab to check record counts:
SELECT 'meat_products' as table_name, COUNT(*) as records FROM meat_products
UNION ALL SELECT 'production_records', COUNT(*) FROM production_records
UNION ALL SELECT 'price_history', COUNT(*) FROM price_history;
```

**Database name issue fix:**
If your database is named differently, update `api/config.php`:
```php
$dbname = 'your_actual_database_name';  // Match what you see in phpMyAdmin
```

**After import, you should see:**
```json
{
  "status": "success",
  "message": "Meat products retrieved successfully",
  "data": [
    {
      "id": 1,
      "meat_type": "Beef",
      "breed_source": "Angus, Holstein",
      "production_volume_tons": 890000,
      "current_price_usd": "35.99"
    }
    // ... more records
  ]
}
```

**🚨 If you get 404 Error:**

**Common causes and solutions:**

1. **Check your project folder name in htdocs:**
   - Make sure your folder is named exactly `meat-supply-chain` in htdocs
   - If named differently, use that name in the URL
   - Example: If folder is `DataBase_Final_Project`, use:
     ```
     http://localhost/DataBase_Final_Project/api/analytics.php?endpoint=meat-products
     ```

2. **Verify file structure:**
   - Ensure `api` folder exists in your project root
   - Check that `analytics.php` is inside the `api` folder
   - File structure should be:
     ```
     htdocs/
     └── your-project-name/
         ├── api/
         │   ├── config.php
         │   └── analytics.php
         ├── pages/
         └── other files...
     ```

3. **Test basic connection first:**
   ```
   http://localhost/your-project-name/test-connection.php
   http://localhost/your-project-name/pages/analytics.html
   ```

4. **Check XAMPP setup:**
   - Ensure Apache is running (green in XAMPP Control Panel)
   - Try accessing: `http://localhost/` (should show XAMPP welcome page)
   - If that fails, restart Apache in XAMPP Control Panel

## Responsive Charts

### Auto-Refresh Features
- Charts update every 30 seconds automatically
- Manual refresh button available
- Real-time notifications for data changes
- Error handling with user feedback

### Chart Types by Feature
1. **Feature #1**: Production volume & price comparison charts
2. **Feature #2**: District production & slaughter rate charts  
3. **Feature #3**: Price history trend charts
4. **Feature #4**: Regional consumption pattern charts
5. **Feature #5**: Price elasticity coefficient charts
6. **Feature #6**: Supply vs demand comparison charts

## Troubleshooting

### Common Issues

**Charts not loading:**
- Check XAMPP services are running
- Verify database connection in browser console
- Ensure all files are in correct htdocs directory

**API errors:**
- Check database name and credentials in `api/config.php`
- Verify database was imported successfully
- Check PHP error logs in XAMPP

**Permission errors:**
- Ensure XAMPP has proper folder permissions
- Try running XAMPP as administrator

### Database Connection Test
Create test file `test-connection.php` in your project root:
```php
<?php
require 'api/config.php';

$db = new Database();
$conn = $db->getConnection();

if($conn) {
    echo "Database connection successful!";
} else {
    echo "Database connection failed!";
}
?>
```

**How to use:**
1. Save this file as `test-connection.php` in your htdocs project folder
2. Open browser and go to: `http://localhost/meat-supply-chain/test-connection.php`
3. Should display "Database connection successful!" if everything is working
4. If it shows "failed", check your database name and XAMPP services

## Data Management

### Cleaning Up Previous Test Data

If you have old test data that needs to be removed before importing the new schema:

#### Method 1: Delete Entire Database (Recommended)
1. Go to phpMyAdmin: `http://localhost/phpmyadmin`
2. Select the `meat_supply_chain` database (if it exists)
3. Click "Drop" button at the top
4. Confirm deletion
5. Create new database with same name: `meat_supply_chain`
6. Import the fresh `database/meat_supply_chain.sql` file

#### Method 2: Clear All Tables
If you want to keep the database but clear all data:
```sql
-- Run these commands in phpMyAdmin SQL tab
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE audit_logs;
TRUNCATE TABLE supply_demand_analysis;
TRUNCATE TABLE price_elasticity;
TRUNCATE TABLE consumption_patterns;
TRUNCATE TABLE price_history;
TRUNCATE TABLE production_records;
TRUNCATE TABLE meat_products;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;
```

#### Method 3: Delete Specific Records
To remove only test records while keeping structure:
```sql
-- Delete all records from specific tables
DELETE FROM meat_products WHERE meat_type LIKE '%test%';
DELETE FROM production_records WHERE district_division LIKE '%test%';
DELETE FROM price_history WHERE product_type LIKE '%test%';
-- Add more DELETE statements as needed
```

#### Method 4: Reset Auto-Increment IDs
After clearing data, reset ID counters:
```sql
ALTER TABLE meat_products AUTO_INCREMENT = 1;
ALTER TABLE production_records AUTO_INCREMENT = 1;
ALTER TABLE price_history AUTO_INCREMENT = 1;
ALTER TABLE consumption_patterns AUTO_INCREMENT = 1;
ALTER TABLE price_elasticity AUTO_INCREMENT = 1;
ALTER TABLE supply_demand_analysis AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE audit_logs AUTO_INCREMENT = 1;
```

### Adding New Records
- Use the web interface modals for data entry
- Data is immediately reflected in charts
- All additions are logged in audit trail

### Backup Database
Regular backups recommended:
1. Go to phpMyAdmin
2. Select `meat_supply_chain` database
3. Click "Export" tab
4. Download SQL file

### Sample Queries
Test database directly in phpMyAdmin:
```sql
-- Get production summary
SELECT * FROM production_summary;

-- Check recent price trends  
SELECT * FROM price_trends WHERE year = 2024;

-- View consumption overview
SELECT * FROM consumption_overview;
```

## Security Notes
- Default XAMPP setup is for development only
- Change default MySQL password for production
- Implement user authentication for live deployment
- Regular database backups recommended

## Next Steps
1. Complete the setup following these instructions
2. Test all 6 analytics features
3. Verify responsive chart updates
4. Add new data through the interface
5. Monitor auto-refresh functionality

The system now provides real-time analytics with responsive charts that update automatically from the database!
