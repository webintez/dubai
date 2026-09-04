<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Submission;
use App\Models\Meeting;
use App\Models\Booking;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function showLogin()
    {
        if (session()->has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $envUsername = env('ADMIN_USERNAME', 'admin');
        $envPassword = env('ADMIN_PASSWORD', 'admin123');

        if ($request->username === $envUsername && $request->password === $envPassword) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['login' => 'Invalid username or password.'])->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    public function index(Request $request)
    {
        // Meetings ordered by start_time
        $meetings = Meeting::withCount('bookings')->orderBy('start_time', 'desc')->get();

        // Bookings Query with filters
        $bookingStatus = $request->query('booking_status');
        $meetingFilter = $request->query('meeting_id');

        $bookingsQuery = Booking::with(['user', 'meeting']);

        if ($bookingStatus) {
            $bookingsQuery->where('status', $bookingStatus);
        }

        if ($meetingFilter) {
            $bookingsQuery->where('meeting_id', $meetingFilter);
        }

        $bookings = $bookingsQuery->orderBy('created_at', 'desc')->paginate(20, ['*'], 'bookings_page');

        // Fetch settings
        $settings = [
            'today_link' => Setting::get('today_link', ''),
            'tomorrow_link' => Setting::get('tomorrow_link', ''),
            'support_phone' => Setting::get('support_phone', '+971 4 301 7777'),
            'payment_qr' => Setting::get('payment_qr', ''),
        ];

        // Stats dynamically calculated from start_time and duration
        $stats = [
            'total_meetings' => $meetings->count(),
            'ongoing_meetings' => $meetings->filter(fn($m) => $m->isLive())->count(),
            'upcoming_meetings' => $meetings->filter(fn($m) => $m->isUpcoming())->count(),
            'past_meetings' => $meetings->filter(fn($m) => $m->isPast())->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'approved_bookings' => Booking::where('status', 'approved')->count(),
        ];

        return view('admin.dashboard', compact('meetings', 'bookings', 'settings', 'stats'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'today_link' => 'nullable|url|max:255',
            'tomorrow_link' => 'nullable|url|max:255',
            'support_phone' => 'required|string|max:50',
            'payment_qr' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        Setting::set('today_link', $request->today_link);
        Setting::set('tomorrow_link', $request->tomorrow_link);
        Setting::set('support_phone', $request->support_phone);

        if ($request->hasFile('payment_qr')) {
            $file = $request->file('payment_qr');
            $filename = 'qr_' . time() . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('uploads/settings');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);

            Setting::set('payment_qr', 'uploads/settings/' . $filename);
        }

        return redirect()->back()->with('success', 'Portal configurations updated successfully!');
    }

    /**
     * Store new Meeting
     */
    public function storeMeeting(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'required|url|max:500',
            'duration' => 'required|string|max:50',
            'password' => 'required|string|max:100',
            'price' => 'required|string|max:50',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'thumbnail_url' => 'nullable|url|max:500',
            'start_time' => 'required|date',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'meeting_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/meetings');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $thumbnailPath = 'uploads/meetings/' . $filename;
        } elseif ($request->filled('thumbnail_url')) {
            $thumbnailPath = $request->thumbnail_url;
        } else {
            // Default elegant Dubai image
            $thumbnailPath = 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80';
        }

        Meeting::create([
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
            'duration' => $request->duration,
            'password' => $request->password,
            'price' => $request->price,
            'thumbnail' => $thumbnailPath,
            'start_time' => $request->start_time,
        ]);

        return redirect()->back()->with('success', 'Meeting session created successfully!');
    }

    /**
     * Update existing Meeting
     */
    public function updateMeeting(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'required|url|max:500',
            'duration' => 'required|string|max:50',
            'password' => 'required|string|max:100',
            'price' => 'required|string|max:50',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'thumbnail_url' => 'nullable|url|max:500',
            'start_time' => 'required|date',
        ]);

        $meeting->title = $request->title;
        $meeting->description = $request->description;
        $meeting->link = $request->link;
        $meeting->duration = $request->duration;
        $meeting->password = $request->password;
        $meeting->price = $request->price;
        $meeting->start_time = $request->start_time;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'meeting_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/meetings');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            
            // Delete old file if local
            if ($meeting->thumbnail && !Str::startsWith($meeting->thumbnail, 'http')) {
                @unlink(public_path($meeting->thumbnail));
            }
            $meeting->thumbnail = 'uploads/meetings/' . $filename;
        } elseif ($request->filled('thumbnail_url')) {
            $meeting->thumbnail = $request->thumbnail_url;
        }

        $meeting->save();

        return redirect()->back()->with('success', 'Meeting updated successfully!');
    }

    /**
     * Toggle meeting status (ongoing <-> upcoming)
     */
    public function toggleMeetingStatus(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);
        $newStatus = $request->input('status');
        
        if (!in_array($newStatus, ['ongoing', 'upcoming', 'completed'])) {
            $newStatus = ($meeting->status === 'ongoing') ? 'upcoming' : 'ongoing';
        }

        $meeting->status = $newStatus;
        $meeting->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Meeting status updated to ' . ucfirst($newStatus)
            ]);
        }

        return redirect()->back()->with('success', 'Meeting status changed to ' . ucfirst($newStatus));
    }

    /**
     * Delete Meeting
     */
    public function deleteMeeting($id)
    {
        $meeting = Meeting::findOrFail($id);

        if ($meeting->thumbnail && !Str::startsWith($meeting->thumbnail, 'http')) {
            @unlink(public_path($meeting->thumbnail));
        }

        $meeting->delete();

        return redirect()->back()->with('success', 'Meeting deleted successfully.');
    }

    /**
     * Approve Booking & Assign Code
     */
    public function approveBooking(Request $request, $id)
    {
        $request->validate([
            'assigned_code' => 'nullable|string|max:50',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $booking = Booking::with('meeting', 'user')->findOrFail($id);

        // Generate code if empty: e.g. DXB-VIP-8492
        $code = $request->assigned_code;
        if (empty(trim($code))) {
            $code = 'DXB-VIP-' . strtoupper(Str::random(4)) . '-' . rand(100, 999);
        }

        $booking->status = 'approved';
        $booking->assigned_code = $code;
        if ($request->filled('admin_notes')) {
            $booking->admin_notes = $request->admin_notes;
        }
        $booking->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking approved! Code assigned: ' . $code,
                'assigned_code' => $code,
                'status' => 'approved'
            ]);
        }

        return redirect()->back()->with('success', "Booking #{$id} approved! Access code '{$code}' assigned.");
    }

    /**
     * Reject Booking
     */
    public function rejectBooking(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'rejected';
        if ($request->filled('admin_notes')) {
            $booking->admin_notes = $request->admin_notes;
        }
        $booking->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking # ' . $id . ' has been rejected.'
            ]);
        }

        return redirect()->back()->with('success', 'Booking rejected.');
    }

    /**
     * Delete Booking
     */
    public function deleteBooking($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->screenshot_path) {
            @unlink(public_path($booking->screenshot_path));
        }

        $booking->delete();

        return redirect()->back()->with('success', 'Booking deleted successfully.');
    }
}
