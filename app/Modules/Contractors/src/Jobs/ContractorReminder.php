<?php


namespace App\Modules\Contractors\src\Jobs;


use App\Modules\Contractors\src\Mail\ContractorMail;
use App\Modules\Contractors\src\Mail\ContractorMailReminder;
use App\Modules\Contractors\src\Models\Contractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ContractorReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Contractor
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @param Contractor $user
     */
    public function __construct(Contractor $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $email = env('APP_ENV') == 'local'
            ? env('SAMPLE_EMAIL', 'daniel.prado@idrd.gov.co')
            : isset($this->user->email) ? $this->user->email : null;

        if ( isset( $email )  && filter_var( $email, FILTER_VALIDATE_EMAIL) ) {
            $mailer->to($email)->send( new ContractorMailReminder( $this->user ) );
        }
    }
}
