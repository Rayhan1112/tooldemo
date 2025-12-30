<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\ScheduledEmail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipients;
    protected $subject;
    protected $body;
    protected $emailId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recipients, $subject, $body, $emailId = null)
    {
        $this->recipients = is_array($recipients) ? $recipients : [$recipients];
        $this->subject = $subject;
        $this->body = $body;
        $this->emailId = $emailId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('SendEmailJob started for ' . count($this->recipients) . ' recipients');
        $successCount = 0;
        $failCount = 0;

        foreach ($this->recipients as $recipient) {
            try {
                Mail::html(nl2br(e($this->body)), function ($message) use ($recipient) {
                    $message->to($recipient)
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->subject($this->subject);
                });

                Log::info('Email sent to ' . $recipient);
                $successCount++;
            } catch (\Throwable $e) {
                Log::error('Email failed to ' . $recipient, ['error' => $e->getMessage()]);
                $failCount++;
            }
        }

        if ($this->emailId) {
            if ($failCount == 0) {
                ScheduledEmail::where('id', $this->emailId)->update(['status' => 'sent']);
            } elseif ($successCount == 0) {
                ScheduledEmail::where('id', $this->emailId)->update(['status' => 'failed']);
            } else {
                // Partial success, maybe add a new status or keep as sent
                ScheduledEmail::where('id', $this->emailId)->update(['status' => 'sent']);
            }
        }

        if ($failCount > 0) {
            throw new \Exception("Failed to send to {$failCount} recipients");
        }
    }
}
