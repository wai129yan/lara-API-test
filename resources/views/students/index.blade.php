@extends('layouts.app')

@section('title', 'Students - Student Management')

@section('content')
<div class="section">
    <div class="section-header">
        <div>
            <h2>Students</h2>
            <p>Manage your student database</p>
        </div>
        <div class="section-actions">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search-input" placeholder="Search students..." value="{{ request('search') }}">
            </div>
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Student
            </a>
        </div>
    </div>

    @if($students->count() > 0)
        <div class="students-grid">
            @foreach($students as $student)
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
                            <span>{{ Str::limit($student->address, 40) }}</span>
                        </div>
                    @endif
                    <div class="student-detail">
                        <i class="fas fa-calendar"></i>
                        <span>Added {{ $student->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="student-actions">
                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-eye"></i> View
                    </a>
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

        <!-- Pagination -->
        @if($students->hasPages())
        <div class="pagination-wrapper">
            {{ $students->appends(request()->query())->links() }}
        </div>
        @endif
    @else
        <div class="no-students">
            <div style="text-align: center; padding: 3rem; background: rgba(255, 255, 255, 0.9); border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
                <i class="fas fa-user-graduate" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; color: #64748b;"></i>
                @if(request('search'))
                    <h3 style="color: #2d3748; margin-bottom: 0.5rem;">No students found</h3>
                    <p style="color: #64748b; margin-bottom: 2rem;">No students match your search criteria "{{ request('search') }}"</p>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary" style="margin-right: 1rem;">
                        <i class="fas fa-times"></i> Clear Search
                    </a>
                @else
                    <h3 style="color: #2d3748; margin-bottom: 0.5rem;">No students found</h3>
                    <p style="color: #64748b; margin-bottom: 2rem;">Start by adding your first student!</p>
                @endif
                <a href="{{ route('students.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Student
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .search-box {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-box i {
        position: absolute;
        left: 12px;
        color: #64748b;
        z-index: 1;
    }

    .search-box input {
        padding: 12px 12px 12px 40px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        width: 300px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
        flex-wrap: wrap;
    }

    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination-wrapper .pagination li {
        display: flex;
    }

    .pagination-wrapper .pagination a,
    .pagination-wrapper .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 8px;
        text-decoration: none;
        color: #64748b;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        min-width: 40px;
    }

    .pagination-wrapper .pagination a:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination-wrapper .pagination .active span {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .pagination-wrapper .pagination .disabled span {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .search-box input {
            width: 100%;
        }
        
        .students-grid {
            grid-template-columns: 1fr;
        }
        
        .student-actions {
            justify-content: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchValue = this.value.trim();
                const url = new URL(window.location);
                
                if (searchValue) {
                    url.searchParams.set('search', searchValue);
                } else {
                    url.searchParams.delete('search');
                }
                
                window.location.href = url.toString();
            }, 500);
        });
    });
</script>
@endpush