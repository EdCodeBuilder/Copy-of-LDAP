<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Activity;
use App\Modules\CitizenPortal\src\Models\Day;
use App\Modules\CitizenPortal\src\Models\Hour;
use App\Modules\CitizenPortal\src\Models\Program;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Models\Stage;
use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $permission = toLower($this->getMethod()) == 'post'
            ? Roles::can(Schedule::class,'create_or_manage', true)
            : Roles::can(Schedule::class,'update_or_manage', true);
        return auth('api')->user()->hasAnyPermission($permission);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $weekday = new Day();
        $daily = new Hour();
        $program = new Program();
        $activity = new Activity();
        $stage = new Stage();
        return [
            'weekday_id'     =>  [
                'required',
                'numeric',
                "exists:{$weekday->getConnectionName()}.{$weekday->getTable()},{$weekday->getKeyName()}"
            ],
            'daily_id'       =>  [
                'required',
                'numeric',
                "exists:{$daily->getConnectionName()}.{$daily->getTable()},{$daily->getKeyName()}"
            ],
            'min_age'        =>  'required|numeric|between:0,100|lte:max_age',
            'max_age'        =>  'required|numeric|between:0,100|gte:min_age',
            'quota'          =>  'required|numeric|min:0',
            'activity_id'    =>  [
                'required',
                'numeric',
                "exists:{$activity->getConnectionName()}.{$activity->getTable()},{$activity->getKeyName()}"
            ],
            'stage_id'       =>  [
                'required',
                'numeric',
                "exists:{$stage->getConnectionName()}.{$stage->getTable()},{$stage->getKeyName()}"
            ],
            'is_paid'        =>  'required|boolean',
            'program_id'     =>  [
                'required',
                'numeric',
                "exists:{$program->getConnectionName()}.{$program->getTable()},{$program->getKeyName()}"
            ],
            'start_date'     =>  'required|date|before_or_equal:final_date',
            'final_date'      =>  'required|date|after_or_equal:start_date',
            'is_activated'   =>  'required|boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'weekday_id'    =>  __('citizen.validations.weekday'),
            'daily_id'      =>  __('citizen.validations.daily'),
            'min_age'       =>  __('citizen.validations.min_age'),
            'max_age'       =>  __('citizen.validations.max_age'),
            'quota'         =>  __('citizen.validations.quota'),
            'activity_id'   =>  __('citizen.validations.activity'),
            'stage_id'      =>  __('citizen.validations.stage'),
            'is_paid'       =>  __('citizen.validations.is_paid'),
            'program_id'    =>  __('citizen.validations.program'),
            'start_date'    =>  __('citizen.validations.start_date'),
            'final_date'     =>  __('citizen.validations.final_date'),
            'is_activated'  =>  __('citizen.validations.is_activated'),
        ];
    }
}
