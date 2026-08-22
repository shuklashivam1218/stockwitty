export function unlistedShares() {
    return {
        companies: [],
        filter: 'All',
        sector: 'All sectors',
        q: '',
        sort: 'Trending',

        init() {
            this.companies = JSON.parse(this.$el.dataset.companies || '[]');
        },

        get list() {
            let rows = this.companies.filter((c) => {
                if (this.filter === 'DRHP-Filed' && !c.drhp) return false;
                if (this.filter !== 'All' && this.filter !== 'DRHP-Filed' && c.tag !== this.filter) return false;
                if (this.sector !== 'All sectors' && c.sector !== this.sector) return false;
                if (this.q && !c.name.toLowerCase().includes(this.q.toLowerCase())) return false;
                return true;
            });
            rows = [...rows];
            if (this.sort === 'Price: high to low') rows.sort((a, b) => b.price - a.price);
            if (this.sort === 'Price: low to high') rows.sort((a, b) => a.price - b.price);
            if (this.sort === 'WittyScore') rows.sort((a, b) => b.wittyScore - a.wittyScore);
            return rows;
        },

        minInvestment(price, lot) {
            const total = price * lot;
            if (total >= 100000) return `₹${(total / 100000).toFixed(2)}L`;
            return `₹${total.toLocaleString('en-IN')}`;
        },
    };
}
