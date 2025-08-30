/**
 * Admin Management JavaScript
 * Handles CRUD operations for all data categories
 */

// API Configuration
const API_BASE_URL = 'http://localhost:3000/DataBase_Final_Project/api';

// Current editing state
let currentCategory = 'meat-products';
let currentEditId = null;
let isEditMode = false;

// Form field configurations for each category
const formConfigs = {
    'meat-products': [
        { name: 'meat_type', label: 'Meat Type', type: 'select', options: ['Beef', 'Chicken', 'Pork', 'Lamb', 'Turkey', 'Duck', 'Goat', 'Fish'], required: true },
        { name: 'breed_source', label: 'Breed/Source', type: 'text', required: true },
        { name: 'avg_weight_kg', label: 'Average Weight (kg)', type: 'number', step: '0.01', required: true },
        { name: 'feed_conversion_ratio', label: 'Feed Conversion Ratio', type: 'number', step: '0.01', required: true },
        { name: 'rearing_period_months', label: 'Rearing Period (months)', type: 'number', required: true },
        { name: 'production_volume_tons', label: 'Production Volume (tons)', type: 'number', step: '0.01', required: true },
        { name: 'current_price_usd', label: 'Current Price (USD)', type: 'number', step: '0.01', required: true },
        { name: 'previous_year_price_usd', label: 'Previous Year Price (USD)', type: 'number', step: '0.01', required: true },
        { name: 'badge_color', label: 'Badge Color', type: 'select', options: ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'] }
    ],
    'production': [
        { name: 'district_division', label: 'District/Division', type: 'text', required: true },
        { name: 'livestock_count', label: 'Livestock Count', type: 'number', required: true },
        { name: 'slaughter_rate_percent', label: 'Slaughter Rate (%)', type: 'number', step: '0.01', required: true },
        { name: 'meat_yield_tons', label: 'Meat Yield (tons)', type: 'number', step: '0.01', required: true },
        { name: 'production_volume_tons', label: 'Production Volume (tons)', type: 'number', step: '0.01', required: true },
        { name: 'period', label: 'Period', type: 'text', required: true },
        { name: 'year', label: 'Year', type: 'number', required: true },
        { name: 'quarter', label: 'Quarter', type: 'select', options: ['Q1', 'Q2', 'Q3', 'Q4'], required: true },
        { name: 'meat_type', label: 'Meat Type', type: 'text' }
    ],
    'market': [
        { name: 'price_per_kg_taka', label: 'Price per kg (৳)', type: 'number', step: '0.01', required: true },
        { name: 'demand_tons', label: 'Demand (tons)', type: 'number', step: '0.01', required: true },
        { name: 'supply_tons', label: 'Supply (tons)', type: 'number', step: '0.01', required: true },
        { name: 'market_share_percent', label: 'Market Share (%)', type: 'number', step: '0.01', required: true },
        { name: 'quarter', label: 'Quarter', type: 'select', options: ['Q1', 'Q2', 'Q3', 'Q4'], required: true },
        { name: 'year', label: 'Year', type: 'number', required: true },
        { name: 'period', label: 'Period', type: 'text', required: true },
        { name: 'region', label: 'Region', type: 'text' }
    ],
    'consumer': [
        { name: 'city_town', label: 'City/Town', type: 'text', required: true },
        { name: 'population', label: 'Population', type: 'number', required: true },
        { name: 'consumption_per_capita_kg_year', label: 'Consumption per capita (kg/year)', type: 'number', step: '0.01', required: true },
        { name: 'total_consumption_tons_year', label: 'Total Consumption (tons/year)', type: 'number', step: '0.01', required: true },
        { name: 'income_level', label: 'Income Level', type: 'select', options: ['Low', 'Medium', 'High'], required: true },
        { name: 'quarter', label: 'Quarter', type: 'select', options: ['Q1', 'Q2', 'Q3', 'Q4'], required: true },
        { name: 'year', label: 'Year', type: 'number', required: true },
        { name: 'region', label: 'Region', type: 'text' },
        { name: 'preferences', label: 'Preferences', type: 'text' }
    ],
    'quality': [
        { name: 'facility_location', label: 'Facility Location', type: 'text', required: true },
        { name: 'inspection_score', label: 'Inspection Score', type: 'number', min: '0', max: '100', required: true },
        { name: 'hygiene_rating', label: 'Hygiene Rating', type: 'select', options: ['A', 'B', 'C', 'D', 'F'], required: true },
        { name: 'safety_violations', label: 'Safety Violations', type: 'number', required: true },
        { name: 'certification_status', label: 'Certification Status', type: 'select', options: ['Certified', 'Pending', 'Expired', 'Revoked'], required: true },
        { name: 'quarter', label: 'Quarter', type: 'select', options: ['Q1', 'Q2', 'Q3', 'Q4'], required: true },
        { name: 'year', label: 'Year', type: 'number', required: true },
        { name: 'facility_type', label: 'Facility Type', type: 'text' },
        { name: 'notes', label: 'Notes', type: 'textarea' }
    ],
    'regulatory': [
        { name: 'facility_name', label: 'Facility Name', type: 'text', required: true },
        { name: 'compliance_percentage', label: 'Compliance (%)', type: 'number', min: '0', max: '100', step: '0.01', required: true },
        { name: 'violations_count', label: 'Violations Count', type: 'number', required: true },
        { name: 'penalty_amount_taka', label: 'Penalty Amount (৳)', type: 'number', step: '0.01', required: true },
        { name: 'license_status', label: 'License Status', type: 'select', options: ['Active', 'Suspended', 'Revoked', 'Pending'], required: true },
        { name: 'quarter', label: 'Quarter', type: 'select', options: ['Q1', 'Q2', 'Q3', 'Q4'], required: true },
        { name: 'year', label: 'Year', type: 'number', required: true },
        { name: 'facility_type', label: 'Facility Type', type: 'text' },
        { name: 'location', label: 'Location', type: 'text' }
    ]
};

// API endpoint mapping
const apiEndpoints = {
    'meat-products': 'meat_products.php',
    'production': 'production_records.php',
    'market': 'market_analytics.php',
    'consumer': 'consumer_demographics.php',
    'quality': 'quality_assessments.php',
    'regulatory': 'regulatory_compliance.php'
};

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    loadData('meat-products');
    
    // Setup tab event listeners
    const tabButtons = document.querySelectorAll('#dataCategories button[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(event) {
            const targetTab = event.target.getAttribute('data-bs-target').substring(1);
            currentCategory = targetTab === 'meat-products' ? 'meat-products' : 
                           targetTab === 'production' ? 'production' :
                           targetTab === 'market' ? 'market' :
                           targetTab === 'consumer' ? 'consumer' :
                           targetTab === 'quality' ? 'quality' : 'regulatory';
            loadData(currentCategory);
        });
    });
});

