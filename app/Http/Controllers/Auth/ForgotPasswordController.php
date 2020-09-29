<?php

namespace App\Http\Controllers\Auth;


use App\Models\Security\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $token = $this->broker()->getRepository();

        $response = $this->sendResetLink(
            $request->only('username', 'document', 'email')
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Validate the email for the given request.
     *
     * @param Request $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'username'  =>  'required|exists:mysql_ldap.users',
            'document'  =>  'required|exists:mysql_ldap.users',
        ]);
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
        return $this->success_message(
            __($response),
            Response::HTTP_OK
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
        $user = User::where([
            ['username', $credentials['username']],
            ['document', $credentials['document']]
        ])->first();

        if (is_null($user)) {
            return Password::INVALID_USER;
        }

        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to reset their password. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $token = $this->broker()->getRepository();

        $user->sendPasswordResetNotification(
            $token->create($user)
        );

        return Password::RESET_LINK_SENT;
    }

}
