<?php

namespace Infrastructure\Libraries;

class Response
{
    private $status;
    private $code;
    private $data;
    private $message;

    public function __construct(){}
/*====================================SERVICE METHODS====================================*/


    public function renderSuccess($code = 'AWS001', $data = null, $message = null)
    {
        $this->success($code, $data, $message);
        return $this->render();
    }

    public function renderError($code, $message = null, $data = null, $fatalError = null, $parameters = [])
    {
        $this->error($code, $message, $data, $fatalError, $parameters);
        return $this->render();
    }
    public function success($code, $data = null, $message = null)
    {
        $this->data = $data;
        $this->code = $code;
        $this->message = $message;
        $this->status = 'success';
    }

    public function error($code, $message = null, $data = [], $fatalError = null, $parameters = [])
    {
        $this->data = $data;
        $this->code = $code;
        if (!$message) {
            $message = $this->getStatusMessage($parameters);
        }
        $this->message = $message;
        $this->fatalError = $fatalError;
        $this->status = 'error';
    }

    public function render()
    {
        switch ($this->status) {
            case 'success':
                $success = array(
                    'status' => 'success',
                );
                if ($this->message) {
                    $success['message'] = $this->message;
                }
                if ($this->code) {
                    $success['code'] = $this->code;
                }
                $success['data'] = ($this->data) ? $this->data : (object) array();
                return \Response::json($success, 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;
            case 'error':
                $error = array(
                    'status' => 'error',
                    'message' => $this->message,
                );
                if ($this->code) {
                    $error['code'] = $this->code;
                }
                if ($this->data) {
                    $error['data'] = $this->data;
                }
                return \Response::json(
                    $error,
                    $this->getCodeStatus(),
                    [],
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                );
                break;
        }
    }

    public function getStatusMessage($parameters = [])
    {
        $statusList = file_get_contents(base_path('config/status_list.json'));
        $statusList = json_decode($statusList);

        foreach ($statusList->error as $code => $message) {
            if ($code == $this->code) {
                return $this->getMessage($parameters, __($message));
            }
        }
    }

    //=============> PRIVATE METHOD <===============
    private function getMessage($parameters, $message)
    {
        if ($parameters) {
            $count = count($parameters);
            $arr = [];
            for ($i = 0; $i < $count; $i++) {
                $arr[] = '$' . ($i + 1);
            }
            return str_replace($arr, $parameters, $message);
        } else {
            return $message;
        }
    }

    private function getCodeStatus()
    {
        switch ($this->code) {
            case 'AWE004':
                $code = 400;
                break;
            case 'AWE999':
                $code = 500;
                break;
            case 'AWE002':
                $code = 404;
                break;
            case 'AWE007':
                $code = 404;
                break;
            case 'AWE013':
                $code = 403;
                break;
            case 'AWE005':
                $code = 401;
                break;
            case 'AWE001':
                $code = 405;
                break;
            case 'AWE014':
                $code = 415;
                break;
            case 'AWE015':
                $code = 406;
                break;
            default:
                $code = 422;
                break;
        }
        return $code;
    }
}
