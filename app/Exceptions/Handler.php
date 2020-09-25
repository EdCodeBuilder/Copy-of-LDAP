<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if ($this->container)
            parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Exception $exception
     * @return JsonResponse|RedirectResponse|Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ( env('APP_ENV') === 'local' ) {
            return parent::render($request, $exception);
        }

        /**
         * Api Request
         */
        if ( $request->expectsJson() ) {
            if ($exception instanceof ThrottleRequestsException)
                return $this->error_response( __('validation.handler.max_attempts'), 429, $exception );
            if ($exception instanceof ValidationException)
                return $this->convertValidationExceptionToResponse( $exception, $request );
            if ($exception instanceof ModelNotFoundException)
                return $this->error_response(__('validation.handler.resource_not_found'), 404, $exception);
            if ($exception instanceof AuthenticationException)
                return $this->unauthenticated($request, $exception);
            if ($exception instanceof NotFoundHttpException)
                return $this->error_response(__('validation.handler.resource_not_found_url'), 404, $exception);
            if ($exception instanceof RelationNotFoundException)
                return $this->error_response(__('validation.handler.relation_not_found'), 404, $exception);
            if ($exception instanceof AuthorizationException)
                return $this->error_response(__('validation.handler.unauthorized'), 403, $exception);
            if ($exception instanceof MethodNotAllowedHttpException)
                return $this->error_response(__('validation.handler.method_allow'), 405, $exception);
            if ($exception instanceof ConnectionException)
                return $this->error_response(__('validation.handler.connection_refused', ['db' => 'Redis']), 405, $exception);
            if ( $exception instanceof Connection) {
                return $this->error_response( $exception, 500, $exception);
            }
            if ($exception instanceof HttpException)
                return $this->error_response($exception->getMessage(), $exception->getStatusCode());
            if ( $exception instanceof ErrorException )
                return $this->error_response(__('validation.handler.unexpected_failure'), 500, $exception);
            if ($exception instanceof PDOException)
                return $this->error_response(__('validation.handler.unexpected_failure'), 500, $exception);
            if ($exception instanceof FatalThrowableError)
                return $this->error_response(__('validation.handler.conflict'), 409, $exception);
            if ($exception instanceof QueryException) {
                if ($exception->errorInfo[0] === "23503")
                    return $this->error_response(__('validation.handler.relation_not_delete'), 409, $exception);
                if ($exception->errorInfo[0] === "42S22")
                    return $this->error_response(__('validation.handler.column_not_found'), 409, $exception);
                if ($exception->errorInfo[0] === "42S02")
                    return $this->error_response(__('validation.handler.column_not_found'), 409, $exception);
                if ($exception->errorInfo[1] == 2002)
                    return $this->error_response( __('validation.handler.connection_refused', ['db' => 'MySQL'] ), 405, $exception);
                if ($exception->errorInfo[1] == 1451)
                    return $this->error_response(__('validation.handler.relation_not_delete'), 409, $exception);
                if ($exception->errorInfo[1] == 7)
                    return $this->error_response(__('validation.handler.conflict'), 409, $exception);
                if ($exception->getCode() == 7)
                    return $this->error_response(__('validation.handler.service_unavailable'), 503, $exception);
            }
            if ($exception instanceof TokenMismatchException)
                return $this->error_response(__('validation.handler.token_mismatch'), 422, $exception);
        }
        /**
         * Request Web
         */
        if ($exception instanceof TokenMismatchException)
            return redirect()->back()->withInput($request->input());
        if ($exception instanceof QueryException) {
            if ($exception->getCode() == 7)
                return response()->view('errors.503');
        }
        if ($exception instanceof AuthenticationException)
            return $this->unauthenticated($request, $exception);
        if ($exception instanceof ClientException || $exception instanceof ServerException) {
            $status = $exception->getResponse()->getStatusCode();
            if (view()->exists("errors.{$status}")) {
                return response()->view("errors.{$status}");
            }
            return parent::render($request, $exception);
        }
        if ($exception instanceof ErrorException) {
            $status = $exception->getPrevious()->getResponse()->getStatusCode();
            if (view()->exists("errors.{$status}")) {
                return response()->view("errors.{$status}");
            } else {
                return parent::render($request, $exception);
            }
        }
        if ($exception instanceof PDOException) {
            if ($exception->getCode() == 7)
                return response()->view('errors.503');
            if ($exception->getCode() == 2002)
                return response()->view('errors.503');
        }
        if ($exception instanceof HttpException) {
            $status = $exception->getStatusCode();
            if (view()->exists("errors.{$status}"))
                return response()->view("errors.{$status}", ['exception' => $exception], $status, $exception->getHeaders());
            return parent::render($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param ValidationException $e
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        return $request->expectsJson()
            ? $this->validation_errors(
                $e->validator->errors()->getMessages(),
                422
            )
            : $this->invalid($request, $e);
    }
    /**
     * Convert an authentication exception into a response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|RedirectResponse|Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ?   $this->error_response(__('validation.handler.unauthenticated'), 401, $exception)
            :   redirect()->guest(route('home'));
    }
}
