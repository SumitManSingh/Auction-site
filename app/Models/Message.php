<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id';
    protected $table = 'messages';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'content',
        'timestamp',
        'is_read',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'is_read' => 'boolean',
    ];

    /**
     * A message is sent by a user.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * A message is received by a user.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
