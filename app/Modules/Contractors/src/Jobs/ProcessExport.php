<?php


namespace App\Modules\Contractors\src\Jobs;


use App\Jobs\NotifyUserOfCompletedExport;
use App\Models\Security\User;
use App\Modules\Contractors\src\Exports\DataExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Imtigger\LaravelJobStatus\JobStatus;
use Imtigger\LaravelJobStatus\Trackable;
use Maatwebsite\Excel\Excel;

class ProcessExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    /**
     * @var array
     */
    private $request;

    /**
     * @var array
     */
    private $params;

    /**
     * @var string
     */
    private $name;

    /**
     * @var User
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @param array $request
     * @param User $user
     * @param array $params
     */
    public function __construct(array $request, User $user, array $params = [])
    {
        $this->name = "PORTAL-CONTRATISTA-".random_img_name().".xlsx";
        $this->params = array_merge(['key' => $this->name], $params);
        $this->request = $request;
        $this->prepareStatus($this->params);
        $this->user = $user;
        $this->setInput([
            'request' => $this->request,
            'user' => $this->user
        ]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->update(
            [
                'queue' => 'excel-contractor-portal',
                'status' => JobStatus::STATUS_EXECUTING,
                'finished_at' => null,
            ]
        );
        $path = env('APP_ENV') == 'local'
            ? env('APP_PATH_DEV')
            : env('APP_PATH_PROD');
        $url = "https://sim.idrd.gov.co/{$path}/es/login";
        (new DataExport($this->request, $this->getJobStatusId()))
            ->queue("exports/$this->name", 'local', Excel::XLSX)
            ->chain([
                new NotifyUserOfCompletedExport($this->user, $this->name, $url)
            ]);
        $this->setOutput(['file' => $this->name]);
    }
}
