// Global variables
let students = [];
let currentStudent = null;
let isEditing = false;

// API Base URL
const API_BASE = '/api/students';

// DOM Elements
const sections = {
    dashboard: document.getElementById('dashboard-section'),
    students: document.getElementById('students-section'),
    form: document.getElementById('form-section')
};

const navButtons = document.querySelectorAll('.nav-btn');
const studentsGrid = document.getElementById('students-grid');
const studentForm = document.getElementById('student-form');
const searchInput = document.getElementById('search-input');
const loadingOverlay = document.getElementById('loading-overlay');
const toastContainer = document.getElementById('toast-container');

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    setupEventListeners();
    showSection('dashboard');
    loadStudents();
    updateStats();
}

// Event Listeners
function setupEventListeners() {
    // Navigation
    navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.dataset.section;
            showSection(section);
        });
    });

    // Search functionality
    searchInput.addEventListener('input', debounce(handleSearch, 300));

    // Form submission
    studentForm.addEventListener('submit', handleFormSubmit);

    // Add student button
    document.getElementById('add-student-btn').addEventListener('click', () => {
        showAddForm();
    });

    // Cancel form button
    document.getElementById('cancel-btn').addEventListener('click', () => {
        showSection('students');
        resetForm();
    });

    // Image upload preview
    document.getElementById('image').addEventListener('change', handleImagePreview);
}

// Navigation Functions
function showSection(sectionName) {
    // Hide all sections
    Object.values(sections).forEach(section => {
        section.classList.remove('active');
    });

    // Show selected section
    sections[sectionName].classList.add('active');

    // Update navigation
    navButtons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.section === sectionName) {
            btn.classList.add('active');
        }
    });

    // Load data if needed
    if (sectionName === 'students') {
        loadStudents();
    } else if (sectionName === 'dashboard') {
        updateStats();
    }
}

// API Functions
async function loadStudents() {
    try {
        showLoading(true);
        const response = await fetch(API_BASE);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        students = data.students || data || [];
        renderStudents(students);
        updateStats();
    } catch (error) {
        console.error('Error loading students:', error);
        showToast('Failed to load students', 'error');
        students = [];
        renderStudents([]);
    } finally {
        showLoading(false);
    }
}

async function createStudent(formData) {
    try {
        showLoading(true);
        const response = await fetch(API_BASE, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Failed to create student');
        }
        
        showToast('Student created successfully!', 'success');
        loadStudents();
        showSection('students');
        resetForm();
    } catch (error) {
        console.error('Error creating student:', error);
        showToast(error.message || 'Failed to create student', 'error');
    } finally {
        showLoading(false);
    }
}

async function updateStudent(id, formData) {
    try {
        showLoading(true);
        
        // Add _method field for Laravel
        formData.append('_method', 'PUT');
        
        const response = await fetch(`${API_BASE}/${id}`, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Failed to update student');
        }
        
        showToast('Student updated successfully!', 'success');
        loadStudents();
        showSection('students');
        resetForm();
    } catch (error) {
        console.error('Error updating student:', error);
        showToast(error.message || 'Failed to update student', 'error');
    } finally {
        showLoading(false);
    }
}

