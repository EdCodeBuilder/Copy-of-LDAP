<?php


namespace App\Modules\CitizenPortal\src\Jobs;


use App\Modules\CitizenPortal\src\Mail\NotificationFileMail;
use App\Modules\CitizenPortal\src\Mail\NotificationMail;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use App\Modules\CitizenPortal\src\Models\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConfirmStatusFileCitizen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ProfileView
     */
    private $user;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var string
     */
    private $observation;
    /**
     * @var File
     */
    private $file;

    /**
     * Create a new job instance.
     *
     * @param ProfileView $profile
     * @param Status $status
     * @param File $file
     * @param $observation
     */
    public function __construct(ProfileView $profile, Status $status, File $file, $observation)
    {
        $this->user = $profile;
        $this->status = $status;
        $this->file = $file;
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
        if ( isset( $email )  && filter_var( $email, FILTER_VALIDATE_EMAIL) ) {
            $mailer->to($email)->send( new NotificationFileMail( $this->user, $this->status, $this->file, $this->observation) );
        }
    }
}
