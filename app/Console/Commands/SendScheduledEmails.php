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
            $emailTime = Carbon::parse($email->scheduled_at)->setTimezone('Asia/Kolkata');
            return $emailTime->lte($now);
        });

        Log::info('Emails found: ' . $emails->count());

        foreach ($emails as $email) {
            try {
                Log::info('Attempting email to ' . $email->recipient_email);

                Mail::html(nl2br(e($email->body)), function ($message) use ($email) {
                    $message->to($email->recipient_email)
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->subject($email->subject);
                });

                $email->update(['status' => 'sent']);

                Log::info('Email SENT to ' . $email->recipient_email);

            } catch (Throwable $e) {

                $email->update(['status' => 'failed']);

                Log::error('Email FAILED', [
                    'email' => $email->recipient_email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return Command::SUCCESS;
    }
}
