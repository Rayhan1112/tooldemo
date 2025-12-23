<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduledEmail;
use Carbon\Carbon;

class ScheduledEmailController extends Controller
{
    public function status()
    {
        $emails = ScheduledEmail::orderBy('scheduled_at', 'desc')->get();
        $currentIST = Carbon::now('Asia/Kolkata');
        
        $output = "<h2>Scheduled Emails Status (IST)</h2>";
        $output .= "<p><strong>Current IST:</strong> <span style='color: red; font-weight: bold;'>" . $currentIST->format('Y-m-d h:i:s A') . "</span></p>";
        $output .= "<hr>";
        
        if ($emails->isEmpty()) {
            $output .= "<p>No scheduled emails found.</p>";
        } else {
            foreach ($emails as $email) {
                $scheduledIST = Carbon::parse($email->scheduled_at)->setTimezone('Asia/Kolkata');
                $output .= "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
                $output .= "<p><strong>ID:</strong> " . $email->id . "</p>";
                $output .= "<p><strong>Recipient:</strong> " . $email->recipient_email . "</p>";
                $output .= "<p><strong>Subject:</strong> " . $email->subject . "</p>";
                $output .= "<p><strong>Scheduled Time (IST):</strong> <span style='color: red;'>" . $scheduledIST->format('Y-m-d h:i:s A') . "</span></p>";
                $output .= "<p><strong>Status:</strong> " . $email->status . "</p>";
                
                if ($email->status === 'pending') {
                    $emailScheduledTime = Carbon::parse($email->scheduled_at)->setTimezone('Asia/Kolkata');
                    if ($emailScheduledTime <= $currentIST) {
                        $output .= "<p style='color: red;'><strong>OVERDUE - Should have been sent!</strong></p>";
                    } else {
                        $minutesRemaining = $currentIST->diffInMinutes($scheduledIST);
                        $output .= "<p><strong>Time remaining:</strong> " . $minutesRemaining . " minutes</p>";
                    }
                }
                
                $output .= "</div>";
            }
        }
        
        return response($output);
    }
    
    public function getEmails()
    {
        $queuedEmails = ScheduledEmail::where('status', 'pending')
            ->orderBy('scheduled_at', 'asc')
            ->get()
            ->map(function ($email) {
                $scheduledIST = Carbon::parse($email->scheduled_at)->setTimezone('Asia/Kolkata');
                return [
                    'id' => $email->id,
                    'recipient_email' => $email->recipient_email,
                    'subject' => $email->subject,
                    'scheduled_at_ist' => $scheduledIST->format('Y-m-d h:i:s A'),
                    'status' => $email->status,
                    'is_overdue' => $email->scheduled_at <= Carbon::now('Asia/Kolkata')
                ];
            });

        $sentEmails = ScheduledEmail::where('status', 'sent')
            ->orderBy('scheduled_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($email) {
                $scheduledIST = Carbon::parse($email->scheduled_at)->setTimezone('Asia/Kolkata');
                return [
                    'id' => $email->id,
                    'recipient_email' => $email->recipient_email,
                    'subject' => $email->subject,
                    'scheduled_at_ist' => $scheduledIST->format('Y-m-d h:i:s A'),
                    'status' => $email->status
                ];
            });

        return response()->json([
            'queued' => $queuedEmails,
            'sent' => $sentEmails,
            'current_ist' => Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s A')
        ]);
    }
}