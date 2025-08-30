/**
 * Database Integration for Analytics Features
 * Replaces localStorage with real-time database API calls
 */

class AnalyticsDatabase {
    constructor() {
        this.baseURL = 'api/analytics.php';
    }

    async request(endpoint, options = {}) {
        try {
            const url = `${this.baseURL}?endpoint=${endpoint}`;
            const response = await fetch(url, {
                method: options.method || 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers
                },
                body: options.body ? JSON.stringify(options.body) : null
            });

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'API request failed');
            }
            
            return data.data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // FEATURE #1: Meat Products
    async getMeatProducts() {
        return await this.request('meat-products');
    }

    async addMeatProduct(product) {
        return await this.request('meat-products', {
            method: 'POST',
            body: product
        });
    }

    // FEATURE #2: Production Records
    async getProductionRecords(filters = {}) {
        let endpoint = 'production-records';
        const params = new URLSearchParams();
        
        if (filters.district) params.append('district', filters.district);
        if (filters.year) params.append('year', filters.year);
        
        if (params.toString()) {
            endpoint += '&' + params.toString();
        }
        
        return await this.request(endpoint);
    }

    async addProductionRecord(record) {
        return await this.request('production-records', {
            method: 'POST',
            body: record
        });
    }

    // FEATURE #3: Price History
    async getPriceHistory(filters = {}) {
        let endpoint = 'price-history';
        const params = new URLSearchParams();
        
        if (filters.product) params.append('product', filters.product);
        if (filters.region) params.append('region', filters.region);
        
        if (params.toString()) {
            endpoint += '&' + params.toString();
        }
        
        return await this.request(endpoint);
    }

    // FEATURE #4: Consumption Patterns
    async getConsumptionPatterns() {
        return await this.request('consumption-patterns');
    }

    // FEATURE #5: Price Elasticity
    async getPriceElasticity() {
        return await this.request('price-elasticity');
    }

    // FEATURE #6: Supply-Demand Analysis
    async getSupplyDemandAnalysis() {
        return await this.request('supply-demand');
    }

    async addSupplyDemandRecord(record) {
        return await this.request('supply-demand', {
            method: 'POST',
            body: record
        });
    }

    // Dashboard Summary
    async getDashboardSummary() {
        return await this.request('dashboard-summary');
    }
}

// Global database instance
const analyticsDB = new AnalyticsDatabase();

// =====================================================================
// DATABASE-DRIVEN CHART UPDATES
// =====================================================================

class ResponsiveChartManager {
    constructor() {
        this.charts = {};
        this.refreshInterval = 30000; // 30 seconds
    }

    async initializeAllCharts() {
        try {
            await this.updateMeatProductsChart();
            await this.updateProductionChart();
            await this.updatePriceHistoryChart();
            await this.updateConsumptionChart();
            await this.updateElasticityChart();
            await this.updateSupplyDemandChart();
            
            // Start auto-refresh
            this.startAutoRefresh();
        } catch (error) {
            console.error('Failed to initialize charts:', error);
            this.showNotification('Failed to load analytics data', 'error');
        }
    }

    async updateMeatProductsChart() {
        const data = await analyticsDB.getMeatProducts();
        
        // Update production volume chart
        const prodChart = Chart.getChart('meatProductionChart');
        if (prodChart) {
            prodChart.data.labels = data.map(item => item.meat_type);
            prodChart.data.datasets[0].data = data.map(item => parseFloat(item.production_volume_tons));
            prodChart.update();
        }

        // Update price comparison chart
        const priceChart = Chart.getChart('priceComparisonChart');
        if (priceChart) {
            priceChart.data.labels = data.map(item => item.meat_type);
            priceChart.data.datasets[0].data = data.map(item => parseFloat(item.current_price_usd));
            priceChart.data.datasets[1].data = data.map(item => parseFloat(item.previous_year_price_usd || 0));
            priceChart.update();
        }

        // Update table
        this.updateMeatProductsTable(data);
    }

