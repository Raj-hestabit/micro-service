<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class AssignTeacher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $user;
    private $base_url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->base_url = 'http://notification.local/api/';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data2 = [
            'id'    => $this->user->id,
            'name'  => $this->user->name,
            'email' => $this->user->email
        ];
        Http::post($this->base_url.'teacher-assign', $data2);
    }
}
