<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduledEmail;
use Carbon\Carbon;

class EmailSchedulerController extends Controller
{
    public function index()
    {
        // Default time for input (IST)
        $now = Carbon::now('Asia/Kolkata')->addMinute()->format('Y-m-d\TH:i');
        return view('email-scheduler', compact('now'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipients'   => 'required|string',
            'subject'      => 'required|string',
            'body'         => 'required|string',
            'scheduled_at' => 'required'
        ]);

        // Parse recipients - can be comma-separated emails
        $recipients = array_map('trim', explode(',', $request->recipients));
        $recipients = array_filter($recipients); // Remove empty entries

        // Validate each email
        foreach ($recipients as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'error' => "Invalid email address: {$email}"
                ], 422);
            }
        }

        /**
         * IMPORTANT:
         * datetime-local → always treat as IST
         */
        $scheduledAtIST = Carbon::createFromFormat(
            'Y-m-d\TH:i',
            $request->scheduled_at,
            'Asia/Kolkata'
        );

        // Current IST time
        $nowIST = Carbon::now('Asia/Kolkata');

        if ($scheduledAtIST->lessThanOrEqualTo($nowIST)) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'error' => 'Scheduled time must be in the future (IST). Current time: ' . $nowIST->format('d-m-Y h:i A')
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', 'Scheduled time must be in the future (IST). Current time: ' . $nowIST->format('d-m-Y h:i A'));
        }

        /**
         * STORE AS STRING (IST LOCKED)
         * This avoids Laravel/DB timezone auto-conversion
         */

        ScheduledEmail::create([
            'recipient_email' => $recipients[0], // Keep for backward compatibility
            'recipients'      => $recipients,
            'subject'         => $request->subject,
            'body'            => $request->body,
            'scheduled_at'    => $scheduledAtIST->format('Y-m-d H:i:s'),
            'status'          => 'pending'
        ]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => 'Email scheduled for ' . $scheduledAtIST->format('d-m-Y h:i A') . ' (IST)'
            ]);
        }

        return back()->with(
            'success',
            'Email scheduled for ' . $scheduledAtIST->format('d-m-Y h:i A') . ' (IST)'
        );
    }

}
