<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'columns',
        'prompts',
        'max_tokens',
        'csv_path',
        'user_id',
        'batch_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function content()
    {
        return $this->hasMany(Content::class);
    }
}
