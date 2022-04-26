<?php


namespace App\Modules\CitizenPortal\src\Resources;


use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\Status;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CitizenActivitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $status = isset($this->status_id) ? (int) $this->status_id : Profile::PENDING_SUBSCRIBE;
        $status = $status == Profile::PENDING ? Profile::PENDING_SUBSCRIBE : $status;
        $status_name = Status::find($status);
        return [
            $this->merge(new ScheduleResource($this->schedule_view)),
            'schedule_status_id'    => $status,
            'schedule_status_color' => Status::getColor($status),
            'schedule_status_name'  => isset($status_name->name) ? (string) $status_name->name : null,
            'citizen_schedule_payment_files'  => FileResource::collection($this->files),
            'citizen_schedule_payment_at'   => isset($this->payment_at) ? $this->payment_at->format('Y-m-d H:i:s') : null,
            'citizen_schedule_created_at'   => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'citizen_schedule_updated_at'   => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function headers()
    {
        $headers = ProfileResource::headers()['headers'];
        $expanded = ProfileResource::headers()['expanded'];
        $headers = array_insert_in_position($headers, [
            [
                'align' => "center",
                'text' => 'Estado inscripción',
                'value'  =>  "schedule_status_name",
                'sortable' => false
            ]
        ], 2);
        return [
            'headers'   => $headers,
            'expanded'   => $expanded,
        ];
    }
}
