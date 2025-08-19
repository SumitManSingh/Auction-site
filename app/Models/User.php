<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // The primary key for the model.
    protected $primaryKey = 'user_id';

    // The table associated with the model.
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'two_factor_enabled',
        'role',
        'contact_info',
        'email_otp',
        'email_otp_expires_at',
        'email_verified_at',
        'password_reset_token',
        'password_reset_expires_at', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
        'email_otp',
        'email_otp_expires_at',
        'password_reset_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'registration_date' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'email_verified_at' => 'datetime',
        'email_otp_expires_at' => 'datetime',
        'password_reset_expires_at' => 'datetime',
    ];

    /**
     * Get the password for the user.
     * This method is crucial for Laravel's authentication to use 'password_hash'.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    //
    // Eloquent Relationships
    //

    /**
     * A user (seller) can list many items.
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'seller_id');
    }

    /**
     * A user (bidder) can place many bids.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class, 'bidder_id');
    }

    /**
     * A user can give many feedback entries.
     */
    public function sentFeedback()
    {
        return $this->hasMany(Feedback::class, 'from_user_id');
    }

    /**
     * A user can receive many feedback entries.
     */
    public function receivedFeedback()
    {
        return $this->hasMany(Feedback::class, 'to_user_id');
    }

    /**
     * A user can send many messages.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * A user can receive many messages.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}