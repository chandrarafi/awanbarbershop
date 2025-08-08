 <!-- Bootstrap 5 CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
 <!-- Bootstrap Icons -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
 <!-- DataTables Bootstrap 5 -->
 <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
 <!-- Google Fonts -->
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
 <!-- Animate CSS -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
 <style>
     :root {
         --primary-color: #2C3639;
         --primary-gradient-start: #3F4E4F;
         --primary-gradient-end: #2C3639;
         --secondary-color: #A27B5C;
         --success-color: #2ecc71;
         --info-color: #3498db;
         --warning-color: #f39c12;
         --danger-color: #e74c3c;
         --light-color: #DCD7C9;
         --dark-color: #2C3639;
         --border-radius: 0.75rem;
         --card-border-radius: 1rem;
         --box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
     }

     body {
         font-family: 'Poppins', sans-serif;
         background-color: #f5f5f5;
         color: #444;
         transition: all 0.3s ease;
         overflow-x: hidden;
         font-size: 14px;
     }

     @media (min-width: 768px) {
         body {
             font-size: 16px;
         }
     }

     /* Notification styling */
     .notification-dropdown {
         box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
         border: none;
         border-radius: 0.5rem;
         overflow: hidden;
         z-index: 1050 !important;
         width: 350px !important;
         max-height: 500px;
         overflow-y: auto;
     }

     @media (max-width: 480px) {
         .notification-dropdown {
             width: 300px !important;
             max-height: 400px;
         }
     }

     .dropdown-menu {
         z-index: 1050 !important;
     }

     .notification-item {
         transition: all 0.3s;
         border-left: 3px solid transparent;
         word-wrap: break-word;
         white-space: normal;
         padding: 10px 15px;
     }

     .notification-item.unread {
         background-color: rgba(13, 110, 253, 0.05);
         border-left: 3px solid var(--secondary-color);
     }

     .notification-item.read {
         opacity: 0.8;
     }

     .notification-item:hover {
         background-color: #f8f9fa;
         border-left: 3px solid var(--secondary-color);
     }

     .notification-icon i {
         transition: all 0.3s;
     }

     .notification-item:hover .notification-icon i {
         transform: scale(1.1);
     }

     .notification-unread-indicator {
         display: inline-block;
         width: 8px;
         height: 8px;
         border-radius: 50%;
         background-color: var(--secondary-color);
         margin-left: 5px;
     }

     #notificationBadge {
         font-size: 0.65rem;
         padding: 0.25rem 0.5rem;
         animation: pulse 1.5s infinite;
     }

     @keyframes pulse {
         0% {
             transform: translate(-50%, -50%) scale(0.95);
         }

         50% {
             transform: translate(-50%, -50%) scale(1.05);
         }

         100% {
             transform: translate(-50%, -50%) scale(0.95);
         }
     }

     /* Scrollbar styling */
     ::-webkit-scrollbar {
         width: 6px;
         height: 6px;
     }

     ::-webkit-scrollbar-track {
         background: var(--light-color);
         border-radius: 10px;
     }

     ::-webkit-scrollbar-thumb {
         background: var(--secondary-color);
         border-radius: 10px;
     }

     ::-webkit-scrollbar-thumb:hover {
         background: var(--primary-color);
     }

     /* Sidebar styling */
     .sidebar {
         /* Pastikan sidebar bisa di-scroll pada mobile */
         height: 100vh;
         min-height: 100vh;
         background: var(--primary-color);
         box-shadow: var(--box-shadow);
         z-index: 1040;
         position: fixed;
         top: 0;
         left: 0;
         bottom: 0;
         width: 280px;
         transition: all 0.3s ease-in-out;
         overflow-y: auto;
         -webkit-overflow-scrolling: touch;
         overscroll-behavior: contain;
         pointer-events: auto;
     }

     .sidebar-brand {
         height: 5rem;
         display: flex;
         align-items: center;
         justify-content: center;
         padding: 1.5rem 1rem;
         background: var(--secondary-color);
         flex-shrink: 0;
     }

     .sidebar-brand img {
         width: 45px;
         height: 45px;
         border-radius: 50%;
         margin-right: 10px;
         background: white;
         padding: 5px;
     }

     .sidebar-brand h3 {
         color: white;
         font-weight: 700;
         font-size: 1.2rem;
         margin-bottom: 0;
         letter-spacing: 1px;
     }

     .sidebar-brand p {
         color: var(--light-color);
         margin-bottom: 0;
         font-size: 0.8rem;
         letter-spacing: 1px;
     }

     .sidebar-divider {
         border-top: 1px solid rgba(255, 255, 255, 0.1);
         margin: 0 1.5rem;
     }

     .nav-header {
         color: var(--light-color);
         font-size: 12px;
         font-weight: 600;
         text-transform: uppercase;
         letter-spacing: 1px;
         margin-top: 1.5rem;
         padding-left: 1.5rem;
     }

     .nav-item {
         position: relative;
         padding: 0 0.5rem;
     }

     .nav-link {
         color: rgba(255, 255, 255, 0.8);
         font-weight: 500;
         padding: 1rem;
         border-radius: var(--border-radius);
         margin: 0.2rem 0;
         transition: all 0.3s;
         position: relative;
         overflow: hidden;
     }

     .nav-link::before {
         content: '';
         position: absolute;
         top: 0;
         left: 0;
         width: 4px;
         height: 100%;
         background-color: var(--secondary-color);
         transform: scaleY(0);
         transition: transform 0.3s, opacity 0.3s;
         transform-origin: top;
         opacity: 0;
         border-radius: 0 2px 2px 0;
     }

     .nav-link:hover {
         background-color: rgba(255, 255, 255, 0.1);
         color: white;
         transform: translateX(5px);
     }

     .nav-link:hover::before {
         transform: scaleY(1);
         opacity: 1;
     }

     .nav-link.active {
         background-color: var(--secondary-color);
         color: white;
         box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.1);
     }

     .nav-link.active::before {
         transform: scaleY(1);
         opacity: 1;
     }

     .nav-link i {
         margin-right: 0.8rem;
         font-size: 1.1rem;
         width: 1.5rem;
         text-align: center;
         transition: all 0.3s;
     }

     /* Main content */
     .main-content {
         margin-left: 280px;
         transition: all 0.3s ease-in-out;
         min-height: 100vh;
         background-color: #f5f5f5;
         padding: 1.5rem;
         position: relative;
         z-index: 1;
     }

     /* Topbar */
     .topbar {
         height: 4.375rem;
         background-color: white;
         box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
         margin-bottom: 1.5rem;
         border-radius: var(--card-border-radius);
         display: flex;
         align-items: center;
         padding: 0 1rem;
         position: relative;
         overflow: hidden;
         flex-wrap: nowrap;
     }

     .topbar::after {
         content: '';
         position: absolute;
         bottom: 0;
         left: 0;
         width: 100%;
         height: 3px;
         background: var(--secondary-color);
     }

     .topbar h1 {
         font-size: 1.5rem;
         font-weight: 700;
         color: var(--primary-color);
         margin-bottom: 0;
         white-space: nowrap;
         overflow: hidden;
         text-overflow: ellipsis;
         flex: 1;
         min-width: 0;
     }

     /* Cards */
     .card {
         border: none;
         border-radius: var(--card-border-radius);
         box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.07);
         transition: all 0.3s ease-in-out;
         background-color: white;
         overflow: hidden;
     }

     .card:hover {
         transform: translateY(-5px);
         box-shadow: 0 1rem 3rem rgba(58, 59, 69, 0.15);
     }

     .card-header {
         background-color: white;
         border-bottom: 1px solid rgba(0, 0, 0, 0.05);
         padding: 1.25rem 1.5rem;
         font-weight: 600;
         color: var(--primary-color);
         display: flex;
         align-items: center;
         justify-content: space-between;
     }

     .card-header .btn {
         padding: 0.5rem 1rem;
         font-size: 0.9rem;
     }

     /* Buttons */
     .btn {
         padding: 0.6rem 1.2rem;
         font-weight: 500;
         border-radius: var(--border-radius);
         transition: all 0.3s;
     }

     .btn-primary {
         background: var(--secondary-color);
         border: none;
         color: white;
     }

     .btn-primary:hover {
         background: var(--primary-color);
         transform: translateY(-2px);
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
     }

     /* Tables */
     .table-responsive {
         border-radius: var(--border-radius);
         background: white;
         padding: 1rem;
     }

     .table th {
         font-weight: 600;
         background-color: #f8f9fc;
         color: var(--primary-color);
         padding: 1rem;
         white-space: nowrap;
         border-bottom: 2px solid var(--secondary-color);
     }

     .table td {
         vertical-align: middle;
         padding: 1rem;
         color: #555;
     }

     .table tbody tr {
         transition: all 0.2s;
     }

     .table tbody tr:hover {
         background-color: rgba(162, 123, 92, 0.05);
     }

     /* Stats Cards */
     .stat-card {
         background: white;
         border-radius: var(--card-border-radius);
         padding: 1.5rem;
         margin-bottom: 1.5rem;
         position: relative;
         overflow: hidden;
         border-left: 4px solid var(--secondary-color);
     }

     .stat-card .icon {
         position: absolute;
         right: 1.5rem;
         top: 50%;
         transform: translateY(-50%);
         font-size: 3rem;
         opacity: 0.1;
         color: var(--primary-color);
     }

     .stat-card h3 {
         font-size: 2rem;
         font-weight: 700;
         color: var(--primary-color);
         margin-bottom: 0.5rem;
     }

     .stat-card p {
         color: #666;
         margin-bottom: 0;
         font-size: 0.9rem;
     }

     /* Mobile overlay for sidebar */
     .sidebar-overlay {
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.5);
         z-index: 1035;
         opacity: 0;
         visibility: hidden;
         transition: all 0.3s ease-in-out;
     }

     .sidebar-overlay.show {
         opacity: 1;
         visibility: visible;
     }

     /* Mobile toggle button */
     .navbar-toggler {
         background: none;
         border: none;
         color: var(--primary-color);
         font-size: 1.5rem;
         padding: 0.5rem;
         border-radius: 0.5rem;
         transition: all 0.3s ease;
     }

     .navbar-toggler:hover {
         background-color: rgba(162, 123, 92, 0.1);
         color: var(--secondary-color);
     }

     .navbar-toggler:focus {
         box-shadow: 0 0 0 0.2rem rgba(162, 123, 92, 0.25);
     }

     /* Responsive Design */
     @media (max-width: 1200px) {
         .main-content {
             padding: 1.25rem;
         }

         .topbar {
             padding: 0 1.25rem;
         }
     }

     @media (max-width: 992px) {
         .sidebar {
             width: 75px;
         }

         .sidebar-brand {
             padding: 1rem 0.5rem;
             flex-direction: column;
             height: auto;
             min-height: 5rem;
         }

         .sidebar-brand img {
             margin-right: 0;
             margin-bottom: 0.5rem;
             width: 35px;
             height: 35px;
         }

         .sidebar-brand h3,
         .sidebar-brand p,
         .sidebar-heading,
         .nav-link span {
             display: none;
         }

         .nav-link {
             padding: 0.75rem 0.5rem;
             text-align: center;
             margin: 0.1rem 0;
         }

         .nav-link i {
             margin: 0;
             font-size: 1.1rem;
         }

         .main-content {
             margin-left: 75px;
             padding: 1rem;
         }

         .topbar {
             padding: 0 1rem;
             margin-bottom: 1.25rem;
         }

         .topbar h1 {
             font-size: 1.35rem;
         }
     }

     @media (max-width: 768px) {
         .main-content {
             margin-left: 0;
             padding: 0.75rem;
         }

         .sidebar {
             transform: translateX(-100%);
             width: 280px;
             height: 100vh;
             /* pastikan full height pada mobile */
             overflow-y: auto;
             /* enable scroll */
             -webkit-overflow-scrolling: touch;
             /* smooth scrolling iOS */
         }

         .sidebar.show {
             transform: translateX(0);
         }

         .sidebar.show .sidebar-brand {
             flex-direction: row;
             padding: 1.5rem 1rem;
         }

         .sidebar.show .sidebar-brand img {
             margin-right: 10px;
             margin-bottom: 0;
             width: 45px;
             height: 45px;
         }

         .sidebar.show .sidebar-brand h3,
         .sidebar.show .sidebar-brand p,
         .sidebar.show .sidebar-heading,
         .sidebar.show .nav-link span {
             display: inline-block;
         }

         .sidebar.show .nav-link {
             text-align: left;
             padding: 1rem;
         }

         .sidebar.show .nav-link i {
             margin-right: 0.8rem;
         }

         .topbar {
             margin-bottom: 1rem;
             padding: 0 0.75rem;
             height: 4rem;
         }

         .topbar h1 {
             font-size: 1.25rem;
         }

         .navbar-toggler {
             display: block !important;
         }
     }

     @media (max-width: 480px) {
         .main-content {
             padding: 0.5rem;
         }

         .topbar {
             padding: 0 0.5rem;
             height: 3.5rem;
         }

         .topbar h1 {
             font-size: 1.1rem;
         }

         .sidebar {
             width: 260px;
         }

         .card {
             margin-bottom: 1rem;
         }

         .card-header {
             padding: 1rem;
             flex-direction: column;
             align-items: flex-start;
             gap: 0.5rem;
         }

         .btn {
             padding: 0.5rem 0.75rem;
             font-size: 0.875rem;
         }

         .table-responsive {
             padding: 0.5rem;
         }

         .table th,
         .table td {
             padding: 0.5rem;
             font-size: 0.875rem;
         }

         .stat-card {
             padding: 1rem;
             margin-bottom: 1rem;
         }

         .stat-card h3 {
             font-size: 1.5rem;
         }

         .stat-card .icon {
             font-size: 2rem;
         }

         /* DataTables controls stacking */
         .dataTables_wrapper .row>[class^="col"],
         .dataTables_wrapper .row>[class*=" col"] {
             width: 100%;
             max-width: 100%;
         }

         .dataTables_length,
         .dataTables_filter,
         .dataTables_info,
         .dataTables_paginate {
             text-align: left !important;
             margin-bottom: 0.5rem;
         }

         .dataTables_filter input {
             width: 100% !important;
             margin-left: 0 !important;
         }

         .btn {
             padding: 0.5rem 0.75rem;
             font-size: 0.9rem;
         }

         .form-control,
         .form-select {
             font-size: 0.95rem;
             padding: 0.5rem 0.75rem;
         }
     }

     /* Ensure any table inside main content can scroll horizontally on small screens if not wrapped */
     @media (max-width: 768px) {
         .main-content table {
             display: block;
             width: 100%;
             overflow-x: auto;
             -webkit-overflow-scrolling: touch;
             white-space: nowrap;
         }

         .input-group {
             flex-wrap: wrap;
         }

         .input-group .form-control {
             min-width: 0;
         }
     }
     }

     /* Landscape orientation fixes */
     @media (max-height: 500px) and (orientation: landscape) {
         .sidebar {
             overflow-y: auto;
         }

         .sidebar-brand {
             height: 4rem;
             min-height: 4rem;
             padding: 0.75rem;
         }

         .nav-link {
             padding: 0.5rem 1rem;
         }
     }
 </style>