<?php


namespace App\Modules\CitizenPortal\src\Request;



use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Activity;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Day;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\Hour;
use App\Modules\CitizenPortal\src\Models\Observation;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\Program;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Models\Stage;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ProfileFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $permissions = Roles::canAny(
            [
                ['model' => Schedule::class, 'actions' => 'view_or_manage'],
                ['model' => Observation::class, 'actions' => 'view_or_manage'],
                ['model' => File::class, 'actions' => 'view_or_manage'],
                ['model' => File::class, 'actions' => 'status'],
                ['model' => File::class, 'actions' => 'destroy'],
                ['model' => Profile::class, 'actions' => 'view_or_manage'],
                ['model' => CitizenSchedule::class, 'actions' => 'status'],
                ['model' => CitizenSchedule::class, 'actions' => 'view_or_manage'],
                ['model' => Profile::class, 'actions' => 'status'],
                ['model' => Profile::class, 'actions' => 'validator'],
            ],
            false,
            true
            );
        return auth('api')->user()->hasAnyPermission($permissions);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date'     =>  [
              'nullable',
              'date',
              function($attribute, $value, $fail) {
                if ($this->has('final_date')) {
                    $start_date = Carbon::parse($value);
                    $final_date = Carbon::parse($this->get('final_date'));
                    if ($start_date->greaterThan($final_date)) {
                        $fail(
                            __('validation.before_or_equal', ['attribute' => __('citizen.validations.start_date_filter'), 'date' => $this->get('final_date')])
                        );
                    }
                }
              },
            ],
            'final_date'      =>  [
                'nullable',
                'date',
                function($attribute, $value, $fail) {
                    if ($this->has('start_date')) {
                        $start_date = Carbon::parse($this->get('start_date'));
                        $final_date = Carbon::parse($value);
                        if ($final_date->lessThan($start_date)) {
                            $fail(
                                __('validation.after_or_equal', ['attribute' => __('citizen.validations.final_date_filter'), 'date' => $this->get('start_date')])
                            );
                        }
                    }
                },
            ]
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
            'start_date'    =>  __('citizen.validations.start_date_filter'),
            'final_date'     =>  __('citizen.validations.final_date_filter'),
        ];
    }
}
