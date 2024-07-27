<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function getPoliklinik(): string
    {
        $data['title'] = "Poliklinik";

        return view('dashboard/poliklinik', $data);
    }
}
