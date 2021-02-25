<?php


namespace App\Modules\Contractors\src\Jobs;


use App\Modules\Contractors\src\Mail\ContractorMail;
use App\Modules\Contractors\src\Models\Contractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConfirmContractor implements ShouldQueue
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
        if ( isset( $this->user->email )  && filter_var( $this->user->email, FILTER_VALIDATE_EMAIL) ) {
            $mailer->to($this->user->email)->send( new ContractorMail( $this->user ) );
        }
    }
}
