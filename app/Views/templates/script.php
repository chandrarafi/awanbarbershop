<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Initialize AOS
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });

    // Swiper initialization
    var swiper = new Swiper(".heroSwiper", {
        spaceBetween: 0,
        effect: "fade",
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });

    // Enhanced Navbar scroll effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.remove('navbar-transparent');
            navbar.classList.add('navbar-fixed');
        } else {
            navbar.classList.remove('navbar-fixed');
            navbar.classList.add('navbar-transparent');
        }
    });

    // Enhanced Active link highlighting
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    // Check if current page is landing page or another page
    const isLandingPage = window.location.pathname === '/' ||
        window.location.pathname === '/index.php' ||
        window.location.pathname === '/index.html' ||
        window.location.pathname.endsWith('/awanbarbershop/');

    // Remove active class from all navigation links first
    navLinks.forEach(link => {
        link.classList.remove('active');
    });

    if (isLandingPage) {
        // Only apply scroll spy on landing page
        window.addEventListener('scroll', () => {
            let current = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - sectionHeight / 3)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href && href.includes('#' + current) && current !== '') {
                    link.classList.add('active');
                }
            });
        });
    } else {
        // For other pages, mark active based on current path
        const currentPath = window.location.pathname;

        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && !href.includes('#') && currentPath.includes(href.replace('<?= base_url() ?>', ''))) {
                link.classList.add('active');
            }
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetElement = document.querySelector(this.getAttribute('href'));
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Trigger scroll event on page load
    window.dispatchEvent(new Event('scroll'));
</script>