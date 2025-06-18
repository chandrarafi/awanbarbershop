<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="py-16">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold gradient-text">Form Booking</h1>
            <p class="text-gray-600 mt-2">Silahkan isi form booking dengan benar</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 animated-border">
            <div id="booking-alert" class="hidden mb-6 p-4 rounded-lg"></div>

            <form id="bookingForm" class="space-y-6">
                <!-- Data Pelanggan -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4 gradient-text">Data Pelanggan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-gray-700">Nama</label>
                            <input type="text" class="bg-white border border-gray-300 rounded-lg w-full p-3" value="<?= $pelanggan['nama_lengkap'] ?>" readonly>
                        </div>
                        <div>
                            <label class="block mb-1 text-gray-700">No. HP</label>
                            <input type="text" class="bg-white border border-gray-300 rounded-lg w-full p-3" value="<?= $pelanggan['no_hp'] ?>" readonly>
                        </div>
                    </div>
                </div>

                <!-- Data Booking -->
                <div>
                    <h2 class="text-xl font-semibold mb-4 gradient-text">Pilih Layanan</h2>
                    <div class="mb-4">
                        <label for="idpaket" class="block mb-1 text-gray-700">Pilih Paket Layanan</label>
                        <select id="idpaket" name="idpaket" class="bg-white border border-gray-300 rounded-lg w-full p-3" required>
                            <option value="" selected disabled>-- Pilih Paket --</option>
                            <?php foreach ($paketList as $paket): ?>
                                <option value="<?= $paket['idpaket'] ?>"
                                    data-harga="<?= $paket['harga'] ?>"
                                    <?= ($selectedPaket && $selectedPaket['idpaket'] == $paket['idpaket']) ? 'selected' : '' ?>>
                                    <?= $paket['namapaket'] ?> - Rp. <?= number_format($paket['harga'], 0, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_booking" class="block mb-1 text-gray-700">Pilih Tanggal</label>
                        <input type="date" id="tanggal_booking" name="tanggal_booking" class="bg-white border border-gray-300 rounded-lg w-full p-3" min="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <!-- Pilihan Waktu -->
                <div id="timeSlotContainer" class="hidden">
                    <h2 class="text-xl font-semibold mb-4 gradient-text">Pilih Waktu</h2>
                    <div class="mb-4">
                        <p id="bookingDateDisplay" class="text-gray-600 mb-4">
                            <i class="bi bi-info-circle"></i> Silakan pilih tanggal terlebih dahulu
                        </p>

                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2" id="timeSlotGrid">
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="09:00">09:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="10:00">10:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="11:00">11:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="12:00">12:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="13:00">13:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="14:00">14:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="15:00">15:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="16:00">16:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="17:00">17:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="18:00">18:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="19:00">19:00</div>
                            <div class="time-slot cursor-pointer p-3 text-center border rounded-md" data-time="20:00">20:00</div>
                        </div>
                        <div class="flex items-center mt-2 text-sm">
                            <div class="flex items-center mr-4">
                                <div class="w-4 h-4 bg-white border mr-1"></div>
                                <span>Tersedia</span>
                            </div>
                            <div class="flex items-center mr-4">
                                <div class="w-4 h-4 bg-green-500 mr-1"></div>
                                <span>Dipilih</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-200 mr-1"></div>
                                <span>Tidak tersedia</span>
                            </div>
                        </div>
                        <input type="hidden" id="jamstart" name="jamstart" required>
                        <input type="hidden" id="jamend" name="jamend">
                    </div>
                </div>

                <!-- Pilihan Karyawan -->
                <div id="karyawanContainer" class="hidden">
                    <h2 class="text-xl font-semibold mb-4 gradient-text">Pilih Karyawan</h2>
                    <div id="karyawanList" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                        <!-- Karyawan akan ditambahkan melalui JavaScript -->
                    </div>
                    <input type="hidden" id="idkaryawan" name="idkaryawan" required>
                </div>

                <!-- Ringkasan Booking -->
                <div id="summaryContainer" class="hidden">
                    <h2 class="text-xl font-semibold mb-4 gradient-text">Ringkasan Booking</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-2 text-gray-700">
                            <div class="font-medium">Paket</div>
                            <div id="summary_paket">-</div>

                            <div class="font-medium">Tanggal</div>
                            <div id="summary_tanggal">-</div>

                            <div class="font-medium">Waktu</div>
                            <div id="summary_waktu">-</div>

                            <div class="font-medium">Karyawan</div>
                            <div id="summary_karyawan">-</div>

                            <div class="font-medium">Total</div>
                            <div id="summary_total" class="font-semibold">-</div>
                        </div>

                        <input type="hidden" id="total" name="total">

                        <div class="mt-6 text-sm text-gray-600">
                            <p>Setelah menekan tombol booking, Anda akan menerima notifikasi booking. Mohon datang tepat waktu sesuai jadwal booking yang telah dipilih.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between pt-4">
                    <a href="<?= site_url('/') ?>" class="btn-secondary px-6 py-3 rounded-full">Batal</a>
                    <button type="submit" id="btnSubmit" class="btn-primary px-8 py-3 rounded-full" disabled>
                        Booking Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom_script') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format tanggal untuk tampilan
        function formatTanggal(tanggal) {
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return new Date(tanggal).toLocaleDateString('id-ID', options);
        }

        // Format angka ke format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(angka);
        }

        // Ketika tanggal dipilih
        $('#tanggal_booking').on('change', function() {
            const selectedDate = $(this).val();

            if (selectedDate) {
                // Format tanggal untuk tampilan
                $('#bookingDateDisplay').html(`<strong>${formatTanggal(selectedDate)}</strong>`);

                // Tampilkan container time slot
                $('#timeSlotContainer').fadeIn(500);

                // Reset semua slot waktu
                $('.time-slot').removeClass('active bg-green-500 text-white booked bg-red-200 text-gray-500');
                $('.time-slot').removeAttr('title');

                // Periksa ketersediaan slot waktu
                checkAvailability();

                // Hide karyawan dan summary containers ketika tanggal berubah
                $('#karyawanContainer').hide();
                $('#summaryContainer').hide();
                $('#btnSubmit').prop('disabled', true);

                // Reset input
                $('#jamstart').val('');
                $('#jamend').val('');
                $('#idkaryawan').val('');
            } else {
                $('#bookingDateDisplay').html(`
                    <i class="bi bi-info-circle"></i> Silakan pilih tanggal terlebih dahulu
                `);

                // Sembunyikan container time slot, karyawan dan summary
                $('#timeSlotContainer').hide();
                $('#karyawanContainer').hide();
                $('#summaryContainer').hide();
                $('#btnSubmit').prop('disabled', true);

                // Reset input
                $('#jamstart').val('');
                $('#jamend').val('');
                $('#idkaryawan').val('');
            }
        });

        // Memeriksa ketersediaan slot waktu
        function checkAvailability() {
            const tanggal = $('#tanggal_booking').val();

            if (!tanggal) return;

            $.ajax({
                url: '<?= site_url('customer/booking/checkAvailability') ?>',
                type: 'GET',
                data: {
                    tanggal: tanggal
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Update status setiap slot waktu
                        response.data.forEach(function(slot) {
                            const timeSlot = $(`.time-slot[data-time="${slot.time}"]`);

                            if (slot.status === 'booked') {
                                timeSlot.addClass('booked bg-red-200 text-gray-500');
                                timeSlot.attr('title', 'Slot penuh');
                            } else {
                                timeSlot.removeClass('booked bg-red-200 text-gray-500');
                                timeSlot.removeAttr('title');
                                timeSlot.data('available-karyawan', slot.availableKaryawan);
                            }
                        });
                    }
                },
                error: function() {
                    $('#booking-alert')
                        .removeClass('hidden bg-green-100 text-green-800')
                        .addClass('bg-red-100 text-red-800')
                        .html('Terjadi kesalahan saat memeriksa ketersediaan slot waktu')
                        .fadeIn();
                }
            });
        }

        // Delegasi event untuk slot waktu
        $('#timeSlotGrid').on('click', '.time-slot', function() {
            if ($(this).hasClass('booked')) {
                return;
            }

            $('.time-slot').removeClass('active bg-green-500 text-white');
            $(this).addClass('active bg-green-500 text-white');

            // Set jam mulai & selesai
            const startTime = $(this).data('time');
            const [startHour, startMinute] = startTime.split(':');
            const endHour = parseInt(startHour) + 1;
            const endTime = `${endHour.toString().padStart(2, '0')}:${startMinute}`;

            $('#jamstart').val(startTime);
            $('#jamend').val(endTime);

            // Load karyawan yang tersedia
            loadAvailableKaryawan();

            // Update summary
            updateSummary();
        });

        // Load daftar karyawan yang tersedia
        function loadAvailableKaryawan() {
            const tanggal = $('#tanggal_booking').val();
            const jamstart = $('#jamstart').val();

            if (!tanggal || !jamstart) return;

            $.ajax({
                url: '<?= site_url('customer/booking/getAvailableKaryawan') ?>',
                type: 'GET',
                data: {
                    tanggal: tanggal,
                    jamstart: jamstart
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let karyawanHTML = '';

                        if (response.data.length === 0) {
                            karyawanHTML = '<div class="col-span-full text-center py-4">Tidak ada karyawan yang tersedia pada slot waktu ini</div>';
                        } else {
                            response.data.forEach(function(karyawan) {
                                karyawanHTML += `
                                    <div class="karyawan-item border rounded-lg overflow-hidden cursor-pointer hover:shadow-md transition-all" data-id="${karyawan.id}">
                                        <div class="p-4">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden mr-3 flex-shrink-0">
                                                    ${karyawan.foto 
                                                        ? `<img src="<?= base_url('uploads/karyawan/') ?>${karyawan.foto}" class="w-full h-full object-cover" alt="${karyawan.nama}">`
                                                        : `<div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-500">
                                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>`
                                                    }
                                                </div>
                                                <div>
                                                    <div class="font-medium">${karyawan.nama}</div>
                                                    <div class="text-sm text-gray-500">Karyawan</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }

                        $('#karyawanList').html(karyawanHTML);
                        $('#karyawanContainer').fadeIn(500);
                    } else {
                        $('#booking-alert')
                            .removeClass('hidden bg-green-100 text-green-800')
                            .addClass('bg-red-100 text-red-800')
                            .html('Terjadi kesalahan saat memuat data karyawan')
                            .fadeIn();
                    }
                },
                error: function() {
                    $('#booking-alert')
                        .removeClass('hidden bg-green-100 text-green-800')
                        .addClass('bg-red-100 text-red-800')
                        .html('Terjadi kesalahan saat memuat data karyawan')
                        .fadeIn();
                }
            });
        }

        // Delegasi event untuk pilihan karyawan
        $('#karyawanList').on('click', '.karyawan-item', function() {
            $('.karyawan-item').removeClass('border-green-500 ring-2 ring-green-500');
            $(this).addClass('border-green-500 ring-2 ring-green-500');

            $('#idkaryawan').val($(this).data('id'));

            // Update summary
            updateSummary();

            // Tampilkan ringkasan booking
            $('#summaryContainer').fadeIn(500);

            // Enable tombol submit
            $('#btnSubmit').prop('disabled', false);
        });

        // Update ringkasan booking
        function updateSummary() {
            const paketId = $('#idpaket').val();
            const paketText = $('#idpaket option:selected').text();
            const tanggal = $('#tanggal_booking').val();
            const jamstart = $('#jamstart').val();
            const jamend = $('#jamend').val();
            const karyawanId = $('#idkaryawan').val();

            // Set nilai ringkasan
            if (paketId) $('#summary_paket').text(paketText);
            if (tanggal) $('#summary_tanggal').text(formatTanggal(tanggal));
            if (jamstart && jamend) $('#summary_waktu').text(`${jamstart} - ${jamend}`);

            // Set total harga
            const harga = $('#idpaket option:selected').data('harga') || 0;
            $('#summary_total').text(formatRupiah(harga));
            $('#total').val(harga);

            // Set nama karyawan jika sudah dipilih
            if (karyawanId) {
                const karyawanName = $(`.karyawan-item[data-id="${karyawanId}"]`).find('.font-medium').text();
                $('#summary_karyawan').text(karyawanName);
            }
        }

        // Ketika paket layanan berubah
        $('#idpaket').on('change', function() {
            // Reset form untuk meminta pelanggan memilih ulang tanggal, jam, dan karyawan
            $('#timeSlotContainer').hide();
            $('#karyawanContainer').hide();
            $('#summaryContainer').hide();
            $('#btnSubmit').prop('disabled', true);

            // Jika tanggal sudah dipilih, tampilkan time slot container
            if ($('#tanggal_booking').val()) {
                $('#timeSlotContainer').fadeIn(500);
            }

            // Reset input
            $('#jamstart').val('');
            $('#jamend').val('');
            $('#idkaryawan').val('');

            // Update summary
            updateSummary();
        });

        // Form submit
        $('#bookingForm').on('submit', function(e) {
            e.preventDefault();

            const requiredFields = ['idpaket', 'tanggal_booking', 'jamstart', 'idkaryawan'];
            let isValid = true;

            // Validasi semua field yang required
            requiredFields.forEach(field => {
                if (!$('#' + field).val()) {
                    isValid = false;
                    return false;
                }
            });

            if (!isValid) {
                $('#booking-alert')
                    .removeClass('hidden bg-green-100 text-green-800')
                    .addClass('bg-red-100 text-red-800')
                    .html('Harap isi semua field yang diperlukan')
                    .fadeIn();
                return;
            }

            // Disable tombol submit untuk mencegah double submit
            $('#btnSubmit').prop('disabled', true).html(`
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `);

            $.ajax({
                url: '<?= site_url('customer/booking/store') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#booking-alert')
                            .removeClass('hidden bg-red-100 text-red-800')
                            .addClass('bg-green-100 text-green-800')
                            .html('Booking berhasil dibuat! Sedang mengalihkan...')
                            .fadeIn();

                        // Redirect ke halaman detail booking
                        setTimeout(function() {
                            window.location.href = '<?= site_url('customer/booking/detail/') ?>' + response.kdbooking;
                        }, 1500);
                    } else {
                        $('#booking-alert')
                            .removeClass('hidden bg-green-100 text-green-800')
                            .addClass('bg-red-100 text-red-800')
                            .html('Gagal membuat booking: ' + response.message)
                            .fadeIn();

                        // Re-enable tombol submit
                        $('#btnSubmit').prop('disabled', false).html('Booking Sekarang');
                    }
                },
                error: function() {
                    $('#booking-alert')
                        .removeClass('hidden bg-green-100 text-green-800')
                        .addClass('bg-red-100 text-red-800')
                        .html('Terjadi kesalahan saat membuat booking')
                        .fadeIn();

                    // Re-enable tombol submit
                    $('#btnSubmit').prop('disabled', false).html('Booking Sekarang');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>