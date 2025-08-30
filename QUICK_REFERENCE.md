# 🚀 MeatChain Pro - Quick Access Reference

## 🌐 Website Access
```
Main URL: http://localhost:3000/meat_production/
Login:    http://localhost:3000/meat_production/login.php
Signup:   http://localhost:3000/meat_production/signup.php
```

## 👤 Demo Accounts
| Role | User ID | Password |
|------|---------|----------|
| Admin | `admin` | `password` |
| Admin | `admin1` | `Password1` |
| Customer | `customer` | `password` |
| Demo | `demo` | `password` |

## 📊 Feature Pages
1. **Dashboard**: `index.php` - Main overview with statistics
2. **Meat Products**: `meat_products.php` - Product inventory management
3. **Production Records**: `production_records.php` - Production tracking with charts
4. **Price Analysis**: `price_analysis.php` - Price trends and analytics
5. **Consumption Insights**: `consumption_insights.php` - Consumption patterns
6. **Demand Elasticity**: `demand_elasticity.php` - Economic elasticity analysis
7. **Supply & Demand**: `supply_demand.php` - Market balance analysis
8. **Admin Panel**: `admin_panel.php` - System administration (Admin only)

## 🔧 XAMPP Commands
```bash
# Start XAMPP
sudo /opt/lampp/lampp start

# Stop XAMPP
sudo /opt/lampp/lampp stop

# Restart XAMPP
sudo /opt/lampp/lampp restart

# Check Status
sudo /opt/lampp/lampp status
```

## 🎯 Key Features Per Page

### Meat Products
- DataTable with search/sort
- Add/Edit/Delete products
- CSV export

### Production Records
- 3 interactive charts (Line, Bar, Pie)
- Production timeline analysis
- Monthly comparisons

### Price Analysis
- 4 price charts (Trends, Seasonal, Regional, Monthly)
- Historical price tracking
- Market analysis

### Consumption Insights
- Demographic analysis charts
- Nutritional breakdown
- Regional consumption patterns

### Demand Elasticity
- Automatic elasticity calculations
- Economic correlation analysis
- Price sensitivity insights

### Supply & Demand
- Supply vs demand visualization
- Surplus/deficit identification
- Market forecasting

### Admin Panel (Admin Only)
- System overview
- User management
- Database backup
- Export functionality

## 🚨 Troubleshooting
- **Can't access?** → Check XAMPP is running
- **Login fails?** → Try demo accounts above
- **Charts missing?** → Check internet connection
- **Database errors?** → Verify MySQL is running

## 📁 File Structure
```
meat_production/
├── login.php              # Login page
├── signup.php             # User registration
├── index.php              # Main dashboard
├── meat_products.php      # Product management
├── production_records.php # Production tracking
├── price_analysis.php     # Price analytics
├── consumption_insights.php # Consumption data
├── demand_elasticity.php  # Elasticity analysis
├── supply_demand.php      # Market analysis
├── admin_panel.php        # Admin dashboard
├── config.php             # Database config
├── logout.php             # Logout handler
└── USER_GUIDE.md          # Full documentation
```

## 🎉 Getting Started
1. Start XAMPP services
2. Go to `http://localhost:3000/meat_production/`
3. Login with `admin` / `password`
4. Explore all 6 feature modules
5. Test CRUD operations
6. Export data as needed

**For detailed instructions, see USER_GUIDE.md**
