<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DateSeat;
use App\Models\BookingDetail; // Model for booking_details table
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Display the default available seats on page load
    public function index()
    {
        $user = Auth::user(); // Get the logged-in user
    
        // Fetch all bookings for the current user based on their emp_id
        $userBookings = BookingDetail::where('emp_id', $user->emp_id)->get();
    
        // Check today's date for each booking and add a cancelable property
        $today = now()->format('Y-m-d');
        foreach ($userBookings as $booking) {
            // Log dates for debugging
            \Log::info('Current Date: ' . $today);
            \Log::info('Booking Date: ' . $booking->date);
            
            // Check if booking date is in the future
            $booking->isCancelable = $booking->date > $today; // Adjusted comparison
        }
    
        // Fetch seats for today or any default date you want to initialize with
        $initialDate = now()->format('Y-m-d');
        $dateSeats = DateSeat::where('date', $initialDate)->get();
    
        // Pass the bookings and seats to the view
        return view('system', compact('userBookings', 'dateSeats', 'initialDate'));
    }    


    public function userBookings()
    {
        $user = Auth::user(); // Get the logged-in user
    
        // Fetch all bookings for the current user based on their emp_id
        $userBookings = DB::table('booking_details')
                            ->where('emp_id', $user->emp_id)
                            ->get();
    
        // Pass the bookings to the view
        return view('system', compact('userBookings'));
    }
    
    // Fetch seats for the selected date
    public function getSeats(Request $request)
    {
        // Validate the date
        $request->validate([
            'date' => 'required|date',
        ]);

        // Fetch the seats based on the selected date
        $dateSeats = DateSeat::where('date', $request->date)->get();

        // Return seat status as JSON response
        return response()->json($dateSeats);
    }


    public function bookSeat(Request $request) 
{
    $request->merge(['seat_no' => (int) $request->seat_no]);

    try {
        // Validate input
        $request->validate([
            'date' => 'required|date',
            'seat_no' => 'required|integer',
        ]);

        // Fetch the current logged-in user's details
        $user = Auth::user();

        // Check if the user has already booked a seat for the selected date
        $existingBookingForUser = BookingDetail::where('emp_id', $user->emp_id)
                                               ->where('date', $request->date)
                                               ->first();

        if ($existingBookingForUser) {
            return response()->json(['success' => false, 'message' => 'You can only book one seat per day.']);
        }

        // Check if the seat is already booked
        $existingBooking = DateSeat::where('date', $request->date)
                                    ->where('seat_no', $request->seat_no)
                                    ->first();

        if ($existingBooking && $existingBooking->is_booked) {
            return response()->json(['success' => false, 'message' => 'This seat is already booked.']);
        }

        // Update the booking status in the DateSeat table
        $affectedRows = DateSeat::where('date', $request->date)
                                ->where('seat_no', $request->seat_no)
                                ->update(['is_booked' => 1]);

        if ($affectedRows > 0) {
            // Store the booking details in the booking_details table
            BookingDetail::create([
                'emp_id' => $user->emp_id,
                'date' => $request->date,
                'seat_no' => $request->seat_no,
                'employee_name' => $user->name,
                'phone_number' => $user->contactno,
                'email' => $user->email,
            ]);

            return response()->json(['success' => true, 'message' => 'Booking Success.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Booking failed.']);
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()]);
    } catch (\Exception $e) {
        \Log::error('Booking Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
}


    public function cancelBooking($id)
{
    try {
        // Find the booking by ID
        $booking = BookingDetail::findOrFail($id);

        // Check if the booking date is in the future
        if (now()->diffInDays($booking->date) > 0) {
            // Update the corresponding date_seat record
            DateSeat::where('date', $booking->date)
                ->where('seat_no', $booking->seat_no)
                ->update(['is_booked' => 0]);

            // Delete the booking from booking_details
            $booking->delete();

            return response()->json(['success' => true, 'message' => 'Booking canceled successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Cannot cancel bookings for today or past dates.']);
        }
    } catch (\Exception $e) {
        \Log::error('Cancel Booking Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
}

}
