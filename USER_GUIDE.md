# 🥩 MeatChain Pro - User Guide & Instructions

## 📋 Table of Contents
1. [System Overview](#system-overview)
2. [Getting Started](#getting-started)
3. [Access Instructions](#access-instructions)
4. [User Authentication](#user-authentication)
5. [Features & Navigation](#features--navigation)
6. [Page-by-Page Guide](#page-by-page-guide)
7. [User Roles & Permissions](#user-roles--permissions)
8. [Troubleshooting](#troubleshooting)
9. [Technical Requirements](#technical-requirements)

---

## 🔍 System Overview

**MeatChain Pro** is a comprehensive meat production data management system designed for tracking, analyzing, and managing all aspects of meat supply chain operations. The system provides:

- **Real-time Analytics** with interactive charts
- **Production Tracking** with detailed records
- **Price Analysis** with trend visualization
- **Consumption Insights** with demographic data
- **Demand Elasticity** calculations
- **Supply & Demand Analysis** with forecasting

---

## 🚀 Getting Started

### Prerequisites
- XAMPP installed and running
- Web browser (Chrome, Firefox, Safari, or Edge)
- Internet connection (for Bootstrap & Chart.js CDN resources)

### System Requirements
- **Operating System**: Windows, macOS, or Linux
- **Web Server**: Apache (via XAMPP)
- **Database**: MySQL 5.7+ (via XAMPP)
- **PHP Version**: 8.0+ (via XAMPP)
- **Browser**: Modern web browser with JavaScript enabled

---

## 🌐 Access Instructions

### 1. Start XAMPP Services
```bash
# Linux/macOS
sudo /opt/lampp/lampp start

# Windows
Start XAMPP Control Panel and start Apache & MySQL
```

### 2. Access the Website
Open your web browser and navigate to:
```
http://localhost:3000/meat_production/
```

### 3. Alternative Access URLs
If port 3000 doesn't work, try:
```
http://localhost/meat_production/
http://127.0.0.1:3000/meat_production/
http://127.0.0.1/meat_production/
```

---

## 🔐 User Authentication

### Demo Accounts
The system comes with pre-configured demo accounts:

| **Role** | **User ID** | **Password** | **Access Level** |
|----------|-------------|--------------|------------------|
| **Admin** | `admin` | `password` | Full system access + Admin panel |
| **Customer** | `customer` | `password` | Standard user access |
| **Demo** | `demo` | `password` | Standard user access |

### Login Process
1. Navigate to: `http://localhost:3000/meat_production/login.php`
2. Enter your **User ID**, **Password**, and select **Role**
3. Click **"Sign In"**
4. You'll be redirected to the appropriate dashboard

### Creating New Accounts
1. Go to: `http://localhost:3000/meat_production/signup.php`
2. Fill in all required fields:
   - **Full Name**: Your complete name
   - **User ID**: Unique identifier (letters, numbers, underscore)
   - **Password**: Minimum 8 characters with 1 uppercase & 1 number
   - **Confirm Password**: Must match the password
   - **Account Type**: Choose Admin or Customer
3. Click **"Create Account"**
4. You'll be redirected to login page upon success

---

## 🧭 Features & Navigation

### Main Dashboard
**URL**: `http://localhost:3000/meat_production/index.php`

The dashboard provides:
- **Quick Statistics**: Live data counts from all modules
- **Feature Navigation**: Direct access to all 6 management modules
- **User Info**: Display current user and role in navbar
- **Responsive Design**: Works on desktop, tablet, and mobile

### Navigation Bar
- **Dashboard**: Return to main page
- **Admin Panel**: (Admin only) System administration
- **Reports**: Generate and export reports
- **User Menu**: Shows logged-in user with logout option

---

## 📊 Page-by-Page Guide

### 1. **Meat Products Management**
**URL**: `meat_products.php`

**Purpose**: Manage meat product inventory and details

**Features**:
- ✅ **View Products**: DataTable with search, sort, pagination
- ✅ **Add New Product**: Modal form with validation
- ✅ **Edit Product**: Click edit button, modify details
- ✅ **Delete Product**: Remove products with confirmation
- ✅ **Export Data**: Download product list as CSV

**How to Use**:
1. Click **"Meat Products"** from dashboard
2. View existing products in the table
3. Use **"Add New Product"** button for new entries
4. Click **"Edit"** or **"Delete"** buttons for existing products
5. Use search box to find specific products

---

### 2. **Production Records**
**URL**: `production_records.php`

**Purpose**: Track production data with visual analytics

**Features**:
- ✅ **Production Timeline**: Line chart showing production trends
- ✅ **Monthly Production**: Bar chart for monthly comparison
- ✅ **Product Distribution**: Pie chart showing product breakdown
- ✅ **Data Management**: Add, edit, delete production records
- ✅ **Export Options**: CSV download available

**How to Use**:
1. Navigate to **"Production Records"**
2. View charts for production insights
3. Scroll down to see detailed records table
4. Add new records using **"Add New Record"** button
5. Edit/delete existing records as needed

---

### 3. **Price Analysis**
**URL**: `price_analysis.php`

**Purpose**: Analyze pricing trends and patterns

**Features**:
- ✅ **Price Trends**: Line chart showing price changes over time
- ✅ **Seasonal Analysis**: Identify seasonal price patterns
- ✅ **Regional Comparison**: Compare prices across regions
- ✅ **Monthly Averages**: Bar chart of monthly price averages
- ✅ **Price Records**: Tabular view of all price data

**How to Use**:
1. Access **"Price Analysis"** from dashboard
2. Review multiple charts for different insights
3. Use the data table for detailed price records
4. Add new price entries using the form
5. Export price data for further analysis

---

### 4. **Consumption Insights**
**URL**: `consumption_insights.php`

**Purpose**: Analyze consumption patterns and demographics

**Features**:
- ✅ **Demographic Analysis**: Charts showing consumption by demographics
- ✅ **Nutritional Breakdown**: Pie chart of nutritional components
- ✅ **Consumption Trends**: Time-based consumption patterns
- ✅ **Regional Data**: Geographic consumption distribution
- ✅ **Data Management**: CRUD operations for consumption data

**How to Use**:
1. Go to **"Consumption Insights"**
2. Analyze charts for consumption patterns
3. Review demographic breakdowns
4. Manage consumption data in the table
5. Export insights for reporting

---

### 5. **Demand Elasticity**
**URL**: `demand_elasticity.php`

**Purpose**: Calculate and analyze demand elasticity

**Features**:
- ✅ **Elasticity Calculations**: Automatic price elasticity computation
- ✅ **Correlation Analysis**: Statistical relationships
- ✅ **Responsive Design**: Clean table layout
- ✅ **Data Entry**: Add new elasticity data points
- ✅ **Economic Insights**: Understanding price sensitivity

**How to Use**:
1. Navigate to **"Demand Elasticity"**
2. Review calculated elasticity values
3. Add new data points for analysis
4. Export elasticity data for economic modeling
5. Use insights for pricing strategies

---

### 6. **Supply & Demand Analysis**
**URL**: `supply_demand.php`

**Purpose**: Comprehensive supply and demand forecasting

**Features**:
- ✅ **Supply vs Demand**: Visual comparison charts
- ✅ **Surplus/Deficit Analysis**: Identify market imbalances
- ✅ **Trend Forecasting**: Predictive analytics
- ✅ **Policy Recommendations**: Data-driven insights
- ✅ **Market Balance**: Real-time supply-demand status

**How to Use**:
1. Access **"Supply & Demand"** from main menu
2. Review supply vs demand charts
3. Identify surplus or deficit periods
4. Use data for strategic planning
5. Export analysis for stakeholder reports

---

### 7. **Admin Panel** (Admin Only)
**URL**: `admin_panel.php`

**Purpose**: System administration and management

**Features**:
- ✅ **System Overview**: Key performance indicators
- ✅ **User Management**: View user accounts
- ✅ **Data Export**: Bulk export capabilities
- ✅ **Database Backup**: System backup functionality
- ✅ **System Statistics**: Comprehensive analytics

**How to Use** (Admin only):
1. Login with admin account
2. Click **"Admin Panel"** in navigation
3. Monitor system performance
4. Manage users and data
5. Perform maintenance tasks

---

## 👥 User Roles & Permissions

### 🔴 **Admin Role**
**Access Level**: Full System Access

**Permissions**:
- ✅ Access all 6 management modules
- ✅ Admin panel access
- ✅ User management capabilities
- ✅ System backup and export
- ✅ Database administration
- ✅ Create, read, update, delete all data

**Typical Users**: System administrators, managers, supervisors

---

### 🔵 **Customer Role**
**Access Level**: Standard User Access

**Permissions**:
- ✅ Access all 6 management modules
- ✅ View and analyze data
- ✅ Add, edit, delete own data entries
- ✅ Export data as CSV
- ❌ No admin panel access
- ❌ No user management capabilities

**Typical Users**: Staff members, data entry personnel, analysts

---

## 🔧 Troubleshooting

### Common Issues & Solutions

#### **Problem**: Cannot access website
**Solutions**:
1. Check if XAMPP services are running
2. Verify correct URL: `http://localhost:3000/meat_production/`
3. Try alternative ports: `http://localhost/meat_production/`
4. Clear browser cache and cookies
5. Disable browser extensions temporarily

#### **Problem**: Login fails with correct credentials
**Solutions**:
1. Ensure database is properly imported
2. Check users table exists in `meat_production_course` database
3. Verify PHP sessions are working
4. Clear browser cookies
5. Try different demo accounts

#### **Problem**: Charts not displaying
**Solutions**:
1. Check internet connection (Chart.js CDN required)
2. Enable JavaScript in browser
3. Clear browser cache
4. Check browser console for errors
5. Try different browser

#### **Problem**: Database connection errors
**Solutions**:
1. Verify MySQL is running in XAMPP
2. Check database name: `meat_production_course`
3. Ensure database is imported correctly
4. Verify MySQL user permissions
5. Check `config.php` database settings

#### **Problem**: Permission denied errors
**Solutions**:
1. Check file permissions in htdocs directory
2. Ensure XAMPP has proper permissions
3. Run XAMPP as administrator (Windows)
4. Check PHP error logs
5. Verify session directory permissions

---

## 🔧 Technical Requirements

### Server Requirements
- **Web Server**: Apache 2.4+
- **PHP**: Version 8.0 or higher
- **MySQL**: Version 5.7 or higher
- **Memory**: Minimum 512MB RAM
- **Storage**: At least 100MB free space

### Browser Compatibility
- **Chrome**: Version 90+
- **Firefox**: Version 88+
- **Safari**: Version 14+
- **Edge**: Version 90+
- **Mobile**: iOS Safari 14+, Chrome Mobile 90+

### Dependencies
- **Bootstrap 5.3.3**: UI framework (CDN)
- **Chart.js**: Data visualization (CDN)
- **jQuery 3.6.0**: JavaScript library (CDN)
- **Font Awesome 6.0**: Icons (CDN)
- **DataTables**: Table enhancements (CDN)

---

## 📞 Support & Contact

### Getting Help
1. **Check this guide** for common solutions
2. **Review error messages** in browser console
3. **Check XAMPP logs** for server issues
4. **Verify database structure** using phpMyAdmin
5. **Test with demo accounts** first

### System Information
- **Project**: MeatChain Pro
- **Version**: 1.0
- **Architecture**: PHP-MySQL (Direct Connection)
- **Framework**: Bootstrap 5
- **Database**: MySQL with 7 tables

---

## 🎯 Quick Start Checklist

- [ ] XAMPP installed and running
- [ ] Navigate to `http://localhost:3000/meat_production/`
- [ ] Test login with demo account: `admin` / `password`
- [ ] Explore all 6 management modules
- [ ] Try adding/editing data in each module
- [ ] Test chart interactions and data visualization
- [ ] Export data as CSV from any module
- [ ] Create new user account via signup
- [ ] Test logout and login again

**🎉 Congratulations! You're ready to use MeatChain Pro!**

---

*Last Updated: August 30, 2025*
*System Version: 1.0*
