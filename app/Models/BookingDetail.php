<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;

    protected $table = 'booking_details'; // Table name

    
    public function attendance()
    {
        return $this->hasOne(Attendance::class, 'booking_id', 'id'); // Adjust if necessary
    }

    protected $fillable = [
        'emp_id', 'employee_name', 'phone_number', 'email', 'date', 'seat_no',
    ];

    //PDF
    protected $fillablee = [
        'emp_id', 'date', 'seat_no', 'employee_name', 'phone_number', 'email'
    ];
    
}
