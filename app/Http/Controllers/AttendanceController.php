<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\BookingDetail;
use Carbon\Carbon;

class AttendanceController extends Controller
{

    
    public function index(Request $request)
{
    $date_filter = $request->input('date-picker', '');
    $emp_id_filter = $request->input('trainee-id', '');

    $present_trainees = [];
    $absent_trainees = [];

    $query = Attendance::with('bookingDetails');

    // Use the previous week's date as default for the date filter if no date is provided
    if ($date_filter) {
        $query->where('date', $date_filter);
    } else {
        // Only fetch records if there's a specific date set
        $query->where('date', '=', null); // Adjust this if you want to show nothing by default
    }

    if ($emp_id_filter) {
        $query->where('emp_id', $emp_id_filter);
    }

    // Only get attendances if a date is selected
    if ($date_filter) {
        $attendances = $query->get();

        foreach ($attendances as $attendance) {
            if ($attendance->attendance_status == 1) {
                $present_trainees[] = $attendance; 
            } else {
                $absent_trainees[] = $attendance; 
            }
        }
    }

    return view('index', compact('present_trainees', 'absent_trainees', 'date_filter', 'emp_id_filter'));
}



    public function mark(Request $request)
{
    
    $request->validate([
        'trainee_id' => 'required|exists:bookings,emp_id', 
        'date' => 'required|date',
        
    ]);

    return redirect()->route('attendance.index')->with('success', 'Attendance marked successfully!');
}

}



