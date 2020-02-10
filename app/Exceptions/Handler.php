<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use App\Libraries\Api;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use App\Libraries\Exceptions\ExceptionFormat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    use ApiResponser;

    /**
     * A list of the exception types that should not be reported.
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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception $exception
     * @throws Exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponse|RedirectResponse|Response
     */
    public function render($request, Exception $exception)
    {
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));

            return $this->errorResponse(
                "No existe ninguna instancia de {$modelName} con el id especificado",
                Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof AuthorizationException){
            return $this->errorResponse(
                'Unauthorized',
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse(
                'El método especificado en la petición no es válido',
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse(
                'No se encontró la URL especificada',
                Response::HTTP_NOT_FOUND
            );
        }

        if($exception instanceof QueryException){
            return $this->errorResponse(
                $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                Api::CODE_ERROR_DB
            );
        }

        if($exception instanceof ExceptionFormat){
            return $this->errorResponse(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getStatus()
            );
        }

        if($exception instanceof Exception){
            return $this->errorResponse(
                $exception->getMessage(),
                $exception->getCode()
            );
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse(
                $exception->getMessage(),
                $exception->getStatusCode()
            );
        }

        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }

        return $this->errorResponse(
            'Falla inesperada. Intente luego',
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|RedirectResponse
     */
    protected function unauthenticated(Request $request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest(route('login'));
        }

        return $this->errorResponse(
            $exception->getMessage(),
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param ValidationException $exc
     * @param Request $request
     * @return $this|JsonResponse
     */
    protected function convertValidationExceptionToResponse(ValidationException $exc, $request)
    {
        $errors = $exc->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return redirect()->back()->withInput(
                $request->input()
            )->withErrors($errors);
        }

        $errors = $this->getFormatValidationErrors($errors);

        return $this->errorResponse(
            'Data validation error',
            Response::HTTP_UNPROCESSABLE_ENTITY,
            Api::CODE_ERROR,
            [Api::IDX_STR_JSON_ERRORS => $errors]
        );
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

    private function getFormatValidationErrors($errors){
        $response = [];

        foreach ($errors as $key => $value) {
            $response[] = [
                'field' => $key
                , 'message' => $value[0]
            ];
        }

        return $response;
    }
}
