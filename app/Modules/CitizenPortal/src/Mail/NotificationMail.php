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
     * Create a new job instance.
     *
     * @param ProfileView $user
     */
    public function __construct(ProfileView $user)
    {
        $this->user = $user;
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

        $observation = $this->user->observations()->latest()->first();

        $status = isset($this->user->status) ? (string)$this->user->status : '';

        $observation_text = isset( $observation->observation )
            ? (string) $observation->observation
            : '';

        $observation_created_at = isset( $observation->created_at )
            ? $observation->created_at->format('Y-m-d H:i:s')
            : '';

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
                        <p>Observación: {$observation_text}</p>
                        <p>Fecha de observación: {$observation_created_at}</p>
                        ",
                // 'hide_btn'  => true,
                'url'       =>  "https://idrd.gov.co/Portal-Ciudadano/login",
                'info'      =>  "Puede ingresar a la plataforma para conocer más servicios que el IDRD tiene para usted.",
                'year'      =>  Carbon::now()->year
            ]);
    }
}
