<?php

namespace App\Listeners;

use App\Events\FileDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class FileDeletedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FileDeleted  $event
     * @return void
     */
    public function handle(FileDeleted $event)
    {
        Storage::delete($event->file->path);
    }
}
