 <!-- jQuery -->
 <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
 <!-- Bootstrap 5 JS -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
 <!-- DataTables -->
 <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
 <!-- SweetAlert2 -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <!-- ApexCharts -->
 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

 <script>
     $(document).ready(function() {
         // Konfigurasi dropdown notifikasi
         var notificationDropdownEl = document.getElementById('notificationDropdown');
         if (notificationDropdownEl) {
             var notificationDropdown = new bootstrap.Dropdown(notificationDropdownEl, {
                 popperConfig: {
                     strategy: 'fixed',
                     modifiers: [{
                         name: 'preventOverflow',
                         options: {
                             padding: 10,
                             boundary: 'viewport'
                         }
                     }]
                 }
             });
         }

         // Toggle sidebar on mobile
         $('#navbarToggler').on('click', function() {
             $('#sidebar').toggleClass('show');
         });

         // Close sidebar when clicking outside
         $(document).on('click', function(e) {
             if ($(window).width() < 768) {
                 if (!$(e.target).closest('#sidebar').length &&
                     !$(e.target).closest('#navbarToggler').length &&
                     $('#sidebar').hasClass('show')) {
                     $('#sidebar').removeClass('show');
                 }
             }
         });

         // Logout functionality
         function handleLogout() {
             Swal.fire({
                 title: 'Apakah Anda yakin?',
                 text: "Anda akan keluar dari sistem",
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#A27B5C',
                 cancelButtonColor: '#6c757d',
                 confirmButtonText: 'Ya, Keluar!',
                 cancelButtonText: 'Batal'
             }).then((result) => {
                 if (result.isConfirmed) {
                     window.location.href = '<?= site_url('auth/logout') ?>';
                 }
             });
         }

         $('#btn-logout, #btn-logout-dropdown').on('click', handleLogout);

         // Notifikasi
         let notificationCheckInterval;

         // Fungsi untuk memuat notifikasi
         function loadNotifications() {
             $.ajax({
                 url: '<?= site_url('admin/notifications/unread') ?>',
                 type: 'GET',
                 dataType: 'json',
                 success: function(response) {
                     //  console.log('Notifikasi response:', response); // Debug response
                     if (response && response.status === 'success') {
                         const notifications = response.data ? response.data.notifications : [];
                         const count = response.data ? response.data.count : 0;
                         //  console.log('Notifikasi count:', count);
                         //  console.log('Notifikasi data:', notifications);

                         renderNotifications(notifications, count);
                     } else {
                         console.error('Response status bukan success:', response ? response.status : 'tidak ada response');
                         // Tampilkan pesan error di dropdown
                         const notificationList = $('#notificationList');
                         notificationList.html(`
                             <div class="dropdown-item text-center py-4">
                                 <i class="bi bi-exclamation-triangle text-warning fs-4 mb-2"></i>
                                 <p class="text-muted mb-0">Gagal memuat notifikasi</p>
                             </div>
                         `);
                     }
                 },
                 error: function(xhr, status, error) {
                     console.error('Gagal memuat notifikasi:', status, error);
                     console.log('XHR:', xhr.responseText);

                     // Tampilkan pesan error di dropdown
                     const notificationList = $('#notificationList');
                     notificationList.html(`
                         <div class="dropdown-item text-center py-4">
                             <i class="bi bi-exclamation-triangle text-warning fs-4 mb-2"></i>
                             <p class="text-muted mb-0">Gagal memuat notifikasi</p>
                         </div>
                     `);
                 }
             });
         }

         // Fungsi untuk merender notifikasi ke dalam dropdown
         function renderNotifications(notifications, count) {
             // Update badge jumlah notifikasi
             if (count > 0) {
                 $('#notificationBadge').show();
                 $('.notification-count').text(count > 99 ? '99+' : count);
             } else {
                 $('#notificationBadge').hide();
             }

             // Render notifikasi ke dalam dropdown
             const notificationList = $('#notificationList');
             notificationList.empty();

             //  console.log('Rendering notifications:', notifications); // Debug

             if (notifications && notifications.length > 0) {
                 // Tampilkan notifikasi
                 notifications.forEach(function(notification) {
                     //  console.log('Processing notification:', notification); // Debug individual notification

                     let iconClass = 'bi-bell';

                     // Sesuaikan icon berdasarkan jenis notifikasi
                     if (notification.type === 'booking_baru') {
                         iconClass = 'bi-calendar-plus';
                     } else if (notification.type === 'pembayaran') {
                         iconClass = 'bi-credit-card';
                     }

                     // Tentukan kelas berdasarkan status dibaca
                     const readStatusClass = parseInt(notification.is_read) === 0 ? 'unread' : 'read';
                     const unreadIndicator = parseInt(notification.is_read) === 0 ? '<span class="notification-unread-indicator"></span>' : '';

                     const notificationItem = `
                         <a href="<?= site_url('admin/notifications/view') ?>/${notification.id}" 
                            class="dropdown-item notification-item border-bottom ${readStatusClass}" 
                            data-id="${notification.id}">
                             <div class="d-flex align-items-start">
                                 <div class="notification-icon me-3">
                                     <span class="bg-light rounded-circle p-2 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                         <i class="bi ${iconClass} text-primary fs-5"></i>
                                     </span>
                                 </div>
                                 <div class="flex-grow-1">
                                     <h6 class="mb-1">
                                         ${notification.title || 'Notifikasi'} 
                                         ${unreadIndicator}
                                     </h6>
                                     <p class="mb-1 text-muted small">${notification.message || 'Tidak ada pesan'}</p>
                                     <div class="text-muted small">${notification.time_ago || 'Baru saja'}</div>
                                 </div>
                             </div>
                         </a>
                     `;

                     notificationList.append(notificationItem);
                 });
             } else {
                 // Tampilkan pesan tidak ada notifikasi
                 notificationList.html(`
                     <div class="dropdown-item text-center py-4">
                         <i class="bi bi-bell-slash fs-4 text-muted mb-2"></i>
                         <p class="text-muted mb-0">Tidak ada notifikasi</p>
                     </div>
                 `);
             }
         }

         // Tandai notifikasi sebagai dibaca saat diklik
         $(document).on('click', '.notification-item', function(e) {
             const notificationId = $(this).data('id');

             // Update server bahwa notifikasi sudah dibaca
             $.ajax({
                 url: '<?= site_url('admin/notifications/mark-read') ?>',
                 type: 'POST',
                 data: {
                     id: notificationId
                 }
             });
         });

         // Tandai semua notifikasi sebagai dibaca
         $('#markAllReadBtn').on('click', function(e) {
             e.preventDefault();
             e.stopPropagation();

             $.ajax({
                 url: '<?= site_url('admin/notifications/mark-all-read') ?>',
                 type: 'POST',
                 dataType: 'json',
                 success: function(response) {
                     if (response.status === 'success') {
                         loadNotifications(); // Refresh notifikasi

                         // Tampilkan feedback ke user
                         Swal.fire({
                             icon: 'success',
                             title: 'Berhasil',
                             text: 'Semua notifikasi telah ditandai sebagai dibaca',
                             timer: 1500,
                             showConfirmButton: false
                         });
                     }
                 }
             });
         });

         // Tombol untuk melihat semua notifikasi
         $('#viewAllNotifications').on('click', function(e) {
             e.preventDefault();
             window.location.href = '<?= site_url('admin/notifications/all') ?>';
         });

         // Muat notifikasi saat halaman dimuat
         loadNotifications();

         // Periksa notifikasi baru setiap 30 detik
         notificationCheckInterval = setInterval(loadNotifications, 30000);
     });
 </script>