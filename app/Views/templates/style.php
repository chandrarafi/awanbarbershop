<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-color: #1E293B;
        --secondary-color: #E74C3C;
        --accent-color: #F1C40F;
        --dark-color: #0F172A;
        --light-color: #94A3B8;
        --text-color: #E2E8F0;
        --card-bg: #334155;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--primary-color);
        color: var(--text-color);
    }

    .swiper {
        width: 100%;
        height: 100vh;
    }

    .gradient-overlay {
        background: linear-gradient(135deg, rgba(44, 62, 80, 0.9) 0%, rgba(0, 0, 0, 0.4) 100%);
    }

    .card-hover {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .card-hover:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        border-color: var(--secondary-color);
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .service-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(231, 76, 60, 0.2), transparent);
        transform: rotate(45deg);
        transition: all 0.5s ease;
        opacity: 0;
    }

    .service-card:hover::before {
        animation: shine 1.5s;
    }

    @keyframes shine {
        0% {
            opacity: 0;
            transform: translateX(-100%) rotate(45deg);
        }

        50% {
            opacity: 1;
        }

        100% {
            opacity: 0;
            transform: translateX(100%) rotate(45deg);
        }
    }

    .floating {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    .gradient-text {
        background: linear-gradient(to right, #E74C3C, #F1C40F);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .custom-shape-divider {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
    }

    .highlight-container {
        position: relative;
        display: inline-block;
    }

    .highlight-container::before {
        content: "";
        position: absolute;
        left: -0.25em;
        right: -0.25em;
        top: 0.1em;
        bottom: 0.1em;
        background: linear-gradient(135deg, rgba(231, 76, 60, 0.2), rgba(241, 196, 15, 0.2));
        z-index: -1;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .highlight-container:hover::before {
        transform: scaleX(1);
        transform-origin: left;
    }

    .nav-link {
        color: white;
        position: relative;
        transition: all 0.3s ease;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        bottom: -2px;
        left: 0;
        background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        transform: scaleX(1);
        transform-origin: left;
    }

    .nav-link:hover,
    .nav-link.active {
        color: var(--accent-color);
    }

    .mobile-menu {
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
    }

    .btn-primary {
        background: linear-gradient(to right, #E74C3C, #F1C40F);
        color: white;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #F1C40F, #E74C3C);
        transform: scale(1.05);
    }

    .btn-secondary {
        background: #2C3E50;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #34495E;
    }

    .animated-border {
        position: relative;
        border: 1px solid transparent;
    }

    .animated-border::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, #E74C3C, #F1C40F, #E74C3C);
        background-size: 400% 400%;
        z-index: -1;
        border-radius: 0.75rem;
        animation: borderAnimation 6s ease infinite;
    }

    @keyframes borderAnimation {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .navbar-transparent {
        background-color: transparent;
        box-shadow: none;
        transition: all 0.5s ease;
    }

    .navbar-fixed {
        background-color: var(--dark-color);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-link.active {
        color: #F1C40F;
        font-weight: bold;
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        bottom: -4px;
        left: 0;
        background: linear-gradient(to right, #E74C3C, #F1C40F);
    }

    /* Mobile menu styling */
    @media (max-width: 768px) {
        .mobile-menu {
            background-color: #2C3E50;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .nav-link {
            margin-bottom: 0.5rem;
        }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--dark-color);
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        border-radius: 8px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
    }

    /* Form and Card Styling with Soft Texture */
    .form-card {
        background-color: #F8F9FA;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3z' fill='%23e2e8f0' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        background-color: #FFFFFF;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f1f5f9' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        border: 1px solid #EDF2F7;
        border-radius: 0.75rem;
    }

    input,
    select,
    textarea {
        background-color: #FFFFFF !important;
        border-color: #E2E8F0 !important;
        color: #4A5568 !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--accent-color) !important;
        box-shadow: 0 0 0 2px rgba(241, 196, 15, 0.2) !important;
    }

    label {
        color: #4A5568 !important;
        font-weight: 500 !important;
    }

    .time-slot {
        background-color: #FFFFFF;
        color: #4A5568;
        border-color: #E2E8F0;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .time-slot:hover:not(.disabled):not(.booked) {
        background-color: #FEF9E7;
        border-color: var(--accent-color);
    }

    .time-slot.active {
        background-color: var(--accent-color) !important;
        color: #1A202C !important;
    }

    /* Additional Booking Form Styles */
    .karyawan-item {
        transition: all 0.3s ease;
    }

    .karyawan-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .karyawan-item.border-green-500 {
        transform: translateY(-3px);
    }

    #booking-alert {
        border-radius: 0.5rem;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Soft box shadow for cards */
    .shadow-sm {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Custom Radio Buttons */
    .payment-option-wrapper label,
    .method-option-wrapper label {
        transition: all 0.2s ease;
    }

    .payment-option-wrapper label.border-blue-500,
    .method-option-wrapper label.border-blue-500 {
        box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.5);
        background-color: rgba(59, 130, 246, 0.05);
    }

    .radio-circle {
        transition: all 0.2s ease;
    }

    .radio-dot {
        transition: all 0.2s ease;
    }
</style>