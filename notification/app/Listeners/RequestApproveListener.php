<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\RequestApproveNotification;
use Illuminate\Support\Facades\Notification;

class RequestApproveListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // dd('testtstt');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Notification::send($event->user, new RequestApproveNotification($event));
    }
}
