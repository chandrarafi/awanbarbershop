<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Laporan Pendapatan Pertahun</h1>
        <p class="mb-0 text-secondary">Laporan pendapatan per tahun</p>
    </div>
    <div>
        <button id="btnPrint" class="btn btn-primary btn-sm" style="display: none;">
            <i class="bi bi-printer me-1"></i> Cetak
        </button>
    </div>
</div>

<!-- Filter Form -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
    </div>
    <div class="card-body">
        <form id="filterForm">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <select class="form-control" id="tahun" name="tahun">
                            <option value="">- Pilih Tahun -</option>
                            <?php
                            $currentYear = date('Y');
                            for ($year = $currentYear - 5; $year <= $currentYear; $year++) {
                                echo "<option value=\"$year\">$year</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" style="margin-top: 32px;">
                        <button type="button" id="filterBtn" class="btn btn-primary">Filter</button>
                        <button type="button" id="showAllBtn" class="btn btn-info">Tampilkan Semua</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Card -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow" id="ringkasanCard" style="display: none;">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ringkasan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Total Pendapatan: <span id="totalPendapatan">Rp 0</span>
                        </div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" id="periodeSummary">
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
                <h6 class="m-0 font-weight-bold text-primary">Data Pendapatan Pertahun</h6>
                <div id="loadingIndicator" class="spinner-border spinner-border-sm text-primary" style="display: none;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info" id="initialMessage">
                    <i class="bi bi-info-circle me-2"></i> Silakan pilih tahun terlebih dahulu atau klik "Tampilkan Semua" untuk menampilkan data pendapatan tahunan.
                </div>

                <div class="alert alert-info" id="noDataMessage" style="display: none;">
                    <i class="bi bi-info-circle me-2"></i> Tidak ada data pendapatan untuk periode yang dipilih.
                </div>

                <!-- Pencarian Sederhana -->
                <div class="row mb-3" id="tableControls" style="display: none;">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
                            <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-flex justify-content-end">
                            <select id="entriesPerPage" class="form-select form-select-sm me-2" style="width: auto;">
                                <option value="10">10 entri</option>
                                <option value="25">25 entri</option>
                                <option value="50">50 entri</option>
                                <option value="100">100 entri</option>
                            </select>
                            <span class="mt-1" id="tableInfo">Menampilkan 0 dari 0 entri</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" id="dataTableContainer" style="display: none;">
                    <table class="table table-bordered" id="pendapatanTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%" class="sortable" data-sort="no">No</th>
                                <th class="sortable" data-sort="bulan">Bulan</th>
                                <th class="sortable" data-sort="total">Total</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data will be loaded here via AJAX -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold" id="tableTotalPendapatan">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination Sederhana -->
                <div class="row mt-3" id="paginationContainer" style="display: none;">
                    <div class="col-md-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center" id="pagination">
                                <!-- Pagination akan diisi oleh JavaScript -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Variabel untuk menyimpan data tabel
        var tableData = [];
        var currentPage = 1;
        var entriesPerPage = 10;
        var totalPages = 1;
        var sortColumn = 'bulan';
        var sortDirection = 'asc';
        var currentFilter = {
            tahun: '',
            showAll: false
        };

        // Styling untuk kolom sortable
        $('.sortable').css('cursor', 'pointer');
        $('.sortable').append(' <span class="sort-icon"></span>');

        // Event listener untuk sorting
        $('.sortable').on('click', function() {
            var column = $(this).data('sort');

            // Jika mengklik kolom yang sama, balik arah sorting
            if (sortColumn === column) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortColumn = column;
                sortDirection = 'asc';
            }

            // Update tampilan ikon sort
            $('.sort-icon').html('');
            var icon = sortDirection === 'asc' ? '&#9650;' : '&#9660;';
            $(this).find('.sort-icon').html(icon);

            // Render ulang tabel
            renderTable();
        });

        // Event listener untuk pencarian
        $('#searchBtn').on('click', function() {
            currentPage = 1;
            renderTable();
        });

        $('#searchInput').on('keyup', function(e) {
            if (e.key === 'Enter') {
                currentPage = 1;
                renderTable();
            }
        });

        // Event listener untuk perubahan jumlah entri per halaman
        $('#entriesPerPage').on('change', function() {
            entriesPerPage = parseInt($(this).val());
            currentPage = 1;
            renderTable();
        });

        // Event handler tombol filter
        $('#filterBtn').on('click', function() {
            var tahun = $('#tahun').val();

            if (!tahun) {
                alert('Silakan pilih tahun terlebih dahulu');
                return;
            }

            currentFilter = {
                tahun: tahun,
                showAll: false
            };

            loadData();
        });

        // Event handler tombol tampilkan semua
        $('#showAllBtn').on('click', function() {
            currentFilter = {
                tahun: '',
                showAll: true
            };

            loadData();
        });

        // Print button click
        $('#btnPrint').on('click', function() {
            let printUrl = '<?= site_url('admin/reports/pendapatan-tahunan/print') ?>';

            if (!currentFilter.showAll) {
                printUrl += '?tahun=' + currentFilter.tahun;
            }

            window.open(printUrl, '_blank');
        });

        // Fungsi untuk memuat data pendapatan dengan AJAX
        function loadData() {
            // Show loading indicator
            $('#loadingIndicator').show();
            $('#initialMessage').hide();
            $('#noDataMessage').hide();
            $('#dataTableContainer').hide();
            $('#tableControls').hide();
            $('#paginationContainer').hide();
            $('#ringkasanCard').hide();
            $('#btnPrint').hide();

            // Build URL with parameters
            let url = '<?= site_url('admin/reports/pendapatan-tahunan/getData') ?>';
            const params = [];

            if (currentFilter.showAll) {
                params.push('show_all=true');
            } else if (currentFilter.tahun) {
                params.push('tahun=' + currentFilter.tahun);
            }

            if (params.length > 0) {
                url += '?' + params.join('&');
            }

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Reset data tabel
                        tableData = [];

                        // Periksa apakah response.data tersedia
                        if (response.data && Array.isArray(response.data) && response.data.length > 0) {
                            var no = 1;
                            response.data.forEach(function(item) {
                                // Pastikan item.bulan ada, jika tidak gunakan nomor urut sebagai fallback
                                var bulanNum = item.bulan || no;
                                var namaBulan = item.bulan_nama || getNamaBulan(bulanNum);
                                tableData.push({
                                    no: no++,
                                    bulan: namaBulan,
                                    bulan_num: bulanNum,
                                    tahun: item.tahun,
                                    total: item.total,
                                    total_formatted: 'Rp ' + formatNumber(item.total)
                                });
                            });

                            // Update total pendapatan
                            if (response.totalPendapatan !== undefined) {
                                $('#totalPendapatan').text('Rp ' + formatNumber(response.totalPendapatan));
                                $('#tableTotalPendapatan').text('Rp ' + formatNumber(response.totalPendapatan));
                            }

                            // Update periode summary
                            var periodeText = currentFilter.showAll ? 'Semua Tahun' : 'Tahun ' + currentFilter.tahun;
                            $('#periodeSummary').text(periodeText);

                            // Show data container and controls
                            $('#dataTableContainer').show();
                            $('#tableControls').show();
                            $('#paginationContainer').show();
                            $('#ringkasanCard').show();
                            $('#btnPrint').show();

                            // Render tabel dengan data baru
                            currentPage = 1;
                            renderTable();
                        } else {
                            // Show no data message
                            $('#noDataMessage').show();
                        }
                    } else {
                        // Show error message
                        alert('Gagal memuat data: ' + (response.message || 'Terjadi kesalahan'));
                        $('#initialMessage').show();
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                    $('#initialMessage').show();
                },
                complete: function() {
                    // Hide loading indicator
                    $('#loadingIndicator').hide();
                }
            });
        }

        // Fungsi untuk merender tabel
        function renderTable() {
            try {
                // Pastikan tableData adalah array
                if (!Array.isArray(tableData)) {
                    console.error('tableData bukan array');
                    tableData = [];
                }

                // Filter data dengan pencarian
                var filteredData = [];
                var searchTerm = $('#searchInput').val() || '';
                searchTerm = searchTerm.toString().toLowerCase();

                for (var i = 0; i < tableData.length; i++) {
                    var item = tableData[i];
                    if (!item) continue;

                    // Jika tidak ada pencarian, tambahkan semua item
                    if (!searchTerm) {
                        filteredData.push(item);
                        continue;
                    }

                    // Jika ada pencarian, cek setiap properti
                    var match = false;
                    for (var key in item) {
                        if (!item.hasOwnProperty(key)) continue;

                        var value = item[key];
                        if (value === null || value === undefined) continue;

                        var strValue = '';
                        try {
                            strValue = String(value).toLowerCase();
                        } catch (e) {
                            continue;
                        }

                        if (strValue.indexOf(searchTerm) !== -1) {
                            match = true;
                            break;
                        }
                    }

                    if (match) {
                        filteredData.push(item);
                    }
                }

                // Sorting
                if (sortColumn) {
                    filteredData.sort(function(a, b) {
                        try {
                            if (!a || !b) return 0;

                            // Special handling for month sorting
                            if (sortColumn === 'bulan') {
                                // Sort berdasarkan tahun dulu, lalu bulan
                                var aTahun = parseInt(a.tahun || '0');
                                var bTahun = parseInt(b.tahun || '0');

                                if (aTahun !== bTahun) {
                                    return sortDirection === 'asc' ? aTahun - bTahun : bTahun - aTahun;
                                }

                                // Jika tahun sama, sort berdasarkan bulan
                                var aBulanNum = parseInt(a.bulan_num || '0');
                                var bBulanNum = parseInt(b.bulan_num || '0');
                                return sortDirection === 'asc' ? aBulanNum - bBulanNum : bBulanNum - aBulanNum;
                            }

                            var aVal = a[sortColumn];
                            var bVal = b[sortColumn];

                            // Handle null/undefined values
                            if (aVal === null || aVal === undefined) aVal = '';
                            if (bVal === null || bVal === undefined) bVal = '';

                            // Convert to appropriate types for comparison
                            if (sortColumn === 'no') {
                                // For numeric columns
                                aVal = parseInt(String(aVal).replace(/[^\d]/g, '')) || 0;
                                bVal = parseInt(String(bVal).replace(/[^\d]/g, '')) || 0;
                            } else if (sortColumn === 'total') {
                                // For currency columns
                                aVal = parseFloat(a.total || 0);
                                bVal = parseFloat(b.total || 0);
                            } else {
                                // For string columns - convert to string without toLowerCase
                                aVal = String(aVal);
                                bVal = String(bVal);
                                // Simple string comparison (case-insensitive but without toLowerCase)
                                return sortDirection === 'asc' ?
                                    aVal.localeCompare(bVal, undefined, {
                                        sensitivity: 'base'
                                    }) :
                                    bVal.localeCompare(aVal, undefined, {
                                        sensitivity: 'base'
                                    });
                            }

                            // Compare values
                            if (aVal < bVal) return sortDirection === 'asc' ? -1 : 1;
                            if (aVal > bVal) return sortDirection === 'asc' ? 1 : -1;
                            return 0;
                        } catch (e) {
                            return 0;
                        }
                    });
                }

                // Pagination
                var entriesPerPageVal = parseInt($('#entriesPerPage').val()) || 10;
                var totalItems = filteredData.length;
                totalPages = Math.max(1, Math.ceil(totalItems / entriesPerPageVal));

                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }

                var startIndex = (currentPage - 1) * entriesPerPageVal;
                var endIndex = Math.min(startIndex + entriesPerPageVal, totalItems);
                var paginatedData = filteredData.slice(startIndex, endIndex);

                // Update info text
                $('#tableInfo').text('Menampilkan ' + (totalItems > 0 ? (startIndex + 1) : 0) +
                    ' sampai ' + endIndex + ' dari ' + totalItems + ' entri');

                // Render table rows
                var tbody = $('#tableBody');
                tbody.empty();

                if (paginatedData.length === 0) {
                    tbody.html('<tr><td colspan="3" class="text-center">Data pendapatan tidak ditemukan. Silakan coba filter dengan kriteria berbeda.</td></tr>');
                } else {
                    for (var i = 0; i < paginatedData.length; i++) {
                        var item = paginatedData[i] || {};
                        var row = '<tr>' +
                            '<td>' + (item.no || '') + '</td>' +
                            '<td>' + (item.bulan || '') + '</td>' +
                            '<td>' + (item.total_formatted || '') + '</td>' +
                            '</tr>';
                        tbody.append(row);
                    }
                }

                // Render pagination
                renderPagination();
            } catch (e) {
                console.error('Error dalam renderTable:', e);
                $('#tableBody').html('<tr><td colspan="3" class="text-center">Terjadi kesalahan saat memuat data. Silakan coba lagi.</td></tr>');
            }
        }

        // Fungsi untuk merender pagination
        function renderPagination() {
            var pagination = $('#pagination');
            pagination.empty();

            if (totalPages <= 1) {
                return;
            }

            // Tombol Previous
            pagination.append(
                '<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') + '">' +
                '<a class="page-link" href="#" data-page="prev">Sebelumnya</a>' +
                '</li>'
            );

            // Batasi jumlah halaman yang ditampilkan
            var maxPagesToShow = 5;
            var startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
            var endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

            // Jika jumlah halaman yang ditampilkan kurang dari maxPagesToShow, sesuaikan startPage
            if (endPage - startPage + 1 < maxPagesToShow) {
                startPage = Math.max(1, endPage - maxPagesToShow + 1);
            }

            // Tombol halaman pertama jika tidak termasuk dalam range
            if (startPage > 1) {
                pagination.append(
                    '<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>'
                );
                if (startPage > 2) {
                    pagination.append('<li class="page-item disabled"><a class="page-link" href="#">...</a></li>');
                }
            }

            // Tombol halaman
            for (var i = startPage; i <= endPage; i++) {
                pagination.append(
                    '<li class="page-item ' + (i === currentPage ? 'active' : '') + '">' +
                    '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a>' +
                    '</li>'
                );
            }

            // Tombol halaman terakhir jika tidak termasuk dalam range
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    pagination.append('<li class="page-item disabled"><a class="page-link" href="#">...</a></li>');
                }
                pagination.append(
                    '<li class="page-item"><a class="page-link" href="#" data-page="' + totalPages + '">' + totalPages + '</a></li>'
                );
            }

            // Tombol Next
            pagination.append(
                '<li class="page-item ' + (currentPage === totalPages ? 'disabled' : '') + '">' +
                '<a class="page-link" href="#" data-page="next">Selanjutnya</a>' +
                '</li>'
            );

            // Event handler untuk tombol pagination
            $('.page-link').on('click', function(e) {
                e.preventDefault();
                var page = $(this).data('page');

                if (page === 'prev') {
                    if (currentPage > 1) {
                        currentPage--;
                    }
                } else if (page === 'next') {
                    if (currentPage < totalPages) {
                        currentPage++;
                    }
                } else {
                    currentPage = parseInt(page);
                }

                renderTable();
            });
        }

        // Fungsi untuk mendapatkan nama bulan dari angka
        function getNamaBulan(bulan) {
            // Pastikan format bulan konsisten
            if (typeof bulan === 'number' || !isNaN(parseInt(bulan))) {
                // Konversi ke integer untuk menghilangkan leading zero
                bulan = parseInt(bulan);
            }

            var namaBulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            return namaBulan[parseInt(bulan) - 1] || 'Bulan ' + bulan;
        }

        // Fungsi format angka
        function formatNumber(number) {
            if (!number) return '0';
            return new Intl.NumberFormat('id-ID').format(number);
        }
    });
</script>

<style>
    .sortable {
        position: relative;
        cursor: pointer;
    }

    .sortable:hover {
        background-color: #f8f9fa;
    }

    .sort-icon {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 10px;
    }
</style>
<?= $this->endSection() ?>