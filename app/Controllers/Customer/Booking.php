<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\KaryawanModel;
use App\Models\PaketModel;
use App\Models\PelangganModel;
use App\Models\PembayaranModel;
use App\Models\UserModel;

class Booking extends BaseController
{
    protected $bookingModel;
    protected $detailBookingModel;
    protected $karyawanModel;
    protected $paketModel;
    protected $pelangganModel;
    protected $pembayaranModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->detailBookingModel = new DetailBookingModel();
        $this->karyawanModel = new KaryawanModel();
        $this->paketModel = new PaketModel();
        $this->pelangganModel = new PelangganModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Menampilkan daftar booking pelanggan
     */
    public function index()
    {
        // Pastikan user sudah login sebagai pelanggan
        if (!session()->get('logged_in') || session()->get('role') != 'pelanggan') {
            return redirect()->to(site_url('customer/login?redirect=booking'));
        }

        // Ambil ID pelanggan dari session user
        $userId = session()->get('user_id');
        $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

        if (!$pelanggan) {
            return redirect()->to(site_url('customer/profil'))->with('error', 'Silakan lengkapi profil Anda terlebih dahulu');
        }

        $idPelanggan = $pelanggan['idpelanggan'];

        $data = [
            'title' => 'History Booking',
            'idpelanggan' => $idPelanggan
        ];

        return view('customer/booking/index', $data);
    }

    /**
     * Mendapatkan data booking milik pelanggan untuk datatable
     */
    public function getBookings()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        // Ambil ID pelanggan dari session user
        $userId = session()->get('user_id');
        $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

