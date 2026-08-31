<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Submission;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $todayLink = Setting::get('today_link');
        $tomorrowLink = Setting::get('tomorrow_link');
        $supportPhone = Setting::get('support_phone', '+971 4 301 7777'); // default Dubai Tourism number
        
        $qrPath = Setting::get('payment_qr');
        // Fallback to a placeholder if not set
        $qrUrl = $qrPath ? asset($qrPath) : null;

        return view('welcome', compact('todayLink', 'tomorrowLink', 'supportPhone', 'qrUrl'));
    }

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

    public function uploadScreenshot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'submission_id' => 'required|exists:submissions,id',
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB
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
            
            // Store directly in public folder for maximum compatibility with shared hosting
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
