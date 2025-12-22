<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'character_length'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function extensions()
    {
        return $this->hasMany(DomainExtension::class);
    }
}
