<?php


namespace App\Modules\CitizenPortal\src\Notifications;

use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProfileStatusNotification extends Notification
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
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $observation;

    /**
     * Create a new notification instance.
     * @param ProfileView $profile
     * @param Status $status
     * @param $observation
     * @param $name
     */
    public function __construct(Profile $profile, Status $status, $observation, $name)
    {

        $this->profile = $profile;
        $this->status = $status;
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
        return [
            'title'         => "VALIDACIÓN DATOS PERSONALES: {$this->profile->full_name}",
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
