<?php

namespace Infrastructure\Libraries;

use Infrastructure\Exceptions as IncException;
use Illuminate\Support\Facades\Log;

class Mail
{
    public function __construct()
    {
    }

    public static function processMail($arrParams)
    {
        try {
            $gird = new SendGrid($arrParams);
            $response = $gird->build();
            if ($response->statusCode() == 413) {
                Log::error('send mail error: 413 Request Entity Too Large = ' . $arrParams['email']);
                throw new IncException\GeneralException('AWE015');
            } elseif ($response->statusCode() == 202) {
                Log::error('send mail success: ' . $arrParams['email']);
            } else {
                Log::error('send mail error' . $arrParams['email']);
                Log::error('end send mail error' . $arrParams['email']);
            }
        } catch (\Exception $e) {
            Log::error('send mail error ' . $arrParams['email']);
        }
    }

    public function sendMailForgotPassword($form, $options = [])
    {
        $email      = $options['email'];
        $subject    = __('mail_forgot_password');
        $params = array(
            'email'      => $email,
            'subject'    => $subject,
            'body'       => $form,
            'listFile'   => [],
            'data'       => $options,
        );
        self::processMail($params);
    }

    public function sendMailEngineer($form, $options = [])
    {
        $email      = $options['email'];
        $subject    = $options['project']['name_project'];
        $params = array(
            'email'      => $email,
            'subject'    => $subject,
            'body'       => $form,
            'listFile'   => [],
            'data'       => $options,
        );
        self::processMail($params);
    }

    public function sendMailPartner($form, $options = [])
    {
        $email      = $options['email'];
        $subject    = $options['project']['name_project'].__('mail_send_partner');
        $params = array(
            'email'      => $email,
            'subject'    => $subject,
            'body'       => $form,
            'listFile'   => [],
            'data'       => $options,
        );
        self::processMail($params);
    }
}