async function deleteStudent(id) {
    if (!confirm('Are you sure you want to delete this student?')) {
        return;
    }
    
    try {
        showLoading(true);
        const response = await fetch(`${API_BASE}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Failed to delete student');
        }
        
        showToast('Student deleted successfully!', 'success');
        loadStudents();
    } catch (error) {
        console.error('Error deleting student:', error);
        showToast(error.message || 'Failed to delete student', 'error');
    } finally {
        showLoading(false);
    }
}

// Render Functions
function renderStudents(studentsToRender) {
    if (!studentsGrid) return;
    
    if (studentsToRender.length === 0) {
        studentsGrid.innerHTML = `
            <div class="no-students">
                <div style="text-align: center; padding: 3rem; color: #64748b;">
                    <i class="fas fa-user-graduate" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <h3>No students found</h3>
                    <p>Start by adding your first student!</p>
                    <button class="btn btn-primary" onclick="showAddForm()" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Add Student
                    </button>
                </div>
            </div>
        `;
        return;
    }
    
    studentsGrid.innerHTML = studentsToRender.map(student => `
        <div class="student-card" data-id="${student.id}">
            <div class="student-header">
                <div class="student-avatar ${!student.image ? 'placeholder' : ''}">
                    ${student.image 
                        ? `<img src="/uploads/students/${student.image}" alt="${student.name}" class="student-avatar">` 
                        : `<span>${getInitials(student.name)}</span>`
                    }
                </div>
                <div class="student-info">
                    <h3>${escapeHtml(student.name)}</h3>
                    <p>${escapeHtml(student.email)}</p>
                </div>
            </div>
            <div class="student-details">
                ${student.phone ? `
                    <div class="student-detail">
                        <i class="fas fa-phone"></i>
                        <span>${escapeHtml(student.phone)}</span>
                    </div>
                ` : ''}
                ${student.address ? `
                    <div class="student-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${escapeHtml(student.address)}</span>
                    </div>
                ` : ''}
            </div>
            <div class="student-actions">
                <button class="btn btn-secondary btn-sm" onclick="editStudent(${student.id})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-danger btn-sm" onclick="deleteStudent(${student.id})">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    `).join('');
}

function updateStats() {
    const totalStudents = students.length;
    const studentsWithImages = students.filter(s => s.image).length;
    const studentsWithPhone = students.filter(s => s.phone).length;
    
    // Update stat cards
    const statCards = document.querySelectorAll('.stat-card');
    if (statCards.length >= 3) {
        statCards[0].querySelector('h3').textContent = totalStudents;
        statCards[1].querySelector('h3').textContent = studentsWithImages;
        statCards[2].querySelector('h3').textContent = studentsWithPhone;
    }
}

// Form Functions
function showAddForm() {
    isEditing = false;
    currentStudent = null;
    resetForm();
    document.getElementById('form-title').textContent = 'Add New Student';
    document.getElementById('submit-btn').innerHTML = '<i class="fas fa-plus"></i> Add Student';
    showSection('form');
}

function editStudent(id) {
    const student = students.find(s => s.id === id);
    if (!student) return;
    
    isEditing = true;
    currentStudent = student;
    
    // Fill form with student data
    document.getElementById('name').value = student.name || '';
    document.getElementById('email').value = student.email || '';
    document.getElementById('phone').value = student.phone || '';
    document.getElementById('address').value = student.address || '';
    
    // Show current image if exists
    const imagePreview = document.getElementById('image-preview');
    if (student.image) {
        imagePreview.innerHTML = `
            <img src="/uploads/students/${student.image}" alt="Current image">
            <p>Current image - Choose a new file to replace</p>
        `;
    }
    
    document.getElementById('form-title').textContent = 'Edit Student';
    document.getElementById('submit-btn').innerHTML = '<i class="fas fa-save"></i> Update Student';
    showSection('form');
}

function resetForm() {
    studentForm.reset();
    document.getElementById('image-preview').innerHTML = `
        <i class="fas fa-cloud-upload-alt"></i>
        <p>Click to upload an image or drag and drop</p>
    `;
    isEditing = false;
    currentStudent = null;
}

function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(studentForm);
    
    // Validate required fields
    const name = formData.get('name');
    const email = formData.get('email');
    
    if (!name || !email) {
        showToast('Name and email are required', 'error');
        return;
    }
    
    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showToast('Please enter a valid email address', 'error');
        return;
    }
    
    if (isEditing && currentStudent) {
        updateStudent(currentStudent.id, formData);
    } else {
        createStudent(formData);
    }
}

function handleImagePreview(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showToast('Please select a valid image file', 'error');
            e.target.value = '';
            return;
        }
        
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            showToast('Image size must be less than 5MB', 'error');
            e.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <p>Image selected: ${file.name}</p>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Click to upload an image or drag and drop</p>
        `;
    }
}

// Search Function
function handleSearch(e) {
    const query = e.target.value.toLowerCase().trim();
    
    if (!query) {
        renderStudents(students);
        return;
    }
    
    const filteredStudents = students.filter(student => 
        student.name.toLowerCase().includes(query) ||
        student.email.toLowerCase().includes(query) ||
        (student.phone && student.phone.includes(query)) ||
        (student.address && student.address.toLowerCase().includes(query))
    );
    
    renderStudents(filteredStudents);
}

// Utility Functions
function showLoading(show) {
    if (loadingOverlay) {
        loadingOverlay.classList.toggle('show', show);
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icon = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        info: 'fas fa-info-circle'
    }[type] || 'fas fa-info-circle';
    
    toast.innerHTML = `
        <i class="${icon}"></i>
        <span class="toast-message">${escapeHtml(message)}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}

function getInitials(name) {
    return name
        .split(' ')
        .map(word => word.charAt(0))
        .join('')
        .toUpperCase()
        .substring(0, 2);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Make functions globally available
window.editStudent = editStudent;
window.deleteStudent = deleteStudent;
window.showAddForm = showAddForm;