<?php

namespace App\Controllers\Api;

use App\Models\Setting\User;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;

class Masterdata extends ResourceController
{
    function __construct() {
        $this->limit = 10;
        $this->m_user = new User();

        $this->client = Services::curlrequest();
        $this->apiUrl = env('app.simrsURL', 'http://0.0.0.0:9000');
    }
    
    private function start($page){
        return (($page - 1) * $this->limit);
    }

    public function getKlinik(): ResponseInterface {
        $search = array(
            'page'  => $this->request->getVar('page'),
            'q'     => $this->request->getVar('q'),
            'ikw'   => $this->request->getVar('ikw'),
            'filter_active'       => $this->request->getVar('filter_active')
        );

        $api = $this->apiUrl.'/e_doc_api/masterdata/klinik';
    
        $options = [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => $search
        ];
    
        try {
            $response = $this->client->get($api, $options);
            $body = $response->getBody();
            $data = json_decode($body, true);
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Error parsing JSON response: ' . json_last_error_msg());
            }
    
            return $this->respond($data, 200);
    
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->respond(['error' => 'An error occurred while fetching data from the API.'], 500);
        }
    }
    
}
