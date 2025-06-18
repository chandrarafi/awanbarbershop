<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-color: #2C3E50;
        --secondary-color: #E74C3C;
        --accent-color: #F1C40F;
        --dark-color: #1a1a1a;
        --light-color: #ECF0F1;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--light-color);
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
        background: rgba(44, 62, 80, 0.98);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
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
        background-color: #2C3E50;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background: var(--light-color);
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
    }
</style>