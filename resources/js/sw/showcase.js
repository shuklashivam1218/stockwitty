import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
    Tooltip,
} from 'chart.js';

Chart.register(LineController, LineElement, PointElement, LinearScale, CategoryScale, Filler, Tooltip);

const SERIES_LABELS = ['Wk 1', 'Wk 2', 'Wk 3', 'Wk 4', 'Wk 5', 'Now'];

function cssVar(name) {
    return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
}

export function showcase() {
    return {
        companies: [],
        slug: null,
        side: 'Buy',
        qty: 1,
        paused: false,
        chart: null,

        init() {
            this.companies = JSON.parse(this.$el.dataset.companies || '[]');
            this.slug = this.companies[0]?.slug ?? null;
            this.qty = this.company.lot || 1;
            this.$nextTick(() => this.initChart());
        },

        get company() {
            return this.companies.find((c) => c.slug === this.slug) || this.companies[0] || {};
        },

        get total() {
            return (this.company.price || 0) * this.qty;
        },

        minInvestment(price, lot) {
            const total = price * lot;
            if (total >= 100000) return `₹${(total / 100000).toFixed(2)}L`;
            return `₹${total.toLocaleString('en-IN')}`;
        },

        select(slug) {
            this.slug = slug;
            this.qty = this.company.lot || 1;
            this.updateChart();
        },

        inc() {
            this.qty += this.company.lot || 1;
        },

        dec() {
            const lot = this.company.lot || 1;
            this.qty = Math.max(lot, this.qty - lot);
        },

        initChart() {
            const canvas = this.$refs.priceChart;
            if (!canvas) return;

            const mint = cssVar('--mint') || '#0ecba1';
            const brand = cssVar('--brand') || '#076550';

            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.clientHeight || 160);
            gradient.addColorStop(0, `${mint}55`);
            gradient.addColorStop(1, `${mint}00`);

            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: SERIES_LABELS.slice(0, (this.company.series || []).length),
                    datasets: [
                        {
                            data: this.company.series || [],
                            borderColor: brand,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3,
                            pointBackgroundColor: mint,
                            pointBorderWidth: 0,
                            borderWidth: 3,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: { label: (ctx) => `₹${ctx.parsed.y}` },
                        },
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#4a5b52', font: { weight: 600, size: 12 } } },
                        y: {
                            grid: { color: '#dbebe1' },
                            ticks: {
                                color: '#4a5b52',
                                font: { weight: 600, size: 12 },
                                callback: (v) => `₹${v}`,
                            },
                        },
                    },
                },
            });
        },

        updateChart() {
            if (!this.chart) return;
            const series = this.company.series || [];
            this.chart.data.labels = SERIES_LABELS.slice(0, series.length);
            this.chart.data.datasets[0].data = series;
            this.chart.update();
        },
    };
}
