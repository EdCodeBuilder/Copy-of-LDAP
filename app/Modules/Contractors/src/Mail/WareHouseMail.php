<?php


namespace App\Modules\Contractors\src\Mail;


use App\Modules\Contractors\src\Models\Certification;
use App\Modules\Contractors\src\Models\Contractor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class WareHouseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Contractor
     */
    private $mail;

    /**
     * @var Certification
     */
    private $certification;

    /**
     * Create a new job instance.
     *
     * @param Contractor $user
     * @param Certification $certification
     */
    public function __construct(Contractor $user, Certification $certification)
    {
        $this->mail = $user;
        $this->certification = $certification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $full_name = isset( $this->mail->full_name ) ? $this->mail->full_name : '';
        $code = isset($this->certification->code) ? $this->certification->code : null;

        $path = env('APP_ENV') == 'local'
            ? env('APP_PATH_DEV')
            : env('APP_PATH_PROD');

        $style = "font-family:'Open Sans',Arial,sans-serif;font-size:42px;color:#3b3b3b;font-weight:bold;letter-spacing:4px;text-align:center";

        return $this->view('mail.mail')
            ->subject('Código de Verificación Reporte Almacén')
            ->with([
                'header'    => 'IDRD',
                'title'     => 'Código de Verificación',
                'content'   =>  "¡Hola {$full_name}! para consultar el reporte de activos a cargo, por favor ingrese el siguiente código de verificación.",
                'details'   =>  "<h1 style=\"$style\">{$code}</h1>",
                // 'hide_btn'  => true,
                'url'       =>  "https://sim.idrd.gov.co/{$path}/es/certificados",
                'info'      =>  "Si no solicitó un código de verificación de reporte de activos almacén, no se requiere ninguna otra acción.",
                'year'      =>  Carbon::now()->year
            ]);
    }
}
