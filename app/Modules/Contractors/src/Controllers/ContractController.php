<?php


namespace App\Modules\Contractors\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Contractors\src\Jobs\ConfirmContractor;
use App\Modules\Contractors\src\Models\Contract;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Contractors\src\Request\StoreLawyerContractRequest;
use App\Modules\Contractors\src\Request\StoreLawyerRequest;
use App\Modules\Contractors\src\Request\UpdateContractorRequest;
use App\Modules\Contractors\src\Request\UpdateContractRequest;
use App\Modules\Contractors\src\Resources\ContractorResource;
use App\Modules\Contractors\src\Resources\ContractResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Contractor $contractor
     * @return JsonResponse
     */
    public function index(Contractor $contractor)
    {
        return $this->success_response(
            ContractResource::collection($contractor->contracts),
            Response::HTTP_OK,
            [
                'headers'   =>  ContractResource::headers(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLawyerRequest $request
     * @param Contractor $contractor
     * @return JsonResponse
     */
        public function store(StoreLawyerContractRequest $request, Contractor $contractor)
    {
        try {
            DB::connection('mysql_contractors')->beginTransaction();
            $contractor->modifiable = now()->format('Y-m-d H:i:s');
            $contractor->saveOrFail();
            $contract_number = str_pad($request->get('contract'), 4, '0', STR_PAD_LEFT);
            $contract = toUpper("IDRD-CTO-{$contract_number}-{$request->get('contract_year')}");
            $contractor->contracts()
                ->create(array_merge(
                    $request->validated(),
                    ['contract' => $contract],
                    ['lawyer_id' => auth()->user()->id]
                ));
            $this->dispatch(new ConfirmContractor($contractor));
            DB::connection('mysql_contractors')->commit();
            return $this->success_message(__('validation.handler.success'), Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            DB::connection('mysql_contractors')->rollBack();
            return $this->error_response(
                __('validation.handler.unexpected_failure'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateContractRequest $request
     * @param Contractor $contractor
     * @return JsonResponse
     */
    public function update(UpdateContractRequest $request, Contractor $contractor)
    {
        try {
            DB::connection('mysql_contractors')->beginTransaction();
            $contract = $contractor->contracts()
                            ->where('id', $request->get('contract_id'))
                            ->first();
            $contract->update($request->validated());
            DB::connection('mysql_contractors')->commit();
            return $this->success_message(__('validation.handler.updated'));
        } catch (\Throwable $e) {
            DB::connection('mysql_contractors')->rollBack();
            return $this->error_response(
                __('validation.handler.unexpected_failure'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }
    }
}
