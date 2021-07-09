<?php

namespace App\Notifications\Auth;

use App\Helpers\GlpiTicket;
use App\Models\Security\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends Notification
{
    /**
     * The secondary email to send notification
     *
     * @var string
     */
    private $email;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The password reset token.
     *
     * @var User
     */
    public $user;

    /**
     * The password reset token.
     *
     * @var string|null
     */
    public $ip;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;


    /**
     * Create a notification instance.
     *
     * @param string $token
     * @param string $email
     * @param User $user
     * @param string $ip
     */
    public function __construct(string $token, string $email, User $user, $ip = "")
    {
        $this->token = $token;
        $this->email = $email;
        $this->user = $user;
        $this->ip = $ip;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }
        $url = "https://sim.idrd.gov.co/es/contrasena/reiniciar?token={$this->token}&email={$notifiable->getEmailForPasswordReset()}";
        $glpi = new GlpiTicket( $this->user,  $this->email, $url, $this->ip);
        $glpi_id = $glpi->create();

        return (new MailMessage)
            ->cc( $this->email )
            ->subject("Notificación de Restablecimiento de Contraseña")
            ->greeting('Hola')
            ->line('Recibió este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para su cuenta.')
            ->line("Se creó su caso en la mesa de servios GLPI con el número: {$glpi_id}.")
            ->action('Restablecer Contraseña', $url)
            ->line(Lang::getFromJson('Este enlace de restablecimiento de contraseña caducará en :count minutos.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line('Si no solicitó un restablecimiento de contraseña, no se requiere ninguna otra acción.')
            ->salutation('Cordialmente/Best Regards: Sistema de Información Misional S.I.M.');
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
