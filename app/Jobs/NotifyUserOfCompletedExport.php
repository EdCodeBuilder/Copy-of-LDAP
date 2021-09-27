<?php


namespace App\Jobs;


use App\Models\Security\User;
use App\Notifications\ExportReady;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUserOfCompletedExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $file;

    /**
     * @var $url
     */
    private $url;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $file
     * @param $url
     */
    public function __construct(User $user, $file, $url)
    {
        $this->user = $user;
        $this->file = $file;
        $this->url = $url;
    }

    public function handle()
    {
        $this->user->notify(new ExportReady($this->file, $this->url));
    }
}
