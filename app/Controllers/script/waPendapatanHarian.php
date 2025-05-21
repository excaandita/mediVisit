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
        $this->RS = env('app.namaRS', 'Umum');
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
        $date = date('Y-m-d', strtotime('-1 day'));

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

        $response   = $this->getDataPendapatanPerHari();
        $body       = $response->getBody();
        $result     = json_decode($body, true);

        $startDate      = $result['response']['start_date'];
        $endDate        = $result['response']['end_date'];
        $pasienNow      = $result['response']['data']['summary']['grand_total']['pasien'];
        $incomeNow      = number_format($result['response']['data']['summary']['grand_total']['pendapatan'], 2, ',', '.');
        $pasienRajalBpjs = $result['response']['data']['summary']['bpjs']['rajal']['pasien'];
        $pasienRanap    = $result['response']['data']['summary']['bpjs']['ranap']['pasien'];
        $klaimRajal     = $result['response']['data']['summary']['bpjs']['rajal']['klaim'];
        $klaimRanap     = $result['response']['data']['summary']['bpjs']['ranap']['klaim'];
        $tarifRajal     = $result['response']['data']['summary']['bpjs']['rajal']['tagihan'];
        $tarifRanap     = $result['response']['data']['summary']['bpjs']['ranap']['tagihan'];
        $pasienTunai    = $result['response']['data']['summary']['tunai']['pasien'];
        $pasienAsuransi = $result['response']['data']['summary']['asuransi']['pasien'];
        $tagihanTunai   = $result['response']['data']['summary']['tunai']['tagihan'];
        $tagihanAsuransi = $result['response']['data']['summary']['asuransi']['tagihan'];

        // Data Pasien RJ
        $dataPasienRJTunai    = $result['response']['data']['rawat_jalan']['tunai'][0]['jumlah_transaksi_pasien'];
        $dataPasienRJBPJS     = $result['response']['data']['rawat_jalan']['bpjs'][0]['jumlah_transaksi_pasien'];
        $dataPasienRJAsuransi = $result['response']['data']['rawat_jalan']['asuransi'][0]['jumlah_transaksi_pasien'];

        $totalPasienRJ = $dataPasienRJTunai + $dataPasienRJBPJS + $dataPasienRJAsuransi;
        // echo "<pre>".number_format($result['response']['data']['rawat_jalan']['bpjs'][0]['total_tagihan_rs'], 2, ',', '.')."</pre>"; die;
        
        // Data Klaim RJ BPJS
        $totalTagihanRJBpjs = number_format($result['response']['data']['rawat_jalan']['bpjs'][0]['total_tagihan_rs'],2, ',', '.');

        $totalKlaimRJBpjs   = number_format($result['response']['data']['rawat_jalan']['bpjs'][0]['total_klaim'], 2, ',', '.');

        // Data Pasien RI
        $dataPasienRITunai    = $result['response']['data']['rawat_inap']['tunai'][0]['jumlah_transaksi_pasien'];
        $dataPasienRIBPJS     = $result['response']['data']['rawat_inap']['bpjs'][0]['jumlah_transaksi_pasien'];
        $dataPasienRIAsuransi = $result['response']['data']['rawat_inap']['asuransi'][0]['jumlah_transaksi_pasien'];

        $totalPasienRI = $dataPasienRITunai + $dataPasienRIBPJS + $dataPasienRIAsuransi;

        // Data Klaim RI BPJS
        $totalTagihanRIBpjs = number_format($result['response']['data']['rawat_inap']['bpjs'][0]['total_tagihan_rs'], 2, ',', '.');
        $totalKlaimRIBpjs   = number_format($result['response']['data']['rawat_inap']['bpjs'][0]['total_klaim'], 2, ',', '.');

        // Asuransi RJ + RI
        $totalPasienAsuransi  = $dataPasienRJAsuransi + $dataPasienRIAsuransi;
        $totalNominalAsuransi = number_format(($result['response']['data']['rawat_jalan']['asuransi'][0]['total_nominal'] + $result['response']['data']['rawat_inap']['asuransi'][0]['total_nominal']), 2, ',', '.');

        $totalPasienTunai  = $dataPasienRJTunai + $dataPasienRITunai;
        $totalNominalTunai = number_format(($result['response']['data']['rawat_jalan']['tunai'][0]['total_nominal'] + $result['response']['data']['rawat_inap']['tunai'][0]['total_nominal']), 2, ',', '.');
        
        $message = <<<EOD
        📊 LAPORAN KUNJUNGAN DAN PENDAPATAN HARIAN RS {$this->RS}

        🗓️ Tanggal : {$startDate}

        🔹 Ringkasan Pasien & Pendapatan:

        Total Pasien Rawat Jalan : {$totalPasienRJ}
        Total Pasien Rawat Inap : {$totalPasienRI}
        Total Pasien Hari Ini : {$pasienNow}
        Total Pendapatan : Rp. {$incomeNow}

        🔹 Rincian Jenis Pelayanan:

        Klaim BPJS

        Rawat Jalan:
        🧍‍♂️ {$dataPasienRJBPJS} pasien — 💳 Rp. {$totalKlaimRJBpjs} (klaim) — 🏥 Rp. {$totalTagihanRJBpjs} (tarif RS)

        Rawat Inap:
        🧍‍♂️ {$dataPasienRIBPJS} pasien — Rp. {$totalKlaimRIBpjs} (klaim) — 🏥 Rp. {$totalTagihanRIBpjs} (tarif RS)

        Non-BPJS

        Asuransi Lainnya:
        🧍‍♂️ {$totalPasienAsuransi} pasien — 💰 Rp. {$totalNominalAsuransi}

        Tunai/Umum:
        🧍‍♂️ {$totalPasienTunai} pasien — 💰 Rp. {$totalNominalTunai}

        Laporan ini dikirim secara otomatis sebagai bentuk transparansi dan pemantauan kinerja rumah sakit oleh manajemen.

        📌 Mohon tidak membalas pesan ini.
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
            'message' => 'Pesan berhasil dikirim.',
            'tanggal' => $startDate
        ]);
    }

    function formatcurrency($floatcurr)
    {
        $result = number_format($floatcurr, 2, ',', '.');
        return $result;
    }

}
