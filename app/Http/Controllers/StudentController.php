<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $student = Student::all();
        
        return response()->json([
            'student'=>$student,
            'message'=>'success',
            'status'=>200
        ],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 422,
                    'message'=>$validator->errors()
                ],422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/students');
            
            // Ensure directory exists and is writable
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            if (!is_writable($uploadDir)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Upload directory is not writable'
                    ], 500);
                }
                return redirect()->back()->with('error', 'Upload directory is not writable')->withInput();
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadDir, $imageName);
            $imagePath = $imageName; // Store just the filename
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $imagePath
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message'=>'Student created successfully',
                'student' => $student,
                'status' => 201
            ],201);
        }
        
        return redirect()->route('students.show', $student->id)->with('success', 'Student created successfully');
    }
    
    public function show(Request $request, $id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Student not found',
                    'status' => 404
                ], 404);
            }
            return redirect()->route('students.index')->with('error', 'Student not found');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'student' => $student,
                'message' => 'success',
                'status' => 200
            ], 200);
        }
        
        return view('students.show', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Student not found',
                    'status' => 404
                ], 404);
            }
            return redirect()->route('students.index')->with('error', 'Student not found');
        }

        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 422,
                    'message' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imagePath = $student->image; // Keep existing image by default
        
        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/students');
            
            // Ensure directory exists and is writable
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            if (!is_writable($uploadDir)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Upload directory is not writable'
                    ], 500);
                }
                return redirect()->back()->with('error', 'Upload directory is not writable')->withInput();
            }
            
            // Delete old image if exists
            if ($student->image && file_exists(public_path('uploads/students/' . $student->image))) {
                unlink(public_path('uploads/students/' . $student->image));
            }
            
            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadDir, $imageName);
            $imagePath = $imageName; // Store just the filename
        }

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $imagePath
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Student updated successfully',
                'student' => $student,
                'status' => 200
            ], 200);
        }
        
        return redirect()->route('students.show', $student->id)->with('success', 'Student updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Student not found',
                    'status' => 404
                ], 404);
            }
            return redirect()->route('students.index')->with('error', 'Student not found');
        }

        // Delete associated image if exists
        if ($student->image && file_exists(public_path('uploads/students/' . $student->image))) {
            unlink(public_path('uploads/students/' . $student->image));
        }

        $student->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Student deleted successfully',
                'status' => 200
            ], 200);
        }
        
        return redirect()->route('students.index')->with('success', 'Student deleted successfully');
    }

    // View methods for UI
    public function dashboard()
    {
        $totalStudents = Student::count();
        $recentStudents = Student::latest()->take(5)->get();
        
        return view('students.dashboard', compact('totalStudents', 'recentStudents'));
    }

    public function indexView(Request $request)
    {
        $query = Student::query();
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }
        
        $students = $query->latest()->paginate(6);
        
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.form', ['student' => null]);
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.form', compact('student'));
    }
}