<?php

namespace App\Models;

use App\Models\Tweet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tweet_id',
        'filename',
        'mime_type',
        'size',
    ];

    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }
}
