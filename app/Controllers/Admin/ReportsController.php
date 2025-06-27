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
            'alamat_perusahaan' => 'Jl. Contoh No. 123, Kota',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
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
            'alamat_perusahaan' => 'Jl. Contoh No. 123, Kota',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
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
            'alamat_perusahaan' => 'Jl. Contoh No. 123, Kota',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
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
        $bookings = $this->bookingModel->getBookingWithPelanggan();
        $bookingData = [];

        foreach ($bookings as $booking) {
            $details = $this->detailBookingModel->getDetailsByBookingCode($booking['kdbooking']);
            if (!empty($details)) {
                $booking['details'] = $details;
                $bookingData[] = $booking;
            }
        }

        $data = [
            'title' => 'Laporan Booking',
            'bookings' => $bookingData
        ];
        return view('admin/reports/booking', $data);
    }

    public function printBooking()
    {
        $bookings = $this->bookingModel->getBookingWithPelanggan();
        $bookingData = [];

        foreach ($bookings as $booking) {
            $details = $this->detailBookingModel->getDetailsByBookingCode($booking['kdbooking']);
            if (!empty($details)) {
                $booking['details'] = $details;
                $bookingData[] = $booking;
            }
        }

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Booking',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Contoh No. 123, Kota',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
            'report_title' => 'LAPORAN DATA BOOKING',
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
        $pembayaranData = [];

        $pembayaran = $this->pembayaranModel->where('status', 'paid')->findAll();

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

        $data = [
            'title' => 'Laporan Pembayaran',
            'pembayaran' => $pembayaranData
        ];
        return view('admin/reports/pembayaran', $data);
    }

    public function printPembayaran()
    {
        $pembayaranData = [];

        $pembayaran = $this->pembayaranModel->where('status', 'paid')->findAll();

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

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Pembayaran',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Contoh No. 123, Kota',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
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

        // Ambil daftar tahun untuk dropdown filter
        $queryTahun = $this->db->query(
            "SELECT DISTINCT YEAR(created_at) as tahun FROM pembayaran WHERE status = 'paid' ORDER BY tahun DESC"
        );
        $daftarTahun = $queryTahun->getResultArray();

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
            'alamat_perusahaan' => 'Jl. Contoh No. 123, Kota',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
            'report_title' => 'LAPORAN PENDAPATAN BULAN ' . $namaBulan[$bulan] . ' ' . $tahun,
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
        // Ambil data pembayaran per tahun
        $query = $this->db->query(
            "SELECT 
                YEAR(created_at) as tahun,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'
            GROUP BY 
                YEAR(created_at)
            ORDER BY 
                tahun DESC"
        );

        $pendapatanTahunan = $query->getResultArray();

        // Hitung total pendapatan keseluruhan
        $totalPendapatan = 0;
        foreach ($pendapatanTahunan as $item) {
            $totalPendapatan += $item['total'];
        }

        $data = [
            'title' => 'Laporan Pendapatan Tahunan',
            'pendapatan' => $pendapatanTahunan,
            'totalPendapatan' => $totalPendapatan
        ];

        return view('admin/reports/pendapatan_tahunan', $data);
    }

    public function printPendapatanTahunan()
    {
        // Ambil data pembayaran per tahun
        $query = $this->db->query(
            "SELECT 
                YEAR(created_at) as tahun,
                SUM(total_bayar) as total
            FROM 
                pembayaran
            WHERE 
                status = 'paid'
            GROUP BY 
                YEAR(created_at)
            ORDER BY 
                tahun DESC"
        );

        $pendapatanTahunan = $query->getResultArray();

        // Hitung total pendapatan keseluruhan
        $totalPendapatan = 0;
        foreach ($pendapatanTahunan as $item) {
            $totalPendapatan += $item['total'];
        }

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Pendapatan Tahunan',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Contoh No. 123, Kota',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
            'report_title' => 'LAPORAN PENDAPATAN TAHUNAN',
            'manager' => 'Pimpinan'
        ];

        // Membuat konten tabel
        $content = '
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>Tahun</th>
                    <th class="text-center">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;

        foreach ($pendapatanTahunan as $item) {
            $content .= '
            <tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . $item['tahun'] . '</td>
                <td class="text-end">Rp ' . number_format($item['total'], 0, ',', '.') . '</td>
            </tr>';
        }

        $content .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end fw-bold">Total Keseluruhan:</td>
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
        $tanggalAwal = $this->request->getGet('tanggal_awal') ?? date('Y-m-01');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-t');

        $pengeluaranModel = new \App\Models\PengeluaranModel();

        // Ambil data pengeluaran berdasarkan rentang tanggal
        $pengeluaran = $pengeluaranModel->where('tgl >=', $tanggalAwal)
            ->where('tgl <=', $tanggalAkhir)
            ->orderBy('tgl', 'ASC')
            ->findAll();

        // Hitung total pengeluaran
        $totalPengeluaran = 0;
        foreach ($pengeluaran as $item) {
            $totalPengeluaran += $item['jumlah'];
        }

        $data = [
            'title' => 'Laporan Pengeluaran',
            'pengeluaran' => $pengeluaran,
            'totalPengeluaran' => $totalPengeluaran,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir
        ];

        return view('admin/reports/pengeluaran', $data);
    }

    public function printPengeluaran()
    {
        $tanggalAwal = $this->request->getGet('tanggal_awal') ?? date('Y-m-01');
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-t');

        $pengeluaranModel = new \App\Models\PengeluaranModel();

        // Ambil data pengeluaran berdasarkan rentang tanggal
        $pengeluaran = $pengeluaranModel->where('tgl >=', $tanggalAwal)
            ->where('tgl <=', $tanggalAkhir)
            ->orderBy('tgl', 'ASC')
            ->findAll();

        // Hitung total pengeluaran
        $totalPengeluaran = 0;
        foreach ($pengeluaran as $item) {
            $totalPengeluaran += $item['jumlah'];
        }

        // Data untuk header laporan
        $headerData = [
            'title' => 'Cetak Laporan Pengeluaran',
            'nama_perusahaan' => 'AWAN BARBERSHOP',
            'alamat_perusahaan' => 'Jl. Contoh No. 123, Kota',
            'telepon' => '081234567890',
            'email' => 'info@awanbarbershop.com',
            'website' => 'www.awanbarbershop.com',
            'tanggal' => date('d F Y'),
            'report_title' => 'LAPORAN PENGELUARAN',
            'manager' => 'Pimpinan'
        ];

        // Format tanggal untuk subtitle
        $subtitle = 'Periode: ' . date('d/m/Y', strtotime($tanggalAwal)) . ' - ' . date('d/m/Y', strtotime($tanggalAkhir));

        // Membuat konten tabel
        $content = '
        <p class="text-center mb-4">' . $subtitle . '</p>
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
                    <td colspan="3" class="text-end fw-bold">Total Pengeluaran:</td>
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
}