    async updateProductionChart() {
        const data = await analyticsDB.getProductionRecords({ year: 2024 });
        
        // Update district production chart
        const districtChart = Chart.getChart('districtProductionChart');
        if (districtChart) {
            districtChart.data.labels = data.summary.map(item => item.district_division);
            districtChart.data.datasets[0].data = data.summary.map(item => parseFloat(item.total_production));
            districtChart.update();
        }

        // Update slaughter rate chart
        const slaughterChart = Chart.getChart('slaughterRateChart');
        if (slaughterChart) {
            slaughterChart.data.labels = data.summary.map(item => item.district_division);
            slaughterChart.data.datasets[0].data = data.summary.map(item => parseFloat(item.avg_slaughter_rate));
            slaughterChart.update();
        }

        // Update table
        this.updateProductionTable(data.records);
    }

    async updatePriceHistoryChart() {
        const data = await analyticsDB.getPriceHistory();
        
        const priceChart = Chart.getChart('priceHistoryChart');
        if (priceChart) {
            const products = [...new Set(data.prices.map(item => item.product_type))];
            priceChart.data.labels = data.trends.map(item => item.product_type);
            priceChart.data.datasets[0].data = data.trends.map(item => parseFloat(item.avg_wholesale));
            priceChart.data.datasets[1].data = data.trends.map(item => parseFloat(item.avg_retail));
            priceChart.update();
        }

        this.updatePriceHistoryTable(data.prices);
    }

    async updateConsumptionChart() {
        const data = await analyticsDB.getConsumptionPatterns();
        
        const consumptionChart = Chart.getChart('consumptionChart');
        if (consumptionChart) {
            consumptionChart.data.labels = data.regional.map(item => item.region);
            consumptionChart.data.datasets[0].data = data.regional.map(item => parseFloat(item.avg_per_capita));
            consumptionChart.update();
        }

        this.updateConsumptionTable(data.patterns);
    }

    async updateElasticityChart() {
        const data = await analyticsDB.getPriceElasticity();
        
        const elasticityChart = Chart.getChart('elasticityChart');
        if (elasticityChart) {
            elasticityChart.data.labels = data.map(item => item.product);
            elasticityChart.data.datasets[0].data = data.map(item => Math.abs(parseFloat(item.elasticity_coefficient)));
            elasticityChart.update();
        }

        this.updateElasticityTable(data);
    }

    async updateSupplyDemandChart() {
        const data = await analyticsDB.getSupplyDemandAnalysis();
        
        const supplyChart = Chart.getChart('supplyDemandChart');
        if (supplyChart) {
            supplyChart.data.labels = data.map(item => item.product);
            supplyChart.data.datasets[0].data = data.map(item => parseFloat(item.supply_tons));
            supplyChart.data.datasets[1].data = data.map(item => parseFloat(item.demand_tons));
            supplyChart.update();
        }

        this.updateSupplyDemandTable(data);
    }

