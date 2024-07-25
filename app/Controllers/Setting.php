<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Setting extends BaseController
{
    public function getUser()
    {
        $data['title'] = "User";

        return view('setting/user', $data);
    }
}
