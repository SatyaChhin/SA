<?php
namespace App\Libraries;

use App\Models\Student;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;

class dashboardLib
{
    public static function dashboardData(Request $request)
    {
        $student = Student::all();
        return $student;
    }
}