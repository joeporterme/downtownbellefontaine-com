// Subtle parallax for [data-parallax] hero images.
// Disabled entirely when the user prefers reduced motion.
function initParallax() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const els = Array.from(document.querySelectorAll('[data-parallax]'));
    if (!els.length) {
        return;
    }

    let ticking = false;

    function update() {
        const vh = window.innerHeight;

        els.forEach((el) => {
            const container = el.parentElement;
            const rect = container.getBoundingClientRect();

            // Skip work when the hero is well outside the viewport.
            if (rect.bottom < -200 || rect.top > vh + 200) {
                return;
            }

            const speed = parseFloat(el.dataset.parallaxSpeed || '0.25');
            const offset = rect.top * speed * -1;
            el.style.transform = `translate3d(0, ${offset}px, 0) scale(1.2)`;
        });

        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
}

document.addEventListener('DOMContentLoaded', initParallax);

export { initParallax };
