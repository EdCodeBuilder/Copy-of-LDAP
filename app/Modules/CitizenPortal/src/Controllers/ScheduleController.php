<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Exports\Excel as ExcelRaw;
use App\Modules\CitizenPortal\src\Exports\TemplateExport;
use App\Modules\CitizenPortal\src\Imports\Excel;
use App\Modules\CitizenPortal\src\Models\Activity;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Day;
use App\Modules\CitizenPortal\src\Models\Hour;
use App\Modules\CitizenPortal\src\Models\Program;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Models\ScheduleView;
use App\Modules\CitizenPortal\src\Models\Stage;
use App\Modules\CitizenPortal\src\Request\FilterSchedulePublicRequest;
use App\Modules\CitizenPortal\src\Request\FilterScheduleRequest;
use App\Modules\CitizenPortal\src\Request\MassiveScheduleRequest;
use App\Modules\CitizenPortal\src\Request\ScheduleRequest;
use App\Modules\CitizenPortal\src\Resources\ProfileResource;
use App\Modules\CitizenPortal\src\Resources\SchedulePublicResource;
use App\Modules\CitizenPortal\src\Resources\ScheduleResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(
            Roles::canAny([
                [
                    'actions'   => 'view_or_manage',
                    'model'     => CitizenSchedule::class
                ],
                [
                    'actions'   => 'status',
                    'model'     => CitizenSchedule::class
                ],
                [
                    'actions'   => 'view_or_manage',
                    'model'     => Schedule::class
                ],
            ], true, true)
        )
            ->only('index', 'show');
        $this->middleware(Roles::actions(Schedule::class, 'create_or_manage'))
            ->only('store', 'template', 'import');
        $this->middleware(Roles::actions(Schedule::class, 'update_or_manage'))
            ->only('update');
        $this->middleware(Roles::actions(Schedule::class, 'destroy_or_manage'))
            ->only('destroy');
    }

    public function publicApi(FilterSchedulePublicRequest $request)
    {
        $query = $this->setQuery(ScheduleView::query(), (new ScheduleView)->getSortableColumn($this->column))
            ->withCount('users_schedules')
            ->when(isset($this->query), function ($query) {
                return $query->search($this->query);
            })
            ->when($request->has('weekday_id'), function ($query) use ($request) {
                return $query->whereIn('weekday_id', $request->get('weekday_id'));
            })
            ->when($request->has('daily_id'), function ($query) use ($request) {
                return $query->whereIn('daily_id', $request->get('daily_id'));
            })
            ->when($request->has('age'), function ($query) use ($request) {
                return $query
                    ->where('min_age', '<=', $request->get('age'))
                    ->where('max_age', '>=', $request->get('age'));
            })
            ->when($request->has('activity_id'), function ($query) use ($request) {
                return $query->whereIn('activity_id', $request->get('activity_id'));
            })
            ->when($request->has('park_code'), function ($query) use ($request) {
                return $query
                    ->whereIn('park_code', $request->get('park_code'))
                    ->orWhereIn('park_id', $request->get('park_code'));
            })
            ->when($request->has('stage_id'), function ($query) use ($request) {
                return $query->whereIn('stage_id', $request->get('stage_id'));
            })
            ->when($request->has('is_paid'), function ($query) use ($request) {
                return $query->where('is_paid', $request->get('is_paid'));
            })
            ->when($request->has('program_id'), function ($query) use ($request) {
                return $query->whereIn('program_id', $request->get('program_id'));
            })
            ->where('quota', '>', 0)
            ->where('is_activated', true)
            ->where('is_initiate', true)
            ->whereColumn('taken', '<', 'quota')
            ->orderBy((new ScheduleView)->getSortableColumn($this->column), $this->order)
            ->paginate($this->per_page);
        return $this->success_response(
            SchedulePublicResource::collection($query),
            Response::HTTP_OK,
            SchedulePublicResource::headers()
        );
    }

    /**
     * @param FilterScheduleRequest $request
     * @return JsonResponse
     */
    public function index(FilterScheduleRequest $request)
    {
        $query = $this->setQuery(ScheduleView::query(), (new ScheduleView)->getSortableColumn($this->column))
            ->withCount('users_schedules')
            ->when(isset($this->query), function ($query) {
                return $query->search($this->query);
            })
            ->when(count($this->where_columns_between) > 0, function ($query) {
                return $query->where(
                    (new ScheduleView)->getSortableColumn($this->where_columns_between['columns'][0]),
                    '>=',
                    $this->where_columns_between['values'][0]
                )->where(
                    (new ScheduleView)->getSortableColumn($this->where_columns_between['columns'][1]),
                    '<=',
                    $this->where_columns_between['values'][1]
                );
            })
            ->when(count($this->where_columns_not_between) > 0, function ($query) {
                return $query->where(
                    (new ScheduleView)->getSortableColumn($this->where_columns_not_between['columns'][0]),
                    '<',
                    $this->where_columns_not_between['values'][0]
                )->where(
                    (new ScheduleView)->getSortableColumn($this->where_columns_not_between['columns'][1]),
                    '>',
                    $this->where_columns_not_between['values'][1]
                );
            })
            ->when($request->has('weekday_id'), function ($query) use ($request) {
                return $query->whereIn('weekday_id', $request->get('weekday_id'));
            })
            ->when($request->has('daily_id'), function ($query) use ($request) {
                return $query->whereIn('daily_id', $request->get('daily_id'));
            })
            ->when($request->has('activity_id'), function ($query) use ($request) {
                return $query->whereIn('activity_id', $request->get('activity_id'));
            })
            ->when($request->has('stage_id'), function ($query) use ($request) {
                return $query->whereIn('stage_id', $request->get('stage_id'));
            })
            ->when($request->has('is_paid'), function ($query) use ($request) {
                return $query->where('is_paid', $request->get('is_paid'));
            })
            ->when($request->has('program_id'), function ($query) use ($request) {
                return $query->whereIn('program_id', $request->get('program_id'));
            })
            ->when($request->has('start_date'), function ($query) use ($request) {
                return $query->where('start_date', '>=', $request->get('start_date'));
            })
            ->when($request->has('final_date'), function ($query) use ($request) {
                return $query->whereDate('final_date', '<=', $request->get('final_date'));
            })
            ->when($request->has('is_activated'), function ($query) use ($request) {
                return $query->where('is_activated', $request->get('is_activated'));
            })
            ->orderBy((new ScheduleView)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            ScheduleResource::collection(
                (int) $this->per_page > 0
                    ? $query->paginate( $this->per_page )
                    : $query->get()
            ),
            Response::HTTP_OK,
            ScheduleResource::headers()
        );
    }

    /**
     * @return JsonResponse
     */
    public function template()
    {
        try {
            $file = ExcelRaw::raw(new TemplateExport(), \Maatwebsite\Excel\Excel::XLSX);
            $name = random_img_name();
            $response =  array(
                'name' => toUpper(str_replace(' ', '-', __('citizen.validations.citizen_portal')))."-$name.xlsx",
                'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file),
            );
            return $this->success_message($response);
        } catch (Exception $exception) {
            return $this->error_response(
                __('validation.handler.service_unavailable'),
              Response::HTTP_UNPROCESSABLE_ENTITY,
              $exception->getMessage()
            );
        }
    }

    /**
     * @param MassiveScheduleRequest $request
     * @return JsonResponse
     */
    public function import(MassiveScheduleRequest $request)
    {
        try {
            $name = random_img_name().microtime();
            $ext = $request->file('file')->getClientOriginalExtension();
            $request->file('file')->storeAs('tmp', "$name.$ext", [ 'disk' => 'local' ]);
            $info = Excel::import($name.'.'.$ext);
            $validator = $this->validateExcel($info);
            if ($validator->fails()) {
                return $this->validation_errors($validator->errors());
            }
            $formatted = [];
            $exists = [];
            foreach ($info as $item) {
                if (!$request->get('force')) {
                    $query = ScheduleView::query()
                        ->withCount('users_schedules')
                        ->where([
                            ['program_id', Arr::get($item, 'id_programa')],
                            ['activity_id', Arr::get($item, 'id_actividad')],
                            ['stage_id', Arr::get($item, 'id_escenario')],
                            ['weekday_id', Arr::get($item, 'id_dia_de_la_semana')],
                            ['daily_id', Arr::get($item, 'id_hora')],
                            ['is_paid', Arr::get($item, 'es_pago')],
                        ])
                        ->get();
                    foreach ($query as $item) {
                        if (isset($item->id)) {
                            $exists[] = new ScheduleResource($item);
                        }
                    }
                }
                $formatted[] = [
                    'program_id'    => Arr::get($item, 'id_programa'),
                    'activity_id'   => Arr::get($item, 'id_actividad'),
                    'stage_id'      => Arr::get($item, 'id_escenario'),
                    'weekday_id'    => Arr::get($item, 'id_dia_de_la_semana'),
                    'daily_id'      => Arr::get($item, 'id_hora'),
                    'min_age'       => Arr::get($item, 'edad_minima'),
                    'max_age'       => Arr::get($item, 'edad_maxima'),
                    'quota'         => Arr::get($item, 'cupo'),
                    'fecha_apertura' => Arr::get($item, 'fecha_apertura'),
                    'fecha_cierre'   => Arr::get($item, 'fecha_cierre'),
                    'is_paid'        => Arr::get($item, 'es_pago'),
                    'is_activated'   => Arr::get($item, 'esta_activo'),
                    'created_at'     => now()->format('Y-m-d H:i:s'),
                    'updated_at'     => now()->format('Y-m-d H:i:s'),
                ];
            }
            if (count($exists) > 0 && !$request->get('force')) {
                return $this->error_response(
                    trans_choice('citizen.excel.matches', count($exists), ['matches' => count($exists), 'rows' => count($info)]),
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    [
                        'data' => $exists,
                        'table' => [
                            'headers'   => array_values(
                                collect(ScheduleResource::headers()['headers'])->where('value', '!=', 'actions')->toArray()
                            ),
                            'expanded'  => ScheduleResource::headers()['expanded']
                        ]
                    ]
                );
            } else {
                foreach ($formatted as $form) {
                    $model = new Schedule();
                    $model->fill($form);
                    $model->save();
                }
            }
            return $this->success_message(
                __('validation.handler.success')
            );
        } catch (Exception $exception) {
            return $this->error_response(
                __('validation.handler.service_unavailable'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $exception->getMessage()
            );
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateExcel($data)
    {
        $weekday = new Day();
        $daily = new Hour();
        $program = new Program();
        $activity = new Activity();
        $stage = new Stage();

        $attributes = [];
        foreach ($data as $key => $value) {
            $row = $key + 2;
            $attributes[ "$key.id_programa"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.program'), 'row' => $row]);
            $attributes[ "$key.id_actividad"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.activity'), 'row' => $row]);
            $attributes[ "$key.id_escenario"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.stage'), 'row' => $row]);
            $attributes[ "$key.id_dia_de_la_semana"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.weekday'), 'row' => $row]);
            $attributes[ "$key.id_hora"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.daily'), 'row' => $row]);
            $attributes[ "$key.edad_minima"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.min_age'), 'row' => $row]);
            $attributes[ "$key.edad_maxima"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.max_age'), 'row' => $row]);
            $attributes[ "$key.cupo"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.quota'), 'row' => $row]);
            $attributes[ "$key.fecha_apertura"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.start_date'), 'row' => $row]);
            $attributes[ "$key.fecha_cierre"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.final_date'), 'row' => $row]);
            $attributes[ "$key.es_pago"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.is_paid'), 'row' => $row]);
            $attributes[ "$key.esta_activo"] = __('citizen.excel.row', ['attribute' => __('citizen.validations.is_activated'), 'row' => $row]);
        }
        return Validator::make(
            $data,
            [
                '*.id_programa'     =>  [
                    'required',
                    'numeric',
                    "exists:{$program->getConnectionName()}.{$program->getTable()},{$program->getKeyName()}"
                ],
                '*.id_actividad'    =>  [
                    'required',
                    'numeric',
                    "exists:{$activity->getConnectionName()}.{$activity->getTable()},{$activity->getKeyName()}"
                ],
                '*.id_escenario'       =>  [
                    'required',
                    'numeric',
                    "exists:{$stage->getConnectionName()}.{$stage->getTable()},{$stage->getKeyName()}"
                ],
                '*.id_dia_de_la_semana'     =>  [
                    'required',
                    'numeric',
                    "exists:{$weekday->getConnectionName()}.{$weekday->getTable()},{$weekday->getKeyName()}"
                ],
                '*.id_hora'       =>  [
                    'required',
                    'numeric',
                    "exists:{$daily->getConnectionName()}.{$daily->getTable()},{$daily->getKeyName()}"
                ],
                '*.edad_minima'        =>  'required|numeric|between:0,100|lte:*.edad_maxima',
                '*.edad_maxima'        =>  'required|numeric|between:0,100|gte:*.edad_minima',
                '*.cupo'          =>  'required|numeric|min:0',
                '*.fecha_apertura'     =>  'required|date|before_or_equal:*.fecha_cierre',
                '*.fecha_cierre'      =>  'required|date|after_or_equal:*.fecha_apertura',
                '*.es_pago'        =>  'required|boolean',
                '*.esta_activo'   =>  'required|boolean',
            ],
            [],
            $attributes
        );
    }

    /**
     * @param ScheduleRequest $request
     * @return JsonResponse
     */
    public function store(ScheduleRequest $request)
    {
        $model = new Schedule();
        $attrs = $model->fillModel($request->validated());
        $model->fill($attrs);
        $model->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param ScheduleView $schedule
     * @return JsonResponse
     */
    public function show(ScheduleView $schedule)
    {
        return $this->success_response(
            new ScheduleResource(
                $schedule
                    ->loadCount('users_schedules')
                    ->load('users_schedules.profile')
            ),
            Response::HTTP_OK,
            ProfileResource::headers()
        );
    }

    /**
     * @param ScheduleRequest $request
     * @param Schedule $schedule
     * @return JsonResponse
     */
    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        $attrs = $schedule->fillModel($request->validated());
        $schedule->fill($attrs);
        $schedule->save();
        return $this->success_message(
            __('validation.handler.updated')
        );
    }

    /**
     * @param Schedule $schedule
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
