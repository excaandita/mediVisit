<?php
namespace App\Controllers\script;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use CodeIgniter\HTTP\IncomingRequest;
use App\Models\Setting\User;

class waPendapatanHarian extends ResourceController
{

    function __construct() {
        $this->client = Services::curlrequest();
        $this->token = env('app.tokenBlast', '0000');
        $this->apiUrl = env('app.simrsURL', 'http://0.0.0.0:9000');
        $this->blastUrl = env('app.waBlastURL', '0000');
    }

    public function fetchDataPendapatanPerHari($date, $day_before=1) : array {
        $search = [
            'date' => $date,
            'day_before'  => $day_before
        ];
        $api = $this->apiUrl.'/kunjungan_api/visit/pendapatan';
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
    
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Error parsing JSON response: ' . json_last_error_msg());
            }
    
            return [
                'success' => true,
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }    
    }

    public function getDataPendapatanPerHari() : ResponseInterface {
        $date = date('Y-m-d');

        $result = $this->fetchDataPendapatanPerHari($date);
        if ($result['success']) {
            return $this->respond([
                'metadata' => [
                    'code' => 200,
                    'message' => 'Success'
                ],
                'response' => $result['data']
            ], 200);
        } else {
            return $this->respond([
                'metadata' => [
                    'code' => 500,
                    'message' => 'An error occurred while fetching data from the API.'
                ],
                'response' => [
                    'message' => $result['error']
                ]
            ], 500);
        }
    }

    public function sendMessage()
    {
        return $this->postSendMessage();
    }

    public function postSendMessage()
    {
        $client = \Config\Services::curlrequest();

        $userModel = new User();
        $phoneList = $userModel->get_phone_user();
        $phoneJson = json_encode($phoneList);

        $response = $this->getDataPendapatanPerHari();
        $body = $response->getBody();
        $result = json_decode($body, true);
        $startDate = $result['response']['start_date'];
        $endDate = $result['response']['end_date'];
        $pasienNow = $result['response']['data']['summary']['grand_total']['pasien'];
        $incomeNow = $result['response']['data']['summary']['grand_total']['pendapatan'];
        $pasienRajal = $result['response']['data']['summary']['bpjs']['rajal']['pasien'];
        $pasienRanap = $result['response']['data']['summary']['bpjs']['ranap']['pasien'];
        $klaimRajal = $result['response']['data']['summary']['bpjs']['rajal']['klaim'];
        $klaimRanap = $result['response']['data']['summary']['bpjs']['ranap']['klaim'];
        $tarifRajal = $result['response']['data']['summary']['bpjs']['rajal']['tagihan'];
        $tarifRanap = $result['response']['data']['summary']['bpjs']['ranap']['tagihan'];
        $pasienTunai = $result['response']['data']['summary']['tunai']['pasien'];
        $pasienAsuransi = $result['response']['data']['summary']['asuransi']['pasien'];
        $tagihanTunai = $result['response']['data']['summary']['tunai']['tagihan'];
        $tagihanAsuransi = $result['response']['data']['summary']['asuransi']['tagihan'];
        $message = <<<EOD
        📊 LAPORAN KUNJUNGAN DAN PENDAPATAN RS TEST

        🗓️ Tanggal : {$startDate} s.d {$endDate}

        🔹 Ringkasan Pasien & Pendapatan:

        Total Pasien Rawat Jalan : {$pasienRajal}
        Total Pasien Rawat Inap : {$pasienRanap}
        Total Pasien Hari Ini : {$pasienNow}
        Total Pendapatan : Rp. {$incomeNow}

        🔹 Rincian Jenis Pelayanan:

        Klaim BPJS

        Rawat Jalan:
        🧍‍♂️ {$pasienRajal} pasien — 💳 Rp. {$klaimRajal} (klaim) — 🏥 Rp. {$tarifRajal} (tarif RS)

        Rawat Inap:
        🧍‍♂️ {$pasienRanap} pasien — Rp. {$klaimRanap} (klaim) — 🏥 Rp. {$tarifRanap} (tarif RS)

        Non-BPJS

        Asuransi Lainnya:
        🧍‍♂️ {$pasienTunai} pasien — 💰 Rp. {$tagihanTunai}

        Tunai/Umum:
        🧍‍♂️ {$pasienAsuransi} pasien — 💰 Rp. {$tagihanAsuransi}

        Laporan ini dikirim secara otomatis sebagai bentuk transparansi dan pemantauan kinerja rumah sakit oleh manajemen.

        📌 Mohon tidak membalas pesan ini.
        (test without reloading page)
        EOD;

        $params = [
            'apiKey'  => $this->token,
            'phone'   => $phoneJson,
            'message' => $message,
            'delay'   => 0
        ];

        try {
            $response = $client->post($this->blastUrl, [
                'form_params' => $params
            ]);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            echo "<script>console.error('WA Error: " . addslashes($error) . "');</script>";
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pesan berhasil dikirim.'
        ]);
    }


}
