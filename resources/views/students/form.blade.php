@extends('layouts.app')

@section('title', isset($student) ? 'Edit Student - Student Management' : 'Add Student - Student Management')

@section('content')
<div class="section">
    <div class="section-header">
        <div>
            <h2>{{ isset($student) ? 'Edit Student' : 'Add New Student' }}</h2>
            <p>{{ isset($student) ? 'Update student information' : 'Fill in the details to add a new student' }}</p>
        </div>
        <div class="section-actions">
            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Students
            </a>
        </div>
    </div>

    <div class="form-container">
        <form action="{{ isset($student) ? route('students.update', $student->id) : route('students.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="student-form" 
              id="student-form">
            @csrf
            @if(isset($student))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">
                    <i class="fas fa-user"></i> Full Name *
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $student->name ?? '') }}" 
                       required 
                       placeholder="Enter student's full name">
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Email Address *
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $student->email ?? '') }}" 
                       required 
                       placeholder="Enter email address">
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">
                    <i class="fas fa-phone"></i> Phone Number
                </label>
                <input type="tel" 
                       id="phone" 
                       name="phone" 
                       value="{{ old('phone', $student->phone ?? '') }}" 
                       placeholder="Enter phone number">
                @error('phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">
                    <i class="fas fa-map-marker-alt"></i> Address
                </label>
                <textarea id="address" 
                          name="address" 
                          rows="3" 
                          placeholder="Enter student's address">{{ old('address', $student->address ?? '') }}</textarea>
                @error('address')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="image">
                    <i class="fas fa-camera"></i> Profile Photo
                </label>
                <div class="image-upload">
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*">
                    <div class="image-preview" id="image-preview">
                        @if(isset($student) && $student->image)
                            <img src="{{ asset('uploads/students/' . $student->image) }}" alt="Current image">
                            <p>Current image - Choose a new file to replace</p>
                        @else
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload an image or drag and drop</p>
                            <small>Maximum file size: 5MB. Supported formats: JPG, PNG, GIF</small>
                        @endif
                    </div>
                </div>
                @error('image')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    @if(isset($student))
                        <i class="fas fa-save"></i> Update Student
                    @else
                        <i class="fas fa-plus"></i> Add Student
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .student-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        font-weight: 600;
        color: #2d3748;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group label i {
        color: #667eea;
        width: 16px;
    }

    .form-group input,
    .form-group textarea {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .error-message {
        color: #f56565;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .error-message::before {
        content: '⚠';
        font-size: 1rem;
    }

    /* Image Upload */
    .image-upload {
        position: relative;
    }

    .image-upload input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }

    .image-preview {
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        position: relative;
    }

    .image-preview:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .image-preview i {
        font-size: 2rem;
        color: #64748b;
    }

    .image-preview p {
        color: #64748b;
        font-weight: 500;
        margin: 0;
    }

    .image-preview small {
        color: #a0aec0;
        font-size: 0.75rem;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    /* Loading state */
    .btn.loading {
        opacity: 0.7;
        cursor: not-allowed;
        position: relative;
    }

    .btn.loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1.5rem;
            margin: 0 10px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const form = document.getElementById('student-form');
        const submitBtn = document.getElementById('submit-btn');
        
        // Image preview functionality
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
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
                    imagePreview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <p>Image selected: ${file.name}</p>
                        <small>Click to change image</small>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Form submission with loading state
        form.addEventListener('submit', function(e) {
            // Basic validation
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!name || !email) {
                e.preventDefault();
                showToast('Name and email are required', 'error');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                showToast('Please enter a valid email address', 'error');
                return;
            }
            
            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            
            // Show loading overlay
            showLoading(true);
        });
        
        // Drag and drop functionality
        const dropZone = imagePreview;
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            dropZone.style.borderColor = '#667eea';
            dropZone.style.background = 'rgba(102, 126, 234, 0.1)';
        }
        
        function unhighlight(e) {
            dropZone.style.borderColor = '#cbd5e0';
            dropZone.style.background = 'transparent';
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                imageInput.files = files;
                imageInput.dispatchEvent(new Event('change'));
            }
        }
    });
</script>
@endpush