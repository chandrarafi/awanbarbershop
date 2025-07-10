<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KaryawanModel;
use App\Models\PaketModel;
use App\Models\PelangganModel;
use App\Models\BookingModel;
use App\Models\DetailBookingModel;
use App\Models\PembayaranModel;
use App\Models\DetailPembayaranModel;

class ReportsController extends BaseController
{
    protected $karyawanModel;
    protected $paketModel;
    protected $pelangganModel;
    protected $bookingModel;
    protected $detailBookingModel;
    protected $pembayaranModel;
    protected $detailPembayaranModel;

    public function __construct()
    {
        $this->karyawanModel = new KaryawanModel();
        $this->paketModel = new PaketModel();
        $this->pelangganModel = new PelangganModel();
        $this->bookingModel = new BookingModel();
        $this->detailBookingModel = new DetailBookingModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->detailPembayaranModel = new DetailPembayaranModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Laporan'
        ];
        return view('admin/reports/index', $data);
    }

    public function karyawan()
    {
        $data = [
            'title' => 'Laporan Karyawan',
            'karyawan' => $this->karyawanModel->findAll()
        ];
        return view('admin/reports/karyawan', $data);
    }

    public function printKaryawan()
    {
        $karyawan = $this->karyawanModel->findAll();

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Karyawan',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => 'Tanggal: ' . date('d F Y'),
            'report_title' => 'LAPORAN DATA KARYAWAN',
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Karyawan</th>
                    <th>Nama Karyawan</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($karyawan as $k) {
            $jenkel = '-';
            if ($k['jenkel'] == 'L') {
                $jenkel = 'Laki-laki';
            } elseif ($k['jenkel'] == 'P') {
                $jenkel = 'Perempuan';
            }

            $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>' . $k['idkaryawan'] . '</td>
                    <td>' . $k['namakaryawan'] . '</td>
                    <td class="text-center">' . $jenkel . '</td>
                    <td>' . ($k['alamat'] ?? '-') . '</td>
                    <td>' . ($k['nohp'] ?? '-') . '</td>
                </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end fw-bold">Total Karyawan:</td>
                    <td class="text-center fw-bold">' . count($karyawan) . ' orang</td>
                </tr>
            </tfoot>
        </table>';

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function paket()
    {
        $data = [
            'title' => 'Laporan Paket',
            'paket' => $this->paketModel->findAll()
        ];
        return view('admin/reports/paket', $data);
    }

    public function printPaket()
    {
        $paket = $this->paketModel->findAll();

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Paket',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => 'Tanggal: ' . date('d F Y'),
            'report_title' => 'LAPORAN DATA PAKET LAYANAN',
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Paket</th>
                    <th>Nama Paket</th>
                    <th>Jenis Paket</th>
                    <th class="text-center">Harga</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        $totalHarga = 0;
        foreach ($paket as $p) {
            $totalHarga += $p['harga'];

            $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>' . $p['idpaket'] . '</td>
                    <td>' . $p['namapaket'] . '</td>
                    <td>' . ($p['deskripsi'] ?? '-') . '</td>
                    <td class="text-end">Rp ' . number_format($p['harga'], 0, ',', '.') . '</td>
                </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total Paket:</td>
                    <td class="text-center fw-bold" colspan="2">' . count($paket) . ' paket</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total Nilai Paket:</td>
                    <td class="text-end fw-bold" colspan="2">Rp ' . number_format($totalHarga, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function pelanggan()
    {
        $data = [
            'title' => 'Laporan Pelanggan',
            'pelanggan' => $this->pelangganModel->findAll()
        ];
        return view('admin/reports/pelanggan', $data);
    }

    public function printPelanggan()
    {

        $pelanggan = $this->pelangganModel->findAll();

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Pelanggan',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => 'Tanggal: ' . date('d F Y'),
            'report_title' => 'LAPORAN DATA PELANGGAN',
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($pelanggan as $p) {
            $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>' . $p['idpelanggan'] . '</td>
                    <td>' . $p['nama_lengkap'] . '</td>
                    <td>' . ($p['alamat'] ?? '-') . '</td>
                    <td>' . ($p['no_hp'] ?? '-') . '</td>
                </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total Pelanggan:</td>
                    <td class="text-center fw-bold">' . count($pelanggan) . ' orang</td>
                </tr>
            </tfoot>
        </table>';

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function booking()
    {
        // Get filter parameters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Inisialisasi array kosong untuk data booking
        $bookingData = [];

        // Hanya proses data jika filter telah dipilih
        if ($startDate || $endDate) {
            // Get all bookings first
            $bookings = $this->bookingModel->getBookingWithPelanggan();

            foreach ($bookings as $booking) {
                $details = $this->detailBookingModel->getDetailsByBookingCode($booking['kdbooking']);
                if (!empty($details)) {
                    $booking['details'] = $details;

                    // Apply date filtering if parameters exist
                    if ($startDate || $endDate) {
                        $filteredDetails = [];
                        foreach ($details as $detail) {
                            $detailDate = date('Y-m-d', strtotime($detail['tgl']));

                            $includeDetail = true;
                            if ($startDate && $detailDate < $startDate) {
                                $includeDetail = false;
                            }
                            if ($endDate && $detailDate > $endDate) {
                                $includeDetail = false;
                            }

                            if ($includeDetail) {
                                $filteredDetails[] = $detail;
                            }
                        }

                        // Only include booking if it has details within the date range
                        if (!empty($filteredDetails)) {
                            $booking['details'] = $filteredDetails;
                            $bookingData[] = $booking;
                        }
                    } else {
                        // No date filter, include all
                        $bookingData[] = $booking;
                    }
                }
            }
        }

        $data = [
            'title' => 'Laporan Booking',
            'bookings' => $bookingData,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        return view('admin/reports/booking', $data);
    }

    public function printBooking()
    {
        // Get filter parameters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Get all bookings first
        $bookings = $this->bookingModel->getBookingWithPelanggan();
        $bookingData = [];

        foreach ($bookings as $booking) {
            $details = $this->detailBookingModel->getDetailsByBookingCode($booking['kdbooking']);
            if (!empty($details)) {
                // Apply date filtering if parameters exist
                if ($startDate || $endDate) {
                    $filteredDetails = [];
                    foreach ($details as $detail) {
                        $detailDate = date('Y-m-d', strtotime($detail['tgl']));

                        $includeDetail = true;
                        if ($startDate && $detailDate < $startDate) {
                            $includeDetail = false;
                        }
                        if ($endDate && $detailDate > $endDate) {
                            $includeDetail = false;
                        }

                        if ($includeDetail) {
                            $filteredDetails[] = $detail;
                        }
                    }

                    // Only include booking if it has details within the date range
                    if (!empty($filteredDetails)) {
                        $booking['details'] = $filteredDetails;
                        $bookingData[] = $booking;
                    }
                } else {
                    // No date filter, include all
                    $booking['details'] = $details;
                    $bookingData[] = $booking;
                }
            }
        }

        // Set report title with date range if applicable
        $reportTitle = 'LAPORAN DATA BOOKING';

        // Persiapkan teks tanggal untuk report berdasarkan parameter filter
        $tanggalLabel = '';
        if ($startDate && $endDate) {
            $tanggalLabel = 'Tanggal: ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate));
        } elseif ($startDate) {
            $tanggalLabel = 'Dari Tanggal: ' . date('d/m/Y', strtotime($startDate));
        } elseif ($endDate) {
            $tanggalLabel = 'Sampai Tanggal: ' . date('d/m/Y', strtotime($endDate));
        } else {
            $tanggalLabel = 'Tanggal: ' . date('d F Y'); // Tanggal hari ini jika tidak ada filter
        }

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Booking',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => $tanggalLabel, // Kirim label tanggal ke template
            'report_title' => $reportTitle,
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Kode Booking</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Nama Paket</th>
                    <th>Jenis Paket</th>
                    <th class="text-center">Harga Paket</th>
                    <th class="text-center">Total Bayar</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        $totalBayar = 0;

        foreach ($bookingData as $booking) {
            foreach ($booking['details'] as $detail) {
                $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>' . $booking['kdbooking'] . '</td>
                    <td>' . $booking['nama_lengkap'] . '</td>
                    <td>' . date('d/m/Y', strtotime($detail['tgl'])) . '</td>
                    <td>' . $detail['nama_paket'] . '</td>
                    <td>' . $detail['deskripsi'] . '</td>
                    <td class="text-end">Rp ' . number_format($detail['harga'], 0, ',', '.') . '</td>
                    <td class="text-end">Rp ' . number_format($booking['total'], 0, ',', '.') . '</td>
                </tr>';
            }
            // Tambahkan total booking sekali saja per booking
            $totalBayar += $booking['total'];
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="text-end fw-bold">Total Seluruh Booking:</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalBayar, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function pembayaran()
    {
        // Get filter parameters
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $pembayaranData = [];

        // Hanya proses data jika filter telah dipilih
        if ($bulan || $tahun) {
            $pembayaran = $this->pembayaranModel->where('status', 'paid');

            // Filter berdasarkan bulan dan tahun
            if ($bulan && $tahun) {
                $pembayaran->where("DATE_FORMAT(created_at, '%m-%Y') = ", "$bulan-$tahun");
            } elseif ($bulan) {
                $pembayaran->where("DATE_FORMAT(created_at, '%m') = ", $bulan);
            } elseif ($tahun) {
                $pembayaran->where("DATE_FORMAT(created_at, '%Y') = ", $tahun);
            }

            $pembayaran = $pembayaran->findAll();

            foreach ($pembayaran as $p) {
                // Ambil data booking dan pelanggan
                $booking = $this->bookingModel->getBookingWithPelanggan($p['fakturbooking']);
                if ($booking) {
                    // Ambil detail booking
                    $details = $this->detailBookingModel->getDetailsByBookingCode($p['fakturbooking']);
                    if (!empty($details)) {
                        $p['booking'] = $booking;
                        $p['details'] = $details;
                        $pembayaranData[] = $p;
                    }
                }
            }
        }

        $data = [
            'title' => 'Laporan Pembayaran',
            'pembayaran' => $pembayaranData
        ];
        return view('admin/reports/pembayaran', $data);
    }

    public function printPembayaran()
    {
        // Get filter parameters
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $pembayaranData = [];
        $pembayaran = $this->pembayaranModel->where('status', 'paid');

        // Filter berdasarkan bulan dan tahun
        if ($bulan && $tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%m-%Y') = ", "$bulan-$tahun");
        } elseif ($bulan) {
            $pembayaran->where("DATE_FORMAT(created_at, '%m') = ", $bulan);
        } elseif ($tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%Y') = ", $tahun);
        }

        $pembayaran = $pembayaran->findAll();

        foreach ($pembayaran as $p) {
            // Ambil data booking dan pelanggan
            $booking = $this->bookingModel->getBookingWithPelanggan($p['fakturbooking']);
            if ($booking) {
                // Ambil detail booking
                $details = $this->detailBookingModel->getDetailsByBookingCode($p['fakturbooking']);
                if (!empty($details)) {
                    $p['booking'] = $booking;
                    $p['details'] = $details;
                    $pembayaranData[] = $p;
                }
            }
        }

        // Siapkan label tanggal untuk laporan
        $tanggalLabel = '';
        if ($bulan && $tahun) {
            $namaBulan = $this->getNamaBulan($bulan);
            $tanggalLabel = "Bulan: $namaBulan $tahun";
        } elseif ($bulan) {
            $namaBulan = $this->getNamaBulan($bulan);
            $tanggalLabel = "Bulan: $namaBulan " . date('Y');
        } elseif ($tahun) {
            $tanggalLabel = "Tahun: $tahun";
        } else {
            $tanggalLabel = 'Bulan: Semua';
        }

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Pembayaran',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal_label' => $tanggalLabel,
            'report_title' => 'LAPORAN DATA PEMBAYARAN',
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>No Transaksi</th>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>Nama Paket</th>
                    <th>Jenis Paket</th>
                    <th class="text-center">Harga Paket</th>
                    <th class="text-center">Total Bayar</th>
                    <th>Metode Pembayaran</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        $totalBayar = 0;

        foreach ($pembayaranData as $p) {
            foreach ($p['details'] as $detail) {
                $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>' . $p['fakturbooking'] . '</td>
                    <td>' . date('d/m/Y', strtotime($p['created_at'])) . '</td>
                    <td>' . $p['booking']['nama_lengkap'] . '</td>
                    <td>' . $detail['nama_paket'] . '</td>
                    <td>' . $detail['deskripsi'] . '</td>
                    <td class="text-end">Rp ' . number_format($detail['harga'], 0, ',', '.') . '</td>
                    <td class="text-end">Rp ' . number_format($p['total_bayar'], 0, ',', '.') . '</td>
                    <td>' . ucfirst($p['metode']) . '</td>
                </tr>';
            }
            // Tambahkan total pembayaran sekali saja per pembayaran
            $totalBayar += $p['total_bayar'];
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="text-end fw-bold">Total Seluruh Pembayaran:</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalBayar, 0, ',', '.') . '</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>';

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function pendapatanBulanan()
    {
        // Ambil daftar tahun untuk dropdown filter
        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();

        // Default nilai untuk data
        $pendapatanBulanan = [];
        $totalPendapatan = 0;
        $tahun = date('Y');
        $bulan = date('m');

        // Cek apakah filter telah dipilih secara eksplisit oleh user
        $filterSelected = $this->request->getGet('tahun') && $this->request->getGet('bulan');

        if ($filterSelected) {
            $tahun = $this->request->getGet('tahun');
            $bulan = $this->request->getGet('bulan');

            // Ambil data pembayaran per bulan
            $query = $this->db->query(
                "SELECT 
                    DATE(p.created_at) as tanggal,
                    db.nama_paket,
                    SUM(p.total_bayar) as total
                FROM 
                    pembayaran p
                JOIN 
                    booking b ON p.fakturbooking = b.kdbooking
                JOIN 
                    detail_booking db ON b.kdbooking = db.kdbooking
                WHERE 
                    p.status = 'paid' 
                    AND MONTH(p.created_at) = ?
                    AND YEAR(p.created_at) = ?
                GROUP BY 
                    DATE(p.created_at), db.nama_paket
                ORDER BY 
                    DATE(p.created_at) ASC",
                [$bulan, $tahun]
            );

            $pendapatanBulanan = $query->getResultArray();

            // Hitung total pendapatan bulan ini
            foreach ($pendapatanBulanan as $item) {
                $totalPendapatan += $item['total'];
            }
        }

        $data = [
            'title' => 'Laporan Pendapatan Bulanan',
            'pendapatan' => $pendapatanBulanan,
            'totalPendapatan' => $totalPendapatan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'daftarTahun' => $daftarTahun
        ];

        return view('admin/reports/pendapatan_bulanan', $data);
    }

    public function getPendapatanBulananData()
    {
        // Cek apakah request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        // Ambil parameter filter
        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');

        // Cek apakah tampilkan semua
        $showAll = $this->request->getGet('show_all') === 'true';

        if (!$showAll && (empty($tahun) || empty($bulan))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        // Format nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Buat query dasar
        $sql = "SELECT 
                DATE(p.created_at) as tanggal,
                db.nama_paket,
                SUM(p.total_bayar) as total
            FROM 
                pembayaran p
            JOIN 
                booking b ON p.fakturbooking = b.kdbooking
            JOIN 
                detail_booking db ON b.kdbooking = db.kdbooking
            WHERE 
                p.status = 'paid'";

        // Tambahkan filter bulan dan tahun jika tidak tampil semua
        if (!$showAll) {
            $sql .= " AND MONTH(p.created_at) = '$bulan' AND YEAR(p.created_at) = '$tahun'";
        }

        // Grup dan urutkan
        $sql .= " GROUP BY 
                DATE(p.created_at), db.nama_paket
            ORDER BY 
                DATE(p.created_at) DESC";

        // Batasi hasil jika tampil semua
        if ($showAll) {
            $sql .= " LIMIT 100";
        }

        $query = $this->db->query($sql);
        $pendapatanBulanan = $query->getResultArray();

        // Format data untuk tampilan tabel
        $formattedData = [];
        $totalPendapatan = 0;

        foreach ($pendapatanBulanan as $item) {
            $formattedData[] = [
                'tanggal' => date('d/m/Y', strtotime($item['tanggal'])),
                'nama_paket' => $item['nama_paket'],
                'total' => $item['total'],
                'total_formatted' => 'Rp ' . number_format($item['total'], 0, ',', '.')
            ];

            $totalPendapatan += $item['total'];
        }

        // Tentukan label periode
        $periodeLabel = $showAll ? 'Semua Data' : 'Bulan: ' . $namaBulan[$bulan] . ' ' . $tahun;

        return $this->response->setJSON([
            'success' => true,
            'data' => $formattedData,
            'totalPendapatan' => $totalPendapatan,
            'totalPendapatanFormatted' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.'),
            'periodeLabel' => $periodeLabel
        ]);
    }

    public function printPendapatanBulanan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('m');

        // Ambil data pembayaran per bulan
        $query = $this->db->query(
            "SELECT 
                DATE(p.created_at) as tanggal,
                db.nama_paket,
                SUM(p.total_bayar) as total
            FROM 
                pembayaran p
            JOIN 
                booking b ON p.fakturbooking = b.kdbooking
            JOIN 
                detail_booking db ON b.kdbooking = db.kdbooking
            WHERE 
                p.status = 'paid' 
                AND MONTH(p.created_at) = ?
                AND YEAR(p.created_at) = ?
            GROUP BY 
                DATE(p.created_at), db.nama_paket
            ORDER BY 
                DATE(p.created_at) ASC",
            [$bulan, $tahun]
        );

        $pendapatanBulanan = $query->getResultArray();

        // Hitung total pendapatan bulan ini
        $totalPendapatan = 0;
        foreach ($pendapatanBulanan as $item) {
            $totalPendapatan += $item['total'];
        }

        // Format nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Pendapatan Bulanan',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => 'Bulan: ' . $namaBulan[$bulan] . ' ' . $tahun,
            'tanggal_label' => 'Bulan: ' . $namaBulan[$bulan] . ' ' . $tahun,
            'report_title' => 'LAPORAN PENDAPATAN PERBULAN',
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Tanggal</th>
                    <th>Nama Paket</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        foreach ($pendapatanBulanan as $item) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . date('d/m/Y', strtotime($item['tanggal'])) . '</td>
                <td>' . $item['nama_paket'] . '</td>
                <td class="text-end">Rp ' . number_format($item['total'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total Pendapatan:</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function pendapatanTahunan()
    {
        // Default nilai untuk data
        $pendapatanBulanan = [];
        $totalPendapatan = 0;

        // Cek apakah filter telah dipilih secara eksplisit oleh user
        $tahun = $this->request->getGet('tahun');

        if ($tahun) {
            // Buat query dengan filter tahun
            $query = $this->db->query(
                "SELECT 
                    MONTH(created_at) as bulan,
                    YEAR(created_at) as tahun,
                    SUM(total_bayar) as total
                FROM 
                    pembayaran
                WHERE 
                    status = 'paid'
                    AND YEAR(created_at) = ?
                GROUP BY 
                    MONTH(created_at), YEAR(created_at)
                ORDER BY 
                    tahun DESC, bulan ASC",
                [$tahun]
            );
        } else {
            // Query tanpa filter tahun
            $query = $this->db->query(
                "SELECT 
                    MONTH(created_at) as bulan,
                    YEAR(created_at) as tahun,
                    SUM(total_bayar) as total
                FROM 
                    pembayaran
                WHERE 
                    status = 'paid'
                GROUP BY 
                    MONTH(created_at), YEAR(created_at)
                ORDER BY 
                    tahun DESC, bulan ASC"
            );
        }

        $pendapatanBulanan = $query->getResultArray();

        // Hitung total pendapatan keseluruhan
        foreach ($pendapatanBulanan as $item) {
            $totalPendapatan += $item['total'];
        }

        $data = [
            'title' => 'Laporan Pendapatan Bulanan',
            'pendapatan' => $pendapatanBulanan,
            'totalPendapatan' => $totalPendapatan
        ];

        return view('admin/reports/pendapatan_tahunan', $data);
    }

    public function getPendapatanTahunanData()
    {
        // Cek apakah request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        // Ambil parameter filter
        $tahun = $this->request->getGet('tahun');

        // Cek apakah tampilkan semua
        $showAll = $this->request->getGet('show_all') === 'true';

        if (!$showAll && empty($tahun)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        // Buat query dasar
        $sql = "SELECT 
                MONTH(created_at) as bulan,
                YEAR(created_at) as tahun,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'";

        // Tambahkan filter tahun jika ada
        if (!$showAll && $tahun) {
            $sql .= " AND YEAR(created_at) = $tahun";
        }

        // Grup dan urutkan
        $sql .= " GROUP BY 
                MONTH(created_at), YEAR(created_at)
            ORDER BY 
                tahun DESC, bulan ASC";

        $query = $this->db->query($sql);
        $pendapatanBulanan = $query->getResultArray();

        // Format data bulan agar tahun tidak duplikat
        $formattedData = [];
        foreach ($pendapatanBulanan as $item) {
            $formattedData[] = [
                'bulan' => $item['bulan'],
                'tahun' => $item['tahun'],
                'bulan_nama' => $this->getNamaBulan($item['bulan']),
                'total' => $item['total']
            ];
        }

        // Hitung total pendapatan
        $totalPendapatan = 0;
        foreach ($pendapatanBulanan as $item) {
            $totalPendapatan += $item['total'];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $formattedData,
            'totalPendapatan' => $totalPendapatan,
            'message' => 'Data berhasil dimuat'
        ]);
    }

    public function printPendapatanTahunan()
    {
        // Ambil parameter filter
        $tahun = $this->request->getGet('tahun');

        // Buat query dasar
        $sql = "SELECT 
                MONTH(created_at) as bulan,
                YEAR(created_at) as tahun,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'";

        // Tambahkan filter tahun jika ada
        if ($tahun) {
            $sql .= " AND YEAR(created_at) = $tahun";
        }

        // Grup dan urutkan
        $sql .= " GROUP BY 
                MONTH(created_at), YEAR(created_at)
            ORDER BY 
                tahun DESC, bulan ASC";

        $query = $this->db->query($sql);
        $pendapatanBulanan = $query->getResultArray();

        // Hitung total pendapatan keseluruhan
        $totalPendapatan = 0;
        foreach ($pendapatanBulanan as $item) {
            $totalPendapatan += $item['total'];
        }

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Pendapatan Bulanan',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => $tahun ? 'Tahun: ' . $tahun : date('d F Y'),
            'tanggal_label' => $tahun ? 'Tahun: ' . $tahun : 'Tahun: ' . date('Y'),
            'report_title' => 'LAPORAN PENDAPATAN PERTAHUN',
            'manager' => 'Pimpinan'
        ];

        // Tambahkan subtitle jika ada filter tahun
        $subtitle = '';
        if ($tahun) {
            // $subtitle = '<p class="text-center mb-4">Tahun: ' . $tahun . '</p>';
            $headerData['report_title'] = 'LAPORAN PENDAPATAN PERTAHUN ';
        }

        // Membuat konten tabel
        $content = $subtitle . '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Bulan</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        foreach ($pendapatanBulanan as $item) {
            $namaBulan = $this->getNamaBulan($item['bulan']);
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $namaBulan . '</td>
                <td class="text-end">Rp ' . number_format($item['total'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total :</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function pengeluaran()
    {
        // Ambil daftar tahun untuk dropdown filter
        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(tgl) as tahun FROM pengeluaran ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();

        // Default nilai untuk data
        $pengeluaran = [];
        $totalPengeluaran = 0;
        $tahun = date('Y');
        $bulan = date('m');

        $data = [
            'title' => 'Laporan Pengeluaran',
            'pengeluaran' => $pengeluaran,
            'totalPengeluaran' => $totalPengeluaran,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'daftarTahun' => $daftarTahun
        ];

        return view('admin/reports/pengeluaran', $data);
    }

    public function getPengeluaranData()
    {
        // Cek apakah request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        // Ambil parameter filter
        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');

        // Cek apakah tampilkan semua
        $showAll = $this->request->getGet('show_all') === 'true';

        if (!$showAll && (empty($tahun) || empty($bulan))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        // Format nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $pengeluaranModel = new \App\Models\PengeluaranModel();

        // Buat query dasar
        $builder = $pengeluaranModel->builder();

        if (!$showAll) {
            // Filter berdasarkan bulan dan tahun
            $builder->where('MONTH(tgl)', $bulan);
            $builder->where('YEAR(tgl)', $tahun);
        }

        // Urutkan berdasarkan tanggal
        $builder->orderBy('tgl', 'ASC');

        // Batasi hasil jika tampil semua
        if ($showAll) {
            $builder->limit(100);
        }

        $pengeluaran = $builder->get()->getResultArray();

        // Format data untuk tampilan tabel
        $formattedData = [];
        $totalPengeluaran = 0;

        foreach ($pengeluaran as $item) {
            $formattedData[] = [
                'id' => $item['idpengeluaran'],
                'tanggal' => date('d/m/Y', strtotime($item['tgl'])),
                'tgl_raw' => $item['tgl'],
                'keterangan' => $item['keterangan'],
                'jumlah' => $item['jumlah'],
                'jumlah_formatted' => 'Rp ' . number_format($item['jumlah'], 0, ',', '.')
            ];

            $totalPengeluaran += $item['jumlah'];
        }

        // Tentukan label periode
        $periodeLabel = $showAll ? 'Semua Data' : 'Bulan: ' . $namaBulan[$bulan] . ' ' . $tahun;

        return $this->response->setJSON([
            'success' => true,
            'data' => $formattedData,
            'totalPengeluaran' => $totalPengeluaran,
            'totalPengeluaranFormatted' => 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'),
            'periodeLabel' => $periodeLabel
        ]);
    }

    public function printPengeluaran()
    {
        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');
        $showAll = $this->request->getGet('show_all') === 'true';

        // Format nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $pengeluaranModel = new \App\Models\PengeluaranModel();

        // Buat query dasar
        $builder = $pengeluaranModel->builder();

        if (!$showAll && !empty($tahun) && !empty($bulan)) {
            // Filter berdasarkan bulan dan tahun
            $builder->where('MONTH(tgl)', $bulan);
            $builder->where('YEAR(tgl)', $tahun);
            $periodeLabel = 'Bulan: ' . $namaBulan[$bulan] . ' ' . $tahun;
        } else {
            // Jika tampilkan semua atau parameter tidak lengkap
            $periodeLabel = 'Semua Data';
            // Batasi hasil untuk "tampilkan semua"
            $builder->limit(100);
        }

        // Urutkan berdasarkan tanggal
        $builder->orderBy('tgl', 'ASC');

        $pengeluaran = $builder->get()->getResultArray();

        // Hitung total pengeluaran
        $totalPengeluaran = 0;
        foreach ($pengeluaran as $item) {
            $totalPengeluaran += $item['jumlah'];
        }

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Pengeluaran',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => $periodeLabel,
            'tanggal_label' => $periodeLabel,
            'report_title' => 'LAPORAN PENGELUARAN',
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th>Keterangan</th>
                    <th class="text-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        foreach ($pengeluaran as $item) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . date('d/m/Y', strtotime($item['tgl'])) . '</td>
                <td>' . $item['keterangan'] . '</td>
                <td class="text-end">Rp ' . number_format($item['jumlah'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total:</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</td>
                </tr>
            </tfoot>
        </table>';

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }

    public function labaRugi()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Format nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Inisialisasi data bulanan
        $dataBulanan = [];
        foreach ($namaBulan as $kode => $nama) {
            $dataBulanan[$kode] = [
                'pendapatan' => 0,
                'pengeluaran' => 0,
                'laba' => 0
            ];
        }

        // Ambil data pendapatan per bulan
        $queryPendapatan = $this->db->query(
            "SELECT 
                MONTH(created_at) as bulan,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'
                AND YEAR(created_at) = ?
            GROUP BY 
                MONTH(created_at)
            ORDER BY 
                bulan ASC",
            [$tahun]
        );

        $pendapatanBulanan = $queryPendapatan->getResultArray();

        // Masukkan data pendapatan ke array dataBulanan
        foreach ($pendapatanBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            $dataBulanan[$bulan]['pendapatan'] = $item['total'];
        }

        // Ambil data pengeluaran per bulan
        $pengeluaranModel = new \App\Models\PengeluaranModel();
        $queryPengeluaran = $this->db->query(
            "SELECT 
                MONTH(tgl) as bulan,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                YEAR(tgl) = ?
            GROUP BY 
                MONTH(tgl)
            ORDER BY 
                bulan ASC",
            [$tahun]
        );

        $pengeluaranBulanan = $queryPengeluaran->getResultArray();

        // Masukkan data pengeluaran ke array dataBulanan
        foreach ($pengeluaranBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            $dataBulanan[$bulan]['pengeluaran'] = $item['total'];
        }

        // Hitung laba per bulan
        foreach ($dataBulanan as $bulan => &$data) {
            $data['laba'] = $data['pendapatan'] - $data['pengeluaran'];
        }

        // Hitung total pendapatan, pengeluaran, dan laba bersih
        $totalPendapatan = 0;
        $totalPengeluaran = 0;

        foreach ($dataBulanan as $data) {
            $totalPendapatan += $data['pendapatan'];
            $totalPengeluaran += $data['pengeluaran'];
        }

        $labaBersih = $totalPendapatan - $totalPengeluaran;

        // Ambil daftar tahun untuk dropdown filter
        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();

        $data = [
            'title' => 'Laporan Laba Rugi',
            'dataBulanan' => $dataBulanan,
            'namaBulan' => $namaBulan,
            'totalPendapatan' => $totalPendapatan,
            'totalPengeluaran' => $totalPengeluaran,
            'labaBersih' => $labaBersih,
            'tahun' => $tahun,
            'daftarTahun' => $daftarTahun
        ];

        // Debug: Pastikan struktur data benar
        if (empty($daftarTahun)) {
            // Jika tidak ada data tahun, buat data default
            $data['daftarTahun'] = [['tahun' => date('Y')]];
        }

        // Pastikan setiap bulan memiliki data pendapatan dan pengeluaran
        foreach ($dataBulanan as $bulan => &$bulanData) {
            if (!isset($bulanData['pendapatan'])) {
                $bulanData['pendapatan'] = 0;
            }
            if (!isset($bulanData['pengeluaran'])) {
                $bulanData['pengeluaran'] = 0;
            }
            if (!isset($bulanData['laba'])) {
                $bulanData['laba'] = $bulanData['pendapatan'] - $bulanData['pengeluaran'];
            }
        }

        return view('admin/reports/laba_rugi', $data);
    }

    public function printLabaRugi()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Format nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Inisialisasi data bulanan
        $dataBulanan = [];
        foreach ($namaBulan as $kode => $nama) {
            $dataBulanan[$kode] = [
                'pendapatan' => 0,
                'pengeluaran' => 0,
                'laba' => 0
            ];
        }

        // Ambil data pendapatan per bulan
        $queryPendapatan = $this->db->query(
            "SELECT 
                MONTH(created_at) as bulan,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'
                AND YEAR(created_at) = ?
            GROUP BY 
                MONTH(created_at)
            ORDER BY 
                bulan ASC",
            [$tahun]
        );

        $pendapatanBulanan = $queryPendapatan->getResultArray();

        // Masukkan data pendapatan ke array dataBulanan
        foreach ($pendapatanBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            $dataBulanan[$bulan]['pendapatan'] = $item['total'];
        }

        // Ambil data pengeluaran per bulan
        $pengeluaranModel = new \App\Models\PengeluaranModel();
        $queryPengeluaran = $this->db->query(
            "SELECT 
                MONTH(tgl) as bulan,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                YEAR(tgl) = ?
            GROUP BY 
                MONTH(tgl)
            ORDER BY 
                bulan ASC",
            [$tahun]
        );

        $pengeluaranBulanan = $queryPengeluaran->getResultArray();

        // Masukkan data pengeluaran ke array dataBulanan
        foreach ($pengeluaranBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            $dataBulanan[$bulan]['pengeluaran'] = $item['total'];
        }

        // Hitung laba per bulan
        foreach ($dataBulanan as $bulan => &$data) {
            $data['laba'] = $data['pendapatan'] - $data['pengeluaran'];
        }

        // Hitung total pendapatan, pengeluaran, dan laba bersih
        $totalPendapatan = 0;
        $totalPengeluaran = 0;

        foreach ($dataBulanan as $data) {
            $totalPendapatan += $data['pendapatan'];
            $totalPengeluaran += $data['pengeluaran'];
        }

        $labaBersih = $totalPendapatan - $totalPengeluaran;

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Laba Rugi',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangan, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '0811-6359-5965',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
            'report_title' => 'LAPORAN LABA RUGI TAHUN ' . $tahun,
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <div class="text-center mb-3">
            <p class="mb-0">Periode: Tahun ' . $tahun . '</p>
        </div>
        
        <!-- Tabel Pendapatan -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="10%">NO</th>
                    <th width="60%">Pendapatan</th>
                    <th width="30%">Jumlah</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($dataBulanan as $bulan => $data) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $namaBulan[$bulan] . '</td>
                <td class="text-end">Rp ' . number_format($data['pendapatan'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            <tr>
                <td colspan="2" class="text-end fw-bold">Total</td>
                <td class="text-end fw-bold">Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</td>
            </tr>
            </tbody>
        </table>

        <!-- Tabel Beban/Pengeluaran -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th width="10%">NO</th>
                    <th width="60%">Beban</th>
                    <th width="30%">Jumlah</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($dataBulanan as $bulan => $data) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $namaBulan[$bulan] . '</td>
                <td class="text-end">Rp ' . number_format($data['pengeluaran'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            <tr>
                <td colspan="2" class="text-end fw-bold">Total</td>
                <td class="text-end fw-bold">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</td>
            </tr>
            </tbody>
        </table>

        <!-- Pendapatan Bersih -->
        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-bordered" width="100%">
                    <tr>
                        <td width="70%" class="text-end fw-bold">Pendapatan Bersih</td>
                        <td width="30%" class="text-end fw-bold">Rp ' . number_format($labaBersih, 0, ',', '.') . '</td>
                    </tr>
                </table>
            </div>
        </div>';

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        // Pastikan setiap bulan memiliki data pendapatan dan pengeluaran
        foreach ($dataBulanan as $bulan => &$bulanData) {
            if (!isset($bulanData['pendapatan'])) {
                $bulanData['pendapatan'] = 0;
            }
            if (!isset($bulanData['pengeluaran'])) {
                $bulanData['pengeluaran'] = 0;
            }
            if (!isset($bulanData['laba'])) {
                $bulanData['laba'] = $bulanData['pendapatan'] - $bulanData['pengeluaran'];
            }
        }

        return view('admin/reports/template_laporan', $data);
    }

    public function labaRugiBulanan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('m');

        // Format nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Mendapatkan jumlah hari dalam bulan yang dipilih
        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, intval($bulan), intval($tahun));

        // Inisialisasi data harian
        $dataHarian = [];
        for ($i = 1; $i <= $jumlahHari; $i++) {
            $tanggal = $tahun . '-' . $bulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $dataHarian[$tanggal] = [
                'pendapatan' => 0,
                'pengeluaran' => 0,
                'laba' => 0
            ];
        }

        // Ambil data pendapatan per hari
        $queryPendapatan = $this->db->query(
            "SELECT 
                DATE(created_at) as tanggal,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'
                AND YEAR(created_at) = ?
                AND MONTH(created_at) = ?
            GROUP BY 
                DATE(created_at)
            ORDER BY 
                tanggal ASC",
            [$tahun, $bulan]
        );

        $pendapatanHarian = $queryPendapatan->getResultArray();

        // Masukkan data pendapatan ke array dataHarian
        foreach ($pendapatanHarian as $item) {
            if (isset($dataHarian[$item['tanggal']])) {
                $dataHarian[$item['tanggal']]['pendapatan'] = $item['total'];
            }
        }

        // Ambil data pengeluaran per hari
        $pengeluaranModel = new \App\Models\PengeluaranModel();
        $queryPengeluaran = $this->db->query(
            "SELECT 
                DATE(tgl) as tanggal,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                YEAR(tgl) = ?
                AND MONTH(tgl) = ?
            GROUP BY 
                DATE(tgl)
            ORDER BY 
                tanggal ASC",
            [$tahun, $bulan]
        );

        $pengeluaranHarian = $queryPengeluaran->getResultArray();

        // Masukkan data pengeluaran ke array dataHarian
        foreach ($pengeluaranHarian as $item) {
            if (isset($dataHarian[$item['tanggal']])) {
                $dataHarian[$item['tanggal']]['pengeluaran'] = $item['total'];
            }
        }

        // Filter data harian (hanya tampilkan hari dengan transaksi)
        $dataHarianFiltered = [];
        foreach ($dataHarian as $tanggal => $data) {
            $data['laba'] = $data['pendapatan'] - $data['pengeluaran'];

            // Hanya tampilkan hari dengan transaksi
            if ($data['pendapatan'] > 0 || $data['pengeluaran'] > 0) {
                $dataHarianFiltered[$tanggal] = $data;
            }
        }

        // Hitung total pendapatan, pengeluaran, dan laba bersih
        $totalPendapatan = 0;
        $totalPengeluaran = 0;

        foreach ($dataHarianFiltered as $data) {
            $totalPendapatan += $data['pendapatan'];
            $totalPengeluaran += $data['pengeluaran'];
        }

        $labaBersih = $totalPendapatan - $totalPengeluaran;

        // Ambil daftar tahun untuk dropdown filter
        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();

        $data = [
            'title' => 'Laporan Laba Rugi Bulanan',
            'dataHarian' => $dataHarianFiltered,
            'namaBulan' => $namaBulan,
            'totalPendapatan' => $totalPendapatan,
            'totalPengeluaran' => $totalPengeluaran,
            'labaBersih' => $labaBersih,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'daftarTahun' => $daftarTahun
        ];

        // Debug: Pastikan struktur data benar
        if (empty($daftarTahun)) {
            // Jika tidak ada data tahun, buat data default
            $data['daftarTahun'] = [['tahun' => date('Y')]];
        }

        // Pastikan setiap hari memiliki data pendapatan dan pengeluaran
        foreach ($dataHarianFiltered as $tanggal => &$hariData) {
            if (!isset($hariData['pendapatan'])) {
                $hariData['pendapatan'] = 0;
            }
            if (!isset($hariData['pengeluaran'])) {
                $hariData['pengeluaran'] = 0;
            }
            if (!isset($hariData['laba'])) {
                $hariData['laba'] = $hariData['pendapatan'] - $hariData['pengeluaran'];
            }
        }

        return view('admin/reports/laba_rugi_bulanan', $data);
    }

    public function printLabaRugiBulanan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan') ?? date('m');

        // Format nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Mendapatkan jumlah hari dalam bulan yang dipilih
        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, intval($bulan), intval($tahun));

        // Inisialisasi data harian
        $dataHarian = [];
        for ($i = 1; $i <= $jumlahHari; $i++) {
            $tanggal = $tahun . '-' . $bulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $dataHarian[$tanggal] = [
                'pendapatan' => 0,
                'pengeluaran' => 0,
                'laba' => 0
            ];
        }

        // Ambil data pendapatan per hari
        $queryPendapatan = $this->db->query(
            "SELECT 
                DATE(created_at) as tanggal,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'
                AND YEAR(created_at) = ?
                AND MONTH(created_at) = ?
            GROUP BY 
                DATE(created_at)
            ORDER BY 
                tanggal ASC",
            [$tahun, $bulan]
        );

        $pendapatanHarian = $queryPendapatan->getResultArray();

        // Masukkan data pendapatan ke array dataHarian
        foreach ($pendapatanHarian as $item) {
            if (isset($dataHarian[$item['tanggal']])) {
                $dataHarian[$item['tanggal']]['pendapatan'] = $item['total'];
            }
        }

        // Ambil data pengeluaran per hari
        $pengeluaranModel = new \App\Models\PengeluaranModel();
        $queryPengeluaran = $this->db->query(
            "SELECT 
                DATE(tgl) as tanggal,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                YEAR(tgl) = ?
                AND MONTH(tgl) = ?
            GROUP BY 
                DATE(tgl)
            ORDER BY 
                tanggal ASC",
            [$tahun, $bulan]
        );

        $pengeluaranHarian = $queryPengeluaran->getResultArray();

        // Masukkan data pengeluaran ke array dataHarian
        foreach ($pengeluaranHarian as $item) {
            if (isset($dataHarian[$item['tanggal']])) {
                $dataHarian[$item['tanggal']]['pengeluaran'] = $item['total'];
            }
        }

        // Filter data harian (hanya tampilkan hari dengan transaksi)
        $dataHarianFiltered = [];
        foreach ($dataHarian as $tanggal => $data) {
            $data['laba'] = $data['pendapatan'] - $data['pengeluaran'];

            // Hanya tampilkan hari dengan transaksi
            if ($data['pendapatan'] > 0 || $data['pengeluaran'] > 0) {
                $dataHarianFiltered[$tanggal] = $data;
            }
        }

        // Hitung total pendapatan, pengeluaran, dan laba bersih
        $totalPendapatan = 0;
        $totalPengeluaran = 0;

        foreach ($dataHarianFiltered as $data) {
            $totalPendapatan += $data['pendapatan'];
            $totalPengeluaran += $data['pengeluaran'];
        }

        $labaBersih = $totalPendapatan - $totalPengeluaran;

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Laba Rugi Bulanan',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangan, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '0811-6359-5965',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
            'report_title' => 'LAPORAN LABA RUGI BULAN ' . $namaBulan[$bulan] . ' ' . $tahun,
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <div class="text-center mb-3">
            <p class="mb-0">Periode: ' . $namaBulan[$bulan] . ' ' . $tahun . '</p>
        </div>';

        if (empty($dataHarianFiltered)) {
            $content .= '
            <div class="alert alert-info">
                Tidak ada data laba rugi untuk bulan ' . $namaBulan[$bulan] . ' ' . $tahun . '
            </div>';
        } else {
            $content .= '
            <!-- Tabel Pendapatan -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="10%">NO</th>
                        <th width="60%">Pendapatan</th>
                        <th width="30%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>';

            $no = 1;
            foreach ($dataHarianFiltered as $tanggal => $data) {
                $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>Tanggal ' . date('d/m/Y', strtotime($tanggal)) . '</td>
                    <td class="text-end">Rp ' . number_format($data['pendapatan'], 0, ',', '.') . '</td>
                </tr>';
            }

            $content .= '
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalPendapatan, 0, ',', '.') . '</td>
                </tr>
                </tbody>
            </table>

            <!-- Tabel Beban/Pengeluaran -->
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th width="10%">NO</th>
                        <th width="60%">Beban</th>
                        <th width="30%">Jumlah</th>
                    </tr>
                </thead>
                <tbody>';

            $no = 1;
            foreach ($dataHarianFiltered as $tanggal => $data) {
                $content .= '
                <tr>
                    <td class="text-center">' . $no++ . '</td>
                    <td>Tanggal ' . date('d/m/Y', strtotime($tanggal)) . '</td>
                    <td class="text-end">Rp ' . number_format($data['pengeluaran'], 0, ',', '.') . '</td>
                </tr>';
            }

            $content .= '
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</td>
                </tr>
                </tbody>
            </table>

            <!-- Pendapatan Bersih -->
            <div class="row mt-3">
                <div class="col-12">
                    <table class="table table-bordered" width="100%">
                        <tr>
                            <td width="70%" class="text-end fw-bold">Pendapatan Bersih</td>
                            <td width="30%" class="text-end fw-bold">Rp ' . number_format($labaBersih, 0, ',', '.') . '</td>
                        </tr>
                    </table>
                </div>
            </div>';
        }

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        // Pastikan setiap hari memiliki data pendapatan dan pengeluaran
        foreach ($dataHarianFiltered as $tanggal => &$hariData) {
            if (!isset($hariData['pendapatan'])) {
                $hariData['pendapatan'] = 0;
            }
            if (!isset($hariData['pengeluaran'])) {
                $hariData['pengeluaran'] = 0;
            }
            if (!isset($hariData['laba'])) {
                $hariData['laba'] = $hariData['pendapatan'] - $hariData['pengeluaran'];
            }
        }

        return view('admin/reports/template_laporan', $data);
    }

    public function getData()
    {
        // Cek apakah request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        // Ambil parameter filter
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Get all bookings first
        $bookings = $this->bookingModel->getBookingWithPelanggan();
        $bookingData = [];
        $totalData = 0;

        foreach ($bookings as $booking) {
            $details = $this->detailBookingModel->getDetailsByBookingCode($booking['kdbooking']);
            if (!empty($details)) {
                // Apply date filtering if parameters exist
                if ($startDate || $endDate) {
                    $filteredDetails = [];
                    foreach ($details as $detail) {
                        $detailDate = date('Y-m-d', strtotime($detail['tgl']));

                        $includeDetail = true;
                        if ($startDate && $detailDate < $startDate) {
                            $includeDetail = false;
                        }
                        if ($endDate && $detailDate > $endDate) {
                            $includeDetail = false;
                        }

                        if ($includeDetail) {
                            $filteredDetails[] = $detail;
                        }
                    }

                    // Only include booking if it has details within the date range
                    if (!empty($filteredDetails)) {
                        $booking['details'] = $filteredDetails;
                        $bookingData[] = $booking;
                    }
                } else {
                    // No date filter, include all
                    $booking['details'] = $details;
                    $bookingData[] = $booking;
                }
            }
        }

        // Buat HTML untuk tabel
        $html = '';
        $no = 1;

        foreach ($bookingData as $booking) {
            foreach ($booking['details'] as $detail) {
                $html .= '<tr>';
                $html .= '<td>' . $no++ . '</td>';
                $html .= '<td>' . ($booking['kdbooking'] ?? '') . '</td>';
                $html .= '<td>' . ($booking['nama_lengkap'] ?? '') . '</td>';
                $html .= '<td>' . (isset($detail['tgl']) ? date('d/m/Y', strtotime($detail['tgl'])) : '') . '</td>';
                $html .= '<td>' . ($detail['nama_paket'] ?? '') . '</td>';
                $html .= '<td>' . ($detail['deskripsi'] ?? '') . '</td>';
                $html .= '<td>Rp ' . number_format(($detail['harga'] ?? 0), 0, ',', '.') . '</td>';
                $html .= '<td>Rp ' . number_format(($booking['total'] ?? 0), 0, ',', '.') . '</td>';
                $html .= '</tr>';
                $totalData++;
            }
        }

        // Jika tidak ada data
        if ($totalData === 0) {
            $html = '<tr><td colspan="8" class="text-center">Tidak ada data yang ditemukan</td></tr>';
        }

        // Siapkan pesan berdasarkan filter
        $pesan = 'Data berhasil dimuat';
        if ($startDate && $endDate) {
            $pesan = 'Data booking periode ' . date('d/m/Y', strtotime($startDate)) . ' - ' .
                date('d/m/Y', strtotime($endDate)) . ' berhasil dimuat';
        } elseif ($startDate) {
            $pesan = 'Data booking dari tanggal ' . date('d/m/Y', strtotime($startDate)) . ' berhasil dimuat';
        } elseif ($endDate) {
            $pesan = 'Data booking sampai tanggal ' . date('d/m/Y', strtotime($endDate)) . ' berhasil dimuat';
        } else {
            $pesan = 'Semua data booking berhasil dimuat';
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $pesan,
            'html' => $html,
            'data' => $bookingData, // Tambahkan data mentah
            'total' => $totalData,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    public function getPembayaranData()
    {
        // Cek apakah request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        // Ambil parameter filter
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $pembayaranData = [];
        $pembayaran = $this->pembayaranModel->where('status', 'paid');

        // Filter berdasarkan bulan dan tahun
        if ($bulan && $tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%m-%Y') = ", "$bulan-$tahun");
        } elseif ($bulan) {
            $pembayaran->where("DATE_FORMAT(created_at, '%m') = ", $bulan);
        } elseif ($tahun) {
            $pembayaran->where("DATE_FORMAT(created_at, '%Y') = ", $tahun);
        }

        $pembayaran = $pembayaran->findAll();
        $totalData = 0;

        foreach ($pembayaran as $p) {
            // Ambil data booking dan pelanggan
            $booking = $this->bookingModel->getBookingWithPelanggan($p['fakturbooking']);
            if ($booking) {
                // Ambil detail booking
                $details = $this->detailBookingModel->getDetailsByBookingCode($p['fakturbooking']);
                if (!empty($details)) {
                    $p['booking'] = $booking;
                    $p['details'] = $details;
                    $pembayaranData[] = $p;
                }
            }
        }

        // Buat HTML untuk tabel
        $html = '';
        $no = 1;

        foreach ($pembayaranData as $p) {
            foreach ($p['details'] as $detail) {
                $html .= '<tr>';
                $html .= '<td>' . $no++ . '</td>';
                $html .= '<td>' . ($p['fakturbooking'] ?? '') . '</td>';
                $html .= '<td>' . (isset($p['created_at']) ? date('d/m/Y', strtotime($p['created_at'])) : '') . '</td>';
                $html .= '<td>' . ($p['booking']['nama_lengkap'] ?? '') . '</td>';
                $html .= '<td>' . ($detail['nama_paket'] ?? '') . '</td>';
                $html .= '<td>' . ($detail['deskripsi'] ?? '') . '</td>';
                $html .= '<td>Rp ' . number_format(($detail['harga'] ?? 0), 0, ',', '.') . '</td>';
                $html .= '<td>Rp ' . number_format(($p['total_bayar'] ?? 0), 0, ',', '.') . '</td>';
                $html .= '<td>' . ucfirst($p['metode'] ?? '') . '</td>';
                $html .= '</tr>';
                $totalData++;
            }
        }

        // Jika tidak ada data
        if ($totalData === 0) {
            $html = '<tr><td colspan="9" class="text-center">Tidak ada data yang ditemukan</td></tr>';
        }

        // Siapkan pesan berdasarkan filter
        $pesan = 'Data berhasil dimuat';
        if ($bulan && $tahun) {
            $namaBulan = $this->getNamaBulan($bulan);
            $pesan = "Data pembayaran bulan $namaBulan $tahun berhasil dimuat";
        } elseif ($bulan) {
            $namaBulan = $this->getNamaBulan($bulan);
            $pesan = "Data pembayaran bulan $namaBulan berhasil dimuat";
        } elseif ($tahun) {
            $pesan = "Data pembayaran tahun $tahun berhasil dimuat";
        } else {
            $pesan = "Semua data pembayaran berhasil dimuat";
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $pesan,
            'html' => $html,
            'data' => $pembayaranData, // Tambahkan data mentah
            'total' => $totalData,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    }

    // Helper method untuk mendapatkan nama bulan
    private function getNamaBulan($bulan)
    {
        // Pastikan format bulan konsisten
        if (is_numeric($bulan)) {
            // Jika bulan adalah angka, format menjadi 2 digit
            $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        }

        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return $namaBulan[$bulan] ?? 'Bulan ' . $bulan;
    }

    public function uangMasukKeluar()
    {
        // Ambil daftar tahun untuk dropdown filter
        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();

        $data = [
            'title' => 'Laporan Uang Masuk dan Keluar',
            'tahun' => date('Y'),
            'daftarTahun' => $daftarTahun
        ];

        return view('admin/reports/uang_masuk_keluar', $data);
    }

    public function getUangMasukKeluarData()
    {
        // Cek apakah request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Permintaan tidak valid'
            ]);
        }

        // Ambil parameter filter
        $tahun = $this->request->getGet('tahun');

        // Cek apakah tampilkan semua
        $showAll = $this->request->getGet('show_all') === 'true';

        if (!$showAll && empty($tahun)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        // Format nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Inisialisasi data bulanan
        $dataBulanan = [];
        foreach ($namaBulan as $kode => $nama) {
            $dataBulanan[$kode] = [
                'bulan_kode' => $kode,
                'bulan' => $nama,
                'uang_masuk' => 0,
                'uang_keluar' => 0,
                'total' => 0,
                'status' => '' // Akan diisi "LABA" atau "RUGI"
            ];
        }

        // Query untuk uang masuk (pendapatan)
        $sqlPendapatan = "SELECT 
                MONTH(created_at) as bulan,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'";

        if (!$showAll && $tahun) {
            $sqlPendapatan .= " AND YEAR(created_at) = ?";
        }

        $sqlPendapatan .= " GROUP BY MONTH(created_at)";

        $queryParams = !$showAll && $tahun ? [$tahun] : [];
        $queryPendapatan = $this->db->query($sqlPendapatan, $queryParams);
        $pendapatanBulanan = $queryPendapatan->getResultArray();

        // Masukkan data pendapatan ke array dataBulanan
        foreach ($pendapatanBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            if (isset($dataBulanan[$bulan])) {
                $dataBulanan[$bulan]['uang_masuk'] = $item['total'];
            }
        }

        // Query untuk uang keluar (pengeluaran)
        $sqlPengeluaran = "SELECT 
                MONTH(tgl) as bulan,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                1=1";

        if (!$showAll && $tahun) {
            $sqlPengeluaran .= " AND YEAR(tgl) = ?";
        }

        $sqlPengeluaran .= " GROUP BY MONTH(tgl)";

        $queryPengeluaran = $this->db->query($sqlPengeluaran, $queryParams);
        $pengeluaranBulanan = $queryPengeluaran->getResultArray();

        // Masukkan data pengeluaran ke array dataBulanan
        foreach ($pengeluaranBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            if (isset($dataBulanan[$bulan])) {
                $dataBulanan[$bulan]['uang_keluar'] = $item['total'];
            }
        }

        // Hitung total dan tentukan status laba/rugi
        $totalUangMasuk = 0;
        $totalUangKeluar = 0;

        foreach ($dataBulanan as $bulan => &$data) {
            $data['total'] = $data['uang_masuk'] - $data['uang_keluar'];
            $data['status'] = $data['total'] >= 0 ? 'LABA' : 'RUGI';

            $totalUangMasuk += $data['uang_masuk'];
            $totalUangKeluar += $data['uang_keluar'];
        }

        $totalKeseluruhan = $totalUangMasuk - $totalUangKeluar;
        $statusKeseluruhan = $totalKeseluruhan >= 0 ? 'LABA' : 'RUGI';

        // Format data untuk tampilan tabel
        $formattedData = [];
        foreach ($dataBulanan as $data) {
            // Hanya tampilkan bulan yang memiliki transaksi
            if ($data['uang_masuk'] > 0 || $data['uang_keluar'] > 0) {
                $formattedData[] = [
                    'bulan_kode' => $data['bulan_kode'],
                    'bulan' => $data['bulan'],
                    'uang_masuk' => $data['uang_masuk'],
                    'uang_masuk_formatted' => 'Rp ' . number_format($data['uang_masuk'], 0, ',', '.'),
                    'uang_keluar' => $data['uang_keluar'],
                    'uang_keluar_formatted' => 'Rp ' . number_format($data['uang_keluar'], 0, ',', '.'),
                    'total' => $data['total'],
                    'total_formatted' => 'Rp ' . number_format($data['total'], 0, ',', '.'),
                    'status' => $data['status']
                ];
            }
        }

        // Urutkan data berdasarkan kode bulan (ascending)
        usort($formattedData, function ($a, $b) {
            return $a['bulan_kode'] <=> $b['bulan_kode'];
        });

        // Tentukan label periode
        $periodeLabel = $showAll ? 'Semua Data' : 'Tahun: ' . $tahun;

        return $this->response->setJSON([
            'success' => true,
            'data' => $formattedData,
            'totalUangMasuk' => $totalUangMasuk,
            'totalUangMasukFormatted' => 'Rp ' . number_format($totalUangMasuk, 0, ',', '.'),
            'totalUangKeluar' => $totalUangKeluar,
            'totalUangKeluarFormatted' => 'Rp ' . number_format($totalUangKeluar, 0, ',', '.'),
            'totalKeseluruhan' => $totalKeseluruhan,
            'totalKeseluruhanFormatted' => 'Rp ' . number_format($totalKeseluruhan, 0, ',', '.'),
            'statusKeseluruhan' => $statusKeseluruhan,
            'periodeLabel' => $periodeLabel
        ]);
    }

    public function printUangMasukKeluar()
    {
        // Ambil parameter filter
        $tahun = $this->request->getGet('tahun');
        $showAll = $this->request->getGet('show_all') === 'true';

        // Format nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Inisialisasi data bulanan
        $dataBulanan = [];
        foreach ($namaBulan as $kode => $nama) {
            $dataBulanan[$kode] = [
                'bulan_kode' => $kode,
                'bulan' => $nama,
                'uang_masuk' => 0,
                'uang_keluar' => 0,
                'total' => 0,
                'status' => '' // Akan diisi "LABA" atau "RUGI"
            ];
        }

        // Query untuk uang masuk (pendapatan)
        $sqlPendapatan = "SELECT 
                MONTH(created_at) as bulan,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'";

        if (!$showAll && $tahun) {
            $sqlPendapatan .= " AND YEAR(created_at) = ?";
        }

        $sqlPendapatan .= " GROUP BY MONTH(created_at)";

        $queryParams = !$showAll && $tahun ? [$tahun] : [];
        $queryPendapatan = $this->db->query($sqlPendapatan, $queryParams);
        $pendapatanBulanan = $queryPendapatan->getResultArray();

        // Masukkan data pendapatan ke array dataBulanan
        foreach ($pendapatanBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            if (isset($dataBulanan[$bulan])) {
                $dataBulanan[$bulan]['uang_masuk'] = $item['total'];
            }
        }

        // Query untuk uang keluar (pengeluaran)
        $sqlPengeluaran = "SELECT 
                MONTH(tgl) as bulan,
                SUM(jumlah) as total
            FROM 
                pengeluaran
            WHERE 
                1=1";

        if (!$showAll && $tahun) {
            $sqlPengeluaran .= " AND YEAR(tgl) = ?";
        }

        $sqlPengeluaran .= " GROUP BY MONTH(tgl)";

        $queryPengeluaran = $this->db->query($sqlPengeluaran, $queryParams);
        $pengeluaranBulanan = $queryPengeluaran->getResultArray();

        // Masukkan data pengeluaran ke array dataBulanan
        foreach ($pengeluaranBulanan as $item) {
            $bulan = str_pad($item['bulan'], 2, '0', STR_PAD_LEFT);
            if (isset($dataBulanan[$bulan])) {
                $dataBulanan[$bulan]['uang_keluar'] = $item['total'];
            }
        }

        // Hitung total dan tentukan status laba/rugi
        $totalUangMasuk = 0;
        $totalUangKeluar = 0;

        foreach ($dataBulanan as $bulan => &$data) {
            $data['total'] = $data['uang_masuk'] - $data['uang_keluar'];
            $data['status'] = $data['total'] >= 0 ? 'LABA' : 'RUGI';

            $totalUangMasuk += $data['uang_masuk'];
            $totalUangKeluar += $data['uang_keluar'];
        }

        $totalKeseluruhan = $totalUangMasuk - $totalUangKeluar;
        $statusKeseluruhan = $totalKeseluruhan >= 0 ? 'LABA' : 'RUGI';

        // Filter data bulanan untuk hanya menampilkan bulan dengan transaksi
        $filteredData = array_filter($dataBulanan, function ($data) {
            return $data['uang_masuk'] > 0 || $data['uang_keluar'] > 0;
        });

        // Urutkan data berdasarkan kode bulan
        uasort($filteredData, function ($a, $b) {
            return $a['bulan_kode'] <=> $b['bulan_kode'];
        });

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Uang Masuk dan Keluar',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Dr. Moh. Hatta No.3kel, RT.01, Cupak Tangah, Kec. Pauh, Kota Padang, Sumatera Barat 25127',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => $showAll ? 'Semua Data' : 'Tahun: ' . $tahun,
            'tanggal_label' => $showAll ? 'Semua Data' : 'Tahun: ' . $tahun,
            'report_title' => 'LAPORAN UANG MASUK DAN KELUAR',
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Bulan</th>
                    <th class="text-center">Uang Masuk (UM)</th>
                    <th class="text-center">Uang Keluar (UK)</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        foreach ($filteredData as $data) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $data['bulan'] . '</td>
                <td class="text-end">Rp ' . number_format($data['uang_masuk'], 0, ',', '.') . '</td>
                <td class="text-end">Rp ' . number_format($data['uang_keluar'], 0, ',', '.') . '</td>
                <td class="text-center fw-bold ' . ($data['status'] == 'LABA' ? 'text-success' : 'text-danger') . '">' . $data['status'] . '</td>
                </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total :</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalUangMasuk, 0, ',', '.') . '</td>
                    <td class="text-end fw-bold">Rp ' . number_format($totalUangKeluar, 0, ',', '.') . '</td>
                    <td class="text-center fw-bold ' . ($statusKeseluruhan == 'LABA' ? 'text-success' : 'text-danger') . '">' . $statusKeseluruhan . '</td>
                </tr>
              
            </tfoot>
        </table>';

        // Menggabungkan data header dan konten
        $data = array_merge($headerData, ['content' => $content]);

        return view('admin/reports/template_laporan', $data);
    }
}
