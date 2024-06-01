<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    public function send($toEmail, $toName, $Subject, $template)
    {
        $content = file_get_contents(dirname(__DIR__) . '/Mail/' . $template);
        $mj = new Client ($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'],true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "developpeur0011@gmail.com",
                        'Name' => "MINT7"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 6016200,
                    'TemplateLanguage' => true,
                    'Subject' =>  $Subject,
                    'Variables' =>[
                        'content' => $content
                    ]
                ]
            ]
        ];

        $mj->post(Resources::$Email, ['body' => $body]);
        

    }

}