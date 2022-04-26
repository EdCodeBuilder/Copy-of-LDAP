<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Security\User;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Exports\CitizenExport;
use App\Modules\CitizenPortal\src\Exports\ProfilesExport;
use App\Modules\CitizenPortal\src\Jobs\ConfirmStatusCitizen;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\Observation;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Models\Status;
use App\Modules\CitizenPortal\src\Notifications\ProfileStatusNotification;
use App\Modules\CitizenPortal\src\Notifications\ValidatorNotification;
use App\Modules\CitizenPortal\src\Request\AssignorRequest;
use App\Modules\CitizenPortal\src\Request\ProfileFilterRequest;
use App\Modules\CitizenPortal\src\Request\StatusProfileRequest;
use App\Modules\CitizenPortal\src\Resources\ProfileResource;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Excel;
use Silber\Bouncer\BouncerFacade;

class ProfileController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(
            Roles::canAny(
                [
                    ['model' => Schedule::class, 'actions' => 'view_or_manage'],
                    ['model' => CitizenSchedule::class, 'actions' => 'status'],
                    ['model' => CitizenSchedule::class, 'actions' => 'view_or_manage'],
                    ['model' => Observation::class, 'actions' => 'view_or_manage'],
                    ['model' => File::class, 'actions' => 'view_or_manage'],
                    ['model' => File::class, 'actions' => 'status'],
                    ['model' => File::class, 'actions' => 'destroy'],
                    ['model' => Profile::class, 'actions' => 'view_or_manage'],
                    ['model' => Profile::class, 'actions' => 'status'],
                    ['model' => Profile::class, 'actions' => 'validator'],
                ],
                true,
                true
            )
        )->only('index', 'show', 'excel');
        $this->middleware(Roles::actions(Profile::class, 'status'))
            ->only('status');
        $this->middleware(Roles::actions(Profile::class, 'validator'))
            ->only('validator');
    }

    /**
     * @param ProfileFilterRequest $request
     * @return JsonResponse
     */
    public function index(ProfileFilterRequest $request)
    {
        $query = $this->setQuery(ProfileView::query(), (new ProfileView)->getSortableColumn($this->column))
            ->withCount([
                'observations',
                'files',
                'files as pending_files_count' => function($query) {
                    return $query->where('status_id', "!=", Profile::VERIFIED);
                },
            ])
            ->when($this->query, function ($query) {
                $keys = Profile::search($this->query)->get(['id'])->pluck('id')->toArray();
                return $query->whereKey($keys);
            })
            ->when($request->has('document'), function ($query) use ($request) {
                return $query->where('document', 'like', "%{$request->get('document')}%");
            })
            ->when($request->has('status_id'), function ($query) use ($request) {
                $status = $request->get('status_id');
                if (is_array($status) && in_array(Profile::PENDING, $status)) {
                    return $query->whereNull('status_id')
                        ->orWhereIn('status_id', $status);
                }
                if ( $request->get('status_id') == Profile::PENDING ) {
                    return $query->whereNull('status_id')
                        ->orWhere('status_id', $request->get('status_id'));
                }
                return is_array($status)
                    ?  $query->whereIn('status_id', $request->get('status_id'))
                    :  $query->where('status_id', $request->get('status_id'));
            })
            ->when($request->has('not_assigned'), function ($query) use ($request) {
                return $query->whereNull('checker_id');
            })
            ->when($request->has('assigned'), function ($query) use ($request) {
                return $query->whereNotNull('checker_id');
            })
            ->when($request->has('validators_id'), function ($query) use ($request) {
                return $query->whereIn('checker_id', $request->get('validators_id'));
            })
            ->when($request->has('profile_type_id'), function ($query) use ($request) {
                return $query->whereIn('profile_type_id', $request->get('profile_type_id'));
            })
            ->when($request->has('start_date'), function ($query) use ($request) {
                return $query->whereDate('created_at', '>=', $request->get('start_date'));
            })
            ->when($request->has('final_date'), function ($query) use ($request) {
                return $query->whereDate('created_at', '<=', $request->get('final_date'));
            })
            ->when(
                (
                    auth('api')->user()->isA(Roles::ROLE_VALIDATOR)
                    && auth('api')->user()->isNotA(...Roles::adminAnd(Roles::ROLE_ASSIGNOR)
                 )
                ), function ($query) use ($request) {
                return $query->where('checker_id', auth('api')->user()->id);
            })
            ->orderBy((new ProfileView)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            ProfileResource::collection($query->paginate( $this->per_page)),
            Response::HTTP_OK,
            ProfileResource::headers()
        );
    }

    /**
     * @param ProfileView $profile
     * @return JsonResponse
     */
    public function show(ProfileView $profile)
    {
        return $this->success_response(
            new ProfileResource($profile->loadCount(
                [
                    'observations',
                    'files',
                    'files as pending_files_count' => function($query) {
                        return $query->where('status_id', "!=", Profile::VERIFIED);
                    },
                ]
            )),
            Response::HTTP_OK,
            [
                'headers' =>  array_values(
                    array_merge( ProfileResource::headers()['headers'], ProfileResource::headers()['expanded'] )
                )
            ]
        );
    }

    /**
     * @param ProfileFilterRequest $request
     * @return JsonResponse
     */
    public function excel(ProfileFilterRequest $request)
    {
        $does_not_have_params = !$request->hasAny([
            'query',
            'document',
            'status_id',
            'not_assigned',
            'assigned',
            'validators_id',
            'profile_type_id',
            'start_date',
            'final_date',
        ]);

        if ($does_not_have_params) {
            $request->request->add([
                'start_date'   => now()->startOfMonth(),
                'final_date'    => now()->endOfMonth(),
            ]);
        }

        $file = \App\Modules\CitizenPortal\src\Exports\Excel::raw(new CitizenExport($request), Excel::XLSX);
        $name = random_img_name();
        $response =  array(
            'name' => toUpper(str_replace(' ', '-', __('citizen.validations.citizen_portal')))."-$name.xlsx",
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file),
        );
        return $this->success_message($response);
    }

    /**
     * @param AssignorRequest $request
     * @param Profile $profile
     * @return JsonResponse
     */
    public function validator(AssignorRequest $request, Profile $profile)
    {
        try {
            DB::beginTransaction();
            if (isset($profile->checker_id)) {
                $profile->viewer->disallow(Roles::can(Profile::class, 'status'), $profile);
            }
            $profile->assigned_by_id = auth('api')->user()->id;
            $profile->assigned_at = now();
            $profile->status_id = Profile::VALIDATING;
            $profile->checker_id = $request->get('validator_id');
            $profile->verified_at = null;
            $profile->save();
            $user = User::find($request->get('validator_id'));
            BouncerFacade::ownedVia(Profile::class, 'checker_id');
            BouncerFacade::allow($user)->toOwn($profile)->to(Roles::can(Profile::class, 'status'));
            $name = isset( $user->full_name ) ? (string) $user->full_name : 'SIN DATOS';
            $profile->observations()->create([
                'observation'   => auth('api')->user()->full_name." ha asignado a {$name} para validar los datos.",
                'user_ldap_id'       =>  auth('api')->user()->id,
            ]);
            Notification::send($user, new ValidatorNotification($profile));
            DB::commit();
            return $this->success_message(
                __('validation.handler.success')
            );
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error_response(
                __('validation.handler.service_unavailable'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $exception->getMessage()
            );
        }
    }

    /**
     * @param StatusProfileRequest $request
     * @param Profile $profile
     * @return JsonResponse
     */
    public function status(StatusProfileRequest $request, Profile $profile)
    {
        try {
            DB::beginTransaction();
            if ( $request->get('status_id') == Profile::VERIFIED ) {
                $profile->verified_at = now()->format('Y-m-d H:i:s');
                $profile->status_id = $request->get('status_id');
            } else {
                $profile->verified_at = null;
                $profile->status_id = $request->get('status_id');
            }
            if (! isset($profile->assigned_by_id)) {
                $profile->assigned_by_id = auth('api')->user()->id;
                $profile->assigned_at = now();
            }
            $profile->save();
            $status = Status::find($request->get('status_id'));
            $statusName = isset( $status->name ) ? (string) $status->name : null;
            $observation = $request->has('observation') &&
            ($request->get('observation') != null || $request->get('observation') != '')
                ? toUpper( $statusName.": ".$request->get('observation') )
                : $status;
            $profile->observations()->create([
                'profile_id'    => $profile->id,
                'observation'   => $observation,
                'user_ldap_id'       =>  auth('api')->user()->id,
            ]);
            if ( in_array( $request->get('status_id'), [Profile::VERIFIED, Profile::RETURNED] ) ) {
                $this->dispatch( new ConfirmStatusCitizen(
                    ProfileView::find($profile->id),
                    $observation,
                    auth('api')->user()->email
                ) );
            }
            $profile->user->notify(new ProfileStatusNotification(
                $profile,
                $status,
                $observation,
                auth('api')->user()->full_name
            ));
            DB::commit();
            return $this->success_message(
                __('validation.handler.success')
            );
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error_response(
                __('validation.handler.service_unavailable'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $exception->getMessage()
            );
        }
    }
}