// Load data for specific category
async function loadData(category) {
    const tableBody = document.getElementById(getTableBodyId(category));
    
    try {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';
        
        const response = await fetch(`${API_BASE_URL}/${apiEndpoints[category]}`);
        const result = await response.json();
        
        if (result.success) {
            displayData(category, result.data);
        } else {
            throw new Error(result.message || 'Failed to load data');
        }
    } catch (error) {
        console.error('Error loading data:', error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error loading data: ${error.message}</td></tr>`;
        showToast('Error', 'Failed to load data', 'error');
    }
}

// Display data in table
function displayData(category, data) {
    const tableBody = document.getElementById(getTableBodyId(category));
    
    if (!data || data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No data available</td></tr>';
        return;
    }
    
    tableBody.innerHTML = data.map(item => {
        switch (category) {
            case 'meat-products':
                return `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.meat_type}</td>
                        <td>${item.breed_source}</td>
                        <td>$${parseFloat(item.current_price_usd).toFixed(2)}</td>
                        <td>${item.production_volume_tons} tons</td>
                        <td class="table-actions">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editRecord('${category}', ${item.id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteRecord('${category}', ${item.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
            case 'production':
                return `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.district_division}</td>
                        <td>${item.livestock_count.toLocaleString()}</td>
                        <td>${item.slaughter_rate_percent}%</td>
                        <td>${item.meat_yield_tons}</td>
                        <td>${item.year}</td>
                        <td class="table-actions">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editRecord('${category}', ${item.id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteRecord('${category}', ${item.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
            case 'market':
                return `
                    <tr>
                        <td>${item.id}</td>
                        <td>৳${parseFloat(item.price_per_kg_taka).toFixed(2)}</td>
                        <td>${item.demand_tons}</td>
                        <td>${item.supply_tons}</td>
                        <td>${item.market_share_percent}%</td>
                        <td>${item.year}</td>
                        <td class="table-actions">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editRecord('${category}', ${item.id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteRecord('${category}', ${item.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
            case 'consumer':
                return `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.city_town}</td>
                        <td>${item.population.toLocaleString()}</td>
                        <td>${item.consumption_per_capita_kg_year}</td>
                        <td><span class="badge bg-secondary">${item.income_level}</span></td>
                        <td>${item.year}</td>
                        <td class="table-actions">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editRecord('${category}', ${item.id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteRecord('${category}', ${item.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
            case 'quality':
                return `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.facility_location}</td>
                        <td>${item.inspection_score}</td>
                        <td><span class="badge bg-${getGradeBadgeColor(item.hygiene_rating)}">${item.hygiene_rating}</span></td>
                        <td>${item.safety_violations}</td>
                        <td><span class="badge bg-${getStatusBadgeColor(item.certification_status)}">${item.certification_status}</span></td>
                        <td class="table-actions">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editRecord('${category}', ${item.id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteRecord('${category}', ${item.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
            case 'regulatory':
                return `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.facility_name}</td>
                        <td>${item.compliance_percentage}%</td>
                        <td>${item.violations_count}</td>
                        <td>৳${parseFloat(item.penalty_amount_taka).toFixed(2)}</td>
                        <td><span class="badge bg-${getStatusBadgeColor(item.license_status)}">${item.license_status}</span></td>
                        <td class="table-actions">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editRecord('${category}', ${item.id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteRecord('${category}', ${item.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
            default:
                return '';
        }
    }).join('');
}

// Helper functions for UI
function getTableBodyId(category) {
    const mapping = {
        'meat-products': 'meatProductsBody',
        'production': 'productionBody',
        'market': 'marketBody',
        'consumer': 'consumerBody',
        'quality': 'qualityBody',
        'regulatory': 'regulatoryBody'
    };
    return mapping[category];
}

function getGradeBadgeColor(grade) {
    const colors = { 'A': 'success', 'B': 'primary', 'C': 'warning', 'D': 'danger', 'F': 'dark' };
    return colors[grade] || 'secondary';
}

function getStatusBadgeColor(status) {
    const colors = {
        'Active': 'success', 'Certified': 'success',
        'Pending': 'warning',
        'Suspended': 'danger', 'Revoked': 'danger', 'Expired': 'danger'
    };
    return colors[status] || 'secondary';
}

// Show add/edit modal
function showAddModal(category) {
    currentCategory = category;
    isEditMode = false;
    currentEditId = null;
    
    const modal = new bootstrap.Modal(document.getElementById('dynamicModal'));
    document.getElementById('modalTitle').textContent = `Add ${getCategoryDisplayName(category)}`;
    
    generateForm(category);
    modal.show();
}

// Edit record
async function editRecord(category, id) {
    try {
        const response = await fetch(`${API_BASE_URL}/${apiEndpoints[category]}`);
        const result = await response.json();
        
        if (result.success) {
            const record = result.data.find(item => item.id == id);
            if (record) {
                currentCategory = category;
                isEditMode = true;
                currentEditId = id;
                
                const modal = new bootstrap.Modal(document.getElementById('dynamicModal'));
                document.getElementById('modalTitle').textContent = `Edit ${getCategoryDisplayName(category)}`;
                
                generateForm(category, record);
                modal.show();
            }
        }
    } catch (error) {
        console.error('Error loading record for edit:', error);
        showToast('Error', 'Failed to load record for editing', 'error');
    }
}

// Delete record
async function deleteRecord(category, id) {
    if (!confirm('Are you sure you want to delete this record?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}/${apiEndpoints[category]}?id=${id}`, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Success', 'Record deleted successfully', 'success');
            loadData(category);
        } else {
            throw new Error(result.message || 'Failed to delete record');
        }
    } catch (error) {
        console.error('Error deleting record:', error);
        showToast('Error', 'Failed to delete record', 'error');
    }
}

// Generate dynamic form
function generateForm(category, data = null) {
    const formFields = document.getElementById('formFields');
    const config = formConfigs[category];
    
    formFields.innerHTML = config.map(field => {
        const value = data ? (data[field.name] || '') : '';
        
        if (field.type === 'select') {
            return `
                <div class="mb-3">
                    <label for="${field.name}" class="form-label">${field.label} ${field.required ? '*' : ''}</label>
                    <select class="form-select" id="${field.name}" name="${field.name}" ${field.required ? 'required' : ''}>
                        <option value="">Select ${field.label}</option>
                        ${field.options.map(option => 
                            `<option value="${option}" ${value === option ? 'selected' : ''}>${option}</option>`
                        ).join('')}
                    </select>
                </div>`;
        } else if (field.type === 'textarea') {
            return `
                <div class="mb-3">
                    <label for="${field.name}" class="form-label">${field.label} ${field.required ? '*' : ''}</label>
                    <textarea class="form-control" id="${field.name}" name="${field.name}" rows="3" ${field.required ? 'required' : ''}>${value}</textarea>
                </div>`;
        } else {
            return `
                <div class="mb-3">
                    <label for="${field.name}" class="form-label">${field.label} ${field.required ? '*' : ''}</label>
                    <input type="${field.type}" class="form-control" id="${field.name}" name="${field.name}" 
                           value="${value}" ${field.required ? 'required' : ''}
                           ${field.min ? `min="${field.min}"` : ''}
                           ${field.max ? `max="${field.max}"` : ''}
                           ${field.step ? `step="${field.step}"` : ''}>
                </div>`;
        }
    }).join('');
}

// Save record
async function saveRecord() {
    const form = document.getElementById('dynamicForm');
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    if (isEditMode) {
        data.id = currentEditId;
    }
    
    try {
        const method = isEditMode ? 'PUT' : 'POST';
        const response = await fetch(`${API_BASE_URL}/${apiEndpoints[currentCategory]}`, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('dynamicModal'));
            modal.hide();
            
            showToast('Success', isEditMode ? 'Record updated successfully' : 'Record added successfully', 'success');
            loadData(currentCategory);
        } else {
            throw new Error(result.message || 'Failed to save record');
        }
    } catch (error) {
        console.error('Error saving record:', error);
        showToast('Error', 'Failed to save record', 'error');
    }
}

// Helper functions
function getCategoryDisplayName(category) {
    const names = {
        'meat-products': 'Product',
        'production': 'Production Record',
        'market': 'Market Analytics',
        'consumer': 'Consumer Demographics',
        'quality': 'Quality Assessment',
        'regulatory': 'Regulatory Compliance'
    };
    return names[category] || 'Record';
}

function showToast(title, message, type = 'info') {
    const toast = document.getElementById('notificationToast');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');
    
    toastTitle.textContent = title;
    toastMessage.textContent = message;
    
    // Set toast styling based on type
    toast.className = `toast ${type === 'success' ? 'bg-success text-white' : 
                             type === 'error' ? 'bg-danger text-white' : ''}`;
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}
