<?php


namespace App\Modules\CitizenPortal\src\Jobs;


use App\Modules\CitizenPortal\src\Mail\NotificationMail;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConfirmStatusCitizen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Profile
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @param ProfileView $profile
     */
    public function __construct(ProfileView $profile)
    {
        $this->user = $profile;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $email = isset( $this->user->user->email ) ? (string) $this->user->user->email : null;
        if (env('APP_ENV') != 'production') {
         $email = isset(auth('api')->user()->email)
             ? auth('api')->user()->email
             : env('SAMPLE_EMAIL');
        }
        if ( $email  && filter_var( $email, FILTER_VALIDATE_EMAIL) ) {
            $mailer->to($email)->send( new NotificationMail( $this->user ) );
        }
    }
}
