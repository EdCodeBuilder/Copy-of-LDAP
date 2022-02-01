<?php


namespace App\Modules\CitizenPortal\src\Mail;


use App\Modules\CitizenPortal\src\Models\ProfileView;
use App\Modules\CitizenPortal\src\Models\Status;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var ProfileView
     */
    private $user;

    /**
     * @var string
     */
    private $observation;

    /**
     * @var Status
     */
    private $status;

    /**
     * Create a new job instance.
     *
     * @param ProfileView $user
     * @param Status $status
     * @param $observation
     */
    public function __construct(ProfileView $user, Status $status, $observation)
    {
        $this->user = $user;
        $this->observation = $observation;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $id = isset( $this->user->id ) ? (int) $this->user->id : '';
        $name = isset( $this->user->full_name ) ? (string) $this->user->full_name : '';

        return $this->view('mail.mail')
            ->subject('Estado de Incripción - Portal Ciudadano')
            ->with([
                'header'    => 'IDRD',
                'title'     => 'Registro Portal Ciudadano',
                'content'   =>  "¡Hola {$name}! este es el estado actual del proceso de inscripción a actividades.",
                'details'   =>  "
                        <p>Número de Registro: {$id}</p>
                        <p>Nombre: {$name}</p>
                        <p>Estado de su Inscripción: {$this->status->name}</p>
                        <p>Observación: {$this->observation}</p>
                ",
                // 'hide_btn'  => true,
                'btn_text'  => 'Ir al Portal',
                'url'       =>  env('CITIZEN_PORTAL_ENDPOINT', 'https://sim1.idrd.gov.co/Portal-Ciudadano/login'),
                'info'      =>  "Puede ingresar a la plataforma para conocer más servicios que el IDRD tiene para usted.",
                'year'      =>  Carbon::now()->year
            ]);
    }
}
