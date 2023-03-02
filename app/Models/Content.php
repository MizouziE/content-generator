<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'content_template_id'
    ];

    public function contentTemplate()
    {
        return $this->belongsTo(ContentTemplate::class);
    }
}
