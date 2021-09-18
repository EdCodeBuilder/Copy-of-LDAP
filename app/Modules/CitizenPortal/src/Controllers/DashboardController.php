<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\Status;
use App\Modules\CitizenPortal\src\Request\DashboardRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param DashboardRequest $request
     * @return JsonResponse
     */
    public function index(DashboardRequest $request)
    {
        $icons = [
            Profile::RETURNED   =>  'mdi-swap-horizontal-bold',
            Profile::PENDING    =>  'mdi-order-bool-ascending-variant',
            Profile::VALIDATING =>  'mdi-clipboard-text-search-outline',
            Profile::VERIFIED   =>  'mdi-shield-check',
        ];
        $colors = [
            Profile::RETURNED   =>  'error',
            Profile::PENDING    =>  'info',
            Profile::VALIDATING =>  'warning',
            Profile::VERIFIED   =>  'success',
        ];
        $counters = Status::profile()->get()->map(function ($model) use ($icons, $colors) {
            return [
                'icon'  =>  $icons[$model->id],
                'color' => $colors[$model->id],
                'name'  =>  $model->name,
                'value' =>  Profile::query()
                    ->where(function ($query) use ($model) {
                        return $query->where('status_id', $model->id)
                                    ->when($model->id == Profile::PENDING, function ($q) {
                                        return $q->orWhereNull('status_id');
                                    });
                     })
                    ->count()
            ];
        });
        $profile = new Profile();

        return $this->success_message([
            'counters'  => $counters,
            'activities'    => DB::connection($profile->getConnectionName())
                ->table('user_schedule')
                ->selectRaw(DB::raw('scenarios.id as id, scenarios.scenario_name as name, COUNT(*) as users_count'))
                ->leftJoin('schedule', 'user_schedule.schedule_id', '=', 'schedule.id')
                ->leftJoin('scenarios', 'schedule.stage_id', '=', 'scenarios.id')
                ->groupBy(['scenarios.id'])
                ->paginate($this->per_page),
            'total' => Profile::when(
                (
                    auth('api')->user()->isA(Roles::ROLE_VALIDATOR)
                    && auth('api')->user()->isNotA(...Roles::adminAnd(Roles::ROLE_ASSIGNOR)
                    )
                ),
                function ($query) {
                    return $query->where('checker_id', auth('api')->user()->id);
                }
            )->count()
        ]);
    }
}
