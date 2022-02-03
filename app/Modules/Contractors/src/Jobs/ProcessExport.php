<?php


namespace App\Modules\Contractors\src\Jobs;


use App\Jobs\NotifyUserOfCompletedExport;
use App\Models\Security\User;
use App\Modules\Contractors\src\Constants\GlobalQuery;
use App\Modules\Contractors\src\Exports\DataExport;
use App\Modules\Contractors\src\Models\ContractorView;
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

        $request = collect($this->request);
        $contractors = [];
        ContractorView::with('contracts')
            ->when($request->has('doesnt_have_arl'), function ($q) {
                return $q->whereNull('modifiable')
                    ->whereHas('contracts', function ($query) {
                        $contracts = new GlobalQuery();
                        return $query->whereIn('id', $contracts->contracts());
                    });
            })
            ->when($request->has('doesnt_have_secop'), function ($q) {
                return $q->whereHas('contracts', function ($query) {
                    $contracts = new GlobalQuery();
                    return $query->whereIn('id', $contracts->contracts('other_files_count'));
                });
            })
            ->when(
                $request->has(['start_date', 'final_date']),
                function ($query) use ($request) {
                    return $query
                        ->whereHas('contracts', function ($query) use ($request) {
                            return $query->where('start_date', '>=', $request->get('start_date'))
                                ->where('final_date', '<=', $request->get('final_date'));
                        });
                }
            )
            ->when($request->has('query'), function ($q) use ($request) {
                $data = toLower($request->get('query'));
                return $q->whereHas('contracts', function ($query) use ($data) {
                    return $query->where('contract', 'like', "%{$data}%");
                })->orWhere('name', 'like', "%{$data}%")
                    ->orWhere('id', 'like', "%{$data}%")
                    ->orWhere('surname', 'like', "%{$data}%")
                    ->orWhere('document', 'like', "%{$data}%");
            })
            ->when($request->has('doesnt_have_data'), function ($q) use ($request) {
                return $q->whereNotNull('modifiable');
            })
            ->orderBy('document', 'desc')
            ->chunk(100, function ($models) use (&$contractors) {
                foreach ($models as $model) {
                    $contractors[] = [
                        'id' => $model->id,
                        'contracts' => $model->contracts->pluck('id')->toArray()
                    ];
                }
            });
        $keys =  [
            'contractors' => collect($contractors)->pluck('id')->toArray(),
            'contracts' => collect($contractors)->pluck('contracts')->flatten()->toArray(),
        ];

        (new DataExport($keys, $this->getJobStatusId()))
            ->queue("exports/$this->name", 'local', Excel::XLSX)
            ->chain([
                new NotifyUserOfCompletedExport($this->user, $this->name, $url)
            ]);
        $this->setOutput(['file' => $this->name]);
    }
}
