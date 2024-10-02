<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance'; // Table name

    
    public function bookingDetails()
    {
        return $this->belongsTo(BookingDetail::class, 'emp_id', 'emp_id'); // Make sure this matches your foreign key and primary key
    }



    //new
    // protected $fillable = [
    //     'emp_id', 'date', 'attendance_status', 'seat_no',
    // ];

    //  public function employee()
    //  {
    //     return $this->belongsTo(BookingDetail::class, 'emp_id');
    // }

    protected $fillable = [
        'emp_id', 'date', 'attendance_status', 'seat_no',
    ];

    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class, 'emp_id');
    // }

}
