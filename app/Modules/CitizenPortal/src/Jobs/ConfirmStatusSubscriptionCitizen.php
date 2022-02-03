<?php


namespace App\Modules\CitizenPortal\src\Jobs;


use App\Modules\CitizenPortal\src\Mail\NotificationSubscriptionMail;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use App\Modules\CitizenPortal\src\Models\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConfirmStatusSubscriptionCitizen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Profile
     */
    private $user;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var string
     */
    private $test_mail;

    /**
     * @var string
     */
    private $observation;

    /**
     * Create a new job instance.
     *
     * @param ProfileView $profile
     * @param Status $status
     * @param $observation
     * @param null $test_mail
     */
    public function __construct(ProfileView $profile, Status $status, $observation, $test_mail = null)
    {
        $this->user = $profile;
        $this->status = $status;
        $this->observation = $observation;
        $this->test_mail = $test_mail;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $email = isset( $this->user->email ) ? (string) $this->user->email : null;
        if (env('APP_ENV') != 'production') {
            $email = $this->test_mail
                ?? explode(',', env('SAMPLE_CITIZEN_PORTAL_EMAIL', 'daniel.prado@idrd.gov.co'));
        }
        if ( $email  && filter_var( $email, FILTER_VALIDATE_EMAIL) ) {
            $mailer->to($email)->send( new NotificationSubscriptionMail( $this->user, $this->status, $this->observation ) );
        }
    }
}
