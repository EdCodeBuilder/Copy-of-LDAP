<?php


namespace App\Modules\Contractors\src\Mail;


use App\Modules\Contractors\src\Models\Contract;
use App\Modules\Contractors\src\Models\Contractor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class ContractorSendArlMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Contractor
     */
    private $mail;

    /**
     * @var Contract
     */
    private $contract;

    /**
     * Create a new job instance.
     *
     * @param Contractor $user
     * @param Contract $contract
     */
    public function __construct(Contractor $user, Contract $contract)
    {
        $this->mail = $user;
        $this->contract = $contract;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $created_at = now()->format('Y-m-d H:i:s');
        $document = isset( $this->mail->document ) ? $this->mail->document : '';
        $first = isset( $this->mail->name ) ? $this->mail->name : '';
        $second = isset( $this->mail->surname ) ? $this->mail->surname : '';
        $name = "$first $second";
        $number = isset($this->contract->contract) ? $this->contract->contract : '000';
        $type = isset($this->contract->contract_type->name) ? $this->contract->contract_type->name : '';
        $file = $this->contract->files()->where('file_type_id', 1)->latest()->first();

        $id_file = isset($file->id) ? $file->id : null;
        $file_name = isset($file->name) ? $file->name : null;

        $subject = "{$name} / {$type} / {$number}";

        return $this->view('mail.mail')
            ->subject($subject)
            ->with([
                'header'    => 'IDRD',
                'title'     => 'Registro Portal Contratista',
                'content'   =>  "Hola {$name}, adjunto encontrarás el certificado ARL.",
                'details'   =>  "
                        <p>Nº Documento: {$document}</p>
                        <p>Nº Contrato: {$number}</p>
                        <p>Tipo de Trámite: {$type}</p>
                        <p>Fecha de Actualización: {$created_at}</p>
                        ",
                // 'hide_btn'  => true,
                'url'       =>  url(route('file.resource', ['file' => $id_file, 'name' => $file_name])),
                'info'      =>  "Si no puede visualizar el archivo adjunto de clic sobre el botón a continuación.",
                'year'      =>  Carbon::now()->year
            ])->attachFromStorageDisk('local', "arl/{$file_name}", "{$file_name}", [
                'mime' => 'application/pdf'
            ]);
    }
}
