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
use App\Models\NotificationModel;

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

        if (!session()->get('logged_in') || session()->get('role') != 'pelanggan') {
            return redirect()->to(site_url('customer/login?redirect=booking'));
        }


        $userId = session()->get('user_id');
        $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

        if (!$pelanggan) {
            return redirect()->to(site_url('customer/profil'))->with('error', 'Silakan lengkapi profil Anda terlebih dahulu');
        }

        $idPelanggan = $pelanggan['idpelanggan'];


        $bookings = $this->db->table('booking b')
            ->select('b.*, k.namakaryawan as namakaryawan, db.nama_paket, db.jamstart, db.jamend')
            ->join('detail_booking db', 'b.kdbooking = db.kdbooking', 'left')
            ->join('karyawan k', 'db.idkaryawan = k.idkaryawan', 'left')
            ->where('b.idpelanggan', $idPelanggan)
            ->orderBy('b.tanggal_booking', 'DESC')
            ->orderBy('db.jamstart', 'ASC')
            ->get()
            ->getResultArray();


        $formattedBookings = [];
        foreach ($bookings as $booking) {
            $statusMap = [
                'pending' => 'Menunggu Konfirmasi',
                'confirmed' => 'Terkonfirmasi',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                'no-show' => 'Tidak Hadir',
                'rejected' => 'Ditolak'
            ];

            $booking['status_text'] = $statusMap[$booking['status']] ?? $booking['status'];
            $booking['total_formatted'] = 'Rp ' . number_format($booking['total'], 0, ',', '.');
            $formattedBookings[] = $booking;
        }

        $data = [
            'title' => 'History Booking',
            'idpelanggan' => $idPelanggan,
            'bookings' => $formattedBookings
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


        $userId = session()->get('user_id');


        $debugInfo = [
            'user_id' => $userId,
            'session_data' => session()->get(),
        ];


        log_message('debug', 'GetBookings Debug: ' . json_encode($debugInfo));

        $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();


        log_message('debug', 'Pelanggan Data: ' . json_encode($pelanggan));

        if (!$pelanggan) {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Data pelanggan tidak ditemukan',
                'debug' => $debugInfo
            ]);
        }

        $idPelanggan = $pelanggan['idpelanggan'];

        $draw = $this->request->getGet('draw');
        $start = $this->request->getGet('start');
        $length = $this->request->getGet('length');
        $search = $this->request->getGet('search')['value'];


        $builder = $this->db->table('booking b')
            ->select('b.*, k.namakaryawan as namakaryawan, p.nama_lengkap, p.no_hp, db.nama_paket, db.jamstart, db.jamend')
            ->join('detail_booking db', 'b.kdbooking = db.kdbooking', 'left')
            ->join('karyawan k', 'db.idkaryawan = k.idkaryawan', 'left')
            ->join('pelanggan p', 'p.idpelanggan = b.idpelanggan', 'left')
            ->where('b.idpelanggan', $idPelanggan);


        $queryString = $this->db->getLastQuery();
        log_message('debug', 'Booking Query: ' . $queryString);

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


        log_message('debug', 'Result Count: ' . count($result));
        log_message('debug', 'Result Data: ' . json_encode($result));


        $data = [];
        foreach ($result as $row) {
            $statusMap = [
                'pending' => 'Menunggu Konfirmasi',
                'confirmed' => 'Terkonfirmasi',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                'no-show' => 'Tidak Hadir',
                'rejected' => 'Ditolak'
            ];

            $row['status_text'] = $statusMap[$row['status']] ?? $row['status'];
            $row['total_formatted'] = 'Rp ' . number_format($row['total'], 0, ',', '.');

            $data[] = $row;
        }

        return $this->response->setJSON([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
            'debug' => [
                'user_id' => $userId,
                'idpelanggan' => $idPelanggan,
                'query' => $queryString
            ]
        ]);
    }

    /**
     * Menampilkan form booking baru
     */
    public function create()
    {

        if (!session()->get('logged_in') || session()->get('role') != 'pelanggan') {
            return redirect()->to(site_url('customer/login?redirect=booking'));
        }


        $userId = session()->get('user_id');
        $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

        if (!$pelanggan) {
            return redirect()->to(site_url('customer/profil'))->with('error', 'Silakan lengkapi profil Anda terlebih dahulu');
        }


        $isProfileComplete = true;
        $missingFields = [];

        if (empty($pelanggan['tanggal_lahir'])) {
            $isProfileComplete = false;
            $missingFields[] = 'tanggal lahir';
        }

        if (empty($pelanggan['no_hp'])) {
            $isProfileComplete = false;
            $missingFields[] = 'nomor HP';
        }

        if (empty($pelanggan['alamat'])) {
            $isProfileComplete = false;
            $missingFields[] = 'alamat';
        }


        $paketId = $this->request->getGet('paket');
        $selectedPaket = null;

        if ($paketId) {
            $selectedPaket = $this->paketModel->find($paketId);
        }


        $paketList = $this->paketModel->findAll();

        $data = [
            'title' => 'Form Booking',
            'pelanggan' => $pelanggan,
            'paketList' => $paketList,
            'selectedPaket' => $selectedPaket,
            'isProfileComplete' => $isProfileComplete,
            'missingFields' => $missingFields
        ];

        return view('customer/booking/create', $data);
    }

    /**
     * Menyimpan data booking baru
     */
    public function store()
    {

        if (!session()->get('logged_in') || session()->get('role') != 'pelanggan') {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }

        $this->db->transBegin();

        try {

            $this->cleanupExpiredBookings();

            $data = $this->request->getPost();


            $userId = session()->get('user_id');
            $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

            if (!$pelanggan) {
                throw new \Exception('Data pelanggan tidak ditemukan');
            }

            $idPelanggan = $pelanggan['idpelanggan'];


            $kdbooking = $this->bookingModel->generateBookingCode();


            $existingBooking = $this->bookingModel->find($kdbooking);
            if ($existingBooking) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Kode booking sudah ada dalam database'
                ]);
            }



            $existingBookingOnSameTime = $this->db->table('detail_booking db')
                ->select('db.*, b.status as booking_status')
                ->join('booking b', 'b.kdbooking = db.kdbooking')
                ->where('db.tgl', $data['tanggal_booking'])
                ->where('db.jamstart', $data['jamstart'])
                ->where('db.idkaryawan', $data['idkaryawan'])
                ->where('db.status !=', '0')  // Tidak mengambil detail booking yang dibatalkan
                ->where('b.status !=', 'expired') // Tidak mengambil booking yang expired
                ->get()
                ->getRowArray();


            log_message('debug', 'Checking booking availability: ' .
                'Date: ' . $data['tanggal_booking'] . ', ' .
                'Time: ' . $data['jamstart'] . ', ' .
                'Karyawan ID: ' . $data['idkaryawan'] . ', ' .
                'Existing booking: ' . ($existingBookingOnSameTime ? 'Yes' : 'No'));

            if ($existingBookingOnSameTime) {
                log_message('debug', 'Booking conflict found: ' . json_encode($existingBookingOnSameTime));

                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Jadwal sudah dipesan oleh pelanggan lain. Silakan pilih jadwal atau karyawan lain.'
                ]);
            }


            $paketIds = $this->request->getPost('idpaket');


            $selectedPakets = $this->request->getPost('selectedPakets');
            if (empty($paketIds) && !empty($selectedPakets)) {

                log_message('debug', 'Raw selectedPakets: ' . $selectedPakets);


                $decodedPakets = json_decode($selectedPakets, true);
                if (is_array($decodedPakets)) {
                    $paketIds = $decodedPakets;
                    log_message('debug', 'Decoded JSON paket IDs: ' . json_encode($paketIds));
                } else if (strpos($selectedPakets, ',') !== false) {

                    $paketIds = explode(',', $selectedPakets);
                    log_message('debug', 'Comma-separated paket IDs: ' . json_encode($paketIds));
                } else {

                    $paketIds = [$selectedPakets];
                    log_message('debug', 'Single paket ID: ' . $selectedPakets);
                }
            }


            if (empty($paketIds) && $this->request->getPost('selectedPakets')) {
                if (is_string($this->request->getPost('selectedPakets')) && !is_array($paketIds)) {
                    $paketIds = [$this->request->getPost('selectedPakets')];
                    log_message('debug', 'Landing page paket ID: ' . $this->request->getPost('selectedPakets'));
                }
            }


            if (!is_array($paketIds)) {
                $paketIds = [$paketIds];
            }


            log_message('debug', 'Final paket IDs: ' . json_encode($paketIds));


            log_message('debug', 'Raw total value: ' . print_r($data['total'], true));


            $calculatedTotal = 0;
            if (!empty($paketIds) && is_array($paketIds)) {
                foreach ($paketIds as $paketId) {
                    $paket = $this->paketModel->find($paketId);
                    if ($paket) {
                        $calculatedTotal += (int)$paket['harga'];
                        log_message('debug', 'Adding paket: ' . $paket['idpaket'] . ' - ' . $paket['namapaket'] . ' with price: ' . $paket['harga']);
                    }
                }
                log_message('debug', 'Calculated total from pakets: ' . $calculatedTotal);
            }


            $total = $calculatedTotal;


            $bookingData = [
                'kdbooking' => $kdbooking,
                'idpelanggan' => $idPelanggan,
                'tanggal_booking' => $data['tanggal_booking'],
                'status' => 'pending', // default status untuk booking dari pelanggan
                'total' => $total, // Nilai total yang sudah diproses
                'idkaryawan' => $data['idkaryawan'] ?? null,
                'jenispembayaran' => 'Belum Bayar', // Set default jenispembayaran
                'jumlahbayar' => 0, // Set default jumlahbayar
                'expired_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')), // Set expired time 5 menit dari sekarang
            ];


            log_message('debug', 'Booking data to be saved: ' . json_encode($bookingData));


            if (!$this->bookingModel->save($bookingData)) {
                $this->db->transRollback();
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal membuat booking',
                    'errors' => $this->bookingModel->errors()
                ]);
            }




            $durasiTotal = 0;
            $jamStart = $data['jamstart'];

            foreach ($paketIds as $paketId) {

                log_message('debug', 'Processing paket ID: ' . $paketId);


                $paket = $this->paketModel->find($paketId);

                if (!$paket) {
                    $this->db->transRollback();
                    log_message('error', 'Paket not found: ' . $paketId);
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Paket dengan ID ' . $paketId . ' tidak ditemukan',
                        'debug' => [
                            'paket_ids' => $paketIds,
                            'current_id' => $paketId,
                            'post_data' => $this->request->getPost()
                        ]
                    ]);
                }


                $durasi = $paket['durasi'] ?? 60; // Default 60 menit jika tidak ada durasi
                $durasiTotal += $durasi;


                $jamStartParts = explode(':', $jamStart);
                $startMinutes = (int)$jamStartParts[0] * 60 + (int)$jamStartParts[1];


                $endMinutes = $startMinutes + $durasi;
                $endHours = floor($endMinutes / 60) % 24;
                $endMins = $endMinutes % 60;
                $jamEnd = sprintf('%02d:%02d', $endHours, $endMins);


                $detailData = [
                    'iddetail' => $this->detailBookingModel->generateDetailId(),
                    'tgl' => $data['tanggal_booking'],
                    'kdbooking' => $kdbooking,
                    'kdpaket' => $paket['idpaket'],
                    'nama_paket' => $paket['namapaket'],
                    'deskripsi' => $paket['deskripsi'],
                    'harga' => $paket['harga'],
                    'jamstart' => $jamStart,
                    'jamend' => $jamEnd,
                    'status' => '1', // 1 = Pending
                    'idkaryawan' => $data['idkaryawan'] ?? null,
                ];

                if (!$this->detailBookingModel->save($detailData)) {
                    $this->db->transRollback();
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal membuat detail booking untuk paket ' . $paket['namapaket'],
                        'errors' => $this->detailBookingModel->errors()
                    ]);
                }


                $jamStart = $jamEnd;
            }


            $notificationModel = new \App\Models\NotificationModel();
            $notificationModel->createBookingNotification(
                $kdbooking,
                $pelanggan['nama_lengkap'],
                $pelanggan['idpelanggan']
            );

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

        if ($this->request->isAJAX()) {
            $booking = $this->bookingModel->find($id);
            if (!$booking) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Booking tidak ditemukan'
                ]);
            }
            return $this->response->setJSON($booking);
        }


        if (!session()->get('logged_in') || session()->get('role') != 'pelanggan') {
            return redirect()->to(site_url('customer/login?redirect=booking'));
        }

        if (empty($id)) {
            return redirect()->to('customer/booking')->with('error', 'Kode booking tidak valid');
        }


        $userId = session()->get('user_id');
        $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

        if (!$pelanggan) {
            return redirect()->to(site_url('customer/profil'))->with('error', 'Data pelanggan tidak ditemukan');
        }

        $idPelanggan = $pelanggan['idpelanggan'];


        $booking = $this->getBookingWithPelanggan($id);

        if (!$booking) {
            return redirect()->to('customer/booking')->with('error', 'Data booking tidak ditemukan');
        }


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


        $this->cleanupExpiredBookings();

        $tanggal = $this->request->getGet('tanggal');
        $durasi = (int)$this->request->getGet('durasi') ?: 60; // Durasi total dalam menit, default 60 menit

        if (empty($tanggal)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Tanggal harus diisi'
            ]);
        }


        $tanggal = date('Y-m-d', strtotime($tanggal));



        $bookings = $this->db->table('detail_booking db')
            ->select('db.*, b.status as booking_status')
            ->join('booking b', 'b.kdbooking = db.kdbooking')
            ->where('db.tgl', $tanggal)
            ->where('db.status !=', '0')  // Tidak mengambil detail booking yang dibatalkan
            ->where('b.status !=', 'expired') // Tidak mengambil booking yang expired
            ->get()->getResultArray();


        $karyawanList = $this->karyawanModel->where('status', 'aktif')->findAll();
        $karyawanIds = array_column($karyawanList, 'idkaryawan');


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


        $availability = [];
        foreach ($slots as $slot) {

            $slotStartMinutes = $this->timeToMinutes($slot);
            $slotEndMinutes = $slotStartMinutes + $durasi;
            $endTime = $this->minutesToTime($slotEndMinutes);


            $availableKaryawan = $karyawanIds;

            foreach ($bookings as $booking) {
                $bookingStart = $booking['jamstart'];
                $bookingEnd = $booking['jamend'];
                $bookingKaryawan = $booking['idkaryawan'];


                if ($this->isOverlapping($slot, $endTime, $bookingStart, $bookingEnd) && in_array($bookingKaryawan, $availableKaryawan)) {
                    $availableKaryawan = array_diff($availableKaryawan, [$bookingKaryawan]);
                }
            }



            if ($durasi > 60) {
                $hoursNeeded = ceil($durasi / 60);


                for ($i = 1; $i < $hoursNeeded; $i++) {
                    $nextSlotStartMinutes = $slotStartMinutes + ($i * 60);
                    $nextSlotTime = $this->minutesToTime($nextSlotStartMinutes);
                    $nextSlotEndMinutes = $nextSlotStartMinutes + 60;
                    $nextSlotEndTime = $this->minutesToTime($nextSlotEndMinutes);

                    foreach ($bookings as $booking) {
                        $bookingStart = $booking['jamstart'];
                        $bookingEnd = $booking['jamend'];
                        $bookingKaryawan = $booking['idkaryawan'];


                        if ($this->isOverlapping($nextSlotTime, $nextSlotEndTime, $bookingStart, $bookingEnd) && in_array($bookingKaryawan, $availableKaryawan)) {
                            $availableKaryawan = array_diff($availableKaryawan, [$bookingKaryawan]);
                        }
                    }
                }
            }

            $status = count($availableKaryawan) > 0 ? 'available' : 'booked';

            $availability[] = [
                'time' => $slot,
                'endTime' => $endTime,
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


        $this->cleanupExpiredBookings();

        $tanggal = $this->request->getGet('tanggal');
        $jamstart = $this->request->getGet('jamstart');
        $durasi = (int)$this->request->getGet('durasi') ?: 60; // Durasi total dalam menit, default 60 menit

        if (empty($tanggal) || empty($jamstart)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Tanggal dan jam mulai harus diisi'
            ]);
        }


        $tanggal = date('Y-m-d', strtotime($tanggal));


        $startMinutes = $this->timeToMinutes($jamstart);
        $endMinutes = $startMinutes + $durasi;
        $jamend = $this->minutesToTime($endMinutes);



        $bookings = $this->db->table('detail_booking db')
            ->select('db.*, b.status as booking_status')
            ->join('booking b', 'b.kdbooking = db.kdbooking')
            ->where('db.tgl', $tanggal)
            ->where('db.status !=', '0')  // Tidak mengambil detail booking yang dibatalkan
            ->where('b.status !=', 'expired') // Tidak mengambil booking yang expired
            ->get()->getResultArray();


        $karyawanList = $this->karyawanModel->where('status', 'aktif')->findAll();


        $availableKaryawan = [];

        foreach ($karyawanList as $karyawan) {
            $isAvailable = true;


            $hoursNeeded = ceil($durasi / 60);


            for ($i = 0; $i < $hoursNeeded; $i++) {
                $currentSlotStartMinutes = $startMinutes + ($i * 60);
                $currentSlotTime = $this->minutesToTime($currentSlotStartMinutes);
                $currentSlotEndMinutes = min($currentSlotStartMinutes + 60, $endMinutes); // Jangan melebihi jam akhir
                $currentSlotEndTime = $this->minutesToTime($currentSlotEndMinutes);

                foreach ($bookings as $booking) {
                    $bookingStart = $booking['jamstart'];
                    $bookingEnd = $booking['jamend'];
                    $bookingKaryawan = $booking['idkaryawan'];


                    if (
                        $bookingKaryawan == $karyawan['idkaryawan'] &&
                        $this->isOverlapping($currentSlotTime, $currentSlotEndTime, $bookingStart, $bookingEnd)
                    ) {
                        $isAvailable = false;
                        break 2; // Keluar dari kedua loop jika ditemukan konflik
                    }
                }
            }

            if ($isAvailable) {
                $availableKaryawan[] = [
                    'id' => $karyawan['idkaryawan'],
                    'nama' => $karyawan['namakaryawan'],

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

    /**
     * Helper: Konversi waktu format HH:MM ke menit
     */
    private function timeToMinutes($time)
    {
        list($hours, $minutes) = explode(':', $time);
        return (int)$hours * 60 + (int)$minutes;
    }

    /**
     * Helper: Konversi menit ke waktu format HH:MM
     */
    private function minutesToTime($minutes)
    {
        $hours = floor($minutes / 60) % 24;
        $mins = $minutes % 60;
        return sprintf('%02d:%02d', $hours, $mins);
    }

    /**
     * Create test notification for debugging
     */
    public function createTestNotification()
    {

        $notificationModel = new \App\Models\NotificationModel();
        $result = $notificationModel->createNotification(
            'booking_baru',
            'Test Notifikasi',
            'Ini adalah notifikasi test untuk debugging',
            'TEST-123',
            null
        );

        return $this->response->setJSON([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Notifikasi test berhasil dibuat' : 'Gagal membuat notifikasi test'
        ]);
    }

    /**
     * Menampilkan form pembayaran
     */
    public function payment($kdbooking = null)
    {

        if (!session()->get('logged_in') || session()->get('role') != 'pelanggan') {
            return redirect()->to(site_url('customer/login?redirect=booking'));
        }

        if (empty($kdbooking)) {
            return redirect()->to('customer/booking')->with('error', 'Kode booking tidak valid');
        }


        $userId = session()->get('user_id');
        $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

        if (!$pelanggan) {
            return redirect()->to(site_url('customer/profil'))->with('error', 'Data pelanggan tidak ditemukan');
        }

        $idPelanggan = $pelanggan['idpelanggan'];


        $booking = $this->getBookingWithPelanggan($kdbooking);

        if (!$booking) {
            return redirect()->to('customer/booking')->with('error', 'Data booking tidak ditemukan');
        }


        if ($booking['idpelanggan'] != $idPelanggan) {
            return redirect()->to('customer/booking')->with('error', 'Anda tidak memiliki akses ke booking ini');
        }


        if (isset($booking['expired_at']) && strtotime($booking['expired_at']) < time()) {

            if ($booking['jenispembayaran'] == 'Belum Bayar') {
                $this->expireBooking($kdbooking);
            }

            return redirect()->to('customer/booking')->with('error', 'Waktu pembayaran untuk booking ini telah berakhir. Silakan buat booking baru.');
        }

        $details = $this->detailBookingModel->getDetailsByBookingCode($kdbooking);

        $data = [
            'title' => 'Pembayaran Booking',
            'booking' => $booking,
            'details' => $details,
        ];

        return view('customer/booking/payment', $data);
    }

    /**
     * Menyimpan data pembayaran
     */
    public function savePayment()
    {

        if (!session()->get('logged_in') || session()->get('role') != 'pelanggan') {
            return $this->response->setStatusCode(403)->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ]);
        }


        $isAjax = $this->request->isAJAX();

        $this->db->transBegin();

        try {
            $data = $this->request->getPost();
            $kdbooking = $data['kdbooking'];


            $userId = session()->get('user_id');
            $pelanggan = $this->pelangganModel->where('user_id', $userId)->first();

            if (!$pelanggan) {
                throw new \Exception('Data pelanggan tidak ditemukan');
            }

            $idPelanggan = $pelanggan['idpelanggan'];


            $booking = $this->bookingModel->find($kdbooking);

            if (!$booking) {
                throw new \Exception('Data booking tidak ditemukan');
            }


            if ($booking['idpelanggan'] != $idPelanggan) {
                throw new \Exception('Anda tidak memiliki akses ke booking ini');
            }


            if (isset($booking['expired_at']) && strtotime($booking['expired_at']) < time()) {

                $this->expireBooking($kdbooking);

                throw new \Exception('Waktu pembayaran untuk booking ini telah berakhir. Silakan buat booking baru.');
            }


            $jenisPembayaran = isset($data['jenis_pembayaran']) ?
                (strtolower($data['jenis_pembayaran']) === 'dp' ? 'DP' : 'Lunas') : 'DP';

            $jumlahBayar = 0;
            if ($jenisPembayaran === 'DP') {

                $jumlahBayar = isset($data['min_payment']) && is_numeric($data['min_payment']) ?
                    (int)$data['min_payment'] : (int)($booking['total'] * 0.5);
            } else {

                $jumlahBayar = (int)$booking['total'];
            }

            $this->bookingModel->update($kdbooking, [
                'jenispembayaran' => $jenisPembayaran,
                'jumlahbayar' => $jumlahBayar,
                'status' => 'pending'
            ]);


            $pembayaranData = [
                'fakturbooking' => $kdbooking,
                'total_bayar' => $jumlahBayar,
                'grandtotal' => $booking['total'],
                'metode' => $data['metode_pembayaran'] ?? 'transfer',
                'status' => 'paid', // Langsung set status menjadi paid karena sudah upload bukti
                'jenis' => $jenisPembayaran,
            ];


            $bukti = $this->request->getFile('bukti_pembayaran');
            if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
                $newName = $kdbooking . '_' . $bukti->getRandomName();
                $bukti->move(FCPATH . 'uploads/bukti_pembayaran', $newName);
                $pembayaranData['bukti'] = $newName;
            }

            if (!$this->pembayaranModel->save($pembayaranData)) {
                $this->db->transRollback();

                if ($isAjax) {
                    return $this->response->setStatusCode(400)->setJSON([
                        'status' => 'error',
                        'message' => 'Gagal membuat pembayaran',
                        'errors' => $this->pembayaranModel->errors()
                    ]);
                } else {
                    return redirect()->back()->with('error', 'Gagal membuat pembayaran: ' . implode(', ', $this->pembayaranModel->errors()));
                }
            }


            $notificationModel = new \App\Models\NotificationModel();
            $notificationModel->createNotification(
                'pembayaran_baru',
                'Pembayaran Baru',
                'Pembayaran baru untuk booking ' . $kdbooking . ' telah diterima dan menunggu konfirmasi.',
                $kdbooking,
                null
            );


            $this->detailBookingModel->where('kdbooking', $kdbooking)->set(['status' => '2'])->update();

            $this->db->transCommit();

            if ($isAjax) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Pembayaran berhasil disimpan',
                    'kdbooking' => $kdbooking,
                    'redirect' => site_url('customer/booking/detail/' . $kdbooking)
                ]);
            } else {
                return redirect()->to('customer/booking/detail/' . $kdbooking)->with('success', 'Pembayaran berhasil disimpan');
            }
        } catch (\Exception $e) {
            $this->db->transRollback();

            if ($isAjax) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            } else {
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }
    }

    /**
     * Menangani booking yang expired via AJAX
     */
    public function expire($kdbooking = null)
    {
        if (empty($kdbooking)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Kode booking tidak valid'
            ]);
        }

        $result = $this->expireBooking($kdbooking);

        return $this->response->setJSON([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Booking berhasil diperbarui menjadi expired' : 'Gagal memperbarui status booking'
        ]);
    }

    /**
     * Helper function untuk mengubah status booking menjadi expired
     * dan membebaskan karyawan yang terkait
     */
    private function expireBooking($kdbooking)
    {
        $this->db->transBegin();

        try {

            $this->bookingModel->update($kdbooking, [
                'status' => 'expired'
            ]);


            $details = $this->detailBookingModel->getDetailsByBookingCode($kdbooking);


            foreach ($details as $detail) {

                $this->detailBookingModel->update($detail['iddetail'], [
                    'status' => '0' // 0 = Dibatalkan/Expired
                ]);


                if (!empty($detail['idkaryawan'])) {
                    log_message('info', 'Membebaskan karyawan ID: ' . $detail['idkaryawan'] . ' dari booking: ' . $kdbooking);
                }
            }


            $booking = $this->bookingModel->find($kdbooking);
            if ($booking) {
                $pelanggan = $this->pelangganModel->find($booking['idpelanggan']);
                if ($pelanggan) {
                    $notificationModel = new \App\Models\NotificationModel();
                    $notificationModel->createNotification(
                        'booking_expired',
                        'Booking Expired',
                        'Booking ' . $kdbooking . ' atas nama ' . ($pelanggan['nama_lengkap'] ?? 'Pelanggan') . ' telah expired karena tidak melakukan pembayaran dalam waktu yang ditentukan.',
                        $kdbooking,
                        null
                    );
                }
            }

            $this->db->transCommit();
            return true;
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error saat mengupdate booking expired: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Membersihkan booking yang sudah expired tapi belum diupdate statusnya
     */
    private function cleanupExpiredBookings()
    {
        try {

            $expiredBookings = $this->bookingModel->where('jenispembayaran', 'Belum Bayar')
                ->where('status', 'pending')
                ->where('expired_at <', date('Y-m-d H:i:s'))
                ->findAll();

            log_message('debug', 'Found ' . count($expiredBookings) . ' expired bookings to clean up');

            foreach ($expiredBookings as $booking) {
                log_message('debug', 'Auto cleaning expired booking: ' . $booking['kdbooking']);
                $this->expireBooking($booking['kdbooking']);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error cleaning up expired bookings: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check status booking
     */
    public function checkStatus($kdbooking)
    {

        if (!session()->has('pelanggan')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
        }

        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($kdbooking);

        if (!$booking) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Booking tidak ditemukan'
            ]);
        }


        $idpelanggan = session()->get('pelanggan')['idpelanggan'];
        if ($booking['idpelanggan'] !== $idpelanggan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
        }


        if (
            $booking['status'] === 'pending' &&
            $booking['jenispembayaran'] === 'Belum Bayar' &&
            !empty($booking['expired_at']) &&
            strtotime($booking['expired_at']) < time()
        ) {


            $bookingModel->update($kdbooking, [
                'status' => 'expired'
            ]);

            $booking['status'] = 'expired';


            $detailBookingModel = new DetailBookingModel();
            $details = $detailBookingModel->where('kdbooking', $kdbooking)->findAll();

            foreach ($details as $detail) {
                $detailBookingModel->update($detail['iddetail'], [
                    'status' => '0' // 0 = Dibatalkan/Expired
                ]);
            }


            $notificationModel = new NotificationModel();
            $pelangganModel = new PelangganModel();
            $pelanggan = $pelangganModel->find($booking['idpelanggan']);

            if ($pelanggan) {
                $notificationModel->createNotification(
                    'booking_expired',
                    'Booking Expired',
                    'Booking ' . $kdbooking . ' atas nama ' . ($pelanggan['nama_lengkap'] ?? 'Pelanggan') . ' telah expired karena tidak melakukan pembayaran dalam waktu yang ditentukan.',
                    $kdbooking,
                    null
                );
            }
        }

        return $this->response->setJSON([
            'status' => $booking['status'],
            'booking' => $booking
        ]);
    }

    /**
     * Memeriksa semua booking yang sudah expired
     * Method ini digunakan sebagai fallback jika WebSocket tidak berfungsi
     */
    public function checkAllExpiredBookings()
    {

        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }


        $now = date('Y-m-d H:i:s');
        $expiredBookings = $this->bookingModel->where('jenispembayaran', 'Belum Bayar')
            ->where('status', 'pending')
            ->where('expired_at <', $now)
            ->findAll();

        $updatedBookings = [];
        $errorBookings = [];

        if (!empty($expiredBookings)) {
            foreach ($expiredBookings as $booking) {
                try {

                    $updated = $this->expireBooking($booking['kdbooking']);

                    if ($updated) {
                        $updatedBookings[] = $booking['kdbooking'];
                    } else {
                        $errorBookings[] = $booking['kdbooking'];
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error updating expired booking ' . $booking['kdbooking'] . ': ' . $e->getMessage());
                    $errorBookings[] = $booking['kdbooking'];
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pemeriksaan booking expired selesai',
            'updated_count' => count($updatedBookings),
            'updated_bookings' => $updatedBookings,
            'error_bookings' => $errorBookings,
            'timezone' => date_default_timezone_get(),
            'server_time' => $now
        ]);
    }
}