        if (!$pelanggan) {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Data pelanggan tidak ditemukan'
            ]);
        }

        $idPelanggan = $pelanggan['idpelanggan'];

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = $this->request->getGet('search')['value'];

        // Get booking data with joins
        $builder = $this->db->table('booking b')
            ->select('b.*, k.nama as namakaryawan, p.nama_lengkap, p.no_hp, db.nama_paket, db.jamstart, db.jamend')
            ->join('detail_booking db', 'b.kdbooking = db.kdbooking', 'left')
            ->join('karyawan k', 'k.idkaryawan = db.idkaryawan', 'left')
            ->join('pelanggan p', 'p.idpelanggan = b.idpelanggan', 'left')
            ->where('b.idpelanggan', $idPelanggan);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('b.kdbooking', $search)
                ->orLike('b.tanggal_booking', $search)
                ->orLike('db.nama_paket', $search)
                ->orLike('b.status', $search)
                ->groupEnd();
        }

        $totalRecords = $builder->countAllResults(false);

        $builder->orderBy('b.tanggal_booking', 'DESC')
            ->orderBy('db.jamstart', 'ASC')
            ->limit($length, $start);

        $result = $builder->get()->getResultArray();

        // Format data for datatables
        $data = [];
        foreach ($result as $row) {
            $statusMap = [
                'pending' => 'Menunggu Konfirmasi',
                'confirmed' => 'Terkonfirmasi',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                'no-show' => 'Tidak Hadir'
            ];

            $row['status_text'] = $statusMap[$row['status']] ?? $row['status'];
            $row['total_formatted'] = 'Rp ' . number_format($row['total'], 0, ',', '.');

            $data[] = $row;
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    /**
     * Menampilkan form booking baru
     */
    public function create()
    {
        // Pastikan user sudah login sebagai pelanggan
        if (!session()->get('logged_in') || session()->get('role') != 'pelanggan') {
            return redirect()->to(site_url('customer/login?redirect=booking'));
        }

        // Ambil ID pelanggan dari session user
        $userId = session()->get('user_id');
        $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

        if (!$pelanggan) {
            return redirect()->to(site_url('customer/profil'))->with('error', 'Silakan lengkapi profil Anda terlebih dahulu');
        }

        // Jika ada ID paket yang dipilih
        $paketId = $this->request->getGet('paket');
        $selectedPaket = null;

        if ($paketId) {
            $selectedPaket = $this->paketModel->find($paketId);
        }

        // Ambil semua paket untuk dropdown jika tidak ada yang dipilih
        $paketList = $this->paketModel->findAll();

        $data = [
            'title' => 'Form Booking',
            'pelanggan' => $pelanggan,
            'paketList' => $paketList,
            'selectedPaket' => $selectedPaket
        ];

        return view('customer/booking/create', $data);
    }

    /**
     * Menyimpan data booking baru
     */
    public function store()
    {
        // Pastikan user sudah login sebagai pelanggan
        if (!session()->get('logged_in') || session()->get('role') != 'pelanggan') {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }

        $this->db->transBegin();

        try {
            $data = $this->request->getPost();

            // Ambil ID pelanggan dari session user
            $userId = session()->get('user_id');
            $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

            if (!$pelanggan) {
                throw new \Exception('Data pelanggan tidak ditemukan');
            }

            $idPelanggan = $pelanggan['idpelanggan'];

            // Generate kode booking baru
            $kdbooking = $this->bookingModel->generateBookingCode();

            // Periksa secara manual apakah kdbooking sudah ada di database
            $existingBooking = $this->bookingModel->find($kdbooking);
            if ($existingBooking) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Kode booking sudah ada dalam database'
                ]);
            }

            // Persiapkan data booking
            $bookingData = [
                'kdbooking' => $kdbooking,
                'idpelanggan' => $idPelanggan,
                'tanggal_booking' => $data['tanggal_booking'],
                'status' => 'pending', // default status untuk booking dari pelanggan
                'jenispembayaran' => 'DP', // default jenis pembayaran
                'jumlahbayar' => 0, // belum ada pembayaran
                'total' => $data['total'],
                'idkaryawan' => $data['idkaryawan'] ?? null,
            ];

            // Simpan data booking
            if (!$this->bookingModel->save($bookingData)) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal membuat booking',
                    'errors' => $this->bookingModel->errors()
                ]);
            }

            // Dapatkan data paket
            $paket = $this->paketModel->find($data['idpaket']);

            if (!$paket) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Paket tidak ditemukan'
                ]);
            }

            // Simpan detail booking
            $detailData = [
                'iddetail' => $this->detailBookingModel->generateDetailId(),
                'tgl' => $data['tanggal_booking'],
                'kdbooking' => $kdbooking,
                'kdpaket' => $paket['idpaket'],
                'nama_paket' => $paket['namapaket'],
                'deskripsi' => $paket['deskripsi'],
                'harga' => $paket['harga'],
                'jamstart' => $data['jamstart'],
                'jamend' => $data['jamend'] ?? date('H:i', strtotime($data['jamstart'] . ' +1 hour')),
                'status' => '1', // 1 = Pending
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

    /**
     * Menampilkan detail booking
     */
    public function detail($id = null)
    {
        // Pastikan user sudah login sebagai pelanggan
        if (!session()->get('logged_in') || session()->get('role') != 'pelanggan') {
            return redirect()->to(site_url('customer/login?redirect=booking'));
        }

        if (empty($id)) {
            return redirect()->to('customer/booking')->with('error', 'Kode booking tidak valid');
        }

        // Ambil ID pelanggan dari session user
        $userId = session()->get('user_id');
        $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

        if (!$pelanggan) {
            return redirect()->to(site_url('customer/profil'))->with('error', 'Data pelanggan tidak ditemukan');
        }

        $idPelanggan = $pelanggan['idpelanggan'];

        // Ambil data booking
        $booking = $this->getBookingWithPelanggan($id);

        if (!$booking) {
            return redirect()->to('customer/booking')->with('error', 'Data booking tidak ditemukan');
        }

        // Pastikan booking hanya bisa dilihat oleh pemiliknya
        if ($booking['idpelanggan'] != $idPelanggan) {
            return redirect()->to('customer/booking')->with('error', 'Anda tidak memiliki akses ke booking ini');
        }

        $details = $this->detailBookingModel->getDetailsByBookingCode($id);
        $pembayaran = $this->pembayaranModel->getPembayaranByBookingCode($id);

        $data = [
            'title' => 'Detail Booking',
            'booking' => $booking,
            'details' => $details,
            'pembayaran' => $pembayaran
        ];

        return view('customer/booking/detail', $data);
    }

    /**
     * Get booking data with pelanggan data
     */
    private function getBookingWithPelanggan($kdbooking)
    {
        $builder = $this->db->table('booking b')
            ->select('b.*, p.*')
            ->join('pelanggan p', 'p.idpelanggan = b.idpelanggan', 'left')
            ->where('b.kdbooking', $kdbooking);

        return $builder->get()->getRowArray();
    }

    /**
     * Memeriksa ketersediaan karyawan dan slot waktu
     */
    public function checkAvailability()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $tanggal = $this->request->getGet('tanggal');

        if (empty($tanggal)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tanggal harus diisi'
            ]);
        }

        // Format tanggal ke Y-m-d
        $tanggal = date('Y-m-d', strtotime($tanggal));

        // Ambil semua booking yang ada pada tanggal tersebut
        $bookings = $this->db->table('detail_booking')
            ->where('tgl', $tanggal)
            ->get()->getResultArray();

        // Ambil semua karyawan aktif
        $karyawanList = $this->karyawanModel->where('status', 'aktif')->findAll();
        $karyawanIds = array_column($karyawanList, 'idkaryawan');

        // Daftar slot waktu yang tersedia (jam operasional 09:00 - 21:00)
        $slots = [
            '09:00',
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
            '17:00',
            '18:00',
            '19:00',
            '20:00'
        ];

        // Mengecek ketersediaan untuk setiap slot waktu
        $availability = [];
        foreach ($slots as $slot) {
            $endTime = date('H:i', strtotime($slot . ' +1 hour'));

            // Cari karyawan yang tersedia di slot ini
            $availableKaryawan = $karyawanIds;

            foreach ($bookings as $booking) {
                $bookingStart = $booking['jamstart'];
                $bookingEnd = $booking['jamend'];
                $bookingKaryawan = $booking['idkaryawan'];

                // Jika ada overlap waktu, tandai karyawan tersebut sebagai tidak tersedia
                if ($this->isOverlapping($slot, $endTime, $bookingStart, $bookingEnd) && in_array($bookingKaryawan, $availableKaryawan)) {
                    $availableKaryawan = array_diff($availableKaryawan, [$bookingKaryawan]);
                }
            }

            $status = count($availableKaryawan) > 0 ? 'available' : 'booked';

            $availability[] = [
                'time' => $slot,
                'status' => $status,
                'availableKaryawan' => $availableKaryawan
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $availability
        ]);
    }

    /**
     * Mendapatkan daftar karyawan yang tersedia pada slot waktu tertentu
     */
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

        // Format tanggal ke Y-m-d
        $tanggal = date('Y-m-d', strtotime($tanggal));

        // Jam selesai (1 jam setelah jam mulai)
        $jamend = date('H:i', strtotime($jamstart . ' +1 hour'));

        // Ambil semua booking di slot waktu yang sama
        $bookings = $this->db->table('detail_booking')
            ->where('tgl', $tanggal)
            ->get()->getResultArray();

        // Ambil semua karyawan aktif
        $karyawanList = $this->karyawanModel->where('status', 'aktif')->findAll();

        // Cari karyawan yang tersedia
        $availableKaryawan = [];

        foreach ($karyawanList as $karyawan) {
            $isAvailable = true;

            foreach ($bookings as $booking) {
                $bookingStart = $booking['jamstart'];
                $bookingEnd = $booking['jamend'];
                $bookingKaryawan = $booking['idkaryawan'];

                // Jika karyawan sama dan waktunya overlap, tandai tidak tersedia
                if (
                    $bookingKaryawan == $karyawan['idkaryawan'] &&
                    $this->isOverlapping($jamstart, $jamend, $bookingStart, $bookingEnd)
                ) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $availableKaryawan[] = [
                    'id' => $karyawan['idkaryawan'],
                    'nama' => $karyawan['nama'],
                    'foto' => $karyawan['foto'] ?? null
                ];
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $availableKaryawan
        ]);
    }

    /**
     * Helper: Memeriksa apakah dua rentang waktu saling overlap
     */
    private function isOverlapping($start1, $end1, $start2, $end2)
    {
        return ((strtotime($start1) < strtotime($end2)) && (strtotime($end1) > strtotime($start2)));
    }
}
