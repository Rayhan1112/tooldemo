<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class ScheduledMail extends Mailable
{
    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject($this->email->subject)
                    ->html($this->email->body);
    }
}
?>