    updateMeatProductsTable(data) {
        const tbody = document.querySelector('#meatProductsTable tbody');
        if (!tbody) return;

        tbody.innerHTML = data.map(item => `
            <tr>
                <td>${item.meat_type}</td>
                <td>${item.breed_source}</td>
                <td>${parseFloat(item.avg_weight_kg).toFixed(1)} kg</td>
                <td>${parseFloat(item.feed_conversion_ratio).toFixed(2)}</td>
                <td>${item.rearing_period_months} months</td>
                <td>${parseFloat(item.production_volume_tons).toFixed(1)} tons</td>
                <td>$${parseFloat(item.current_price_usd).toFixed(2)}</td>
                <td>
                    <span class="badge bg-${item.price_trend === 'Rising' ? 'success' : 
                                            item.price_trend === 'Falling' ? 'danger' : 'secondary'}">
                        ${item.price_trend} ${item.price_change_percent}%
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="editMeatProduct(${item.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    updateProductionTable(data) {
        const tbody = document.querySelector('#productionTable tbody');
        if (!tbody) return;

        tbody.innerHTML = data.map(item => `
            <tr>
                <td>${item.district_division}</td>
                <td>${parseInt(item.livestock_count).toLocaleString()}</td>
                <td>${parseFloat(item.slaughter_rate_percent).toFixed(1)}%</td>
                <td>${parseFloat(item.meat_yield_tons).toFixed(1)} tons</td>
                <td>${parseFloat(item.production_volume_tons).toFixed(1)} tons</td>
                <td>
                    <span class="badge bg-info">${item.period} ${item.year}</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="editProductionRecord(${item.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    updatePriceHistoryTable(data) {
        const tbody = document.querySelector('#priceHistoryTable tbody');
        if (!tbody) return;

        tbody.innerHTML = data.map(item => `
            <tr>
                <td>${item.product_type}</td>
                <td>${item.region}</td>
                <td>$${parseFloat(item.wholesale_price_usd).toFixed(2)}</td>
                <td>$${parseFloat(item.retail_price_usd).toFixed(2)}</td>
                <td>
                    <span class="badge bg-${parseFloat(item.yoy_change_percent) >= 0 ? 'success' : 'danger'}">
                        ${parseFloat(item.yoy_change_percent).toFixed(1)}%
                    </span>
                </td>
                <td>
                    <span class="badge bg-info">${item.seasonal_trend}</span>
                </td>
                <td>Q${item.quarter} ${item.year}</td>
            </tr>
        `).join('');
    }

    updateConsumptionTable(data) {
        const tbody = document.querySelector('#consumptionTable tbody');
        if (!tbody) return;

        tbody.innerHTML = data.map(item => `
            <tr>
                <td>${item.region}</td>
                <td>${item.meat_type}</td>
                <td>${parseFloat(item.per_capita_consumption_kg).toFixed(1)} kg</td>
                <td>${parseInt(item.population).toLocaleString()}</td>
                <td>${item.demographic_group}</td>
                <td>${parseInt(item.nutritional_intake_calories).toLocaleString()}</td>
                <td>
                    <span class="badge bg-${parseFloat(item.dietary_impact_score) >= 7 ? 'success' : 
                                            parseFloat(item.dietary_impact_score) >= 4 ? 'warning' : 'danger'}">
                        ${parseFloat(item.dietary_impact_score).toFixed(1)}
                    </span>
                </td>
            </tr>
        `).join('');
    }

    updateElasticityTable(data) {
        const tbody = document.querySelector('#elasticityTable tbody');
        if (!tbody) return;

        tbody.innerHTML = data.map(item => `
            <tr>
                <td>${item.product}</td>
                <td>${parseFloat(item.elasticity_coefficient).toFixed(3)}</td>
                <td>
                    <span class="badge bg-${item.classification === 'Elastic' ? 'warning' : 
                                            item.classification === 'Inelastic' ? 'success' : 'info'}">
                        ${item.classification}
                    </span>
                </td>
                <td>${parseFloat(item.price_impact_percent).toFixed(1)}%</td>
                <td>$${parseFloat(item.optimal_price_usd).toFixed(2)}</td>
                <td>${parseFloat(item.revenue_potential_percent).toFixed(1)}%</td>
                <td>
                    <span class="badge bg-${item.sensitivity_level === 'High' ? 'danger' : 
                                            item.sensitivity_level === 'Medium' ? 'warning' : 'success'}">
                        ${item.sensitivity_level}
                    </span>
                </td>
            </tr>
        `).join('');
    }

    updateSupplyDemandTable(data) {
        const tbody = document.querySelector('#supplyDemandTable tbody');
        if (!tbody) return;

        tbody.innerHTML = data.map(item => `
            <tr>
                <td>${item.product}</td>
                <td>${parseFloat(item.supply_tons).toFixed(1)} tons</td>
                <td>${parseFloat(item.demand_tons).toFixed(1)} tons</td>
                <td>
                    <span class="badge bg-${parseFloat(item.balance_tons) > 0 ? 'success' : 
                                            parseFloat(item.balance_tons) < 0 ? 'danger' : 'warning'}">
                        ${parseFloat(item.balance_tons) > 0 ? '+' : ''}${parseFloat(item.balance_tons).toFixed(1)} tons
                    </span>
                </td>
                <td>
                    <span class="badge bg-${item.market_status === 'Surplus' ? 'success' : 
                                            item.market_status === 'Shortage' ? 'danger' : 'warning'}">
                        ${item.market_status}
                    </span>
                </td>
                <td>${parseFloat(item.price_impact_percent).toFixed(1)}%</td>
                <td>
                    <span class="badge bg-primary">${item.business_action}</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="editSupplyDemandRecord(${item.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    startAutoRefresh() {
        setInterval(async () => {
            try {
                await this.initializeAllCharts();
                this.showNotification('Analytics data refreshed', 'success', 2000);
            } catch (error) {
                console.error('Auto-refresh failed:', error);
            }
        }, this.refreshInterval);
    }

    showNotification(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <strong>${message}</strong>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, duration);
    }
}

// Global chart manager
const chartManager = new ResponsiveChartManager();

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    chartManager.initializeAllCharts();
});

// =====================================================================
// ENHANCED CRUD OPERATIONS WITH DATABASE
// =====================================================================

async function addMeatProduct() {
    const form = document.getElementById('meatProductForm');
    const formData = new FormData(form);
    
    const product = {
        meat_type: formData.get('meat_type'),
        breed_source: formData.get('breed_source'),
        avg_weight_kg: parseFloat(formData.get('avg_weight_kg')),
        feed_conversion_ratio: parseFloat(formData.get('feed_conversion_ratio')),
        rearing_period_months: parseInt(formData.get('rearing_period_months')),
        production_volume_tons: parseFloat(formData.get('production_volume_tons')),
        current_price_usd: parseFloat(formData.get('current_price_usd')),
        previous_year_price_usd: parseFloat(formData.get('previous_year_price_usd') || 0),
        badge_color: formData.get('badge_color') || 'primary'
    };

    try {
        await analyticsDB.addMeatProduct(product);
        await chartManager.updateMeatProductsChart();
        form.reset();
        bootstrap.Modal.getInstance(document.getElementById('meatProductModal')).hide();
        chartManager.showNotification('Meat product added successfully', 'success');
    } catch (error) {
        chartManager.showNotification('Failed to add meat product: ' + error.message, 'error');
    }
}

async function addProductionRecord() {
    const form = document.getElementById('productionRecordForm');
    const formData = new FormData(form);
    
    const record = {
        district_division: formData.get('district_division'),
        livestock_count: parseInt(formData.get('livestock_count')),
        slaughter_rate_percent: parseFloat(formData.get('slaughter_rate_percent')),
        meat_yield_tons: parseFloat(formData.get('meat_yield_tons')),
        production_volume_tons: parseFloat(formData.get('production_volume_tons')),
        period: formData.get('period'),
        year: parseInt(formData.get('year')),
        quarter: parseInt(formData.get('quarter')),
        meat_type: formData.get('meat_type') || 'Mixed'
    };

    try {
        await analyticsDB.addProductionRecord(record);
        await chartManager.updateProductionChart();
        form.reset();
        bootstrap.Modal.getInstance(document.getElementById('productionRecordModal')).hide();
        chartManager.showNotification('Production record added successfully', 'success');
    } catch (error) {
        chartManager.showNotification('Failed to add production record: ' + error.message, 'error');
    }
}

async function addSupplyDemandRecord() {
    const form = document.getElementById('supplyDemandForm');
    const formData = new FormData(form);
    
    const record = {
        product: formData.get('product'),
        supply_tons: parseFloat(formData.get('supply_tons')),
        demand_tons: parseFloat(formData.get('demand_tons')),
        market_status: formData.get('market_status'),
        price_impact_percent: parseFloat(formData.get('price_impact_percent')),
        business_action: formData.get('business_action'),
        region: formData.get('region') || 'National',
        period: formData.get('period'),
        year: parseInt(formData.get('year')),
        quarter: parseInt(formData.get('quarter'))
    };

    try {
        await analyticsDB.addSupplyDemandRecord(record);
        await chartManager.updateSupplyDemandChart();
        form.reset();
        bootstrap.Modal.getInstance(document.getElementById('supplyDemandModal')).hide();
        chartManager.showNotification('Supply-demand record added successfully', 'success');
    } catch (error) {
        chartManager.showNotification('Failed to add supply-demand record: ' + error.message, 'error');
    }
}

// Filter functions with database integration
async function filterProductionData() {
    const district = document.getElementById('districtFilter').value;
    const year = document.getElementById('yearFilter').value;
    
    await chartManager.updateProductionChart();
}

async function filterPriceData() {
    const product = document.getElementById('productFilter').value;
    const region = document.getElementById('regionFilter').value;
    
    await chartManager.updatePriceHistoryChart();
}

// Manual refresh function
async function refreshAnalytics() {
    const button = document.querySelector('.btn-refresh');
    if (button) {
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
        button.disabled = true;
    }
    
    try {
        await chartManager.initializeAllCharts();
        chartManager.showNotification('All analytics data refreshed successfully', 'success');
    } finally {
        if (button) {
            button.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh Data';
            button.disabled = false;
        }
    }
}
