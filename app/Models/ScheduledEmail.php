<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledEmail extends Model
{
    protected $fillable = [
        'recipient_email',
        'recipient_name',
        'subject',
        'body',
        'scheduled_at',
        'status'
    ]; 
}
