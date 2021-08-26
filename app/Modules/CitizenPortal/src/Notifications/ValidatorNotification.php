<?php


namespace App\Modules\CitizenPortal\src\Notifications;

use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ValidatorNotification extends Notification
{
    use Queueable;

    /**
     * @var Profile
     */
    private $profile;

    /**
     * @var string
     */
    private $subject = 'Asignación de Usuario';

    /**
     * @var string
     */
    private $user;

    /**
     * Create a new notification instance.
     *
     * @param Profile $profile
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
        $this->user = isset($profile->assigned->full_name)
            ? (string) $profile->assigned->full_name
            : 'SYSTEM';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $id = isset($this->profile->id) ? (int) $this->profile->id : 0;
        $full_name = isset($this->profile->full_name) ? (string) $this->profile->full_name : '';

        return [
            'title'         => $this->subject,
            'subject'       => $full_name,
            'user'          => $this->user,
            'created_at'    => now()->format('Y-m-d H:i:s'),
            'url'           => [
                'name'      =>  'user-validation-id-citizen',
                'params'    =>  [ 'id'  => $id ]
            ]
        ];
    }
}
