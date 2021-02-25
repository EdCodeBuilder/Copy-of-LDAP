<?php


namespace App\Modules\Contractors\src\Notifications;


use App\Modules\Contractors\src\Models\Contract;
use App\Modules\Contractors\src\Models\Contractor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ArlNotification extends Notification
{
    use Queueable;

    /**
     * @var Contractor
     */
    private $contractor;

    /**
     * @var Contract
     */
    private $contract;

    /**
     * Create a new notification instance.
     *
     * @param Contractor $contractor
     * @param Contract $contract
     */
    public function __construct(Contractor $contractor, Contract $contract)
    {
        $this->contractor = $contractor;
        $this->contract = $contract;
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
        $id = isset( $this->contractor->id ) ? $this->contractor->id : '';
        $first = isset( $this->contractor->name ) ? $this->contractor->name : '';
        $second = isset( $this->contractor->surname ) ? $this->contractor->surname : '';
        $number = isset($this->contract->contract) ? $this->contract->contract : '000';
        $file = $this->contract->files()->where('file_type_id', 1)->latest()->first();
        $type = isset($this->contract->contract_type->name) ? $this->contract->contract_type->name : '';
        $user = isset($file->user->full_name) ? $file->user->full_name : 'SYSTEM';
        $name = "$first $second";
        $subject = "{$name} / {$type} / {$number}";
        return [
            'title'         => 'Actualización de Datos',
            'subject'       => $subject,
            'user'          => $user,
            'created_at'    => now()->format('Y-m-d H:i:s'),
            'url'           => [
                'name'      =>  'user-id-contractor',
                'params'    =>  [ 'id'  => $id ]
            ]
        ];
    }
}
