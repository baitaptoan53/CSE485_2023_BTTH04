<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Student::latest()->paginate(5);
        return view('index', compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'studnet_name' => 'required',
            'student_email'         =>  'required|email|unique:students',
            'student_image'         =>  'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000'

        ]);
        $file_name = time() . '.' . $request->student_image->extension();
        // time() dùng để lấy thời gian hiện tại
        // extension() dùng để lấy đuôi file
        $request->student_image->move(public_path('images'), $file_name);
        // move() dùng để di chuyển file từ thư mục tạm sang thư mục chính
        // public_path('images') dùng để lấy đường dẫn đến thư mục images
        $student = new Student;
        $student->student_name = $request->student_name;
        // dòng này dùng để lấy dữ liệu từ form và lưu vào cột student_name
        $student->student_email = $request->student_email;
        $student->student_gender = $request->student_gender;
        $student->student_image = $file_name;
        $student->save();
        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        // compact() dùng để truyền biến vào view như là $student = student
        return view('edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate(
            [
                'student_name'      =>  'required',
                'student_email'     =>  'required|email',
                'student_image'     =>  'image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000'
            ]
        );
        $student_image = $request->hidden_student_image;

        if ($request->student_image != '') {
            $student_image = time() . '.' . request()->student_image->getClientOriginalExtension();

            request()->student_image->move(public_path('images'), $student_image);
        }
        $student = Student::find($request->hidden_id);
        $student->student_name = $request->student_name;
        $student->student_email = $request->student_email;
        $student->student_gender = $request->student_gender;
        $student->student_image = $student_image;
        $student->save();

        return redirect()->route('students.index')->with('success', 'Student Data has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully');
    }
}
