<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private string $api_key = 'e813f44df7d597a16ba1b0670b61e5a7';
    private string $api_key_secret = '6124dfdf6cbc3d5eae42887044ff0b4b';

    public function send($to_email, $to_name, $subject, $content): void
    {
        $mj = new Client($this->api_key,$this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "scoreo@mail.com",
                        'Name' => "Shopingo"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4398692,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}