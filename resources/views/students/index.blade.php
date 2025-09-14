@extends('layouts.app')

@section('title', 'Students - Student Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">Students</h1>
            <p class="text-gray-600 mt-1">Manage your student database</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="search-input" placeholder="Search students..." value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Add Student
            </a>
        </div>
    </div>

    @if($students->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($students as $student)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="flex-shrink-0">
                            @if($student->image)
                                <img src="{{ asset('uploads/students/' . $student->image) }}" alt="{{ $student->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-indigo-100">
                            @else
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">{{ strtoupper(substr($student->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $student->name)[1] ?? '', 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $student->name }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $student->email }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        @if($student->phone)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-phone w-4 h-4 mr-2 text-indigo-500"></i>
                                <span class="truncate">{{ $student->phone }}</span>
                            </div>
                        @endif
                        @if($student->address)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt w-4 h-4 mr-2 text-indigo-500"></i>
                                <span class="truncate">{{ Str::limit($student->address, 40) }}</span>
                            </div>
                        @endif
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar w-4 h-4 mr-2 text-indigo-500"></i>
                            <span class="truncate">Added {{ $student->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                        <a href="{{ route('students.show', $student->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors duration-200">
                            <i class="fas fa-eye mr-1"></i>
                            View
                        </a>
                        <a href="{{ route('students.edit', $student->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors duration-200">
                            <i class="fas fa-edit mr-1"></i>
                            Edit
                        </a>
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this student?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-200">
                                <i class="fas fa-trash mr-1"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
            {{ $students->appends(request()->query())->links('custom.pagination') }}
        @endif
    @else
        <div class="text-center py-12">
            <div class="bg-white rounded-xl shadow-lg p-8 max-w-md mx-auto">
                <i class="fas fa-user-graduate text-6xl text-gray-400 mb-4"></i>
                @if(request('search'))
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No students found</h3>
                    <p class="text-gray-600 mb-6">No students match your search criteria "{{ request('search') }}"</p>
                    <div class="space-x-3">
                        <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Clear Search
                        </a>
                        <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Add Student
                        </a>
                    </div>
                @else
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No students found</h3>
                    <p class="text-gray-600 mb-6">Start by adding your first student!</p>
                    <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Add Student
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    @media (max-width: 768px) {
        .grid {
            grid-template-columns: 1fr !important;
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