<?php


namespace App\Modules\Contractors\src\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Security\User;
use App\Modules\Contractors\src\Constants\Roles;
use App\Modules\Contractors\src\Exports\ContractorsExport;
use App\Modules\Contractors\src\Jobs\ConfirmContractor;
use App\Modules\Contractors\src\Jobs\ConfirmUpdateContractor;
use App\Modules\Contractors\src\Models\Contract;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Contractors\src\Models\ContractType;
use App\Modules\Contractors\src\Models\File;
use App\Modules\Contractors\src\Notifications\ArlNotification;
use App\Modules\Contractors\src\Request\FinderRequest;
use App\Modules\Contractors\src\Request\StoreLawyerRequest;
use App\Modules\Contractors\src\Request\UpdateContractorLawyerRequest;
use App\Modules\Contractors\src\Request\UpdateContractorRequest;
use App\Modules\Contractors\src\Request\UpdateThirdPartyRequest;
use App\Modules\Contractors\src\Resources\ContractorResource;
use App\Modules\Contractors\src\Resources\UserContractorResource;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ContractorController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function counter()
    {
        return $this->success_message([
            'total'  => Contractor::count(),
            'arl'    =>  Contractor::query()->whereHas('contracts', function ($query) {
                            return $query->whereHas('files', function ($query) {
                                return $query->where('file_type_id', 1);
                            });
                        })->count(),
            'secop' =>  Contractor::query()->whereHas('contracts', function ($query) {
                            return $query->whereHas('files', function ($query) {
                                return $query->where('file_type_id', 2);
                            });
                        })->count(),
            'users' =>  Contractor::whereNotNull('modifiable')->count(),
        ]);
    }

    public function stats()
    {
        return $this->success_message([
            'types' => ContractType::withCount('contracts')->get(),
            'certified' => [
                'arl'   => Contract::query()
                    ->whereHas('files', function ($q) {
                        return $q->where('file_type_id', 1);
                    })
                    ->where('contract_type_id', '!=', 3)
                    ->count(),
                'not_arl'   => Contract::query()
                    ->whereKeyNot(
                        File::query()->where('file_type_id', 1)->get()->pluck('contract_id')->toArray()
                    )
                    ->where('contract_type_id', '!=', 3)
                    ->count()
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
       $contractors = $this->query($request, Contractor::query());
       $contractors = $contractors->with('contracts')
                                  ->latest()
                                  ->paginate($this->per_page);
        return $this->success_response(
            ContractorResource::collection($contractors),
            Response::HTTP_OK,
            [
                'headers'   =>  ContractorResource::headers(),
                'expanded'  =>  ContractorResource::additionalData(),
            ]
        );
    }

    /**
     * @param Request $request
     * @param Builder $builder
     * @return BuildsQueries|Builder|mixed
     */
    public function query(Request $request, Builder $builder)
    {
        $is_hiring_and_not_admin = !auth()->user()->isAll( Roles::ROLE_ADMIN, Roles::ROLE_HIRING );
        $is_legal_and_not_admin = !auth()->user()->isAll( Roles::ROLE_ADMIN, Roles::ROLE_LEGAL );
        return $builder->when($request->has('has_arl'), function ($q) {
                    return $q->whereHas('contracts', function ($query) {
                        return $query->whereHas('files', function ($query) {
                            return $query->where('file_type_id', 1);
                        });
                    });
                })->when($request->has('has_secop'), function ($q) {
                    return $q->whereHas('contracts', function ($query) {
                        return $query->whereHas('files', function ($query) {
                            return $query->where('file_type_id', 2);
                        });
                    });
                })->when($request->has('query'), function ($q) use ($request) {
                    $data = toLower($request->get('query'));
                    return $q->whereHas('contracts', function ($query) use ($data) {
                        return $query->where('contract', 'like', "%{$data}%");
                    })->orWhere('name', 'like', "%{$data}%")
                      ->orWhere('surname', 'like', "%{$data}%")
                      ->orWhere('document', 'like', "%{$data}%");
                });
    }

    /**
     * @param Request $request
     * @return Response|BinaryFileResponse
     */
    public function excel(Request $request)
    {
        return (new ContractorsExport($request))->download('PORTAL_CONTRATISTA.xlsx', Excel::XLSX);
    }

    /**
     * Display the specified resource.
     *
     * @param Contractor $contractor
     * @return JsonResponse
     */
    public function show(Contractor $contractor)
    {
        return $this->success_response(
            new ContractorResource($contractor->load('contracts')),
            Response::HTTP_OK,
            [
                'keys'  => array_merge( ContractorResource::headers(), ContractorResource::additionalData())
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLawyerRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(StoreLawyerRequest $request)
    {
        try {
            DB::connection('mysql_contractors')->beginTransaction();
            $form = new Contractor();
            $form->fill($request->validated());
            $form->user_id = auth()->user()->id;
            $form->modifiable = now()->format('Y-m-d H:i:s');
            $form->saveOrFail();
            $form->contracts()
                ->create(array_merge(
                    $request->validated(),
                    ['lawyer_id' => auth()->user()->id]
                ));
            $this->dispatch(new ConfirmContractor($form));
            DB::connection('mysql_contractors')->commit();
            return $this->success_message(
                __('validation.handler.success'),
                Response::HTTP_CREATED,
                Response::HTTP_CREATED,
                [
                    'id'    => $form->id
                ]
            );
        } catch (Throwable $e) {
            DB::connection('mysql_contractors')->rollBack();
            return $this->error_response(
                __('validation.handler.unexpected_failure'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }
    }

    /**
     * @param Contractor $contractor
     * @return JsonResponse
     */
    public function resendNotification(Contractor $contractor)
    {
        try {
            $contractor->modifiable = now()->format('Y-m-d H:i:s');
            $contractor->saveOrFail();
            $this->dispatch(new ConfirmContractor($contractor));
            return $this->success_message(__('validation.handler.success'));
        } catch (Throwable $e) {
            return $this->error_response(
                __('validation.handler.unexpected_failure'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }
    }

    /**
     * @param UpdateContractorLawyerRequest $request
     * @param Contractor $contractor
     * @return JsonResponse
     */
    public function updateBasicData(UpdateContractorLawyerRequest $request, Contractor $contractor)
    {
        try {
            $contractor->fill($request->validated());
            $contractor->saveOrFail();
            if ($request->has('notify') && $request->get('notify')) {
                $contractor->modifiable = now()->format('Y-m-d H:i:s');
                $contractor->saveOrFail();
                $this->dispatch(new ConfirmContractor($contractor));
            }
            return $this->success_message(__('validation.handler.success'));
        } catch (Throwable $e) {
            return $this->error_response(
                __('validation.handler.unexpected_failure'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }
    }

    /**
     * @param $contractor
     * @return JsonResponse
     */
    public function user($contractor)
    {
        $document = Crypt::decrypt($contractor);
        $form = Contractor::query()->where('document', $document)->firstOrFail();
        abort_unless(!is_null($form->modifiable), Response::HTTP_UNPROCESSABLE_ENTITY, __('validation.handler.unauthorized'));
        return $this->success_response(
            new UserContractorResource($form)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateContractorRequest $request
     * @param $contractor
     * @return JsonResponse
     */
    public function update(UpdateContractorRequest $request, $contractor)
    {
        try {
            $document = Crypt::decrypt($contractor);
            $form = Contractor::where('document', $document)->firstOrFail();
            abort_unless(!is_null($form->modifiable), Response::HTTP_UNPROCESSABLE_ENTITY, __('validation.handler.unauthorized'));
            DB::connection('mysql_contractors')->beginTransaction();
            if ($form->getOriginal('rut') && Storage::disk('contractor')->exists($form->getOriginal('rut'))) {
                Storage::disk('contractor')->delete($form->getOriginal('rut'));
            }
            if ($form->getOriginal('bank') && Storage::disk('contractor')->exists($form->getOriginal('bank'))) {
                Storage::disk('contractor')->delete($form->getOriginal('bank'));
            }
            $ext = $request->file('rut')->getClientOriginalExtension();
            $now = now()->format('YmdHis');
            $rut = "RUT_{$document}_{$now}.$ext";
            $request->file('rut')->storeAs('contractor', $rut, [ 'disk' => 'local' ]);
            $ext = $request->file('bank')->getClientOriginalExtension();
            $now = now()->format('YmdHis');
            $bank = "CERTIFICADO_BANCARIO_{$document}_{$now}.$ext";
            $request->file('bank')->storeAs('contractor', $bank, [ 'disk' => 'local' ]);
            $form->fill($request->validated());
            $form->rut = $rut;
            $form->bank = $bank;
            $form->modifiable = null;
            $form->saveOrFail();
            $contract = Contract::findOrFail($request->get('contract_id'));
            $contract->update($request->validated());
            Notification::send( User::whereIs(Roles::ROLE_ARL, Roles::ROLE_THIRD_PARTY)->get(), new ArlNotification($form, $contract) );
            $this->dispatch(new ConfirmUpdateContractor($form));
            DB::connection('mysql_contractors')->commit();
            return $this->success_message(__('validation.handler.updated'));
        } catch (Throwable $e) {
            DB::connection('mysql_contractors')->rollBack();
            return $this->error_response(
                __('validation.handler.unexpected_failure'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }
    }

    /**
     * @param FinderRequest $request
     * @return JsonResponse
     */
    public function find(FinderRequest $request)
    {
        $contractor =  Contractor::query()->where('document', $request->get('document'))->firstOrFail();
        return $this->success_response(
            new ContractorResource($contractor)
        );
    }

    /**
     * @param Contractor $contractor
     * @param $name
     * @return BinaryFileResponse
     */
    public function rut(Contractor $contractor, $name)
    {
        if ($contractor->getOriginal('rut') == $name) {
            if (Storage::disk('contractor')->exists($name)) {
                return response()->file(storage_path("app/contractor/{$name}"));
            }
        }
        abort(Response::HTTP_NOT_FOUND, __('validation.handler.resource_not_found_url'));
    }

    /**
     * @param Contractor $contractor
     * @param $name
     * @return BinaryFileResponse
     */
    public function bank(Contractor $contractor, $name)
    {
        if ($contractor->getOriginal('bank') == $name) {
            if (Storage::disk('contractor')->exists($name)) {
                return response()->file(storage_path("app/contractor/{$name}"));
            }
        }
        abort(Response::HTTP_NOT_FOUND, __('validation.handler.resource_not_found_url'));
    }

    /**
     * @param UpdateThirdPartyRequest $request
     * @param Contractor $contractor
     * @return JsonResponse
     */
    public function thirdParty(UpdateThirdPartyRequest $request, Contractor $contractor)
    {
        try {
            $contractor->third_party = $request->get('third_party');
            $contractor->saveOrFail();
            return $this->success_message(__('validation.handler.updated'));
        } catch (Throwable $e) {
            return $this->error_response(
                __('validation.handler.unexpected_failure'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->getMessage()
            );
        }
    }
}
