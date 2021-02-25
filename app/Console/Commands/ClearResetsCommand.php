<?php

namespace App\Console\Commands;

use App\Helpers\GlpiTicket;
use App\Models\Security\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ClearResetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clear-resets-glpi {name? : The name of the password broker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush expired password reset tokens and GLPI Tickets';

    /**
     * The number of seconds a token should last.
     *
     * @var int
     */
    protected $expires;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->expires = 60 * 60;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $expiredAt = Carbon::now()->subSeconds($this->expires);

        DB::table(config('auth.passwords.users.table'))
            ->where('created_at', '<', $expiredAt)
            ->orderBy('created_at')->chunk(100, function ($tokens) {
            foreach ($tokens as $token) {
                $user = User::where( "email", $token->email )->first();
                if ( isset($user->id) && $token->ticket_id ) {
                    $glpi = new GlpiTicket( $user, $token->email );
                    $glpi->inactivity( $token->ticket_id, $token->created_at,  now()->format('Y-m-d H:i:s'));
                }
            }
        });

        DB::table( config('auth.passwords.users.table') )
            ->where('created_at', '<', $expiredAt)->delete();

        $this->info('Expired reset tokens cleared and GLPI tickets closed');
    }
}
