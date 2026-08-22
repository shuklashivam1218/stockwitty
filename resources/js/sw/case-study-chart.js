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

export function caseStudyChart() {
    return {
        points: [],

        init() {
            this.points = JSON.parse(this.$el.dataset.chart || '[]');
            this.$nextTick(() => this.renderChart());
        },

        renderChart() {
            const canvas = this.$refs.chart;
            if (!canvas) return;

            const mint = cssVar('--mint') || '#0ecba1';
            const brand = cssVar('--brand') || '#076550';
            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.clientHeight || 240);
            gradient.addColorStop(0, `${mint}70`);
            gradient.addColorStop(1, `${mint}00`);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.points.map((p) => p.label),
                    datasets: [
                        {
                            data: this.points.map((p) => p.value),
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
                        tooltip: { callbacks: { label: (ctx) => ctx.parsed.y } },
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#4a5b52', font: { weight: 600, size: 11 } } },
                        y: { grid: { color: '#dbebe1' }, ticks: { color: '#4a5b52', font: { weight: 600, size: 11 } } },
                    },
                },
            });
        },
    };
}
