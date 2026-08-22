const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function initReveal() {
    const targets = document.querySelectorAll('[data-reveal]');
    if (!targets.length) return;

    if (reducedMotion) {
        targets.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -80px 0px', threshold: 0.1 }
    );

    targets.forEach((el) => observer.observe(el));
}

function easeOutCubic(t) {
    return 1 - Math.pow(1 - t, 3);
}

function runCountUp(el) {
    const to = parseFloat(el.dataset.to || '0');
    const decimals = parseInt(el.dataset.decimals || '0', 10);
    const prefix = el.dataset.prefix || '';
    const suffix = el.dataset.suffix || '';
    const duration = reducedMotion ? 0 : parseInt(el.dataset.duration || '1200', 10);

    if (duration === 0) {
        el.textContent = `${prefix}${to.toFixed(decimals)}${suffix}`;
        return;
    }

    const start = performance.now();

    function tick(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const value = to * easeOutCubic(progress);
        el.textContent = `${prefix}${value.toFixed(decimals)}${suffix}`;
        if (progress < 1) requestAnimationFrame(tick);
    }

    requestAnimationFrame(tick);
}

export function initCountUp() {
    const targets = document.querySelectorAll('[data-countup]');
    if (!targets.length) return;

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    runCountUp(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -80px 0px', threshold: 0.1 }
    );

    targets.forEach((el) => observer.observe(el));
}
