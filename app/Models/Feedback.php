<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $primaryKey = 'feedback_id';
    protected $table = 'feedback';

    protected $fillable = [
        'item_id',
        'from_user_id',
        'to_user_id',
        'rating',
        'comment',
        'feedback_date',
    ];

    protected $casts = [
        'feedback_date' => 'datetime',
    ];

    /**
     * Feedback is given for a specific item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * The user who gave the feedback.
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * The user who received the feedback.
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
