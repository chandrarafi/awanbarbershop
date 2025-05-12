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
     });
 </script>