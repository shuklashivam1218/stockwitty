export function tocSpy(ids) {
    return {
        active: ids[0] || '',

        init() {
            const nodes = ids.map((id) => document.getElementById(id)).filter(Boolean);
            if (!nodes.length) return;

            const visible = new Set();
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((e) => {
                        if (e.isIntersecting) visible.add(e.target.id);
                        else visible.delete(e.target.id);
                    });
                    const first = ids.find((id) => visible.has(id));
                    if (first) {
                        this.active = first;
                        return;
                    }
                    let candidate = ids[0];
                    for (const n of nodes) {
                        if (n.getBoundingClientRect().top - 96 <= 0) candidate = n.id;
                    }
                    this.active = candidate;
                },
                { rootMargin: '-96px 0px -65% 0px', threshold: 0 }
            );

            nodes.forEach((n) => observer.observe(n));
        },

        scrollToSection(id) {
            const el = document.getElementById(id);
            if (!el) return;
            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const top = el.getBoundingClientRect().top + window.scrollY - 96;
            window.scrollTo({ top, behavior: reduce ? 'auto' : 'smooth' });
            history.replaceState(null, '', `#${id}`);
        },
    };
}
