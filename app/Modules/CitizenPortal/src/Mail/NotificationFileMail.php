<?php


namespace App\Modules\CitizenPortal\src\Mail;


use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use App\Modules\CitizenPortal\src\Models\Status;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class NotificationFileMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var ProfileView
     */
    private $user;
    /**
     * @var Status
     */
    private $status;
    /**
     * @var File
     */
    private $file;
    private $observation;

    /**
     * Create a new job instance.
     *
     * @param ProfileView $user
     * @param Status $status
     * @param File $file
     * @param $observation
     */
    public function __construct(ProfileView $user, Status $status, File $file, $observation)
    {
        $this->user = $user;
        $this->status = $status;
        $this->file = $file;
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
        $file_type = isset( $this->file->file_type->name ) ? (string) $this->file->file_type->name : '';


        return $this->view('mail.mail')
            ->subject('Estado de Verificación de Archivos - Portal Ciudadano')
            ->with([
                'header'    => 'IDRD',
                'title'     => 'Registro Portal Ciudadano',
                'content'   =>  "¡Hola {$name}! este es el estado actual del proceso de validación de archivos adjuntos.",
                'details'   =>  "
                        <p>Número de Registro: {$id}</p>
                        <p>Nombre: {$name}</p>
                        <p>Estado de Validación de Archivo: {$this->status->name}</p>
                        <p>Archivo: {$file_type}</p>
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
