<?php


namespace App\Notifications;


use App\Modules\Contractors\src\Models\Contract;
use App\Modules\Contractors\src\Models\Contractor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Imtigger\LaravelJobStatus\JobStatus;

class ExportReady extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $file;
    private $url;

    /**
     * Create a new notification instance.
     *
     * @param $file
     * @param $url
     */
    public function __construct($file, $url)
    {
        $this->file = $file;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $exist = Storage::disk('local')->exists("exports/{$this->file}");

        $job = JobStatus::query()->where('key', $this->file)->first();
        if (isset($job->key)) {
            $job->status = 'exported';
            $job->finished_at = now();
            $job->save();
        }

        return [
            'title'         => 'Solicitud de Reporte',
            'subject'       => "El archivo que solicitaste está listo para su descarga.",
            'user'          => 'SISTEMA',
            'created_at'    => now()->format('Y-m-d H:i:s'),
            'url'           => [
                'name'      =>  'reports',
            ]
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Solicitud de reporte - $this->file")
            ->greeting('¡Hola!')
            ->line('El archivo que solicitaste está listo para su descarga.')
            ->line("Nombre del Archivo: $this->file")
            ->action('Ver Archivo', $this->url)
            ->line('¡Gracia por usar nuestra aplicación!');
    }
}
