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

function cssVar(name) {
    return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
}

export function nsePriceChart() {
    return {
        period: '6M',
        series: {},
        chart: null,

        init() {
            this.series = JSON.parse(this.$el.dataset.series || '{}');
            this.$nextTick(() => this.renderChart());
        },

        renderChart() {
            const canvas = this.$refs.chart;
            if (!canvas) return;

            const mint = cssVar('--mint') || '#0ecba1';
            const brand = cssVar('--brand') || '#076550';
            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.clientHeight || 300);
            gradient.addColorStop(0, `${mint}70`);
            gradient.addColorStop(1, `${mint}00`);

            const data = this.series[this.period] || [];

            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map((d) => d.label),
                    datasets: [
                        {
                            data: data.map((d) => d.price),
                            borderColor: brand,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3,
                            pointBackgroundColor: mint,
                            pointBorderWidth: 0,
                            borderWidth: 2.5,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: (ctx) => `₹${ctx.parsed.y}` } },
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#4a5b52', font: { weight: 600, size: 12 } } },
                        y: {
                            grid: { color: '#dbebe1' },
                            ticks: { color: '#4a5b52', font: { weight: 600, size: 12 }, callback: (v) => `₹${v}` },
                        },
                    },
                },
            });
        },

        updateChart() {
            if (!this.chart) return;
            const data = this.series[this.period] || [];
            this.chart.data.labels = data.map((d) => d.label);
            this.chart.data.datasets[0].data = data.map((d) => d.price);
            this.chart.update();
        },
    };
}
