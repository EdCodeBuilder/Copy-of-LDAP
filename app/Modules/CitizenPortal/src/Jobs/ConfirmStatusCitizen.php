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
     * @var string
     */
    private $observation;

    /**
     * Create a new job instance.
     *
     * @param ProfileView $profile
     * @param $observation
     */
    public function __construct(ProfileView $profile, $observation)
    {
        $this->user = $profile;
        $this->observation = $observation;
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
             : explode(',', env('SAMPLE_CITIZEN_PORTAL_EMAIL', 'daniel.prado@idrd.gov.co'));
        }
        if ( $email  && filter_var( $email, FILTER_VALIDATE_EMAIL) ) {
            $mailer->to($email)
                ->send( new NotificationMail( $this->user, $this->observation ) );
        }
    }
}
