<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketModel extends Model
{
    protected $table            = 'paket';
    protected $primaryKey       = 'idpaket';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idpaket', 'namapaket', 'deskripsi', 'harga', 'image', 'durasi'];


    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function __construct()
    {
        parent::__construct();
        $this->initValidationRules();
    }

    protected function initValidationRules()
    {
        $this->validationRules = [
            'idpaket' => [
                'rules' => 'required|max_length[10]|is_unique[paket.idpaket,idpaket,{idpaket}]',
                'errors' => [
                    'required' => 'ID Paket harus diisi',
                    'max_length' => 'ID Paket maksimal 10 karakter',
                    'is_unique' => 'ID Paket sudah ada dalam database'
                ]
            ],
            'namapaket' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama Paket harus diisi',
                    'min_length' => 'Nama Paket minimal 3 karakter',
                    'max_length' => 'Nama Paket maksimal 100 karakter'
                ]
            ],
            'deskripsi' => [
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Deskripsi harus diisi',
                    'min_length' => 'Deskripsi minimal 10 karakter'
                ]
            ],
            'harga' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Harga harus diisi',
                    'numeric' => 'Harga harus berupa angka',
                    'greater_than' => 'Harga harus lebih besar dari 0'
                ]
            ],
            'durasi' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Durasi harus diisi',
                    'numeric' => 'Durasi harus berupa angka',
                    'greater_than' => 'Durasi harus lebih besar dari 0'
                ]
            ],
            'gambar' => [
                'rules' => 'uploaded[gambar]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,2048]',
                'errors' => [
                    'uploaded' => 'Gambar harus dipilih',
                    'is_image' => 'File yang dipilih bukan gambar',
                    'mime_in' => 'Format gambar tidak didukung (gunakan JPG, JPEG, atau PNG)',
                    'max_size' => 'Ukuran gambar terlalu besar (maksimal 2MB)'
                ]
            ]
        ];
    }

    public function validateImage($file)
    {
        if (!$file->isValid()) {
            return [
                'status' => false,
                'error' => 'File tidak valid: ' . $file->getErrorString()
            ];
        }


        $maxSize = 2 * 1024 * 1024; // 2MB dalam bytes
        if ($file->getSize() > $maxSize) {
            $fileSizeMB = number_format($file->getSize() / (1024 * 1024), 2);
            return [
                'status' => false,
                'error' => "Ukuran file ({$fileSizeMB}MB) terlalu besar (maksimal 2MB)"
            ];
        }


        $validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file->getMimeType(), $validTypes)) {
            return [
                'status' => false,
                'error' => 'Format file tidak didukung (gunakan JPG, JPEG, atau PNG)'
            ];
        }

        return [
            'status' => true,
            'error' => null
        ];
    }

    public function uploadImage($image)
    {
        if (!$image->isValid()) {
            return [
                'status' => false,
                'error' => 'File tidak valid'
            ];
        }

        if (!in_array($image->getExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
            return [
                'status' => false,
                'error' => 'Format file harus jpg, jpeg, png, atau gif'
            ];
        }

        if ($image->getSizeByUnit('mb') > 2) {
            return [
                'status' => false,
                'error' => 'Ukuran file maksimal 2MB'
            ];
        }

        $filename = $image->getRandomName();

        if ($image->move('uploads/paket', $filename)) {
            return [
                'status' => true,
                'filename' => $filename
            ];
        }

        return [
            'status' => false,
            'error' => 'Gagal mengupload file'
        ];
    }

    public function generateId()
    {
        $lastId = $this->select('idpaket')
            ->orderBy('idpaket', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();

        if (!$lastId) {
            return 'PKT001';
        }

        $lastNumber = (int) substr($lastId->idpaket, 3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return 'PKT' . $newNumber;
    }

    public function save($data): bool
    {

        if (!empty($data['idpaket'])) {
            $this->validationRules['idpaket']['rules'] = 'required|max_length[10]|is_unique[paket.idpaket,idpaket,' . $data['idpaket'] . ']';
        }


        if (empty($data['image'])) {
            unset($this->validationRules['gambar']);
        }

        return parent::save($data);
    }

    public function deleteImage($filename)
    {
        $path = FCPATH . 'uploads/paket/' . $filename;
        if (file_exists($path)) {
            return unlink($path);
        }
        return true;
    }
}
