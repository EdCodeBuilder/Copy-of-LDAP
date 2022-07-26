<?php

namespace App\Modules\CitizenPortal\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Security\User;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Schedule;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Modules\CitizenPortal\src\Models\ActivityAsisstance;
use App\Modules\CitizenPortal\src\Resources\ActivityAsisstanceResource;
use Illuminate\Http\Request;
use App\Modules\CitizenPortal\src\Request\ActivityAsisstanceRequest;

class ActivityAsisstanceController extends Controller
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
                    'model'     => ActivityAsisstance::class
                ],
                ['model' => CitizenSchedule::class, 'actions' => 'status'],
                ['model' => CitizenSchedule::class, 'actions' => 'view_or_manage'],
                [
                    'actions'   => 'view_or_manage',
                    'model'     => Schedule::class
                ],
            ], true, true)
        )
            ->only('index');
        $this->middleware(Roles::actions(ActivityAsisstance::class, 'create_or_manage'))
            ->only('store');
        $this->middleware(Roles::actions(ActivityAsisstance::class, 'update_or_manage'))
            ->only('update');
        $this->middleware(Roles::actions(ActivityAsisstance::class, 'destroy_or_manage'))
            ->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->setQuery(ActivityAsisstance::query(), (new ActivityAsisstance)->getSortableColumn($this->column))
            ->when(isset($this->query), function ($query) {
                return $query->where('activity_name', 'like', "%$this->query%");
            })
            ->orderBy((new ActivityAsisstance)->getSortableColumn($this->column), $this->order);
        return $this->success_response(
            ActivityAsisstanceResource::collection(
                (int) $this->per_page > 0
                    ? $query->paginate( $this->per_page )
                    : $query->get()
            ),
            Response::HTTP_OK,
            [
                'headers'   => ActivityAsisstanceResource::headers()
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modules\CitizenPortal\src\Models\ActivityAsisstance  $activityAsisstance
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityAsisstance $activityAsisstance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modules\CitizenPortal\src\Models\ActivityAsisstance  $activityAsisstance
     * @return \Illuminate\Http\Response
     */
    public function edit(ActivityAsisstance $activityAsisstance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modules\CitizenPortal\src\Models\ActivityAsisstance  $activityAsisstance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActivityAsisstance $activityAsisstance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modules\CitizenPortal\src\Models\ActivityAsisstance  $activityAsisstance
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivityAsisstance $activityAsisstance)
    {
        //
    }
}
