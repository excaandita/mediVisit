<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function getDashboard(): string
    {
        $data['title'] = "Home Dashboard";

        return view('dashboard', $data);
    }
}
