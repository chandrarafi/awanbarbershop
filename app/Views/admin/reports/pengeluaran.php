<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Laporan Pengeluaran</h1>
        <p class="mb-0 text-secondary">Laporan uang keluar</p>
    </div>
    <div>
        <button id="btnPrint" class="btn btn-primary btn-sm" style="display: none;">
            <i class="bi bi-printer me-1"></i> Cetak
        </button>
    </div>
</div>

<!-- Filter Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
            </div>
            <div class="card-body">
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control">
                                <option value="">- Pilih Tahun -</option>
                                <?php if (empty($daftarTahun)): ?>
                                    <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
                                <?php else: ?>
                                    <?php foreach ($daftarTahun as $t): ?>
                                        <option value="<?= $t['tahun'] ?>"><?= $t['tahun'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" id="bulan" class="form-control">
                                <option value="">- Pilih Bulan -</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" id="btnFilter" class="btn btn-primary btn-sm">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                        <button type="button" id="btnShowAll" class="btn btn-info btn-sm">
                            <i class="bi bi-list"></i> Tampilkan Semua
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow" id="ringkasanCard" style="display: none;">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ringkasan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalPengeluaran">
                            Total Pengeluaran: Rp 0
                        </div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" id="periodLabel">
                            -
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran</h6>
                <div class="loading-indicator" id="loadingIndicator" style="display: none;">
                    <i class="bi bi-hourglass-split"></i> Memuat data...
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info" id="initialMessage">
                    <i class="bi bi-info-circle me-2"></i> Silakan pilih tahun dan bulan terlebih dahulu untuk menampilkan data pengeluaran.
                </div>

                <div class="alert alert-info" id="noDataMessage" style="display: none;">
                    <i class="bi bi-info-circle me-2"></i> Tidak ada data pengeluaran untuk periode yang dipilih.
                </div>

                <div class="table-responsive" id="dataTableContainer" style="display: none;">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data will be loaded here via AJAX -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold" id="tableTotalPengeluaran">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        let dataTable = null;
        let currentFilter = {
            tahun: '',
            bulan: '',
            showAll: false
        };

        // Filter button click
        $('#btnFilter').on('click', function() {
            const tahun = $('#tahun').val();
            const bulan = $('#bulan').val();

            if (!tahun || !bulan) {
                alert('Silakan pilih tahun dan bulan terlebih dahulu');
                return;
            }

            currentFilter = {
                tahun: tahun,
                bulan: bulan,
                showAll: false
            };

            loadData();
        });

        // Show All button click
        $('#btnShowAll').on('click', function() {
            currentFilter = {
                tahun: '',
                bulan: '',
                showAll: true
            };

            loadData();
        });

        // Print button click
        $('#btnPrint').on('click', function() {
            let printUrl = '<?= site_url('admin/reports/pengeluaran/print') ?>';

            if (!currentFilter.showAll) {
                printUrl += '?tahun=' + currentFilter.tahun + '&bulan=' + currentFilter.bulan;
            } else {
                printUrl += '?show_all=true';
            }

            window.open(printUrl, '_blank');
        });

        // Load data function
        function loadData() {
            // Show loading indicator
            $('#loadingIndicator').show();
            $('#initialMessage').hide();
            $('#noDataMessage').hide();
            $('#dataTableContainer').hide();

            // Destroy existing DataTable if it exists
            if (dataTable) {
                dataTable.destroy();
                dataTable = null;
            }

            // Build URL with parameters
            let url = '<?= site_url('admin/reports/pengeluaran/data') ?>';
            const params = [];

            if (currentFilter.showAll) {
                params.push('show_all=true');
            } else {
                params.push('tahun=' + currentFilter.tahun);
                params.push('bulan=' + currentFilter.bulan);
            }

            if (params.length > 0) {
                url += '?' + params.join('&');
            }

            // Make AJAX request
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            // Populate table
                            const tableBody = $('#tableBody');
                            tableBody.empty();

                            $.each(response.data, function(index, item) {
                                tableBody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.tanggal}</td>
                                        <td>${item.keterangan}</td>
                                        <td>${item.jumlah_formatted}</td>
                                    </tr>
                                `);
                            });

                            // Update summary
                            $('#totalPengeluaran').text('Total Pengeluaran: ' + response.totalPengeluaranFormatted);
                            $('#tableTotalPengeluaran').text(response.totalPengeluaranFormatted);
                            $('#periodLabel').text(response.periodeLabel);

                            // Show data container and summary
                            $('#dataTableContainer').show();
                            $('#ringkasanCard').show();
                            $('#btnPrint').show();

                            // Initialize DataTable
                            dataTable = $('#dataTable').DataTable({
                                "order": [
                                    [1, 'asc']
                                ],
                                "pageLength": 25,
                                "language": {
                                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                                    "zeroRecords": "Tidak ada data yang ditemukan",
                                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                                    "infoEmpty": "Tidak ada data yang tersedia",
                                    "infoFiltered": "(difilter dari _MAX_ total data)",
                                    "search": "Cari:",
                                    "paginate": {
                                        "first": "Pertama",
                                        "last": "Terakhir",
                                        "next": "Selanjutnya",
                                        "previous": "Sebelumnya"
                                    }
                                }
                            });
                        } else {
                            // Show no data message
                            $('#noDataMessage').show();
                            $('#ringkasanCard').hide();
                            $('#btnPrint').hide();
                        }
                    } else {
                        // Show error message
                        alert('Gagal memuat data: ' + response.message);
                        $('#initialMessage').show();
                        $('#ringkasanCard').hide();
                        $('#btnPrint').hide();
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                    $('#initialMessage').show();
                    $('#ringkasanCard').hide();
                    $('#btnPrint').hide();
                },
                complete: function() {
                    // Hide loading indicator
                    $('#loadingIndicator').hide();
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>