import {
    Chart,
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
    Tooltip,
    DoughnutController,
    ArcElement,
} from 'chart.js';

Chart.register(
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Filler,
    Tooltip,
    DoughnutController,
    ArcElement
);

function cssVar(name) {
    return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
}

function inr(n) {
    return `₹${Math.round(n).toLocaleString('en-IN')}`;
}

function compact(n) {
    if (n >= 10000000) return `${(n / 10000000).toFixed(2)}Cr`;
    if (n >= 100000) return `${(n / 100000).toFixed(1)}L`;
    return `${Math.round(n / 1000)}K`;
}

export function sipCalculator() {
    // Kept as plain closure variables, NOT x-data properties: Chart.js
    // instances hold circular internal references (scales <-> chart <-> canvas),
    // and if Alpine wraps one in its reactive Proxy it blows the call stack on
    // creation and later corrupts Chart.js's internal state (the classic
    // "Cannot read properties of undefined (reading 'axis')" crash on update()).
    let growthChart = null;
    let donutChart = null;

    return {
        monthly: 10000,
        rate: 12,
        years: 10,

        init() {
            this.$nextTick(() => {
                this.renderGrowthChart();
                this.renderDonutChart();
            });
            this.$watch('monthly', () => this.update());
            this.$watch('rate', () => this.update());
            this.$watch('years', () => this.update());
        },

        get series() {
            const i = this.rate / 100 / 12;
            const fv = (n) => (i === 0 ? this.monthly * n : this.monthly * ((Math.pow(1 + i, n) - 1) / i) * (1 + i));
            const years = Math.max(1, Math.round(this.years));
            return Array.from({ length: years }, (_, k) => {
                const m = (k + 1) * 12;
                return { y: `Y${k + 1}`, invested: this.monthly * m, value: Math.round(fv(m)) };
            });
        },

        get invested() {
            return this.monthly * Math.max(1, Math.round(this.years * 12));
        },

        get total() {
            const n = Math.max(1, Math.round(this.years * 12));
            const i = this.rate / 100 / 12;
            return i === 0 ? this.monthly * n : this.monthly * ((Math.pow(1 + i, n) - 1) / i) * (1 + i);
        },

        get returns() {
            return this.total - this.invested;
        },

        fmt(n) {
            return inr(n);
        },

        renderGrowthChart() {
            const canvas = this.$refs.growthChart;
            if (!canvas) return;
            const brand = cssVar('--brand') || '#076550';
            const mint = cssVar('--mint') || '#0ecba1';
            const green200 = cssVar('--green-200') || '#b4d5c2';
            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.clientHeight || 208);
            gradient.addColorStop(0, `${mint}80`);
            gradient.addColorStop(1, `${mint}08`);

            const data = this.series;
            growthChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map((d) => d.y),
                    datasets: [
                        {
                            label: 'Invested',
                            data: data.map((d) => d.invested),
                            borderColor: green200,
                            borderWidth: 2,
                            fill: false,
                            pointRadius: 0,
                            tension: 0.3,
                        },
                        {
                            label: 'Total value',
                            data: data.map((d) => d.value),
                            borderColor: brand,
                            backgroundColor: gradient,
                            borderWidth: 2.5,
                            fill: true,
                            pointRadius: 0,
                            tension: 0.3,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: (ctx) => `${ctx.dataset.label}: ${inr(ctx.parsed.y)}` } },
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#4a5b52', font: { size: 11 } } },
                        y: { grid: { color: '#dbebe1' }, ticks: { color: '#4a5b52', font: { size: 11 }, callback: (v) => compact(v) } },
                    },
                },
            });
        },

        renderDonutChart() {
            const canvas = this.$refs.donutChart;
            if (!canvas) return;
            const brand = cssVar('--brand') || '#076550';
            const green200 = cssVar('--green-200') || '#b4d5c2';

            donutChart = new Chart(canvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Invested amount', 'Est. returns'],
                    datasets: [
                        {
                            data: [Math.round(this.invested), Math.max(0, Math.round(this.returns))],
                            backgroundColor: [green200, brand],
                            borderWidth: 0,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${inr(ctx.parsed)}` } },
                    },
                },
            });
        },

        update() {
            if (growthChart) {
                const data = this.series;
                growthChart.data.labels = data.map((d) => d.y);
                growthChart.data.datasets[0].data = data.map((d) => d.invested);
                growthChart.data.datasets[1].data = data.map((d) => d.value);
                growthChart.update();
            }
            if (donutChart) {
                donutChart.data.datasets[0].data = [Math.round(this.invested), Math.max(0, Math.round(this.returns))];
                donutChart.update();
            }
        },
    };
}
