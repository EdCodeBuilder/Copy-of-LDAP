<?php


namespace App\Modules\CitizenPortal\src\Notifications;

use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Models\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProfileScheduleStatusNotification extends Notification
{
    use Queueable;

    /**
     * @var Profile
     */
    private $profile;
    /**
     * @var Status
     */
    private $status;
    /**
     * @var Schedule
     */
    private $schedule;

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $observation;

    /**
     * Create a new notification instance.
     * @param Profile $profile
     * @param Status $status
     * @param Schedule $schedule
     * @param $observation
     * @param $name
     */
    public function __construct(Profile $profile, Status $status, Schedule $schedule, $observation, $name)
    {

        $this->profile = $profile;
        $this->status = $status;
        $this->schedule = $schedule;
        $this->observation = $observation;
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [CitizenNotification::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $activity = isset($this->schedule->activities->name)
            ? (string) $this->schedule->activities->name
            : '';
        return [
            'title'         => "ESTADO INSCRIPCIÓN: {$activity} / {$this->profile->full_name}",
            'subject'       => "$this->observation",
            'user'          => $this->name,
            'created_at'    => now()->format('Y-m-d H:i:s'),
            'url'           => [
                'name'      =>  $this->profile->profile_type_id == Profile::PROFILE_PERSONAL ? "Profile" : "Person",
                'params'    =>  [ 'id'  => $this->profile->id ]
            ]
        ];
    }
}
