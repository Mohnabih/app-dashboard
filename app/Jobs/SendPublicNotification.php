<?php

namespace App\Jobs;

use App\Enums\UserRoleEnum;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPublicNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notify_id;

    /**
     * Create a new job instance.
     * @param $notify_id
     *
     * @return void
     */
    public function __construct($notify_id)
    {
        $this->notify_id = $notify_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($notify = Notification::find($this->notify_id)) {
            $clients = User::where('role', UserRoleEnum::CLIENT)->get();

            // Link between notification and the users
            $notify->user()->attach($clients->pluck('id')->toArray());

            // Sending Push Notifications by Using Firebase Cloud Messaging.
            foreach ($clients as $user) {
                if ($user->fcm_token != null)
                    $user->notify(new SendNotification($notify));
                else continue;
            }
        }
    }
}
