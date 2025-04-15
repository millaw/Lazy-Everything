document.addEventListener('DOMContentLoaded', function () {
    const lazyFiles = document.querySelectorAll('[data-lazy-file]');
    const lazyFrames = document.querySelectorAll('iframe[data-src], video[data-src]');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                if (el.dataset.href) {
                    el.href = el.dataset.href;
                } else if (el.dataset.src) {
                    el.src = el.dataset.src;
                }
                observer.unobserve(el);
            }
        });
    });

    [...lazyFiles, ...lazyFrames].forEach(el => observer.observe(el));
});
