<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengeluaranModel;
use CodeIgniter\API\ResponseTrait;

class PengeluaranController extends BaseController
{
    use ResponseTrait;

    protected $pengeluaranModel;
    protected $validation;

    public function __construct()
    {
        $this->pengeluaranModel = new PengeluaranModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Pengeluaran',
        ];

        return view('admin/pengeluaran/index', $data);
    }

    public function getPengeluaran()
    {
        $pengeluaran = $this->pengeluaranModel->orderBy('tgl', 'DESC')->findAll();

        $data = [];
        $no = 1;

        foreach ($pengeluaran as $p) {
            $row = [];
            $row[] = $no++;
            $row[] = $p['idpengeluaran'];
            $row[] = date('d-m-Y', strtotime($p['tgl']));
            $row[] = $p['keterangan'] ?? '-';
            $row[] = 'Rp ' . number_format($p['jumlah'], 0, ',', '.');

            $actions = '<a href="javascript:void(0)" class="btn btn-warning btn-sm btn-edit" data-id="' . $p['idpengeluaran'] . '">
                            <i class="bi bi-pencil"></i> Edit
                        </a> ';
            $actions .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm btn-delete" data-id="' . $p['idpengeluaran'] . '">
                            <i class="bi bi-trash"></i> Hapus
                        </a>';

            $row[] = $actions;
            $data[] = $row;
        }

        return $this->respond(['data' => $data]);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Pengeluaran',
            'validation' => $this->validation
        ];

        return view('admin/pengeluaran/create', $data);
    }

    public function store()
    {

        $rules = [
            'tgl' => 'required',
            'keterangan' => 'permit_empty|max_length[255]',
            'jumlah' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }


        $data = [
            'tgl' => $this->request->getVar('tgl'),
            'keterangan' => $this->request->getVar('keterangan'),
            'jumlah' => $this->request->getVar('jumlah'),
        ];

        $this->pengeluaranModel->insert($data);

        return $this->respondCreated(['message' => 'Data pengeluaran berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $pengeluaran = $this->pengeluaranModel->find($id);

        if (!$pengeluaran) {
            return $this->failNotFound('Data pengeluaran tidak ditemukan');
        }

        return $this->respond($pengeluaran);
    }

    public function update($id)
    {
        $pengeluaran = $this->pengeluaranModel->find($id);

        if (!$pengeluaran) {
            return $this->failNotFound('Data pengeluaran tidak ditemukan');
        }


        $rules = [
            'tgl' => 'required',
            'keterangan' => 'permit_empty|max_length[255]',
            'jumlah' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }


        $data = [
            'tgl' => $this->request->getVar('tgl'),
            'keterangan' => $this->request->getVar('keterangan'),
            'jumlah' => $this->request->getVar('jumlah'),
        ];

        $this->pengeluaranModel->update($id, $data);

        return $this->respondUpdated(['message' => 'Data pengeluaran berhasil diperbarui']);
    }

    public function delete($id)
    {
        $pengeluaran = $this->pengeluaranModel->find($id);

        if (!$pengeluaran) {
            return $this->failNotFound('Data pengeluaran tidak ditemukan');
        }

        $this->pengeluaranModel->delete($id);

        return $this->respondDeleted(['message' => 'Data pengeluaran berhasil dihapus']);
    }
}
