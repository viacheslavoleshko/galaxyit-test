<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\UserDeletedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserDeletedNotification implements ShouldQueue
{
    use Queueable;

    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        info('Processing job for user ID: ' . $this->user->id);

        $deletedUser = User::withTrashed()->where('id', $this->user->id)->first();

        if ($deletedUser->trashed()) {
            info('Sending user deleted notification email to: ' . $deletedUser->firstname . ' ' . $deletedUser->lastname);
            Mail::to($deletedUser->email)->send(new UserDeletedMail($deletedUser));
        } else {
            info('Abort sending notification email, user was restored: ' . $deletedUser->firstname . ' ' . $deletedUser->lastname);
        }
    }

    public function uniqueId()
    {
        return $this->user->id;
    }
}
