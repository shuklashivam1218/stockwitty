export function fixedDeposits() {
    return {
        fds: [],
        type: 'All',
        tenure: 'Any tenure',

        init() {
            this.fds = JSON.parse(this.$el.dataset.fds || '[]');
        },

        get list() {
            return this.fds.filter((f) => {
                if (this.type !== 'All' && f.type !== this.type) return false;
                if (this.tenure === 'Up to 2 years' && !/(546|1001|1 year|2 year)/.test(f.tenure)) return false;
                if (this.tenure === '3 years and above' && !/(5 years|44 months|60 months)/.test(f.tenure)) return false;
                return true;
            });
        },
    };
}
