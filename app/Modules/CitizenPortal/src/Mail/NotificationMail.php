<?php


namespace App\Modules\CitizenPortal\src\Mail;


use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use App\Modules\Parks\src\Models\Status;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class NotificationMail extends Mailable
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
     * Create a new job instance.
     *
     * @param ProfileView $user
     * @param $observation
     */
    public function __construct(ProfileView $user, $observation)
    {
        $this->user = $user;
        $this->observation = $observation;
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
        $status = isset($this->user->status) ? (string)$this->user->status : '';
        $observation_created_at = now()->format('Y-m-d H:i:s');

        return $this->view('mail.mail')
            ->subject('Estado de Verificación de Datos - Portal Ciudadano')
            ->with([
                'header'    => 'IDRD',
                'title'     => 'Registro Portal Ciudadano',
                'content'   =>  "¡Hola {$name}! este es el estado actual del proceso de validación de datos.",
                'details'   =>  "
                        <p>Número de Registro: {$id}</p>
                        <p>Nombre: {$name}</p>
                        <p>Estado de Validación de Usuario: {$status}</p>
                        <p>Observación: {$this->observation}</p>
                        <p>Fecha de observación: {$observation_created_at}</p>
                        ",
                // 'hide_btn'  => true,
                'btn_text'  => 'Ir al Portal',
                'url'       =>  env('CITIZEN_PORTAL_ENDPOINT', 'https://sim1.idrd.gov.co/Portal-Ciudadano/login'),
                'info'      =>  "Puede ingresar a la plataforma para conocer más servicios que el IDRD tiene para usted.",
                'year'      =>  Carbon::now()->year
            ]);
    }
}
