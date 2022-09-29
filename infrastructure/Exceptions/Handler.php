<?php

namespace Infrastructure\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Infrastructure\Libraries\Adapter;
use Infrastructure\Libraries\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {});
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        return $this->renderException($request, $exception);
        return parent::render($request, $exception);
    }

    protected function renderException($request, $exception) {
        if(App::make('Adapter')->getWeb()){
            if($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return \redirect('/errors/404');
            }else if($exception instanceof \Illuminate\Validation\ValidationException){
                return redirect()->back()->withErrors($exception->errors())->withInput($request->all());
            }else if($exception instanceof ExceptionInterface) {
                $message = $this->getMessageByCode($exception->getCode(),'error');
                return redirect()->back()->withErrors(['notify_error_system' => $message]);
            }
        }else{
            $response = new Response();
            $debugMode = \Config('config.app_debug');
            if ($exception instanceof ExceptionInterface) {
                $message = $response->renderError(
                    $exception->getCode(),
                    $exception->getMessage(),
                    $exception->getData(),
                    null,
                    $exception->getParameters()
                );
            } elseif ($exception instanceof \Illuminate\Database\QueryException) {
                if ($debugMode) {
                    $message = $response->renderError('AWE003', $exception->getMessage(), null, $exception->getMessage());
                } else {
                    $message = $response->renderError('AWE003', null, null, $exception->getMessage());
                }
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                $message = $response->renderError('AWE001', null, null, $exception->getMessage());
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $message = $response->renderError('AWE002', null, null, $exception->getMessage());
            } elseif ($exception instanceof \Illuminate\Contracts\Encryption\DecryptException) {
                $message = $response->renderError('AWE010', null, null, $exception->getMessage());
            } elseif ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $message = $response->renderError('AWE007', null, null, $exception->getMessage());
            }else {
                $file = $exception->getFile();
                $line = $exception->getLine();
                $message = $exception->getMessage();
                if ($debugMode) {
                    $message = $response->renderError(
                        'AWE999',
                        'File: ' . $file . ', Line: ' . $line . ', Message: ' . $message,
                        null,
                        $exception->getMessage()
                    );
                } else {
                    $message = $response->renderError('AWE999', null, null, $exception->getMessage());
                }
            }
            return $message;
        }
    }

    //=================> SUPPORT METHOD <======================
    private function getMessageByCode($code,$status)
    {
        $statusList = file_get_contents(base_path('config/status_list.json'));
        $statusList = json_decode($statusList,true);
        $message = __(@$statusList[$status][$code]);
        return $message;
    }
}