<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Meeting;
use App\Models\Booking;
use App\Models\User;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $allMeetings = Meeting::orderBy('start_time', 'asc')->get();
        $ongoingMeetings = $allMeetings->filter(fn($m) => $m->isLive())->values();
        $upcomingMeetings = $allMeetings->filter(fn($m) => $m->isUpcoming())->values();
        $pastMeetings = $allMeetings->filter(fn($m) => $m->isPast())->values();

        $todayMeetings = $allMeetings->filter(fn($m) => !$m->isPast() && $m->start_time && $m->start_time->isToday())->values();
        $tomorrowMeetings = $allMeetings->filter(fn($m) => $m->start_time && $m->start_time->isTomorrow())->values();

        $todayCount = $todayMeetings->count();
        $tomorrowCount = $tomorrowMeetings->count();

        $supportPhone = Setting::get('support_phone', '+971 4 301 7777');
        $todayLink = Setting::get('today_link');
        $tomorrowLink = Setting::get('tomorrow_link');

        $qrPath = Setting::get('payment_qr');
        $qrUrl = $qrPath ? asset($qrPath) : null;

        return view('welcome', compact(
            'allMeetings',
            'ongoingMeetings',
            'upcomingMeetings',
            'pastMeetings',
            'todayMeetings',
            'tomorrowMeetings',
            'todayCount',
            'tomorrowCount',
            'supportPhone',
            'todayLink',
            'tomorrowLink',
            'qrUrl'
        ));
    }

    /**
     * Handle Meeting Booking Submission
     */
    public function bookMeeting(Request $request)
    {
        $rules = [
            'meeting_id' => 'required|exists:meetings,id',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ];

        // If user is guest, also require registration fields so they get a free account instantly
        if (!Auth::check()) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255|unique:users,email';
            $rules['phone'] = 'required|string|max:25';
            $rules['password'] = 'required|string|min:6';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle user (authenticated or new free registration)
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);
            Auth::login($user);
        }

        // Upload payment screenshot
        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/screenshots');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $screenshotPath = 'uploads/screenshots/' . $filename;
        }

        // Create booking
        $booking = Booking::create([
            'user_id' => $user->id,
            'meeting_id' => $request->meeting_id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'screenshot_path' => $screenshotPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your payment proof has been submitted! Our concierge will verify it and assign your access code shortly.',
            'booking_id' => $booking->id,
            'redirect' => route('user.bookings')
        ]);
    }

    /**
     * Legacy registration support if needed
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'day' => 'required|in:today,tomorrow',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $submission = Submission::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'day' => $request->day,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'submission_id' => $submission->id
        ]);
    }

    /**
     * Legacy screenshot upload support if needed
     */
    public function uploadScreenshot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'submission_id' => 'required|exists:submissions,id',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $submission = Submission::findOrFail($request->submission_id);

        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/screenshots');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $submission->screenshot_path = 'uploads/screenshots/' . $filename;
            $submission->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Your submission has been received. We will verify your payment shortly!'
        ]);
    }
}
