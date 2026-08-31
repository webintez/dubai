<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Submission;
use Illuminate\Support\Facades\Hash;

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

        // Allow both plain comparison (from env direct value) and fallback
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
        // Get filter options if any
        $statusFilter = $request->query('status');
        $dayFilter = $request->query('day');

        $query = Submission::query();

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($dayFilter) {
            $query->where('day', $dayFilter);
        }

        $submissions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Fetch settings
        $settings = [
            'today_link' => Setting::get('today_link', ''),
            'tomorrow_link' => Setting::get('tomorrow_link', ''),
            'support_phone' => Setting::get('support_phone', '+971 4 301 7777'),
            'payment_qr' => Setting::get('payment_qr', ''),
        ];

        return view('admin.dashboard', compact('submissions', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'today_link' => 'nullable|url|max:255',
            'tomorrow_link' => 'nullable|url|max:255',
            'support_phone' => 'required|string|max:50',
            'payment_qr' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $submission = Submission::findOrFail($id);
        $submission->status = $request->status;
        $submission->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated to ' . ucfirst($request->status)
        ]);
    }

    public function deleteSubmission($id)
    {
        $submission = Submission::findOrFail($id);
        
        // Remove screenshot if it exists
        if ($submission->screenshot_path) {
            $path = public_path($submission->screenshot_path);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        
        $submission->delete();

        return redirect()->back()->with('success', 'Submission deleted successfully.');
    }
}
