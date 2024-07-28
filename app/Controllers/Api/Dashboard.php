<?php

namespace App\Controllers\Api;

use App\Models\Setting\User;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;

class Dashboard extends ResourceController
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

    public function getDashboard(): ResponseInterface {
        $search = array(
            'layanan'       => $this->request->getVar('layanan'),
            'kelamin'       => $this->request->getVar('kelamin'),
            'start_date'    => $this->request->getVar('start_date'),
            'end_date'      => $this->request->getVar('end_date')
        );

        $api = $this->apiUrl.'/kunjungan_api/visit/dashboard_all';
    
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

    public function getDashboardWidget(): ResponseInterface {
        $search = array(
            'start_date'    => $this->request->getVar('start_date'),
            'end_date'      => $this->request->getVar('end_date')
        );

        $api = $this->apiUrl.'/kunjungan_api/visit/dashboard_widget';
    
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

    public function getListPoliklinik(): ResponseInterface {
        $search = array(
            'page'          => $this->request->getVar('page'),
            'id_poliklinik' => $this->request->getVar('id_poliklinik'),
            'id_penjamin'   => $this->request->getVar('id_penjamin'),
            'kelamin'       => $this->request->getVar('kelamin'),
            'start_date'    => $this->request->getVar('start_date'),
            'end_date'      => $this->request->getVar('end_date')
        );

        $api = $this->apiUrl.'/kunjungan_api/visit/poli_all';
    
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

    public function getListIgd(): ResponseInterface {
        $search = array(
            'page'          => $this->request->getVar('page'),
            'jenis_igd'     => $this->request->getVar('jenis_igd'),
            'id_penjamin'   => $this->request->getVar('id_penjamin'),
            'kelamin'       => $this->request->getVar('kelamin'),
            'start_date'    => $this->request->getVar('start_date'),
            'end_date'      => $this->request->getVar('end_date')
        );

        $api = $this->apiUrl.'/kunjungan_api/visit/igd_all';
    
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
    
    public function getListRawatInap(): ResponseInterface {
        $search = array(
            'page'          => $this->request->getVar('page'),
            'id_bangsal'    => $this->request->getVar('id_bangsal'),
            'id_penjamin'   => $this->request->getVar('id_penjamin'),
            'kelamin'       => $this->request->getVar('kelamin'),
            'start_date'    => $this->request->getVar('start_date'),
            'end_date'      => $this->request->getVar('end_date')
        );

        $api = $this->apiUrl.'/kunjungan_api/visit/rawat_inap_all';
    
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

    public function getListLaboratorium(): ResponseInterface {
        $search = array(
            'page'          => $this->request->getVar('page'),
            'id_penjamin'   => $this->request->getVar('id_penjamin'),
            'kelamin'       => $this->request->getVar('kelamin'),
            'start_date'    => $this->request->getVar('start_date'),
            'end_date'      => $this->request->getVar('end_date')
        );

        $api = $this->apiUrl.'/kunjungan_api/visit/laboratorium_all';
    
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

    public function getListRadiologi(): ResponseInterface {
        $search = array(
            'page'          => $this->request->getVar('page'),
            'id_penjamin'   => $this->request->getVar('id_penjamin'),
            'kelamin'       => $this->request->getVar('kelamin'),
            'start_date'    => $this->request->getVar('start_date'),
            'end_date'      => $this->request->getVar('end_date')
        );

        $api = $this->apiUrl.'/kunjungan_api/visit/radiologi_all';
    
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
