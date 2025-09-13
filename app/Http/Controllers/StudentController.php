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
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message'=>$validator->errors()
            ],422);
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return response()->json([
            'message'=>'success',
            'status' => 201
        ],200);
    }
    
    public function show($id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'student' => $student,
            'message' => 'success',
            'status' => 200
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
                'status' => 404
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ], 422);
        }

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return response()->json([
            'message' => 'Student updated successfully',
            'status' => 200
        ], 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            return response()->json([
                'message' => 'Student not found',
                'status' => 404
            ], 404);
        }

        $student->delete();

        return response()->json([
            'message' => 'Student deleted successfully',
            'status' => 200
        ], 200);
    }
}