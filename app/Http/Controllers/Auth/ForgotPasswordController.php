<?php

namespace App\Http\Controllers\Auth;


use App\Helpers\GlpiTicket;
use App\Models\Security\PlantOfficials;
use App\Models\Security\User;
use App\Modules\Contractors\src\Models\Contractor;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

/**
 * @group Password
 *
 * Gestión de Contraseñas
 */
class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * The email to reset password.
     *
     * @var string|null
     */
    private $reset_email = null;

    /**
     * @group Password
     *
     * Forgot Password
     *
     * Send a reset link to the given user.
     *
     * @bodyParam document string required Número de documento del usuario. Example: 1234567
     * @response {
     *      "data": "¡Te hemos enviado por correo el enlace para restablecer tu contraseña, verifica los correos no deseados!",
     *      "details": { "email": "Hemos enviado un correo a c****@g***.com para restablecer la contraseña de tu cuenta" },
     *      "code": 200,
     *      "requested_at": "2021-09-12T16:45:39-05:00"
     * }
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $this->validateEmail($request);

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.

            $token = $this->broker()->getRepository();

            $response = $this->sendResetLink(
                $request->only('document')
            );

            return $response == Password::RESET_LINK_SENT
                ? $this->sendResetLinkResponse($request, $response)
                : $this->sendResetLinkFailedResponse($request, $response);
        } catch (\Exception $exception) {
            return $this->error_response(
                __('validation.handler.service_unavailable'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $exception->getMessage()
            );
        }
    }

    /**
     * Validate the email for the given request.
     *
     * @param Request $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['document'  =>  'required|exists:mysql_ldap.users']);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param Request $request
     * @param  string  $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        $email = mask_email( $this->reset_email );
        return $this->success_message(
            __($response),
            Response::HTTP_OK,
            Response::HTTP_OK,
            [
                'email' => "Hemos enviado un correo a $email para restablecer la contraseña de tu cuenta"
            ]
        );
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param Request $request
     * @param  string  $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return $this->error_response(
            __($response),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Send a password reset link to a user.
     *
     * @param  array  $credentials
     * @return string
     */
    public function sendResetLink(array $credentials)
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
        $user = User::where('document', $credentials['document'])->first();

        if (is_null($user)) {
            return Password::INVALID_USER;
        }
        $contractor = Contractor::query()->where('document', $credentials['document'])->first();
        $email = null;
        if (isset($contractor->email)) {
            $email = $contractor->email;
        } else {
            $plant = PlantOfficials::query()->where('document', $credentials['document'])->first();
            if (isset($plant->email)) {
                $email = $plant->email;
            }
        }
        if (is_null($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Password::INVALID_USER;
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $not_allowed = ['idrd.gov.co', 'adidrd.local'];
            // Separate string by @ characters (there should be only one)
            $parts = explode('@', $email);
            // Remove and return the last part, which should be the domain
            $domain = array_pop($parts);
            // Check if the domain is in our list
            if ( in_array($domain, $not_allowed) ) {
                return 'passwords.email';
            }
        }

        $this->reset_email = $email;

        $glpi = new GlpiTicket($user,  $email);
        $glpi->verifyIfLatestTicketsExists();

        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to reset their password. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $token = $this->broker()->getRepository();

        $user->setResetEmail($email);
        $user->sendPasswordResetNotification(
            $token->create($user)
        );

        return Password::RESET_LINK_SENT;
    }

}
