<?php


namespace App\Modules\Contractors\src\Jobs;


use App\Modules\Contractors\src\Mail\ContractorLegalMail;
use App\Modules\Contractors\src\Mail\ContractorMail;
use App\Modules\Contractors\src\Mail\ContractorUpdateMail;
use App\Modules\Contractors\src\Models\Contract;
use App\Modules\Contractors\src\Models\Contractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConfirmArlContractor implements ShouldQueue
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
        if ( env('LEGAL_EMAIL_NOTIFICATION')  && filter_var( env('LEGAL_EMAIL_NOTIFICATION'), FILTER_VALIDATE_EMAIL) ) {
            $mailer->to(env('LEGAL_EMAIL_NOTIFICATION'))->send( new ContractorLegalMail( $this->user, $this->contract ) );
        }
    }
}
