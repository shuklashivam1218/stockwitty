function ipoProbability(slug, drhp) {
    if (drhp) return 'High';
    return ['swiggy', 'razorpay', 'cdsl-unlisted'].includes(slug) ? 'Medium' : 'Low';
}

export function screener() {
    return {
        companies: [],
        nl: '',
        sector: 'All sectors',
        maxPrice: 15000,
        minScore: 0,
        tag: 'Any',
        ipo: 'Any',

        init() {
            this.companies = JSON.parse(this.$el.dataset.companies || '[]');
        },

        ipoProbability(c) {
            return ipoProbability(c.slug, c.drhp);
        },

        get rows() {
            const q = this.nl.toLowerCase();
            return this.companies.filter((c) => {
                if (this.sector !== 'All sectors' && c.sector !== this.sector) return false;
                if (c.price > this.maxPrice) return false;
                if (c.wittyScore < this.minScore) return false;
                if (this.tag !== 'Any' && c.tag !== this.tag) return false;
                const prob = ipoProbability(c.slug, c.drhp);
                if (this.ipo !== 'Any' && prob !== this.ipo) return false;
                if (q) {
                    const words = q.split(/[^a-z0-9₹.]+/).filter(Boolean);
                    const hay = `${c.name} ${c.sector} ${c.tag} ${prob} ipo`.toLowerCase();
                    const numeric = q.match(/(\d[\d,]*)/);
                    if (numeric) {
                        const limit = Number(numeric[1].replace(/,/g, ''));
                        if (/under|below|less/.test(q) && c.price > limit) return false;
                        if (/above|over|more/.test(q) && c.price < limit) return false;
                    }
                    if (/fintech/.test(q) && c.sector !== 'Fintech') return false;
                    if (/(ipo likely|ipo soon|drhp)/.test(q) && prob !== 'High') return false;
                    const textWords = words.filter(
                        (w) =>
                            !/^\d/.test(w) &&
                            !['under', 'below', 'above', 'over', 'less', 'more', 'with', 'an', 'and', 'ipo', 'likely', 'profitable', 'soon'].includes(w)
                    );
                    if (textWords.length && !textWords.some((w) => hay.includes(w))) return false;
                }
                return true;
            });
        },

        reset() {
            this.nl = '';
            this.sector = 'All sectors';
            this.maxPrice = 15000;
            this.minScore = 0;
            this.tag = 'Any';
            this.ipo = 'Any';
        },
    };
}
