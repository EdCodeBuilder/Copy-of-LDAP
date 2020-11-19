<?php


namespace App\Helpers;


use App\Models\Security\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GlpiTicket
{

    const GLPI_USER_ID = 2;

    const USER_TYPE = [
        'REQUESTER' =>  1,
        'TECHNICIAN'=>  2,
        'OBSERVER'  =>  3,
    ];

    const STATUS = [
        'IN_PROGRESS'   =>  3,
        'CLOSED'   =>  6,
    ];

    /**
     * The HTTP instance to interact with GLPI API.
     *
     * @var Client|null
     */
    public $http;

    /**
     * The requested token for auth in GLPI.
     *
     * @var string|null
     */
    public $token;

    /**
     * The alternative user email to sent link.
     *
     * @var string|null
     */
    public $email;

    /**
     * The url generated to send to the user.
     *
     * @var string|null
     */
    public $url;

    /**
     * The user instance.
     *
     * @var User|null
     */
    public $user;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var User|null
     */
    public $glpi_user_id;

    public function __construct(User $user, $alternative_email, $url = "")
    {
        $this->email = $alternative_email;
        $this->user = $user;
        $this->url = $url;
        $this->http = new Client([
            'base_uri'  =>  env('GLPI_END_POINT'),
            'headers'   =>  [
                'Authorization' =>  "user_token ".env('GLPI_USER_TOKEN'),
                'Content-Type' =>  "application/json",
                'Accept' =>  "application/json",
                'App-Token' =>  "user_token ".env('GLPI_APP_TOKEN'),
            ]
        ]);
        $this->glpi_user_id = $this->findUser();
    }

    public function initSession()
    {
        try {
            $response = $this->http->get('initSession');
            $data = json_decode( (string) $response->getBody(), true);
            if ( isset( $data->session_token ) ) {
                $this->token = $data->session_token;
            } else {
                Log::info( "GLPI initSession - {$data}" );
            }
        } catch (ClientException $exception) {
            Log::error( "GLPI initSession - ".$exception->getMessage() );
        }
    }

    public function killSession()
    {
        try {
            $this->http->get('killSession', [
                'headers'   => [
                    'Session-Token' =>   $this->token
                ]
            ]);
        } catch (ClientException $exception) {
            Log::error( "GLPI killSession - ".$exception->getMessage() );
        }
    }
    
    public function create()
    {
        try {
            $this->initSession();
            $expire = config('auth.passwords.users.expire');
            $response = $this->http->post('Ticket', [
                'json' => [
                    "input" =>  [
                        "name"      =>  "Notificación de Restablecimiento de Contraseña - {$this->user->full_name}",
                        "content"   =>  "
                            <p>Se ha creado esta solicitud de restablecimiento de contraseña para</p><br><br>
                            <p>Usuario: {$this->user->full_name} </p><br>
                            <p>Nombre de Usuario: {$this->user->username} </p><br>
                            <p>Correo Aletarnativo: {$this->email} </p><br>
                            <p>Url de restauración: {$this->url} </p><br>
                            <p>La url caducará en {$expire} minutos</p><br>
                            <p>Se ha creado esta solicitud de restablecimiento de contraseña</p><br>
                        ",
                        "users_id_recipient"   => $this->glpi_user_id ? (int) $this->glpi_user_id : GlpiTicket::GLPI_USER_ID,
                        "type"  =>  1, // Tipo 1. Incidencia
                        "use_notification"  =>  1, //notificar al correo
                        "status"  => GlpiTicket::STATUS['CLOSED'], // En curso | Cerrado == 6
                        "urgency"  => 3,    // Medio
                        "impact"  => 3, // Medio
                        "priority"  => 3,   // Medio
                        "itilcategories_id"  => 100, // Categoría de cuentas de red
                        "requesttypes_id"   =>  6, // Solicitado desde "Otro" | "Helpdesk == 1", "Email == 2"
                    ]
                ],
                'headers'   => [
                    'Session-Token' =>   $this->token
                ]
            ])->withHeader('Session-Token', $this->token);
            $data = json_decode( (string) $response->getBody(), true);
            $this->killSession();
            if ( isset( $data->id ) ) {
                DB::table( config('auth.passwords.users.table') )
                    ->where('email', $this->user->email)
                    ->update([
                        'ticket_id' => $data->id,
                    ]);
                $this->setUsers( $data->id, $this->glpi_user_id, GlpiTicket::USER_TYPE['REQUESTER'] ); // Solicitante
                $this->setUsers( $data->id, $this->glpi_user_id, GlpiTicket::USER_TYPE['OBSERVER'] );  // Observador
                $this->setUsers( $data->id, GlpiTicket::GLPI_USER_ID, GlpiTicket::USER_TYPE['TECHNICIAN'] );  // Asignado a Técnico GLPI
                return (int) $data->id;
            } else {
                Log::info( "GLPI Create Ticket - ".$data );
            }
            return null;
        } catch (ClientException $exception) {
            $this->killSession();
            Log::error( "GLPI Create Ticket - ".$exception->getMessage() );
            return null;
        }
    }

    public function findUser()
    {
        try {
            $this->initSession();
            $response = $this->http->get('/search/User', [
                'query' => "criteria[0][field]=1&criteria[0][searchtype]=equal&criteria[0][value]={$this->user->username}&forcedisplay[0]=2",
                'headers'   => [
                    'Session-Token' =>   $this->token
                ]
            ]);
            $data = json_decode( (string) $response->getBody(), true);
            $this->killSession();
            if (isset($data->count)) {
                if ( $data->count > 1 ) {
                    return isset( $data->data[0]["2"] ) ? (int) $data->data[0]["2"] : null;
                }
            }
            return null;
        } catch (ClientException $exception) {
            $this->killSession();
            Log::error( "GLPI Find User ID - ".$exception->getMessage() );
            return null;
        }
    }

    public function update($ticket_id)
    {
        try {
            $this->initSession();
            $response = $this->http->put("/Ticket/{$ticket_id}", [
                'json' => [
                    "input" =>  [
                        "status"    => GlpiTicket::STATUS['CLOSED'],
                    ]
                ],
                'headers'   => [
                    'Session-Token' =>   $this->token
                ]
            ])->withHeader('Session-Token', $this->token);
            $data = json_decode( (string) $response->getBody(), true);
            $this->killSession();
            return isset($data->id);
        } catch (ClientException $exception) {
            Log::error( "GLPI Set Users - ".$exception->getMessage() );
            return false;
        }
    }

    public function addSolution( $ticket_id, $renew = false )
    {
        try {
            $this->initSession();
            $response = $this->http->post("/Ticket/{$ticket_id}/ITILSolution", [
                'json' => [
                    "input" =>  [
                        "itemtype"    => "Ticket",
                        "items_id"    => $ticket_id,
                        "solutiontypes_id"  => 0,
                        "content"   => $renew
                                    ? "El usuario ha realizado una nueva solicitud de restauración de contraseña o el token expiró y fue eliminado de la base de datos."
                                    : "La contraseña se ha cambiado satisfactoriamenten, la propagación de la contraseña tardará entre 45 y 60 segundos.",
                        "users_id"  =>  GlpiTicket::GLPI_USER_ID,
                        "users_id_approval" => GlpiTicket::GLPI_USER_ID,
                    ]
                ],
                'headers'   => [
                    'Session-Token' =>   $this->token
                ]
            ]);
            $this->killSession();
            $data = json_decode( (string) $response->getBody(), true);
            return isset($data->id);
        } catch (ClientException $exception) {
            $this->killSession();
            Log::error( "GLPI Add Solution - ".$exception->getMessage() );
            return false;
        }
    }

    public function setUsers( $ticket_id, $user_id, $type )
    {
        try {
            $this->initSession();
            $response = $this->http->put("/Ticket/{$ticket_id}/Ticket_User", [
                'json' => [
                    "input" =>  [
                        "tickets_id"    => $ticket_id,
                        "users_id"  => $user_id,
                        "type"      =>  $type, // 1 Solicitante, 2 Técnico, 3 Observador
                        "alternative_email" => (
                            $type == GlpiTicket::USER_TYPE['REQUESTER'] ||
                            $type == GlpiTicket::USER_TYPE['OBSERVER']
                        )
                            ? $this->email : "",
                    ]
                ],
                'headers'   => [
                    'Session-Token' =>   $this->token
                ]
            ]);
            $data = json_decode( (string) $response->getBody(), true);
            $this->killSession();
            return isset($data->id);
        } catch (ClientException $exception) {
            $this->killSession();
            Log::error( "GLPI Set Users - ".$exception->getMessage() );
            return false;
        }
    }

    public function verifyIfLatestTicketsExists()
    {
        $ticket_id = $this->getStoredTicketId();
        if ($ticket_id) {
            $this->addSolution( $ticket_id, true );
        }
    }

    public function getStoredTicketId()
    {
        $data = DB::table( config('auth.passwords.users.table') )
            ->where('email', $this->user->email)
            ->first();
        return isset( $data->ticket_id ) ? $data->ticket_id : null;
    }
}