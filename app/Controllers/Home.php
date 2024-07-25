<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function getDashboard(): string
    {
        return view('dashboard');
    }
}
