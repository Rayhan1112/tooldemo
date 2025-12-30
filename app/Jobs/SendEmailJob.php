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

    protected $recipient;
    protected $subject;
    protected $body;
    protected $emailId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recipient, $subject, $body, $emailId = null)
    {
        $this->recipient = $recipient;
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
        try {
            Mail::html(nl2br(e($this->body)), function ($message) {
                $message->to($this->recipient)
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($this->subject);
            });

            Log::info('Email sent to ' . $this->recipient);

            if ($this->emailId) {
                ScheduledEmail::where('id', $this->emailId)->update(['status' => 'sent']);
            }
        } catch (\Throwable $e) {
            Log::error('Email failed to ' . $this->recipient, ['error' => $e->getMessage()]);

            if ($this->emailId) {
                ScheduledEmail::where('id', $this->emailId)->update(['status' => 'failed']);
            }

            throw $e;
        }
    }
}
