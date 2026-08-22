function emailOk(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim());
}

function mobileOk(v) {
    return /^[0-9]{10}$/.test(v.replace(/\D/g, ''));
}

function fileName(study) {
    return `StockWitty-case-study-${study.slug}.pdf`;
}

function triggerBlob(blob, name) {
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = name;
    document.body.appendChild(a);
    a.click();
    a.remove();
    setTimeout(() => URL.revokeObjectURL(url), 4000);
}

function textFallback(study) {
    const lines = [
        'StockWitty — Invest Smart, Stay Witty.',
        'Case study: ' + study.title,
        '',
        `Investor type: ${study.meta.investor}`,
        `Holding period: ${study.meta.holding}`,
        `Products used: ${study.meta.products}`,
        `Region: ${study.meta.region}`,
        '',
        'At a glance (illustrative)',
        ...study.table.map((r) => `- ${r.row}: ${r.before} -> ${r.after}`),
        '',
        study.disclaimer,
        'StockWitty is a distributor of unlisted shares, not a SEBI-registered investment adviser.',
    ];
    triggerBlob(new Blob([lines.join('\n')], { type: 'application/pdf' }), fileName(study));
}

async function makePdf(study) {
    try {
        const { jsPDF } = await import('jspdf');
        const doc = new jsPDF({ unit: 'pt', format: 'a4' });
        const W = doc.internal.pageSize.getWidth();
        const M = 48;

        doc.setFillColor(7, 101, 80);
        doc.rect(0, 0, W, 86, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(20);
        doc.text('StockWitty', M, 44);
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);
        doc.setTextColor(17, 241, 196);
        doc.text('Invest Smart, Stay Witty.  ·  Case study', M, 62);

        let y = 128;
        doc.setTextColor(26, 26, 26);
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(17);
        const titleLines = doc.splitTextToSize(study.title, W - M * 2);
        doc.text(titleLines, M, y);
        y += 24 * titleLines.length;

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10.5);
        doc.setTextColor(90, 100, 94);
        const summary = doc.splitTextToSize(study.summary, W - M * 2);
        doc.text(summary, M, y);
        y += 14 * summary.length + 16;

        const meta = [
            { k: 'Investor type', v: study.meta.investor },
            { k: 'Holding period', v: study.meta.holding },
            { k: 'Products used', v: study.meta.products },
            { k: 'Region', v: study.meta.region },
        ];
        const cellW = (W - M * 2) / 4;
        doc.setDrawColor(219, 235, 225);
        doc.setFillColor(231, 241, 235);
        doc.rect(M, y, W - M * 2, 48, 'FD');
        meta.forEach(({ k, v }, i) => {
            const x = M + i * cellW + 10;
            doc.setFontSize(7.5);
            doc.setTextColor(7, 101, 80);
            doc.text(k.toUpperCase(), x, y + 18);
            doc.setFontSize(9.5);
            doc.setTextColor(26, 26, 26);
            doc.text(doc.splitTextToSize(v, cellW - 18), x, y + 33);
        });
        y += 74;

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.setTextColor(26, 26, 26);
        doc.text('At a glance (illustrative)', M, y);
        y += 16;

        const colX = [M, M + 230, M + 380];
        doc.setFontSize(8.5);
        doc.setTextColor(7, 101, 80);
        doc.text('MEASURE', colX[0], y);
        doc.text('BEFORE', colX[1], y);
        doc.text('AFTER', colX[2], y);
        y += 6;
        doc.setDrawColor(180, 213, 194);
        doc.line(M, y, W - M, y);
        y += 14;

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(9.5);
        for (const r of study.table) {
            const a = doc.splitTextToSize(r.row, 210);
            const b = doc.splitTextToSize(r.before, 140);
            const c = doc.splitTextToSize(r.after, 150);
            const h = 12 * Math.max(a.length, b.length, c.length);
            doc.setTextColor(26, 26, 26);
            doc.text(a, colX[0], y);
            doc.setTextColor(90, 100, 94);
            doc.text(b, colX[1], y);
            doc.setTextColor(7, 101, 80);
            doc.text(c, colX[2], y);
            y += h + 8;
            doc.setDrawColor(231, 241, 235);
            doc.line(M, y - 6, W - M, y - 6);
        }

        y += 10;
        doc.setFont('helvetica', 'italic');
        doc.setFontSize(9);
        doc.setTextColor(90, 100, 94);
        const q = doc.splitTextToSize(`"${study.quote.text}" — ${study.quote.author}`, W - M * 2);
        doc.text(q, M, y);
        y += 13 * q.length + 18;

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(8.5);
        doc.setTextColor(146, 64, 14);
        doc.text(doc.splitTextToSize(study.disclaimer, W - M * 2), M, y);
        y += 20;
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(120, 128, 124);
        doc.setFontSize(8);
        doc.text(
            doc.splitTextToSize(
                'StockWitty is a distributor of unlisted shares and not a SEBI-registered investment adviser. Figures shown are illustrative and for layout purposes only. Verify all prices, ISINs and documents before you invest. stockswitty.com',
                W - M * 2
            ),
            M,
            y
        );

        doc.save(fileName(study));
    } catch {
        textFallback(study);
    }
}

export function downloadGate() {
    return {
        open: false,
        busy: false,
        done: false,
        form: { name: '', email: '', mobile: '', interest: 'Unlisted shares', consent: false },
        study: null,

        init() {
            this.study = JSON.parse(this.$el.dataset.study || '{}');
        },

        get valid() {
            return (
                this.form.name.trim().length >= 2 &&
                emailOk(this.form.email) &&
                mobileOk(this.form.mobile) &&
                this.form.consent
            );
        },

        async submit() {
            if (!this.valid || this.busy) return;
            this.busy = true;
            await makePdf(this.study);
            this.busy = false;
            this.done = true;
            setTimeout(() => {
                this.open = false;
                this.done = false;
                this.form = { name: '', email: '', mobile: '', interest: 'Unlisted shares', consent: false };
            }, 2200);
        },
    };
}
