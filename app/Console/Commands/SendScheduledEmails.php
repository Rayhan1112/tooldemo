<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduledEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Throwable;

class SendScheduledEmails extends Command
{
    protected $signature = 'emails:send-scheduled';
    protected $description = 'Send scheduled emails';

    public function handle()
    {
        $now = Carbon::now('Asia/Kolkata');

        Log::info('Scheduler running at IST: ' . $now->format('Y-m-d H:i:s'));

        // Get all pending emails and filter by time in IST
        $pendingEmails = ScheduledEmail::where('status', 'pending')->get();
        $emails = $pendingEmails->filter(function ($email) use ($now) {
            $emailTime = Carbon::createFromFormat('Y-m-d H:i:s', $email->scheduled_at, 'Asia/Kolkata');
            return $emailTime->lte($now);
        });

        Log::info('Emails found: ' . $emails->count());

        foreach ($emails as $email) {
            try {
                // Get recipients from JSON field, fallback to single recipient_email
                $recipients = $email->recipients ?: [$email->recipient_email];

                // Send emails directly
                $successCount = 0;
                $failCount = 0;

                foreach ($recipients as $recipient) {
                    try {
                        Mail::html(nl2br(e($email->body)), function ($message) use ($recipient, $email) {
                            $message->to($recipient)
                                ->from(config('mail.from.address'), config('mail.from.name'))
                                ->subject($email->subject);
                        });

                        Log::info('Email sent to ' . $recipient);
                        $successCount++;
                    } catch (\Throwable $e) {
                        Log::error('Email failed to ' . $recipient, ['error' => $e->getMessage()]);
                        $failCount++;
                    }
                }

                // Update status
                if ($failCount == 0) {
                    $email->update(['status' => 'sent']);
                } elseif ($successCount == 0) {
                    $email->update(['status' => 'failed']);
                } else {
                    $email->update(['status' => 'sent']); // Partial success
                }

                Log::info('Emails processed for ' . count($recipients) . ' recipients: ' . $successCount . ' sent, ' . $failCount . ' failed');
            } catch (\Throwable $e) {
                Log::error('Failed to process email for ID ' . $email->id, ['error' => $e->getMessage()]);
                $email->update(['status' => 'failed']);
            }
        }

        return Command::SUCCESS;
    }
}
