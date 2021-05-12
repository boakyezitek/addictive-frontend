<?php

namespace App\Events;

use App\Models\AudioBook;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AddMultipleChapters
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AudioBook $audiobook;

    public string $filename;

    public string $original_filename;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AudioBook $audiobook, string $filename, string $original_filename)
    {
        $this->audiobook = $audiobook;
        $this->filename = $filename;
        $this->original_filename = $original_filename;
    }
}
