<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function getPoliklinik(): string
    {
        $data['title'] = "Poliklinik";

        return view('dashboard/poliklinik', $data);
    }

    public function getIgd(): string
    {
        $data['title'] = "IGD";

        return view('dashboard/igd', $data);
    }

    public function getRawatInap(): string 
    {
        $data['title'] = "Rawat Inap";

        return view('dashboard/rawat-inap', $data);
    }
}
