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

export function reliancePage() {
    // Kept as plain closure variables, NOT x-data properties: Chart.js
    // instances hold circular internal references (scales <-> chart <-> canvas),
    // and if Alpine wraps one in its reactive Proxy it blows the call stack on
    // creation and later corrupts Chart.js's internal state (the classic
    // "Cannot read properties of undefined (reading 'axis')" crash on update()).
    let perfChart = null;
    let pieChart = null;

    return {
        tab: 'Performance',

        init() {
            this.$nextTick(() => this.renderPerfChart());
        },

        selectTab(t) {
            this.tab = t;
            this.$nextTick(() => {
                if (t === 'Performance' && !perfChart) this.renderPerfChart();
                if (t === 'Shareholding' && !pieChart) this.renderPieChart();
                if (perfChart) perfChart.resize();
                if (pieChart) pieChart.resize();
            });
        },

        renderPerfChart() {
            const canvas = this.$refs.perfChart;
            if (!canvas) return;
            const series = JSON.parse(this.$refs.perfData.dataset.series);

            const mint = cssVar('--mint') || '#0ecba1';
            const brand = cssVar('--brand') || '#076550';
            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.clientHeight || 280);
            gradient.addColorStop(0, `${mint}80`);
            gradient.addColorStop(1, `${mint}05`);

            perfChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: series.map((s) => s.m),
                    datasets: [
                        {
                            data: series.map((s) => s.p),
                            borderColor: brand,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3,
                            pointBackgroundColor: mint,
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
                        y: { grid: { color: '#dbebe1' }, ticks: { color: '#4a5b52', font: { weight: 600, size: 12 }, callback: (v) => `₹${v}` } },
                    },
                },
            });
        },

        renderPieChart() {
            const canvas = this.$refs.pieChart;
            if (!canvas) return;
            const holding = JSON.parse(this.$refs.pieData.dataset.holding);
            const colors = [cssVar('--brand'), cssVar('--mint'), cssVar('--green-200'), cssVar('--beige')];

            pieChart = new Chart(canvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: holding.map((h) => h.name),
                    datasets: [
                        {
                            data: holding.map((h) => h.value),
                            backgroundColor: colors,
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
                        tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.parsed}%` } },
                    },
                },
            });
        },
    };
}
