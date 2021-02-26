<?php


namespace App\Modules\Contractors\src\Mail;


use App\Modules\Contractors\src\Models\Contractor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class ContractorMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Contractor
     */
    private $mail;

    /**
     * Create a new job instance.
     *
     * @param Contractor $user
     */
    public function __construct(Contractor $user)
    {
        $this->mail = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $id = isset( $this->mail->id ) ? $this->mail->id : '';
        $created_at = isset( $this->mail->created_at ) ? $this->mail->created_at->format('Y-m-d H:i:s') : '';

        $document = isset( $this->mail->document ) ? $this->mail->document : '';
        $first = isset( $this->mail->name ) ? $this->mail->name : '';
        $second = isset( $this->mail->surname ) ? $this->mail->surname : '';
        $name = "$first $second";

        $document = Crypt::encrypt($document);

        $path = env('APP_ENV') == 'local'
            ? env('APP_PATH_DEV')
            : env('APP_PATH_PROD');

        return $this->view('mail.mail')
            ->subject('Creación de Usuario Portal Contratista')
            ->with([
                'header'    => 'IDRD',
                'title'     => 'Registro Portal Contratista',
                'content'   =>  "¡{$name}! hemos registrado sus datos de forma satisfactoria. Por favor ingrese al link que se relaciona a continuación para que complete el registro de datos personales.",
                'details'   =>  "
                        <p>Número de Registro: {$id}</p>
                        <p>Nombre: {$name}</p>
                        <p>Fecha de Registro: {$created_at}</p>
                        ",
                // 'hide_btn'  => true,
                'url'       =>  "https://sim.idrd.gov.co/{$path}/es/contracts?payload=$document",
                'info'      =>  "Será notificado a este correo electrónico una vez se haya expedido su certificado de afiliación a la ARL Positiva.",
                'year'      =>  Carbon::now()->year
            ]);
    }
}
