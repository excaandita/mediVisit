<?php

namespace App\Controllers\Api;

use App\Models\Setting\User;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use CodeIgniter\HTTP\IncomingRequest;

class Telebot extends ResourceController
{

    function __construct() {
        $this->limit = 10;
        $this->m_user = new User();

        $this->client = Services::curlrequest();
        $this->token = env('app.tokenTele', '0000');
    }

    public function webhook()
    {
        $request = $this->request->getJSON(true); // JSON auto-decode to array

        if (isset($request['message'])) {
            $chatId = $request['message']['chat']['id'];
            $messageText = $request['message']['text'] ?? '';

            $responseText = "Hai! Kamu mengirim: " . $messageText;

            $this->sendMessage($chatId, $responseText);
        }

        return $this->response->setJSON(['status' => 'ok']);
    }

    private function sendMessage($chatId, $text)
    {
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";

        $params = [
            'chat_id' => $chatId,
            'text'    => $text,
        ];

        $client = \Config\Services::curlrequest();
        $client->post($url, [
            'form_params' => $params
        ]);
    }
}
