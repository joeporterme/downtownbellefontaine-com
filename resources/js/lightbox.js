// Vanilla-JS photo lightbox. Any element with [data-lightbox] opens the modal;
// group them with [data-lightbox-group] to page prev/next through a set.
function initLightbox() {
    const modal = document.querySelector('[data-lightbox-modal]');
    if (!modal) {
        return;
    }

    const imgEl = modal.querySelector('[data-lightbox-image]');
    const capEl = modal.querySelector('[data-lightbox-caption]');
    const closeEls = modal.querySelectorAll('[data-lightbox-close]');
    const prevBtn = modal.querySelector('[data-lightbox-prev]');
    const nextBtn = modal.querySelector('[data-lightbox-next]');

    let group = [];
    let index = 0;
    let lastFocused = null;

    const srcFor = (el) =>
        el.dataset.lightboxSrc ||
        el.getAttribute('href') ||
        (el.tagName === 'IMG' ? el.src : el.querySelector('img')?.src) ||
        '';

    const captionFor = (el) =>
        el.dataset.lightboxCaption ||
        (el.tagName === 'IMG' ? el.alt : el.querySelector('img')?.alt) ||
        '';

    function show(i) {
        if (!group.length) return;
        index = (i + group.length) % group.length;
        const el = group[index];
        imgEl.src = srcFor(el);
        imgEl.alt = captionFor(el);
        capEl.textContent = captionFor(el);

        const multi = group.length > 1;
        if (prevBtn) prevBtn.style.display = multi ? '' : 'none';
        if (nextBtn) nextBtn.style.display = multi ? '' : 'none';
    }

    function open(el) {
        const groupName = el.dataset.lightboxGroup || 'default';
        group = Array.from(document.querySelectorAll('[data-lightbox]'))
            .filter((n) => (n.dataset.lightboxGroup || 'default') === groupName);
        lastFocused = document.activeElement;
        show(group.indexOf(el));
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        closeEls[0]?.focus();
        document.addEventListener('keydown', onKey);
    }

    function close() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        document.removeEventListener('keydown', onKey);
        lastFocused?.focus();
    }

    function onKey(e) {
        if (e.key === 'Escape') {
            close();
        } else if (e.key === 'ArrowLeft') {
            show(index - 1);
        } else if (e.key === 'ArrowRight') {
            show(index + 1);
        } else if (e.key === 'Tab') {
            const focusable = Array.from(modal.querySelectorAll('button')).filter((b) => b.offsetParent !== null);
            if (!focusable.length) return;
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    }

    // Event delegation so it works regardless of when triggers render.
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-lightbox]');
        if (trigger) {
            e.preventDefault();
            open(trigger);
        }
    });

    closeEls.forEach((el) => el.addEventListener('click', close));
    prevBtn?.addEventListener('click', () => show(index - 1));
    nextBtn?.addEventListener('click', () => show(index + 1));
}

document.addEventListener('DOMContentLoaded', initLightbox);

export { initLightbox };
