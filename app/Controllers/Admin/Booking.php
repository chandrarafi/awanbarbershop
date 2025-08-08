<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\KaryawanModel;
use App\Models\PaketModel;
use App\Models\PembayaranModel;
use App\Models\PelangganModel;
use CodeIgniter\I18n\Time;
use Config\Database;

class Booking extends BaseController
{
    protected $bookingModel;
    protected $detailBookingModel;
    protected $karyawanModel;
    protected $paketModel;
    protected $pelangganModel;
    protected $pembayaranModel;
    protected $db;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->detailBookingModel = new DetailBookingModel();
        $this->karyawanModel = new KaryawanModel();
        $this->paketModel = new PaketModel();
        $this->pelangganModel = new PelangganModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->db = Database::connect();
    }

    public function index()
    {
        $title = 'Kelola Booking';
        return view('admin/booking/index', compact('title'));
    }

    public function create()
    {
        $title = 'Tambah Booking';
        $karyawanList = $this->karyawanModel->where('status', 'aktif')->findAll();
        $paketList = $this->paketModel->findAll();

        return view('admin/booking/create', compact('title', 'karyawanList', 'paketList'));
    }

    public function getBookings()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Invalid request'
            ]);
        }

        $request = $this->request;

        $start = (int) $request->getGet('start');
        $length = (int) $request->getGet('length');
        $search = trim($request->getGet('search')['value'] ?? '');
        $order = $request->getGet('order') ?? [];
        $filterStatus = $request->getGet('status'); // Filter berdasarkan status


        $builder = $this->db->table('booking b')
            ->select('b.*, p.nama_lengkap, p.no_hp, k.namakaryawan')
            ->join('pelanggan p', 'p.idpelanggan = b.idpelanggan', 'left')
            ->join('karyawan k', 'k.idkaryawan = b.idkaryawan', 'left');


        $totalRecords = $this->db->table('booking')->countAllResults();


        if (!empty($filterStatus)) {
            $builder->where('b.status', $filterStatus);
        }


        if (!empty($search)) {
            $searchValue = $this->db->escapeLikeString($search);

            $builder->groupStart()
                ->orLike('b.kdbooking', $searchValue, 'both', null, true)
                ->orLike('p.nama_lengkap', $searchValue, 'both', null, true)
                ->orLike('p.no_hp', $searchValue, 'both', null, true)
                ->orLike('k.namakaryawan', $searchValue, 'both', null, true)
                ->groupEnd();
        }


        $totalFiltered = $builder->countAllResults(false);


        $columns = ['b.kdbooking', 'p.nama_lengkap', 'b.tanggal_booking', 'b.status', 'b.jenispembayaran', 'k.namakaryawan'];
        $orderColumn = isset($order[0]['column']) ? (int) $order[0]['column'] : 0;
        $orderDir = isset($order[0]['dir']) ? strtoupper($order[0]['dir']) : 'DESC';
        $orderField = $columns[$orderColumn] ?? 'b.created_at';


        $results = $builder->orderBy($orderField, $orderDir)
            ->limit($length, $start)
            ->get()
            ->getResultArray();


        $data = array_map(function ($row) {

            $statusText = $this->getStatusText($row['status']);

            return [
                'kdbooking' => $row['kdbooking'],
                'nama_lengkap' => $row['nama_lengkap'] ?? 'Pelanggan tidak ditemukan',
                'no_hp' => $row['no_hp'] ?? '-',
                'tanggal_booking' => date('d-m-Y', strtotime($row['tanggal_booking'])),
                'status' => $row['status'],
                'status_text' => $statusText,
                'jenispembayaran' => $row['jenispembayaran'],
                'total' => $row['total'],
                'total_formatted' => 'Rp ' . number_format($row['total'], 0, ',', '.'),
                'jumlahbayar' => $row['jumlahbayar'],
                'jumlahbayar_formatted' => 'Rp ' . number_format($row['jumlahbayar'], 0, ',', '.'),
                'namakaryawan' => $row['namakaryawan'] ?? 'Belum ditentukan',
                'actions' => ''
            ];
        }, $results);

        return $this->response->setJSON([
            'draw' => (int) $request->getGet('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    private function getStatusText($status)
    {
        $statusMap = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Terkonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'no-show' => 'Tidak Hadir'
        ];

        return $statusMap[$status] ?? $status;
    }

    public function store()
    {
        $this->db->transBegin();

        try {
            $data = $this->request->getPost();


            $kdbooking = $this->bookingModel->generateBookingCode();


            $bookingData = [
                'kdbooking' => $kdbooking,
                'idpelanggan' => $data['idpelanggan'],
                'tanggal_booking' => $data['tanggal_booking'],
                'status' => 'pending',
                'jenispembayaran' => $data['jenispembayaran'],
                'jumlahbayar' => $data['jumlahbayar'] ?? 0,
                'total' => $data['total'],
                'idkaryawan' => $data['idkaryawan'] ?? null,
            ];


            if (!$this->bookingModel->save($bookingData)) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal membuat booking',
                    'errors' => $this->bookingModel->errors()
                ]);
            }


            $paketModel = new PaketModel();
            $paket = $paketModel->find($data['idpaket']);

            if (!$paket) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Paket tidak ditemukan'
                ]);
            }


            $detailData = [
                'iddetail' => $this->detailBookingModel->generateDetailId(),
                'tgl' => $data['tanggal_booking'],
                'kdbooking' => $kdbooking,
                'idpaket' => $paket['kdpaket'],
                'nama_paket' => $paket['namapaket'],
                'deskripsi' => $paket['deskripsi'],
                'harga' => $paket['harga'],
                'jamstart' => $data['jamstart'],
                'jamend' => $data['jamend'],
                'status' => 1, // Status default: 1 (booked)
                'idkaryawan' => $data['idkaryawan'] ?? null,
            ];

            if (!$this->detailBookingModel->save($detailData)) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal membuat detail booking',
                    'errors' => $this->detailBookingModel->errors()
                ]);
            }


            if ($data['jenispembayaran'] != '' && $data['jumlahbayar'] > 0) {
                $pembayaranData = [
                    'fakturbooking' => $kdbooking,
                    'total_bayar' => $data['jumlahbayar'],
                    'grandtotal' => $data['total'],
                    'metode' => $data['metode_pembayaran'] ?? 'cash',
                    'status' => 'paid',
                ];

                if (!$this->pembayaranModel->save($pembayaranData)) {
                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal mencatat pembayaran',
                        'errors' => $this->pembayaranModel->errors()
                    ]);
                }
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Booking berhasil dibuat',
                'kdbooking' => $kdbooking
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id = null)
    {
        if (empty($id)) {
            return redirect()->to('admin/booking')->with('error', 'Kode booking tidak valid');
        }

        $booking = $this->bookingModel->getBookingWithPelanggan($id);

        if (!$booking) {
            return redirect()->to('admin/booking')->with('error', 'Data booking tidak ditemukan');
        }

        $details = $this->detailBookingModel->getDetailsByBookingCode($id);
        $pembayaran = $this->pembayaranModel->getPembayaranByBookingCode($id);

        $title = 'Detail Booking';
        return view('admin/booking/show', compact('title', 'booking', 'details', 'pembayaran'));
    }

    public function edit($id = null)
    {
        if (empty($id)) {
            return redirect()->to('admin/booking')->with('error', 'Kode booking tidak valid');
        }

        $booking = $this->bookingModel->getBookingWithPelanggan($id);

        if (!$booking) {
            return redirect()->to('admin/booking')->with('error', 'Data booking tidak ditemukan');
        }

        $details = $this->detailBookingModel->getDetailsByBookingCode($id);
        $karyawanList = $this->karyawanModel->findAll();
        $paketList = $this->paketModel->findAll();

        $title = 'Edit Booking';
        return view('admin/booking/edit', compact('title', 'booking', 'details', 'karyawanList', 'paketList'));
    }

    public function update($id = null)
    {
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Kode booking tidak valid'
            ]);
        }

        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Data booking tidak ditemukan'
            ]);
        }

        $this->db->transBegin();

        try {
            $data = $this->request->getPost();


            $bookingData = [
                'kdbooking' => $id,
                'tanggal_booking' => $data['tanggal_booking'] ?? $booking['tanggal_booking'],
                'status' => $data['status'] ?? $booking['status'],
                'jenispembayaran' => $data['jenispembayaran'] ?? $booking['jenispembayaran'],
                'jumlahbayar' => $data['jumlahbayar'] ?? $booking['jumlahbayar'],
                'total' => $data['total'] ?? $booking['total'],
                'idkaryawan' => $data['idkaryawan'] ?? $booking['idkaryawan'],
            ];

            if (!$this->bookingModel->save($bookingData)) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui booking',
                    'errors' => $this->bookingModel->errors()
                ]);
            }


            if (isset($data['detail_id']) && !empty($data['detail_id'])) {
                $detailId = $data['detail_id'];


                $paketModel = new PaketModel();
                $paket = null;

                if (isset($data['idpaket']) && !empty($data['idpaket'])) {
                    $paket = $paketModel->find($data['idpaket']);

                    if (!$paket) {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(400)->setJSON([
                            'status' => 'error',
                            'message' => 'Paket tidak ditemukan'
                        ]);
                    }
                }

                $detail = $this->detailBookingModel->find($detailId);

                if ($detail) {
                    $detailData = [
                        'iddetail' => $detailId,
                        'tgl' => $data['tanggal_booking'] ?? $detail['tgl'],
                        'kdbooking' => $id,
                        'status' => $data['detail_status'] ?? $detail['status'],
                        'jamstart' => $data['jamstart'] ?? $detail['jamstart'],
                        'jamend' => $data['jamend'] ?? $detail['jamend'],
                        'idkaryawan' => $data['idkaryawan'] ?? $detail['idkaryawan'],
                    ];


                    if ($paket) {
                        $detailData['idpaket'] = $paket['kdpaket'];
                        $detailData['nama_paket'] = $paket['namapaket'];
                        $detailData['deskripsi'] = $paket['deskripsi'];
                        $detailData['harga'] = $paket['harga'];
                    }

                    if (!$this->detailBookingModel->save($detailData)) {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(400)->setJSON([
                            'status' => 'error',
                            'message' => 'Gagal memperbarui detail booking',
                            'errors' => $this->detailBookingModel->errors()
                        ]);
                    }
                }
            }


            if (
                isset($data['tambah_pembayaran']) && $data['tambah_pembayaran'] == '1' &&
                isset($data['jumlah_bayar_tambahan']) && $data['jumlah_bayar_tambahan'] > 0
            ) {

                $pembayaranData = [
                    'fakturbooking' => $id,
                    'total_bayar' => $data['jumlah_bayar_tambahan'],
                    'grandtotal' => $data['total'] ?? $booking['total'],
                    'metode' => $data['metode_pembayaran'] ?? 'cash',
                    'status' => 'paid',
                ];

                if (!$this->pembayaranModel->save($pembayaranData)) {
                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal mencatat pembayaran tambahan',
                        'errors' => $this->pembayaranModel->errors()
                    ]);
                }


                $totalBayar = $booking['jumlahbayar'] + $data['jumlah_bayar_tambahan'];
                $this->bookingModel->update($id, ['jumlahbayar' => $totalBayar]);
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Booking berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function updateStatus()
    {
        $kdbooking = $this->request->getPost('kdbooking');
        $status = $this->request->getPost('status');

        if (empty($kdbooking) || empty($status)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Kode booking dan status harus diisi'
            ]);
        }

        $booking = $this->bookingModel->find($kdbooking);

        if (!$booking) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Data booking tidak ditemukan'
            ]);
        }


        $this->bookingModel->update($kdbooking, ['status' => $status]);


        $detailStatus = '1'; // Default: Pending

        switch ($status) {
            case 'confirmed':
                $detailStatus = '2'; // Confirmed
                break;
            case 'completed':
                $detailStatus = '3'; // Completed
                break;
            case 'cancelled':
            case 'no-show':
                $detailStatus = '4'; // Cancelled
                break;
        }

        $this->db->table('detail_booking')
            ->where('kdbooking', $kdbooking)
            ->set(['status' => $detailStatus])
            ->update();

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Status booking berhasil diperbarui'
        ]);
    }

    public function delete($id = null)
    {
        if (empty($id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Kode booking tidak valid'
            ]);
        }

        $this->db->transBegin();

        try {

            $this->db->table('detail_booking')
                ->where('kdbooking', $id)
                ->delete();


            $this->db->table('pembayaran')
                ->where('fakturbooking', $id)
                ->delete();


            if (!$this->bookingModel->delete($id)) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menghapus booking'
                ]);
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Booking berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function searchPelanggan()
    {
        $keyword = $this->request->getGet('term');

        if (empty($keyword)) {

            $pelanggan = $this->pelangganModel->orderBy('created_at', 'DESC')->findAll(20);
        } else {

            $pelanggan = $this->pelangganModel->like('nama_lengkap', $keyword)
                ->orLike('no_hp', $keyword)
                ->findAll(10);
        }

        $result = [];
        foreach ($pelanggan as $p) {
            $result[] = [
                'id' => $p['idpelanggan'],
                'text' => $p['nama_lengkap'] . ' (' . $p['no_hp'] . ')'
            ];
        }

        return $this->response->setJSON($result);
    }

    public function checkAvailability()
    {
        $tanggal = $this->request->getGet('tanggal');
        $jamstart = $this->request->getGet('jamstart');
        $jamend = $this->request->getGet('jamend');
        $idkaryawan = $this->request->getGet('idkaryawan');

        if (empty($tanggal) || empty($jamstart) || empty($jamend) || empty($idkaryawan)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Semua parameter harus diisi'
            ]);
        }

        $isAvailable = $this->detailBookingModel->checkKaryawanAvailability(
            $idkaryawan,
            $tanggal,
            $jamstart,
            $jamend
        );

        return $this->response->setJSON([
            'status' => 'success',
            'available' => $isAvailable
        ]);
    }

    public function getKaryawanSchedule()
    {
        $tanggal = $this->request->getGet('tanggal');
        $idkaryawan = $this->request->getGet('idkaryawan');

        if (empty($tanggal) || empty($idkaryawan)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Tanggal dan ID Karyawan harus diisi'
            ]);
        }

        $schedule = $this->detailBookingModel->getKaryawanSchedule($idkaryawan, $tanggal);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $schedule
        ]);
    }

    public function getAvailableKaryawan()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $tanggal = $this->request->getGet('tanggal');
        $jamstart = $this->request->getGet('jamstart');

        if (empty($tanggal) || empty($jamstart)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Tanggal dan jam mulai harus diisi'
            ]);
        }

        $karyawanList = $this->karyawanModel->getAvailableKaryawan($tanggal, $jamstart);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $karyawanList
        ]);
    }
}
