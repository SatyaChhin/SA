<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\dashboardLib;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Group;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;

class LibrariesCrudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dashboardLib::dashboardData();
        // $classroom = Classroom::all()->count();
        $student = Student::all()->count();
        $teacher = Teacher::all()->count();
        $subject = Subject::all()->count();
        $group = Group::all()->count();
        return view('dashboard', compact('student', 'teacher', 'subject','group'));
    }
}
