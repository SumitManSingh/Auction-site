<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load(['sender','receiver']);
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('private-chat.' . $this->message->receiver_id);
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'message_id' => $this->message->message_id,
                'sender' => [
                    'id' => $this->message->sender?->user_id,
                    'username' => $this->message->sender?->username,
                ],
                'receiver_id' => $this->message->receiver_id,
                'content' => $this->message->content,
                'timestamp' => optional($this->message->timestamp)->toDateTimeString(),
                'is_read' => (bool) $this->message->is_read,
            ],
        ];
    }
}
