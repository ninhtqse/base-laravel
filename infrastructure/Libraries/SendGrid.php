<?php

namespace Infrastructure\Libraries;

use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class SendGrid extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $subject;

    public $email;

    public $data;

    public function __construct($data = [])
    {
        $this->email    = @$data['email'];
        $this->data     = @$data;
        $this->subject  = @$data['subject'];
    }

    public function build()
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        $email->setSubject($this->subject);
        $email->addTo($this->email, $this->email);
        $email->addContent('text/html', view($this->data['body'], ['options' => @$this->data['data']])->render());

        if (@$this->data['listFile']) {
            foreach ($this->data['listFile'] as $item) {
                $expl      = explode('.', $item);
                $expl_name = explode('/', $item);
                $extension = end($expl);
                $file_name = end($expl_name);
                $attach    = [
                    base64_encode(file_get_contents($item)),
                    $extension,
                    $file_name,
                    "attachment",
                    uuid()
                ];
                $email->addAttachment($attach);
            }
        }
        if (@$this->data['listFileS3']) {
            foreach ($this->data['listFileS3'] as $item) {
                $expl      = explode('.', $item['file_name']);
                $extension = end($expl);
                $attach    = [
                    base64_encode($item['file_data']),
                    $extension,
                    $item['file_name'],
                    "attachment",
                    uuid()
                ];
                $email->addAttachment($attach);
            }
        }
        $sendgrid = new \SendGrid(env('MAIL_PASSWORD'));
        $response = $sendgrid->send($email);
        return $response;
    }

    public function bounces()
    {
        $apiKey = env('MAIL_PASSWORD');
        $sg = new \SendGrid($apiKey);
        try {
            $response = $sg->client->suppression()->bounces()->get();
            return [$response->statusCode(),$response->headers(),$response->body()];
        } catch (\Exception $e) {
            echo 'Caught exception: ' .  $e->getMessage() . "\n";
        }
    }

    public function deleteBounce($email)
    {
        $apiKey = env('MAIL_PASSWORD');
        $sg = new \SendGrid($apiKey);
        $query_params = json_decode('{
            "email_address": "' . $email . '"
        }');
        $response = $sg->client->suppression()->bounces()->_($email)->delete(null, $query_params);
        return [$response->statusCode(),$response->headers(),$response->body()];
    }
}
