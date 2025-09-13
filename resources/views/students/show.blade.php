@extends('layouts.app')

@section('title', $student->name . ' - Student Details')

@section('content')
<div class="section">
    <div class="section-header">
        <div>
            <h2>Student Details</h2>
            <p>View {{ $student->name }}'s information</p>
        </div>
        <div class="section-actions">
            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Students
            </a>
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Student
            </a>
        </div>
    </div>

    <div class="student-profile">
        <div class="profile-header">
            <div class="profile-avatar">
                @if($student->image)
                    <img src="{{ asset('uploads/students/' . $student->image) }}" alt="{{ $student->name }}" class="avatar-image">
                @else
                    <div class="avatar-placeholder">
                        <span>{{ strtoupper(substr($student->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $student->name)[1] ?? '', 0, 1)) }}</span>
                    </div>
                @endif
            </div>
            <div class="profile-info">
                <h1>{{ $student->name }}</h1>
                <p class="profile-email">{{ $student->email }}</p>
                <div class="profile-meta">
                    <span class="meta-item">
                        <i class="fas fa-calendar-plus"></i>
                        Added {{ $student->created_at->diffForHumans() }}
                    </span>
                    @if($student->updated_at != $student->created_at)
                        <span class="meta-item">
                            <i class="fas fa-edit"></i>
                            Updated {{ $student->updated_at->diffForHumans() }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="profile-content">
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-header">
                        <i class="fas fa-user"></i>
                        <h3>Personal Information</h3>
                    </div>
                    <div class="info-body">
                        <div class="info-row">
                            <label>Full Name:</label>
                            <span>{{ $student->name }}</span>
                        </div>
                        <div class="info-row">
                            <label>Email Address:</label>
                            <span>
                                <a href="mailto:{{ $student->email }}" class="email-link">
                                    {{ $student->email }}
                                </a>
                            </span>
                        </div>
                        @if($student->phone)
                            <div class="info-row">
                                <label>Phone Number:</label>
                                <span>
                                    <a href="tel:{{ $student->phone }}" class="phone-link">
                                        {{ $student->phone }}
                                    </a>
                                </span>
                            </div>
                        @endif
                        @if($student->address)
                            <div class="info-row">
                                <label>Address:</label>
                                <span class="address-text">{{ $student->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-header">
                        <i class="fas fa-clock"></i>
                        <h3>Timeline</h3>
                    </div>
                    <div class="info-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon created">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <div class="timeline-content">
                                    <h4>Student Added</h4>
                                    <p>{{ $student->created_at->format('F j, Y \a\t g:i A') }}</p>
                                    <small>{{ $student->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @if($student->updated_at != $student->created_at)
                                <div class="timeline-item">
                                    <div class="timeline-icon updated">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h4>Last Updated</h4>
                                        <p>{{ $student->updated_at->format('F j, Y \a\t g:i A') }}</p>
                                        <small>{{ $student->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-cards">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="action-content">
                        <h3>Edit Information</h3>
                        <p>Update student's personal details, contact information, or profile photo</p>
                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Student
                        </a>
                    </div>
                </div>

                <div class="action-card danger">
                    <div class="action-icon">
                        <i class="fas fa-trash"></i>
                    </div>
                    <div class="action-content">
                        <h3>Delete Student</h3>
                        <p>Permanently remove this student from the database. This action cannot be undone.</p>
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete {{ $student->name }}? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Student
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .student-profile {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
        color: white;
    }

    .profile-avatar {
        flex-shrink: 0;
    }

    .avatar-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }

    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }

    .profile-info h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .profile-email {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }

    .profile-meta {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .profile-content {
        padding: 2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .info-card {
        background: #f8fafc;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .info-header {
        background: white;
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-header i {
        color: #667eea;
        font-size: 1.25rem;
    }

    .info-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
    }

    .info-body {
        padding: 1.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row label {
        font-weight: 600;
        color: #4a5568;
        flex-shrink: 0;
        margin-right: 1rem;
    }

    .info-row span {
        color: #2d3748;
        text-align: right;
        word-break: break-word;
    }

    .email-link, .phone-link {
        color: #667eea;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .email-link:hover, .phone-link:hover {
        color: #5a67d8;
        text-decoration: underline;
    }

    .address-text {
        max-width: 200px;
    }

    .timeline {
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
        flex-shrink: 0;
        z-index: 1;
    }

    .timeline-icon.created {
        background: #48bb78;
    }

    .timeline-icon.updated {
        background: #4299e1;
    }

    .timeline-content h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0 0 0.25rem 0;
    }

    .timeline-content p {
        color: #4a5568;
        margin: 0 0 0.25rem 0;
        font-size: 0.9rem;
    }

    .timeline-content small {
        color: #718096;
        font-size: 0.8rem;
    }

    .action-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .action-card {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .action-card:hover {
        border-color: #cbd5e0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .action-card.danger {
        border-color: #fed7d7;
        background: #fffafa;
    }

    .action-card.danger:hover {
        border-color: #feb2b2;
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .action-card .action-icon {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .action-card.danger .action-icon {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }

    .action-content h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0 0 0.5rem 0;
    }

    .action-content p {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0 0 1rem 0;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
        
        .profile-meta {
            justify-content: center;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .action-cards {
            grid-template-columns: 1fr;
        }
        
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
        
        .info-row span {
            text-align: left;
        }
        
        .address-text {
            max-width: none;
        }
    }

    @media (max-width: 480px) {
        .profile-header {
            padding: 1.5rem;
        }
        
        .profile-content {
            padding: 1.5rem;
        }
        
        .avatar-image,
        .avatar-placeholder {
            width: 80px;
            height: 80px;
        }
        
        .avatar-placeholder {
            font-size: 1.75rem;
        }
        
        .profile-info h1 {
            font-size: 1.75rem;
        }
    }
</style>
@endpush