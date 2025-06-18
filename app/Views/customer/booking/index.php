<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="py-16">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold gradient-text">Riwayat Booking</h1>
                <p class="text-gray-600 mt-2">Daftar booking Anda</p>
            </div>
            <a href="<?= site_url('customer/booking/create') ?>" class="btn-primary px-6 py-3 rounded-full inline-flex items-center transform hover:scale-105 transition-all duration-300">
                <span>Booking Baru</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            <div class="overflow-x-auto">
                <table id="bookingTable" class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700">
                            <th class="py-3 px-4 font-medium">No. Booking</th>
                            <th class="py-3 px-4 font-medium">Tanggal</th>
                            <th class="py-3 px-4 font-medium">Layanan</th>
                            <th class="py-3 px-4 font-medium">Status</th>
                            <th class="py-3 px-4 font-medium">Total</th>
                            <th class="py-3 px-4 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi oleh DataTables -->
                    </tbody>
                </table>
            </div>

            <div class="mt-8 text-sm text-gray-600">
                <h3 class="text-lg font-semibold mb-2">Informasi Status Booking:</h3>
                <ul class="space-y-2">
                    <li class="flex items-center">
                        <span class="inline-block w-24 py-1 px-2 bg-yellow-100 text-yellow-800 text-xs font-medium text-center rounded-full">Menunggu Konfirmasi</span>
                        <span class="ml-2">Booking Anda sedang menunggu konfirmasi dari kami</span>
                    </li>
                    <li class="flex items-center">
                        <span class="inline-block w-24 py-1 px-2 bg-blue-100 text-blue-800 text-xs font-medium text-center rounded-full">Terkonfirmasi</span>
                        <span class="ml-2">Booking Anda telah dikonfirmasi</span>
                    </li>
                    <li class="flex items-center">
                        <span class="inline-block w-24 py-1 px-2 bg-green-100 text-green-800 text-xs font-medium text-center rounded-full">Selesai</span>
                        <span class="ml-2">Layanan telah selesai diberikan</span>
                    </li>
                    <li class="flex items-center">
                        <span class="inline-block w-24 py-1 px-2 bg-red-100 text-red-800 text-xs font-medium text-center rounded-full">Dibatalkan</span>
                        <span class="ml-2">Booking telah dibatalkan</span>
                    </li>
                    <li class="flex items-center">
                        <span class="inline-block w-24 py-1 px-2 bg-gray-100 text-gray-800 text-xs font-medium text-center rounded-full">Tidak Hadir</span>
                        <span class="ml-2">Anda tidak hadir pada waktu yang telah ditentukan</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function() {
        $('#bookingTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '<?= site_url('customer/booking/getBookings') ?>',
            columns: [{
                    data: 'kdbooking',
                    render: function(data, type, row) {
                        return `<span class="font-medium">${data}</span>`;
                    }
                },
                {
                    data: 'tanggal_booking',
                    render: function(data, type, row) {
                        const date = new Date(data);
                        const options = {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        };
                        return `${date.toLocaleDateString('id-ID', options)}<br>
                                <span class="text-xs text-gray-500">${row.jamstart} - ${row.jamend}</span>`;
                    }
                },
                {
                    data: 'nama_paket',
                    render: function(data, type, row) {
                        return `<div>
                                    <span class="font-medium">${data || 'Paket tidak ditemukan'}</span><br>
                                    <span class="text-xs text-gray-500">Karyawan: ${row.namakaryawan || 'Belum ditentukan'}</span>
                                </div>`;
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        let badgeClass = '';

                        switch (data) {
                            case 'pending':
                                badgeClass = 'bg-yellow-100 text-yellow-800';
                                break;
                            case 'confirmed':
                                badgeClass = 'bg-blue-100 text-blue-800';
                                break;
                            case 'completed':
                                badgeClass = 'bg-green-100 text-green-800';
                                break;
                            case 'cancelled':
                                badgeClass = 'bg-red-100 text-red-800';
                                break;
                            case 'no-show':
                                badgeClass = 'bg-gray-100 text-gray-800';
                                break;
                            default:
                                badgeClass = 'bg-gray-100 text-gray-800';
                        }

                        return `<span class="inline-block py-1 px-2 ${badgeClass} text-xs font-medium rounded-full">
                                    ${row.status_text}
                                </span>`;
                    }
                },
                {
                    data: 'total_formatted',
                    render: function(data, type, row) {
                        return `<span class="font-medium">${data}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `<a href="<?= site_url('customer/booking/detail/') ?>${row.kdbooking}" 
                                   class="inline-flex items-center justify-center p-2 bg-blue-50 text-blue-700 rounded-full hover:bg-blue-100 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>`;
                    }
                }
            ],
            order: [
                [1, 'desc']
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            },
            initComplete: function() {
                $('.dataTables_wrapper .dataTables_filter input').addClass('border border-gray-300 rounded-lg p-2 ml-2');
                $('.dataTables_wrapper .dataTables_length select').addClass('border border-gray-300 rounded-lg p-2 mx-1');
                $('.dataTables_wrapper .dataTables_paginate .paginate_button').addClass('px-3 py-1 mx-1 border border-gray-300 rounded-lg');
                $('.dataTables_wrapper .dataTables_paginate .paginate_button.current').addClass('bg-gray-100');
            }
        });
    });
</script>
<?= $this->endSection() ?>