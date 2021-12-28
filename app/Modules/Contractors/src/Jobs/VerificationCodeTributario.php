<?php


namespace App\Modules\Contractors\src\Jobs;


use App\Modules\Contractors\src\Mail\ContractorLegalMail;
use App\Modules\Contractors\src\Mail\ContractorMail;
use App\Modules\Contractors\src\Mail\ContractorSendArlMail;
use App\Modules\Contractors\src\Mail\ContractorUpdateMail;
use App\Modules\Contractors\src\Mail\TributarioMail;
use App\Modules\Contractors\src\Models\Certification;
use App\Modules\Contractors\src\Models\Contract;
use App\Modules\Contractors\src\Models\Contractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerificationCodeTributario implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Contractor
     */
    private $user;

    /**
     * @var Certification
     */
    private $certification;

    /**
     * Create a new job instance.
     *
     * @param Contractor $user
     * @param Certification $certification
     */
    public function __construct(Contractor $user, Certification $certification)
    {
        $this->user = $user;
        $this->certification = $certification;
        if (!isset($this->certification->code)) {
            $digits = 6;
            $code = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $this->certification->code = $code;
            $this->certification->save();
        }
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

        if ( isset($email)  && filter_var( $email, FILTER_VALIDATE_EMAIL) ) {
            $mailer->to($email)->send( new TributarioMail( $this->user, $this->certification ) );
        }
    }
}
