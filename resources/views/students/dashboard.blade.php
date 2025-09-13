@extends('layouts.app')

@section('title', 'Dashboard - Student Management')

@section('content')
<div class="section">
    <div class="section-header">
        <div>
            <h2>Dashboard</h2>
            <p>Overview of your student management system</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalStudents }}</h3>
                <p>Total Students</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-image"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $studentsWithImages }}</h3>
                <p>Students with Photos</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-phone"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $studentsWithPhone }}</h3>
                <p>Students with Phone</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $studentsWithAddress }}</h3>
                <p>Students with Address</p>
            </div>
        </div>
    </div>

    <!-- Recent Students -->
    @if($recentStudents->count() > 0)
    <div class="recent-students">
        <div class="section-header">
            <div>
                <h2>Recent Students</h2>
                <p>Latest additions to your student database</p>
            </div>
            <div class="section-actions">
                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    <i class="fas fa-eye"></i> View All
                </a>
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Student
                </a>
            </div>
        </div>
        
        <div class="students-grid">
            @foreach($recentStudents as $student)
            <div class="student-card">
                <div class="student-header">
                    <div class="student-avatar {{ !$student->image ? 'placeholder' : '' }}">
                        @if($student->image)
                            <img src="{{ asset('uploads/students/' . $student->image) }}" alt="{{ $student->name }}" class="student-avatar">
                        @else
                            <span>{{ strtoupper(substr($student->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $student->name)[1] ?? '', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="student-info">
                        <h3>{{ $student->name }}</h3>
                        <p>{{ $student->email }}</p>
                    </div>
                </div>
                <div class="student-details">
                    @if($student->phone)
                        <div class="student-detail">
                            <i class="fas fa-phone"></i>
                            <span>{{ $student->phone }}</span>
                        </div>
                    @endif
                    @if($student->address)
                        <div class="student-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ Str::limit($student->address, 30) }}</span>
                        </div>
                    @endif
                    <div class="student-detail">
                        <i class="fas fa-calendar"></i>
                        <span>Added {{ $student->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="student-actions">
                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this student?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="no-students">
        <div style="text-align: center; padding: 3rem; background: rgba(255, 255, 255, 0.9); border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
            <i class="fas fa-user-graduate" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; color: #64748b;"></i>
            <h3 style="color: #2d3748; margin-bottom: 0.5rem;">No students found</h3>
            <p style="color: #64748b; margin-bottom: 2rem;">Start by adding your first student to get started!</p>
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Your First Student
            </a>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .stat-info p {
        color: #64748b;
        font-weight: 500;
    }

    .students-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .student-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .student-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .student-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .student-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .student-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e2e8f0;
    }

    .student-avatar.placeholder {
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .student-info h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .student-info p {
        color: #64748b;
        font-size: 0.9rem;
    }

    .student-details {
        margin-bottom: 1.5rem;
    }

    .student-detail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        color: #64748b;
        font-size: 0.9rem;
    }

    .student-detail i {
        width: 16px;
        color: #667eea;
    }

    .student-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .recent-students {
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .students-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush