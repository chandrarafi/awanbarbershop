<?php

namespace App\Controllers;

use App\Models\PaketModel;

class Home extends BaseController
{
    protected $paketModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
    }

    public function index(): string
    {
        $data = [
            'pakets' => $this->paketModel->findAll()

        ];

        return view('landing', $data);
    }
}
