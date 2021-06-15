<?php

namespace App\Console\Commands;

use App\Modules\Contractors\src\Jobs\ConfirmContractor;
use App\Modules\Contractors\src\Jobs\ContractorReminder;
use App\Modules\Contractors\src\Models\Contractor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendContractorReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contractor:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders to contractors after 1 day from available to modify data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = 0;
        Contractor::whereNotNull('modifiable')->chunk(100, function ($contractors) use ($count) {
            foreach ($contractors as $contractor) {
                $date = Carbon::parse( $contractor->modifiable );
                if ($date->diffInDays( now(), false ) >= 1 ) {
                    dispatch( new ContractorReminder($contractor) );
                    $count++;
                }
            }
        });
        $this->info("$count reminders was sent to contractors.");
    }
}
