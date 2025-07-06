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

class BookingNewController extends BaseController
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
        // Periksa dan perbarui status booking yang expired
        $this->checkExpiredBookings();

        $title = 'Kelola Booking';
        return view('admin/booking_new/index', compact('title'));
    }

    public function create()
    {
        $pelangganModel = new PelangganModel();
        $paketModel = new PaketModel();

        $data = [
            'title' => 'Tambah Booking Baru',
            'pelanggan' => $pelangganModel->findAll(),
            'paketList' => $paketModel->findAll(),
        ];

        return view('admin/booking_new/create_multi', $data);
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
        $dateFilter = $request->getGet('date_filter'); // Filter berdasarkan tanggal

        // Parameter untuk mengelompokkan berdasarkan kode booking
        $groupByKdbooking = false;
        if ($request->getGet('group_by_kdbooking')) {
            $groupByKdbooking = ($request->getGet('group_by_kdbooking') === 'true');
        }

        // Query dasar dengan joins untuk data lengkap dan informasi waktu booking dari detail_booking
        if ($groupByKdbooking) {
            // Query untuk mengelompokkan booking berdasarkan kode booking
            $builder = $this->db->table('booking b')
                ->select('b.*, p.nama_lengkap, p.no_hp, k.namakaryawan, 
                          (SELECT COUNT(*) FROM detail_booking WHERE kdbooking = b.kdbooking) as jumlah_paket,
                          (SELECT GROUP_CONCAT(CONCAT(jamstart, "-", jamend) ORDER BY jamstart SEPARATOR ", ") FROM detail_booking WHERE kdbooking = b.kdbooking) as jam_detail')
                ->join('pelanggan p', 'p.idpelanggan = b.idpelanggan', 'left')
                ->join('karyawan k', 'k.idkaryawan = b.idkaryawan', 'left')
                ->groupBy('b.kdbooking')
                ->orderBy('b.created_at', 'DESC');
        } else {
            // Query dasar dengan joins untuk data lengkap dan informasi waktu booking dari detail_booking
            $builder = $this->db->table('booking b')
                ->select('b.*, p.nama_lengkap, p.no_hp, k.namakaryawan, db.jamstart, db.jamend')
                ->join('pelanggan p', 'p.idpelanggan = b.idpelanggan', 'left')
                ->join('karyawan k', 'k.idkaryawan = b.idkaryawan', 'left')
                ->join('detail_booking db', 'db.kdbooking = b.kdbooking', 'left')
                ->orderBy('b.created_at', 'DESC');
        }

        // Total records (cache result)
        $totalRecords = $this->db->table('booking')->countAllResults();

        // Filter berdasarkan status
        if (!empty($filterStatus)) {
            $builder->where('b.status', $filterStatus);
        }

        // Filter berdasarkan tanggal (hari ini)
        if ($dateFilter === 'today') {
            $today = date('Y-m-d');
            $builder->where('b.tanggal_booking', $today);
        }

        // Pencarian yang dioptimalkan
        if (!empty($search)) {
            $searchValue = $this->db->escapeLikeString($search);

            $builder->groupStart()
                ->orLike('b.kdbooking', $searchValue, 'both', null, true)
                ->orLike('p.nama_lengkap', $searchValue, 'both', null, true)
                ->orLike('p.no_hp', $searchValue, 'both', null, true)
                ->orLike('k.namakaryawan', $searchValue, 'both', null, true)
                ->groupEnd();
        }

        // Hitung total filtered records
        $totalFiltered = $builder->countAllResults(false);

        // Pengurutan
        $columns = ['b.kdbooking', 'p.nama_lengkap', 'b.tanggal_booking', 'b.status', 'b.jenispembayaran', 'k.namakaryawan'];
        $orderColumn = isset($order[0]['column']) ? (int) $order[0]['column'] : 0;
        $orderDir = isset($order[0]['dir']) ? strtoupper($order[0]['dir']) : 'DESC';
        $orderField = $columns[$orderColumn] ?? 'b.created_at';

        // Ambil data dengan limit
        $results = $builder->orderBy($orderField, $orderDir)
            ->limit($length, $start)
            ->get()
            ->getResultArray();

        // Format data
        $data = array_map(function ($row) {
            // Dapatkan status yang lebih user-friendly
            $statusText = $this->getStatusText($row['status']);

            // Format detail jam jika menggunakan pengelompokan
            $detailJam = null;
            if (isset($row['jam_detail'])) {
                $detailJam = $row['jam_detail'];
            } elseif (!empty($row['jamstart'])) {
                $detailJam = $row['jamstart'] . ' - ' . $row['jamend'];
            }

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
                'jumlah_paket' => $row['jumlah_paket'] ?? 1,
                'detail_jam' => $detailJam,
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
            'no-show' => 'Tidak Hadir',
            'rejected' => 'Ditolak'
        ];

        return $statusMap[$status] ?? $status;
    }

    public function store()
    {
        $this->db->transBegin();

        try {
            $data = $this->request->getPost();

            // Log data yang diterima untuk debugging
            log_message('debug', 'Data POST yang diterima: ' . json_encode($data));

            // Generate kode booking baru
            $kdbooking = $this->bookingModel->generateBookingCode();
            log_message('debug', 'Kode booking yang dibuat: ' . $kdbooking);

            // Periksa secara manual apakah kdbooking sudah ada di database
            $existingBooking = $this->bookingModel->find($kdbooking);
            if ($existingBooking) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Kode booking sudah ada dalam database'
                ]);
            }

            // Validasi data
            if (
                empty($data['idpelanggan']) || empty($data['tanggal_booking']) ||
                empty($data['jamstart']) || empty($data['idkaryawan'])
            ) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak lengkap'
                ]);
            }

            // Validasi paket yang dipilih
            if (empty($data['selected_pakets'])) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Tidak ada paket yang dipilih'
                ]);
            }

            // Decode paket yang dipilih
            $selectedPakets = json_decode($data['selected_pakets'], true);
            if (empty($selectedPakets)) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Format paket tidak valid'
                ]);
            }

            log_message('debug', 'Paket yang dipilih: ' . json_encode($selectedPakets));

            // Hitung total harga dan durasi
            $totalHarga = 0;
            $totalDurasi = 0;
            foreach ($selectedPakets as $paket) {
                $totalHarga += $paket['harga'];
                $totalDurasi += $paket['durasi'];
            }

            log_message('debug', 'Total harga: ' . $totalHarga . ', Total durasi: ' . $totalDurasi);

            // Siapkan data booking
            $bookingData = [
                'kdbooking' => $kdbooking,
                'idpelanggan' => $data['idpelanggan'],
                'idkaryawan' => $data['idkaryawan'],
                'tanggal_booking' => $data['tanggal_booking'],
                'total' => $totalHarga,
                'status' => 'Pending', // Huruf besar untuk status
                'jenispembayaran' => !empty($data['jenispembayaran']) ? $data['jenispembayaran'] : 'Lunas',
                'jumlahbayar' => !empty($data['jumlahbayar']) ? $data['jumlahbayar'] : $totalHarga,
                'created_at' => date('Y-m-d H:i:s')
            ];

            log_message('debug', 'Data booking yang akan disimpan: ' . json_encode($bookingData));

            // Insert data booking
            $result = $this->bookingModel->insert($bookingData);
            log_message('debug', 'Hasil insert booking: ' . ($result ? 'Berhasil' : 'Gagal'));

            if (!$result) {
                log_message('error', 'Error saat insert booking: ' . json_encode($this->bookingModel->errors()));
                $this->db->transRollback();
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data booking'
                ]);
            }

            // Insert detail booking untuk setiap paket
            $detailBookingModel = new \App\Models\DetailBookingModel();
            foreach ($selectedPakets as $paket) {
                $detailId = $detailBookingModel->generateDetailId();
                $detailBookingData = [
                    'iddetail' => $detailId,
                    'kdbooking' => $kdbooking,
                    'kdpaket' => $paket['id'],
                    'nama_paket' => $paket['nama'],
                    'harga' => $paket['harga'],
                    'tgl' => $data['tanggal_booking'],
                    'jamstart' => $data['jamstart'],
                    'jamend' => $data['jamend'],
                    'status' => '1', // 1 = Pending
                    'idkaryawan' => $data['idkaryawan'] // Tambahkan idkaryawan di detail booking
                ];

                log_message('debug', 'Data detail booking yang akan disimpan: ' . json_encode($detailBookingData));

                $detailResult = $detailBookingModel->insert($detailBookingData);
                log_message('debug', 'Hasil insert detail booking: ' . ($detailResult ? 'Berhasil' : 'Gagal'));

                if (!$detailResult) {
                    log_message('error', 'Error saat insert detail booking: ' . json_encode($detailBookingModel->errors()));
                    $this->db->transRollback();
                    return $this->response->setStatusCode(500)->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal menyimpan detail booking'
                    ]);
                }
            }

            // Jika ada pembayaran, insert data pembayaran
            if (!empty($data['jenispembayaran'])) {
                $pembayaranModel = new \App\Models\PembayaranModel();

                // Generate kode pembayaran
                $kdpembayaran = 'PAY-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                log_message('debug', 'Kode pembayaran yang dibuat: ' . $kdpembayaran);

                // Hitung jumlah bayar berdasarkan jenis pembayaran
                $jumlahBayar = $data['jumlahbayar'] ?? ($data['jenispembayaran'] == 'DP' ? $totalHarga / 2 : $totalHarga);

                // Siapkan data pembayaran
                $pembayaranData = [
                    'fakturbooking' => $kdbooking,
                    'total_bayar' => $jumlahBayar,
                    'grandtotal' => $totalHarga,
                    'metode' => $data['metode_pembayaran'] ?? 'cash',
                    'status' => 'paid',
                    'jenis' => $data['jenispembayaran'] ?? 'Lunas'
                ];

                log_message('debug', 'Data pembayaran yang akan disimpan: ' . json_encode($pembayaranData));

                // Insert data pembayaran
                $paymentResult = $pembayaranModel->insert($pembayaranData);
                log_message('debug', 'Hasil insert pembayaran: ' . ($paymentResult ? 'Berhasil' : 'Gagal'));

                if (!$paymentResult) {
                    log_message('error', 'Error saat insert pembayaran: ' . json_encode($pembayaranModel->errors()));
                    $this->db->transRollback();
                    return $this->response->setStatusCode(500)->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal menyimpan data pembayaran'
                    ]);
                }

                // Update status booking jika pembayaran lunas
                if ($data['jenispembayaran'] == 'Lunas') {
                    $updateResult = $this->bookingModel->update($kdbooking, ['status' => 'confirmed']);
                    log_message('debug', 'Hasil update status booking: ' . ($updateResult ? 'Berhasil' : 'Gagal'));
                }
            }

            // Buat notifikasi untuk admin
            $notificationModel = new \App\Models\NotificationModel();
            $notificationData = [
                'title' => 'Booking Baru',
                'message' => 'Booking baru dengan kode ' . $kdbooking . ' telah dibuat oleh admin',
                'type' => 'booking',
                'reference_id' => $kdbooking,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];

            log_message('debug', 'Data notifikasi yang akan disimpan: ' . json_encode($notificationData));

            $notifResult = $notificationModel->insert($notificationData);
            log_message('debug', 'Hasil insert notifikasi: ' . ($notifResult ? 'Berhasil' : 'Gagal'));

            $this->db->transCommit();
            log_message('debug', 'Transaksi berhasil di-commit');

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Booking berhasil disimpan',
                'kdbooking' => $kdbooking
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Exception saat menyimpan booking: ' . $e->getMessage() . ' at line ' . $e->getLine() . ' in ' . $e->getFile());
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id = null)
    {
        // Periksa dan perbarui status booking yang expired
        $this->checkExpiredBookings();

        if (empty($id)) {
            return redirect()->to('admin/booking')->with('error', 'ID booking tidak valid');
        }

        $booking = $this->bookingModel->getBookingWithPelanggan($id);

        if (!$booking) {
            return redirect()->to('admin/booking')->with('error', 'Data booking tidak ditemukan');
        }

        $details = $this->detailBookingModel->getDetailsByBookingCode($id);
        $pembayaran = $this->pembayaranModel->getPembayaranByBookingCode($id);

        $title = 'Detail Booking';
        return view('admin/booking_new/show', compact('title', 'booking', 'details', 'pembayaran'));
    }

    public function getAllPelanggan()
    {
        $keyword = $this->request->getGet('search');

        if (empty($keyword)) {
            // Jika tidak ada keyword, ambil 20 pelanggan terbaru
            $pelanggan = $this->pelangganModel->orderBy('created_at', 'DESC')->findAll(20);
        } else {
            // Jika ada keyword, cari berdasarkan nama atau nomor HP
            $pelanggan = $this->pelangganModel->like('nama_lengkap', $keyword)
                ->orLike('no_hp', $keyword)
                ->findAll(10);
        }

        $result = [];
        foreach ($pelanggan as $p) {
            $result[] = [
                'id' => $p['idpelanggan'],
                'nama' => $p['nama_lengkap'],
                'nohp' => $p['no_hp'],
                'email' => $p['email'] ?? '-'
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $result
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

    public function getTimeSlotAvailability()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $tanggal = $this->request->getGet('tanggal');

        if (empty($tanggal)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Tanggal harus diisi'
            ]);
        }

        // Dapatkan semua booking pada tanggal tersebut
        $bookedSlots = $this->db->table('detail_booking db')
            ->select('db.jamstart as jam, COUNT(*) as total_booking')
            ->join('booking b', 'b.kdbooking = db.kdbooking')
            ->where('db.tgl', $tanggal)
            ->where('db.status !=', '4') // 4 = Dibatalkan
            ->groupBy('db.jamstart')
            ->get()
            ->getResultArray();

        // Tambahkan label untuk slot yang sudah dibooking
        foreach ($bookedSlots as &$slot) {
            if ($slot['total_booking'] > 0) {
                $slot['label'] = 'Booked';
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'bookedSlots' => $bookedSlots
            ]
        ]);
    }

    public function checkAvailability()
    {
        $date = $this->request->getGet('date');

        if (!$date) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Date parameter is required',
                'bookedSlots' => [],
                'slotStatus' => []
            ]);
        }

        // Hitung jumlah karyawan aktif
        $totalKaryawan = $this->karyawanModel->where('status', 'aktif')->countAllResults();

        if ($totalKaryawan == 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak ada karyawan aktif',
                'bookedSlots' => [],
                'slotStatus' => []
            ]);
        }

        // Ambil data dari detail_booking berdasarkan tanggal
        // Dan hitung jumlah booking per jamstart
        $bookingsPerSlot = $this->db->table('detail_booking')
            ->select('jamstart, COUNT(*) as total_bookings')
            ->where('tgl', $date)
            ->where('status !=', '4') // 4 = Dibatalkan
            ->groupBy('jamstart')
            ->get()
            ->getResultArray();

        // Slot dengan status
        $slotStatus = [];

        // Kumpulkan semua slot waktu yang sudah terisi penuh
        $fullBookedSlots = [];
        foreach ($bookingsPerSlot as $slot) {
            $time = substr($slot['jamstart'], 0, 5); // Format jam:menit
            $bookingCount = (int)$slot['total_bookings'];

            // Jika jumlah booking sama dengan atau melebihi jumlah karyawan
            // maka slot tersebut sudah penuh
            if ($bookingCount >= $totalKaryawan) {
                $fullBookedSlots[] = $time;
            }

            // Simpan status untuk setiap slot waktu
            $slotStatus[] = [
                'time' => $time,
                'booked' => $bookingCount,
                'available' => $totalKaryawan - $bookingCount,
                'isFull' => $bookingCount >= $totalKaryawan
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Time slot availability retrieved successfully',
            'totalKaryawan' => $totalKaryawan,
            'bookedSlots' => $fullBookedSlots, // Hanya slot yang terisi penuh
            'slotStatus' => $slotStatus // Informasi lebih detail tentang setiap slot
        ]);
    }

    public function storePelanggan()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        // Validasi data
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'no_hp' => 'required|min_length[10]|is_unique[pelanggan.no_hp]',
            'email' => 'required|valid_email|is_unique[pelanggan.email]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Persiapkan data
        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'no_hp' => $this->request->getPost('no_hp'),
            'email' => $this->request->getPost('email'),
            'alamat' => $this->request->getPost('alamat'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Simpan data
        try {
            $this->pelangganModel->insert($data);
            $insertId = $this->pelangganModel->getInsertID();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data pelanggan berhasil disimpan',
                'data' => [
                    'id' => $insertId,
                    'nama' => $data['nama_lengkap'],
                    'nohp' => $data['no_hp'],
                    'email' => $data['email'],
                    'alamat' => $data['alamat']
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menyimpan data pelanggan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mengambil informasi pembayaran booking untuk keperluan pelunasan
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function getPaymentInfo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $kdbooking = $this->request->getPost('kdbooking');

        if (empty($kdbooking)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kode booking harus diisi'
            ]);
        }

        try {
            // Ambil data booking
            $booking = $this->bookingModel->find($kdbooking);

            if (!$booking) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Booking tidak ditemukan'
                ]);
            }

            // Hitung sisa pembayaran
            $total = $booking['total'];
            $jumlahbayar = $booking['jumlahbayar'];
            $sisa = $total - $jumlahbayar;

            return $this->response->setJSON([
                'status' => 'success',
                'data' => [
                    'total' => $total,
                    'jumlahbayar' => $jumlahbayar,
                    'sisa' => $sisa,
                    'jenispembayaran' => $booking['jenispembayaran']
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengambil informasi pembayaran: ' . $e->getMessage()
            ]);
        }
    }

    public function updateStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $kdbooking = $this->request->getPost('kdbooking');
        $status = $this->request->getPost('status');
        $updatePayment = $this->request->getPost('update_payment');

        if (empty($kdbooking) || empty($status)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kode booking dan status harus diisi'
            ]);
        }

        // Validasi status
        $validStatus = ['pending', 'confirmed', 'completed', 'cancelled', 'no-show', 'rejected'];
        if (!in_array($status, $validStatus)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Status tidak valid'
            ]);
        }

        $this->db->transBegin();

        try {
            // Ambil data booking terlebih dahulu
            $booking = $this->bookingModel->find($kdbooking);
            if (!$booking) {
                throw new \Exception('Booking tidak ditemukan');
            }

            // Update status booking
            $this->bookingModel->update($kdbooking, ['status' => $status]);

            // Jika status dibatalkan, update juga status detail booking
            if ($status == 'cancelled') {
                $this->detailBookingModel->where('kdbooking', $kdbooking)->set(['status' => '4'])->update();
            }

            // Jika status ditolak, update juga status detail booking
            if ($status == 'rejected') {
                $this->detailBookingModel->where('kdbooking', $kdbooking)->set(['status' => '5'])->update();
            }

            // Update status pembayaran jika diminta atau status booking menjadi completed
            if ($updatePayment == 'true' || $status == 'completed') {
                // Cek pembayaran yang sudah ada
                $existingPayments = $this->pembayaranModel->where('fakturbooking', $kdbooking)->findAll();

                if ($status == 'completed') {
                    // Jika status selesai, update semua pembayaran menjadi paid menggunakan metode baru
                    log_message('debug', 'Updating all payments for booking ' . $kdbooking . ' to paid');
                    $updateAllStatus = $this->pembayaranModel->updateStatusByBookingCode($kdbooking, 'paid');
                    if (!$updateAllStatus) {
                        log_message('error', 'Failed to update payments by booking code');
                        throw new \Exception('Gagal memperbarui status pembayaran');
                    }

                    // Update booking untuk menandai sudah lunas
                    $this->bookingModel->update($kdbooking, [
                        'jenispembayaran' => 'Lunas',
                        // Jika jumlah bayar kurang dari total, update menjadi total (pelunasan otomatis)
                        'jumlahbayar' => ($booking['jumlahbayar'] < $booking['total']) ? $booking['total'] : $booking['jumlahbayar']
                    ]);

                    // Jika belum ada riwayat pembayaran pelunasan, buat pembayaran pelunasan otomatis
                    if ($booking['jumlahbayar'] < $booking['total']) {
                        $sisaPembayaran = $booking['total'] - $booking['jumlahbayar'];

                        // Cek apakah sudah ada pembayaran pelunasan untuk booking ini
                        $existingPelunasan = $this->pembayaranModel->where('fakturbooking', $kdbooking)
                            ->where('jenis', 'Pelunasan')
                            ->countAllResults();

                        // Hanya tambahkan pembayaran pelunasan jika belum ada
                        if ($existingPelunasan == 0) {
                            // Buat riwayat pembayaran pelunasan
                            $pelunasanData = [
                                'fakturbooking' => $kdbooking,
                                'total_bayar' => $sisaPembayaran,
                                'grandtotal' => $booking['total'],
                                'metode' => 'cash', // Default ke cash untuk pelunasan otomatis
                                'status' => 'paid',
                                'jenis' => 'Pelunasan'
                            ];

                            $this->pembayaranModel->insert($pelunasanData);
                        }
                    }
                } else if ($status == 'cancelled' || $status == 'rejected') {
                    // Jika status dibatalkan/ditolak, update status pembayaran menjadi cancelled menggunakan metode baru
                    log_message('debug', 'Updating all payments for booking ' . $kdbooking . ' to cancelled');
                    $updateAllStatus = $this->pembayaranModel->updateStatusByBookingCode($kdbooking, 'cancelled');
                    if (!$updateAllStatus) {
                        log_message('error', 'Failed to update payments to cancelled by booking code');
                        throw new \Exception('Gagal memperbarui status pembayaran ke cancelled');
                    }
                }
            }

            // Jika status selesai dan ada request pelunasan manual (dari form)
            if ($status == 'completed' && $this->request->getPost('pelunasan')) {
                // Ambil data pembayaran dari request
                $jumlahPembayaran = (float) $this->request->getPost('jumlah_pembayaran');
                $metodePembayaran = $this->request->getPost('metode_pembayaran');

                if ($jumlahPembayaran <= 0) {
                    throw new \Exception('Jumlah pembayaran harus lebih dari 0');
                }

                // Validasi metode pembayaran
                $validMetode = ['cash', 'transfer', 'qris'];
                if (!in_array($metodePembayaran, $validMetode)) {
                    throw new \Exception('Metode pembayaran tidak valid');
                }

                // Cek apakah sudah ada pembayaran pelunasan untuk booking ini
                $existingPelunasan = $this->pembayaranModel->where('fakturbooking', $kdbooking)
                    ->where('jenis', 'Pelunasan')
                    ->countAllResults();

                // Hanya tambahkan pembayaran pelunasan jika belum ada
                if ($existingPelunasan == 0) {
                    // Tambahkan data pembayaran baru
                    $pembayaranData = [
                        'fakturbooking' => $kdbooking,
                        'total_bayar' => $jumlahPembayaran,
                        'grandtotal' => $booking['total'],
                        'metode' => $metodePembayaran,
                        'status' => 'paid',
                        'jenis' => 'Pelunasan'
                    ];

                    $this->pembayaranModel->insert($pembayaranData);

                    // Update jumlah pembayaran di booking
                    $totalPembayaran = $booking['jumlahbayar'] + $jumlahPembayaran;
                    $this->bookingModel->update($kdbooking, [
                        'jumlahbayar' => $totalPembayaran,
                        'jenispembayaran' => 'Lunas'
                    ]);
                }
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status booking berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Menampilkan form edit booking
     *
     * @param string $id
     * @return view
     */
    public function edit($id)
    {
        $booking = $this->bookingModel->getBookingWithPelanggan($id);

        if (!$booking) {
            return redirect()->to(site_url('admin/booking'))->with('error', 'Booking tidak ditemukan');
        }

        $details = $this->detailBookingModel->getDetailsByBookingCode($id);
        $karyawanList = $this->karyawanModel->where('status', 'aktif')->findAll();
        $paketList = $this->paketModel->findAll();
        $pembayaran = $this->pembayaranModel->where('fakturbooking', $id)->first();

        $title = 'Edit Booking';
        return view('admin/booking_new/edit', compact(
            'title',
            'booking',
            'details',
            'karyawanList',
            'paketList',
            'pembayaran'
        ));
    }

    /**
     * Memperbarui data booking
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function update()
    {
        $this->db->transBegin();

        try {
            $data = $this->request->getPost();
            $kdbooking = $data['kdbooking'];

            // Cek apakah booking ada
            $existingBooking = $this->bookingModel->find($kdbooking);
            if (!$existingBooking) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Booking tidak ditemukan'
                ]);
            }

            // Validasi data booking
            $bookingData = [
                'kdbooking' => $kdbooking,
                'idpelanggan' => $data['idpelanggan'],
                'tanggal_booking' => $data['tanggal_booking'],
                'status' => $data['status'],
                'jenispembayaran' => $data['jenispembayaran'],
                'jumlahbayar' => $data['jumlahbayar'] ?? ($data['jenispembayaran'] == 'DP' ? ($data['total'] * 0.5) : 0),
                'total' => $data['total'],
                'idkaryawan' => $data['idkaryawan'] ?? null,
            ];

            // Pastikan validasi kdbooking mengabaikan data yang sedang diupdate
            $this->bookingModel->skipValidation(true);

            // Update data booking
            if (!$this->bookingModel->update($kdbooking, $bookingData)) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui booking',
                    'errors' => $this->bookingModel->errors()
                ]);
            }

            // Hapus detail booking lama
            if (isset($data['update_details']) && $data['update_details'] == 'yes') {
                // Validasi data paket terlebih dahulu
                if (empty($data['kdpaket'])) {
                    // Coba cari dari detail booking yang sudah ada
                    $existingDetails = $this->detailBookingModel->getDetailsByBookingCode($kdbooking);
                    if (!empty($existingDetails) && isset($existingDetails[0]['kdpaket'])) {
                        $data['kdpaket'] = $existingDetails[0]['kdpaket'];
                        log_message('info', 'Menggunakan kdpaket dari detail booking yang sudah ada: ' . $data['kdpaket']);
                    } else {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(400)->setJSON([
                            'status' => 'error',
                            'message' => 'ID Paket tidak boleh kosong'
                        ]);
                    }
                }

                if (empty($data['jamstart'])) {
                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Jam mulai tidak boleh kosong'
                    ]);
                }

                if (empty($data['jamend'])) {
                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Jam selesai tidak boleh kosong'
                    ]);
                }

                if (empty($data['idkaryawan'])) {
                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Karyawan tidak boleh kosong'
                    ]);
                }

                $this->db->table('detail_booking')->where('kdbooking', $kdbooking)->delete();

                // Dapatkan data paket
                $paketModel = new PaketModel();
                $paket = $paketModel->find($data['kdpaket']);

                if (!$paket) {
                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Paket tidak ditemukan'
                    ]);
                }

                // Buat detail booking baru
                $detailData = [
                    'iddetail' => $this->detailBookingModel->generateDetailId(),
                    'tgl' => $data['tanggal_booking'],
                    'kdbooking' => $kdbooking,
                    'kdpaket' => $paket['idpaket'],
                    'nama_paket' => $paket['namapaket'],
                    'deskripsi' => $paket['deskripsi'],
                    'harga' => $paket['harga'],
                    'jamstart' => $data['jamstart'],
                    'jamend' => $data['jamend'],
                    'status' => 2, // Status default: 2 (confirmed)
                    'idkaryawan' => $data['idkaryawan'] ?? null,
                ];

                // Pastikan validasi iddetail dilewati karena ini adalah ID baru
                $this->detailBookingModel->skipValidation(true);

                // Debug log untuk melihat data yang akan diinsert
                log_message('debug', 'Detail booking data: ' . json_encode($detailData));

                if (!$this->detailBookingModel->insert($detailData)) {
                    // Debug log untuk melihat error
                    log_message('error', 'Error inserting detail booking: ' . json_encode($this->detailBookingModel->errors()));

                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal membuat detail booking',
                        'errors' => $this->detailBookingModel->errors(),
                        'debug_data' => $detailData // Menambahkan data untuk debugging
                    ]);
                }
            }

            // Update pembayaran jika ada
            if (isset($data['update_payment']) && $data['update_payment'] == 'yes') {
                // Validasi data pembayaran terlebih dahulu
                if (empty($data['jenispembayaran'])) {
                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Jenis pembayaran tidak boleh kosong'
                    ]);
                }

                if ($data['jenispembayaran'] != 'Belum Bayar' && (empty($data['jumlahbayar']) || $data['jumlahbayar'] <= 0)) {
                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Jumlah pembayaran tidak boleh kosong atau nol'
                    ]);
                }

                $pembayaranModel = new PembayaranModel();
                $existingPayment = $pembayaranModel->where('fakturbooking', $kdbooking)->first();

                $paymentData = [
                    'fakturbooking' => $kdbooking,
                    'total_bayar' => $data['jumlahbayar'] ?? 0,
                    'grandtotal' => $data['total'],
                    'metode' => $data['metode_pembayaran'] ?? 'Tunai',
                    'status' => $data['jenispembayaran'] == 'Lunas' ? 'lunas' : 'DP'
                ];

                if ($existingPayment) {
                    if (!$pembayaranModel->update($existingPayment['id'], $paymentData)) {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(400)->setJSON([
                            'status' => 'error',
                            'message' => 'Gagal memperbarui pembayaran',
                            'errors' => $pembayaranModel->errors()
                        ]);
                    }
                } else {
                    if (!$pembayaranModel->insert($paymentData)) {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(400)->setJSON([
                            'status' => 'error',
                            'message' => 'Gagal membuat pembayaran',
                            'errors' => $pembayaranModel->errors()
                        ]);
                    }
                }
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Booking berhasil diperbarui',
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
     * Mengambil konten invoice untuk dicetak langsung
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function print_invoice()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $kdbooking = $this->request->getPost('kdbooking');
        if (empty($kdbooking)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kode booking harus diisi'
            ]);
        }

        try {
            // Ambil data booking dan semua informasi terkait
            $booking = $this->bookingModel->getBookingWithPelanggan($kdbooking);
            if (!$booking) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Booking tidak ditemukan'
                ]);
            }

            // Ambil detail booking
            $details = $this->detailBookingModel->getDetailsByBookingCode($kdbooking);

            // Ambil data pembayaran
            $pembayaran = $this->pembayaranModel->getPembayaranByBookingCode($kdbooking);

            // Hasilkan konten faktur HTML
            $invoiceHtml = $this->generateInvoiceHTML($booking, $details, $pembayaran);

            return $this->response->setJSON([
                'status' => 'success',
                'invoiceHtml' => $invoiceHtml
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memuat faktur: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate HTML untuk faktur/invoice
     * 
     * @param array $booking Data booking
     * @param array $details Data detail booking
     * @param array $pembayaran Data pembayaran
     * @return string HTML content
     */
    private function generateInvoiceHTML($booking, $details, $pembayaran)
    {
        // Siapkan status teks
        $statusMap = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Terkonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'no-show' => 'Tidak Hadir',
            'rejected' => 'Ditolak'
        ];

        ob_start();
?>
        <div class="booking-detail-container" id="invoice">
            <div class="invoice-header">
                <div>
                    <img src="<?= base_url('assets/images/logo.png') ?>" alt="Awan Barbershop" onerror="this.src='https://via.placeholder.com/100x50?text=LOGO'">
                    <h4>AWAN BARBERSHOP</h4>
                    <p class="mb-0">Jl. Contoh No. 123, Kota</p>
                    <p class="mb-0">Telp: 081234567890</p>
                </div>
                <div>
                    <div class="invoice-title">FAKTUR BOOKING</div>
                    <div class="invoice-number">#<?= $booking['kdbooking'] ?></div>
                    <div class="invoice-date">Tanggal: <?= date('d/m/Y', strtotime($booking['created_at'] ?? date('Y-m-d'))) ?></div>
                </div>
            </div>

            <div class="row">
                <!-- Informasi Booking -->
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="card-header">
                            <h5><i class="fas fa-info-circle"></i> Informasi Booking</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless detail-table">
                                <tr>
                                    <th>ID Booking</th>
                                    <td><?= $booking['kdbooking'] ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal Booking</th>
                                    <td><?= date('d F Y', strtotime($booking['tanggal_booking'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="booking-status status-<?= $booking['status'] ?>">
                                            <?= $statusMap[$booking['status']] ?? $booking['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jenis Pembayaran</th>
                                    <td><?= $booking['jenispembayaran'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pelanggan -->
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="card-header">
                            <h5><i class="fas fa-user"></i> Informasi Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless detail-table">
                                <tr>
                                    <th>Nama</th>
                                    <td><?= $booking['nama_lengkap'] ?? 'Data tidak tersedia' ?></td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td><?= $booking['no_hp'] ?? 'Data tidak tersedia' ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= $booking['email'] ?? 'Data tidak tersedia' ?></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td><?= $booking['alamat'] ?? 'Data tidak tersedia' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Layanan -->
            <div class="info-card">
                <div class="card-header">
                    <h5><i class="fas fa-list"></i> Detail Layanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered service-table">
                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Paket</th>
                                    <th>Deskripsi</th>
                                    <th>Waktu</th>
                                    <th>Karyawan</th>
                                    <th class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0;
                                $counter = 1; ?>
                                <?php foreach ($details as $detail): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $detail['nama_paket'] ?></td>
                                        <td><?= $detail['deskripsi'] ?></td>
                                        <td><?= $detail['jamstart'] ?> - <?= $detail['jamend'] ?></td>
                                        <td>
                                            <?php
                                            $karyawanModel = new \App\Models\KaryawanModel();
                                            $karyawan = $karyawanModel->find($detail['idkaryawan']);
                                            echo $karyawan ? $karyawan['namakaryawan'] : 'Belum ditentukan';
                                            ?>
                                        </td>
                                        <td class="text-end">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                    </tr>
                                    <?php $total += $detail['harga']; ?>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">Total</th>
                                    <th class="text-end">Rp <?= number_format($booking['total'], 0, ',', '.') ?></th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-end">Jumlah Bayar</th>
                                    <th class="text-end">Rp <?= number_format($booking['jumlahbayar'], 0, ',', '.') ?></th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-end">Sisa</th>
                                    <th class="text-end">Rp <?= number_format($booking['total'] - $booking['jumlahbayar'], 0, ',', '.') ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Payment History -->
                    <?php if (!empty($pembayaran) && is_array($pembayaran)): ?>
                        <div class="payment-info">
                            <h6>Riwayat Pembayaran</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Metode</th>
                                            <th>Status</th>
                                            <th>Jenis</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pembayaran as $bayar): ?>
                                            <tr>
                                                <td><?= date('d/m/Y H:i', strtotime($bayar['created_at'])) ?></td>
                                                <td><?= ucfirst($bayar['metode']) ?></td>
                                                <td>
                                                    <span class="badge <?= $bayar['status'] == 'paid' ? 'bg-success' : 'bg-warning' ?>">
                                                        <?= $bayar['status'] == 'paid' ? 'Dibayar' : 'Belum Dibayar' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge <?= ($bayar['jenis'] ?? '') == 'DP' ? 'bg-warning' : 'bg-info' ?>">
                                                        <?= ($bayar['jenis'] ?? 'Lunas') ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">Rp <?= number_format($bayar['total_bayar'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Footer -->
            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="mb-4">
                        <h6>Catatan:</h6>
                        <p>1. Harap datang 10 menit sebelum waktu yang dijadwalkan.</p>
                        <p>2. Pembatalan harus dilakukan minimal 2 jam sebelum jadwal.</p>
                        <p>3. Faktur ini sebagai bukti sah pembayaran.</p>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <p>Terima kasih atas kunjungan Anda!</p>
                    <p class="mb-0">AWAN BARBERSHOP</p>
                </div>
            </div>
        </div>
<?php
        return ob_get_clean();
    }

    // Metode untuk mengecek slot waktu yang tersedia
    public function checkAvailableTimeSlots()
    {
        $tanggal = $this->request->getPost('tanggal');
        $durasi = $this->request->getPost('durasi');

        if (!$tanggal) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tanggal tidak boleh kosong'
            ]);
        }

        // Jam operasional (default: 09:00 - 21:00)
        $jamBuka = '09:00';
        $jamTutup = '21:00';

        // Konversi jam ke menit untuk perhitungan
        list($bukajam, $bukamenit) = explode(':', $jamBuka);
        list($tutupjam, $tutupmenit) = explode(':', $jamTutup);

        $bukaInMinutes = ($bukajam * 60) + $bukamenit;
        $tutupInMinutes = ($tutupjam * 60) + $tutupmenit;

        // Hitung jumlah karyawan aktif
        $karyawanModel = new \App\Models\KaryawanModel();
        $totalKaryawan = $karyawanModel->where('status', 'aktif')->countAllResults();

        // Ambil semua booking pada tanggal tersebut melalui detail_booking
        // Perbaikan: Hanya pilih kolom yang ada dalam GROUP BY atau gunakan fungsi agregat
        $bookings = $this->db->table('detail_booking db')
            ->select('db.jamstart, COUNT(db.jamstart) as total_bookings')
            ->join('booking b', 'b.kdbooking = db.kdbooking')
            ->where('db.tgl', $tanggal)
            ->where('db.status !=', '4') // 4 = Dibatalkan
            ->groupBy('db.jamstart')
            ->get()
            ->getResultArray();

        // Buat array untuk menyimpan status setiap slot waktu
        $slots = [];

        // Inisialisasi semua slot waktu sebagai tersedia
        for ($time = $bukaInMinutes; $time < $tutupInMinutes; $time += 60) {
            $hour = floor($time / 60);
            $minute = $time % 60;
            $timeSlot = sprintf('%02d:%02d', $hour, $minute);
            $slots[$timeSlot] = 'available';
        }

        // Periksa apakah tanggal adalah hari ini
        $isToday = date('Y-m-d') === $tanggal;
        $currentHour = (int)date('H');
        $currentMinute = (int)date('i');
        $currentTimeInMinutes = ($currentHour * 60) + $currentMinute;

        // Jika tanggal adalah hari ini, tandai slot waktu yang sudah lewat sebagai tidak tersedia
        if ($isToday) {
            foreach ($slots as $timeSlot => $status) {
                list($slotHour, $slotMinute) = explode(':', $timeSlot);
                $slotTimeInMinutes = ((int)$slotHour * 60) + (int)$slotMinute;

                // Jika waktu slot sudah lewat (tambahkan buffer 5 menit)
                if ($slotTimeInMinutes <= $currentTimeInMinutes + 5) {
                    $slots[$timeSlot] = 'booked';
                }
            }
        }

        // Buat array untuk menyimpan jumlah booking per slot waktu
        $bookingsPerSlot = [];
        foreach ($bookings as $booking) {
            $bookingsPerSlot[$booking['jamstart']] = (int)$booking['total_bookings'];
        }

        // Tandai slot waktu berdasarkan jumlah booking
        foreach ($bookingsPerSlot as $time => $count) {
            // Jika jumlah booking sama dengan atau melebihi jumlah karyawan
            if ($count >= $totalKaryawan) {
                $slots[$time] = 'booked';
            } else if ($count > 0) {
                // Jika ada booking tapi masih ada karyawan yang tersedia
                $slots[$time] = 'partial';
            }
        }

        // Periksa apakah slot waktu cukup untuk durasi yang diminta
        if ($durasi > 60) {
            // Untuk durasi lebih dari 1 jam, periksa apakah ada slot berurutan yang cukup
            for ($time = $bukaInMinutes; $time <= $tutupInMinutes - $durasi; $time += 60) {
                $hour = floor($time / 60);
                $minute = $time % 60;
                $timeSlot = sprintf('%02d:%02d', $hour, $minute);

                // Periksa apakah slot waktu ini dan slot-slot berikutnya tersedia
                $isAvailable = true;
                for ($i = 0; $i < $durasi; $i += 60) {
                    $checkHour = floor(($time + $i) / 60);
                    $checkMinute = ($time + $i) % 60;
                    $checkTimeSlot = sprintf('%02d:%02d', $checkHour, $checkMinute);

                    if (isset($slots[$checkTimeSlot]) && $slots[$checkTimeSlot] === 'booked') {
                        $isAvailable = false;
                        break;
                    }

                    // Jika melebihi jam tutup
                    if ($time + $i >= $tutupInMinutes) {
                        $isAvailable = false;
                        break;
                    }
                }

                // Jika tidak tersedia, tandai sebagai disabled
                if (!$isAvailable && $slots[$timeSlot] === 'available') {
                    $slots[$timeSlot] = 'disabled';
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'slots' => $slots,
            'totalKaryawan' => $totalKaryawan,
            'isToday' => $isToday,
            'currentTime' => date('H:i')
        ]);
    }

    // Metode untuk mengecek karyawan yang tersedia
    public function checkAvailableKaryawan()
    {
        $tanggal = $this->request->getPost('tanggal');
        $jamMulai = $this->request->getPost('jamMulai');
        $jamSelesai = $this->request->getPost('jamSelesai');

        if (!$tanggal || !$jamMulai || !$jamSelesai) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        // Ambil semua karyawan aktif
        $karyawanModel = new \App\Models\KaryawanModel();
        $allKaryawan = $karyawanModel->where('status', 'aktif')->findAll();

        if (empty($allKaryawan)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tidak ada karyawan aktif',
                'karyawan' => []
            ]);
        }

        // Ambil semua booking pada tanggal dan jam tersebut
        $bookings = $this->db->table('detail_booking db')
            ->select('b.idkaryawan')
            ->join('booking b', 'b.kdbooking = db.kdbooking')
            ->where('db.tgl', $tanggal)
            ->where('db.status !=', '4') // 4 = Dibatalkan
            ->where('db.jamstart', $jamMulai)
            ->get()
            ->getResultArray();

        // Kumpulkan ID karyawan yang sudah dibooking pada jam tersebut
        $bookedKaryawanIds = [];
        foreach ($bookings as $booking) {
            if (!empty($booking['idkaryawan'])) {
                $bookedKaryawanIds[] = $booking['idkaryawan'];
            }
        }

        // Filter karyawan yang tersedia (tidak sedang dibooking)
        $availableKaryawan = [];
        foreach ($allKaryawan as $karyawan) {
            if (!in_array($karyawan['idkaryawan'], $bookedKaryawanIds)) {
                $availableKaryawan[] = $karyawan;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'karyawan' => $availableKaryawan,
            'debug' => [
                'total_karyawan' => count($allKaryawan),
                'booked_karyawan' => $bookedKaryawanIds,
                'available_karyawan' => count($availableKaryawan)
            ]
        ]);
    }
}
