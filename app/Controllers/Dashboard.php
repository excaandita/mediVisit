<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function getPendapatan(): string 
    {
        $data['title'] = "Analisa Pendapatan";

        return view('dashboard/pendapatan', $data);

    }

    public function getKunjungan(): string
    {
        $data['title'] = "Analisa Kunjungan";

        return view('dashboard/kunjungan', $data);
    }
}
