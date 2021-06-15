<?php


namespace App\Modules\Contractors\src\Jobs;


use App\Modules\Contractors\src\Mail\ContractorLegalMail;
use App\Modules\Contractors\src\Mail\ContractorMail;
use App\Modules\Contractors\src\Mail\ContractorSendArlMail;
use App\Modules\Contractors\src\Mail\ContractorUpdateMail;
use App\Modules\Contractors\src\Models\Contract;
use App\Modules\Contractors\src\Models\Contractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendArlContractor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Contractor
     */
    private $user;

    /**
     * @var Contract
     */
    private $contract;

    /**
     * Create a new job instance.
     *
     * @param Contractor $user
     * @param Contract $contract
     */
    public function __construct(Contractor $user, Contract $contract)
    {
        $this->user = $user;
        $this->contract = $contract;
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
            $mailer->to($email)->send( new ContractorSendArlMail( $this->user, $this->contract ) );
        }
    }
}
