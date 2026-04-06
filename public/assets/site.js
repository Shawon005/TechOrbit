document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('.js-site-header');
    const navToggle = document.querySelector('.js-nav-toggle');
    const mobileNav = document.querySelector('.js-mobile-nav');

    if (header) {
        const updateHeader = () => {
            header.classList.toggle('is-scrolled', window.scrollY > 24);
        };

        updateHeader();
        window.addEventListener('scroll', updateHeader);
    }

    if (navToggle && mobileNav) {
        navToggle.addEventListener('click', () => {
            mobileNav.classList.toggle('open');
        });
    }

    document.querySelectorAll('[data-slider]').forEach((slider) => {
        const slides = Array.from(slider.querySelectorAll('[data-slide]'));
        const dots = Array.from(document.querySelectorAll('[data-slide-dot]'));

        if (slides.length < 2) {
            return;
        }

        let activeIndex = 0;

        const activate = (index) => {
            activeIndex = index;
            slides.forEach((slide, slideIndex) => {
                slide.classList.toggle('active', slideIndex === index);
            });

            dots.forEach((dot, dotIndex) => {
                dot.classList.toggle('active', dotIndex === index);
            });
        };

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => activate(index));
        });

        setInterval(() => {
            activate((activeIndex + 1) % slides.length);
        }, 4500);
    });

    document.querySelectorAll('[data-filter-group]').forEach((group) => {
        const buttons = Array.from(group.querySelectorAll('[data-filter]'));
        const cards = group.parentElement.querySelectorAll('[data-filter-item]');

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;

                buttons.forEach((item) => item.classList.remove('active'));
                button.classList.add('active');

                cards.forEach((card) => {
                    const matches = filter === 'all' || card.dataset.filterItem === filter;
                    card.classList.toggle('is-hidden', !matches);
                });
            });
        });
    });

    const counterObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            const element = entry.target;
            const target = Number(element.dataset.counter || 0);
            const suffix = element.dataset.suffix || '';
            let current = 0;
            const increment = Math.max(1, Math.ceil(target / 32));

            const tick = () => {
                current = Math.min(target, current + increment);
                element.textContent = `${current}${suffix}`;

                if (current < target) {
                    window.requestAnimationFrame(tick);
                }
            };

            tick();
            observer.unobserve(element);
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('[data-counter]').forEach((counter) => counterObserver.observe(counter));

    document.querySelectorAll('[data-faq-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            button.closest('.faq-item')?.classList.toggle('open');
        });
    });
});
