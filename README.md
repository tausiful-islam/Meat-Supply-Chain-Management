# 🥩 Meat Supply Chain Management System - Database Edition

## 🎯 Complete XAMPP Database Integration with Responsive Charts

### 📊 System Overview
A comprehensive meat supply chain analytics platform now featuring **real-time database integration** with responsive charts that automatically update from MySQL data via XAMPP and phpMyAdmin.

---

## 🚀 Key Features Complete

### ✅ 6 Core Analytics Features (All Numbered & Database-Driven)

#### 📈 **Feature #1: Detailed Meat Product Data**
- **Database Table**: `meat_products`
- **Charts**: Production Volume Chart, Price Comparison Chart
- **Responsive**: Auto-updates every 30 seconds from database
- **CRUD**: Add/Edit/Delete products with immediate chart refresh
- **API Endpoint**: `api/analytics.php?endpoint=meat-products`

#### 🏭 **Feature #2: Production Records by District/Division** 
- **Database Table**: `production_records`
- **Charts**: District Production Chart, Slaughter Rate Chart
- **Responsive**: Real-time updates with district filtering
- **CRUD**: Full production record management
- **API Endpoint**: `api/analytics.php?endpoint=production-records`

#### 💰 **Feature #3: Historical Price Data & Trend Analysis**
- **Database Table**: `price_history` 
- **Charts**: Price History Trend Chart
- **Responsive**: Live price trend updates
- **CRUD**: Historical data management
- **API Endpoint**: `api/analytics.php?endpoint=price-history`

#### 🍽️ **Feature #4: Regional Consumption Patterns**
- **Database Table**: `consumption_patterns`
- **Charts**: Regional Consumption Chart
- **Responsive**: Real-time consumption analytics
- **CRUD**: Consumption data management
- **API Endpoint**: `api/analytics.php?endpoint=consumption-patterns`

#### � **Feature #5: Price Elasticity Analysis**
- **Database Table**: `price_elasticity`
- **Charts**: Elasticity Coefficient Chart
- **Responsive**: Dynamic elasticity calculations
- **CRUD**: Elasticity data management
- **API Endpoint**: `api/analytics.php?endpoint=price-elasticity`

#### ⚖️ **Feature #6: Supply vs Demand Analysis**
- **Database Table**: `supply_demand_analysis`
- **Charts**: Supply vs Demand Chart
- **Responsive**: Live market balance updates
- **CRUD**: Supply-demand record management
- **API Endpoint**: `api/analytics.php?endpoint=supply-demand`

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Bootstrap 5.3.3
- **Icons**: Bootstrap Icons
- **Charts**: Chart.js
- **Storage**: LocalStorage (for demo purposes)

## 📱 Responsive Design

The application is fully responsive and works seamlessly on:
- Desktop computers
- Tablets
- Mobile phones

## 🎨 Design Features

- Modern gradient backgrounds
- Interactive hover effects
- Smooth animations
- Professional color scheme
- Intuitive user interface
- Clean, minimalist design

## 📦 Installation

1. Clone the repository:
   \`\`\`bash
   git clone https://github.com/yourusername/meatchain-pro.git
   \`\`\`

2. Navigate to the project directory:
   \`\`\`bash
   cd meatchain-pro
   \`\`\`

3. Open `index.html` in your web browser or serve it using a local server:
   \`\`\`bash
   # Using Python 3
   python -m http.server 8000
   
   # Using Node.js
   npx serve .
   \`\`\`

4. Access the application at `http://localhost:8000`

## 📁 Project Structure

\`\`\`
MeatChain-Pro/
├── index.html                 # Homepage
├── assests/
│   ├── Style/
│   │   └── style.css         # Custom styles
│   └── image/
├── pages/
│   ├── login.html            # Authentication page
│   ├── admin_dashboard.html  # Admin dashboard
│   ├── inventory.html        # Inventory management
│   ├── suppliers.html        # Supplier management
│   └── ...
├── Js/
│   └── ...
└── README.md
\`\`\`

## 🔧 Customization

### Adding New Products
1. Navigate to the Inventory page
2. Click "Add New Product"
3. Fill in the product details
4. Click "Add Product"

### Managing Suppliers
1. Go to the Suppliers page
2. Use "Add New Supplier" to add suppliers
3. Edit or delete existing suppliers using action buttons

### Modifying Styles
Edit `assests/Style/style.css` to customize:
- Color schemes
- Layout designs
- Animation effects
- Responsive breakpoints

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

## 🙏 Acknowledgments

- Bootstrap team for the excellent framework
- Bootstrap Icons for the comprehensive icon set
- Chart.js for beautiful charts and graphs

---

⭐ Star this repository if you find it helpful!
