<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('private-chat.{userId}', function ($user, $userId) {
    // $user->getKey() returns custom PK (user_id) correctly
    return (int) $user->getKey() === (int) $userId;
});
