<?php

namespace App\Listeners;

use App\Events\AssignTeacherToStudentEvent;
use App\Notifications\AssignTeacherToStudentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class AssignTeacherToStudentListener
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
     * @param  \App\Events\AssignTeacherToStudentEvent  $event
     * @return void
     */
    public function handle(AssignTeacherToStudentEvent $event)
    {
        Notification::send($event->user, new AssignTeacherToStudentNotification($event));
    }
}
