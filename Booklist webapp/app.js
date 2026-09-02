document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    if (heroTitle && heroSubtitle) {
        setTimeout(() => { 
            heroTitle.style.opacity = 1;
            heroTitle.style.transform = 'translateY(0)';
            heroSubtitle.style.opacity = 1;
            heroSubtitle.style.transform = 'translateY(0)';
        }, 500); 
    }

    if (typeof ScrollReveal !== 'undefined') {
        ScrollReveal().reveal('#featured .swiper-container', { delay: 600, origin: 'bottom', distance: '100px' });
        ScrollReveal().reveal('#categories .category-card', { delay: 200, interval: 200, origin: 'bottom', distance: '50px' });
    }

    const parallax = document.querySelector('.hero'); 
    if (parallax && typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        const tl = gsap.timeline({ scrollTrigger: { trigger: parallax }});
        tl.fromTo(parallax, { yPercent: 0 }, { yPercent: -20, ease: "none" });
    }

    if (typeof Swiper !== 'undefined') {
        new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true, 
            pagination: {
              el: '.swiper-pagination',
              clickable: true,
            },
            breakpoints: { 
                768: {
                    slidesPerView: 2,
                },
                1024: { 
                    slidesPerView: 3, 
                }
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category-select');
    const books = document.querySelectorAll('.book');
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('book-search');

    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;
            books.forEach(book => {
                const bookCategory = book.getAttribute('data-filter');
                if (selectedCategory === 'All' || bookCategory === selectedCategory) {
                    book.style.display = 'block';
                } else {
                    book.style.display = 'none';
                }
            });
        });
    }

    if (searchButton && searchInput) {
        searchButton.addEventListener('click', function() {
            const query = searchInput.value.toLowerCase();
            books.forEach(book => {
                const bookTitle = book.getAttribute('data-title').toLowerCase();
                if (bookTitle.includes(query)) {
                    book.style.display = 'block';
                } else {
                    book.style.display = 'none';
                }
            });
        });

        searchInput.addEventListener('input', function() {
            const query = searchInput.value.toLowerCase();
            books.forEach(book => {
                const bookTitle = book.getAttribute('data-title').toLowerCase();
                if (bookTitle.includes(query)) {
                    book.style.display = 'block';
                } else {
                    book.style.display = 'none';
                }
            });
        });
    }
});
