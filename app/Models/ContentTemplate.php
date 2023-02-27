<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'prompts',
        'csv_path',
        'columns',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
