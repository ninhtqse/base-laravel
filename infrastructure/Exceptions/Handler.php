<?php

namespace Infrastructure\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

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
        $this->reportable(function (Throwable $e) {
            
        });
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
        if($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return \redirect('/errors/404');
        }else if($exception instanceof \Illuminate\Validation\ValidationException){
            return redirect()->back()->withErrors($exception->errors())->withInput($request->all());
        }else if($exception instanceof ExceptionInterface) {
            $message = $this->getMessageByCode($exception->getCode(),'error');
            return redirect()->back()->withErrors(['notify_error_system' => $message]);
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