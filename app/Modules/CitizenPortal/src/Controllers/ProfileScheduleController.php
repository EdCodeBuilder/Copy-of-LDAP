<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Exports\CitizenScheduleExport;
use App\Modules\CitizenPortal\src\Jobs\ConfirmStatusSubscriptionCitizen;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Models\ScheduleView;
use App\Modules\CitizenPortal\src\Models\Status;
use App\Modules\CitizenPortal\src\Notifications\ProfileScheduleStatusNotification;
use App\Modules\CitizenPortal\src\Request\ProfileFilterRequest;
use App\Modules\CitizenPortal\src\Request\StatusProfileScheduleRequest;
use App\Modules\CitizenPortal\src\Resources\CitizenActivitiesResource;
use App\Modules\CitizenPortal\src\Resources\CitizenScheduleResource;
use App\Modules\CitizenPortal\src\Resources\ProfileResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Excel;

class ProfileScheduleController extends Controller
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
                    ['model' => Profile::class, 'actions' => 'view_or_manage'],
                    ['model' => CitizenSchedule::class, 'actions' => 'status'],
                    ['model' => CitizenSchedule::class, 'actions' => 'view_or_manage'],
                    ['model' => Profile::class, 'actions' => 'validator'],
                ],
                true,
                true
            )
        )
            ->only('index', 'store', 'show');
        $this->middleware(Roles::actions(CitizenSchedule::class, 'status'))
            ->only('update');
    }

    /**
     * @param ProfileFilterRequest $request
     * @param Schedule $schedule
     * @return JsonResponse
     */
    public function index(ProfileFilterRequest $request, Schedule $schedule)
    {
        $query = CitizenSchedule::query()
            ->whereHas('profiles_view', function ($query) use ($request) {
                return $this->setQuery($query, (new ProfileView)->getSortableColumn($this->column))
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
                    ->orderBy((new ProfileView)->getSortableColumn($this->column), $this->order);
            })
            ->with([
                'profiles_view' => function ($query) {
                    return $query->withCount([
                        'observations',
                        'files',
                        'files as pending_files_count' => function($query) {
                            return $query->where('status_id', "!=", Profile::VERIFIED);
                        },
                    ]);
                }
            ])
            ->where('schedule_id', $schedule->id);
        return $this->success_response(
            CitizenScheduleResource::collection($query->paginate( $this->per_page)),
            Response::HTTP_OK,
            CitizenScheduleResource::headers()
        );
    }

    /**
     * @param $schedule
     * @param $profile
     * @return JsonResponse
     */
    public function show($schedule, $profile)
    {
        $query = CitizenSchedule::query()
            ->with(
                [
                    'profiles_view' => function ($query) {
                        return $query->withCount([
                            'observations',
                            'files',
                            'files as pending_files_count' => function($query) {
                                return $query->where('status_id', "!=", Profile::VERIFIED);
                            },
                        ]);
                    }
                ]
            )->where('schedule_id', $schedule)
            ->where('profile_id', $profile)
            ->firstOrFail();
        return $this->success_response(
            new CitizenScheduleResource(
                $query
            ),
            Response::HTTP_OK,
            CitizenScheduleResource::headers()
        );
    }

    /**
     * @param $profile
     * @return JsonResponse
     */
    public function activities($profile)
    {
        $query = CitizenSchedule::query()
            ->with('schedule_view')
            ->where('profile_id', $profile)
            ->latest()
            ->paginate($this->per_page);
        return $this->success_response(
            CitizenActivitiesResource::collection($query)
        );
    }

    /**
     * @param ProfileFilterRequest $request
     * @param ScheduleView $schedule
     * @return JsonResponse
     */
    public function store(ProfileFilterRequest $request, ScheduleView $schedule)
    {
        $users = CitizenSchedule::query()
            ->where('schedule_id', $schedule->id)
            ->get(['profile_id'])->pluck('profile_id')->toArray();

        $file = \App\Modules\CitizenPortal\src\Exports\Excel::raw(new CitizenScheduleExport($request, $users, $schedule), Excel::XLSX);
        $name = random_img_name();
        $response =  array(
            'name' => toUpper(str_replace(' ', '-', __('citizen.validations.citizen_portal')))."-$name.xlsx",
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file),
        );
        return $this->success_message($response);
    }

    /**
     * @param StatusProfileScheduleRequest $request
     * @param Schedule $schedule
     * @param Profile $profile
     * @return JsonResponse
     */
    public function update(StatusProfileScheduleRequest $request, Schedule $schedule, Profile $profile)
    {
        try {
            $user = CitizenSchedule::query()
                ->where('schedule_id', $schedule->id)
                ->where('profile_id', $profile->id)
                ->firstOrFail();
            $status = Status::find($request->get('status_id'));
            $status_name = isset($status->name) ? (string) $status->name : '';
            $activity = isset($schedule->activities->name)
                ? (string) $schedule->activities->name
                : '';
            $day = isset($schedule->day->name)
                ? (string) $schedule->day->name
                : '';
            $hour = isset($schedule->hour->name)
                ? (string) $schedule->hour->name
                : '';
            $stage = isset($schedule->stage->name)
                ? (string) $schedule->stage->name
                : '';

            $text = "ESTADO: $status_name / ACTIVIDAD: $activity / HORARIO: $day - $hour / ESCENARIO: $stage / OBSERVACIÓN: {$request->get('observation')}";

            $profile->observations()->create([
                'observation'   => $text,
                'user_ldap_id'  =>  auth('api')->user()->id,
            ]);

            if ($request->get('status_id') == Status::UNSUBSCRIBED) {
                $message = __('validation.handler.deleted');
                $user->status_id = $request->get('status_id');
                $user->save();
                $user->delete();
            } else {
                $user->status_id = $request->get('status_id');
                if (Status::SUBSCRIBED == (int) $request->get('status_id') && $schedule->is_paid) {
                    $user->payment_at = now()->addDay()->endOfDay();
                }
                $user->save();
                $message = __('validation.handler.updated');
            }

            $this->dispatch( new ConfirmStatusSubscriptionCitizen(
                $user->profiles_view,
                $status,
                $text,
                auth('api')->user()->email
            ) );

            $profile->user->notify(new ProfileScheduleStatusNotification(
                $profile,
                $status,
                $schedule,
                $text,
                auth('api')->user()->full_name
            ));

            return $this->success_message(
                $message,
                Response::HTTP_OK,
                $request->get('status_id') == Profile::UNSUBSCRIBED
                    ? Response::HTTP_NO_CONTENT
                    : Response::HTTP_OK
            );
        } catch (Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                return $this->error_response(
                    __('validation.handler.resource_not_found'),
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    $exception->getMessage()
                );
            }
            return $this->error_response(
                __('validation.handler.service_unavailable'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $exception->getMessage()
            );
        }
    }
}
