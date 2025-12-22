<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainExtension extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'domain_id',
        'extension',
        'price'
    ];
    
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
