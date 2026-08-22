function formatNumber(value, decimals) {
    return value.toLocaleString('en-IN', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}

// Usage: x-countup="{ value: company.price, prefix: '₹', decimals: 0, duration: 900 }"
// Animates the element's text between the previous and next value whenever
// `value` changes reactively (company switch, etc.) — not scroll-triggered,
// unlike the static homepage CountUp (see reveal.js / <x-sw.count-up>).
export function registerCountupDirective(Alpine) {
    Alpine.directive('countup', (el, { expression }, { effect, evaluateLater }) => {
        const getOpts = evaluateLater(expression);
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let from = 0;
        let first = true;

        effect(() => {
            getOpts((result) => {
                const opts = typeof result === 'object' && result !== null ? result : { value: result };
                const target = Number(opts.value ?? 0);
                const decimals = opts.decimals ?? 0;
                const prefix = opts.prefix ?? '';
                const suffix = opts.suffix ?? '';
                const duration = opts.duration ?? 900;

                if (!isFinite(target)) {
                    el.textContent = `${prefix}—${suffix}`;
                    return;
                }

                if (reduce) {
                    el.textContent = `${prefix}${formatNumber(target, decimals)}${suffix}`;
                    from = target;
                    first = false;
                    return;
                }

                const start = performance.now();
                const startValue = first ? 0 : from;
                first = false;

                function tick(now) {
                    const t = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - t, 3);
                    const value = startValue + (target - startValue) * eased;
                    el.textContent = `${prefix}${formatNumber(value, decimals)}${suffix}`;
                    if (t < 1) requestAnimationFrame(tick);
                    else from = target;
                }

                requestAnimationFrame(tick);
            });
        });
    });
}
