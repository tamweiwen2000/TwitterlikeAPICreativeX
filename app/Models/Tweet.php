<?php

namespace App\Models;

use App\Models\User;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tweet extends Model
{
    use HasFactory;
    protected $fillable = [
        'tweet_body',
        'user_id',
    ];

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
