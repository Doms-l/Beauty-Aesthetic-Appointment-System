{{-- =====================================================
     INCOME ANALYTICS (admin dashboard)
     Cancelled appointments are never counted here.
     ===================================================== --}}

@php
    $peso = fn ($n) => '₱' . number_format((float) $n, 2);
@endphp

<div class="panel income-panel" id="income-analytics">

    <div class="section-heading inline-heading">

        <div>
            <span class="eyebrow">ANALYTICS</span>
            <h2>Income Summary</h2>
            <p>
                Income from all non-cancelled appointments.
                Cancelled and archived appointments are not counted.
            </p>
        </div>

        <button type="button" class="income-print-btn" id="income-print">
            🖨 Print summary
        </button>

    </div>


    {{-- Summary cards --}}
    <div class="income-cards">

        <div class="income-card">
            <span>This week</span>
            <strong>{{ $peso($incomeAnalytics['summary']['week']) }}</strong>
        </div>

        <div class="income-card">
            <span>This month</span>
            <strong>{{ $peso($incomeAnalytics['summary']['month']) }}</strong>
        </div>

        <div class="income-card">
            <span>This quarter</span>
            <strong>{{ $peso($incomeAnalytics['summary']['quarter']) }}</strong>
        </div>

        <div class="income-card">
            <span>This year</span>
            <strong>{{ $peso($incomeAnalytics['summary']['year']) }}</strong>
        </div>

    </div>

    <div class="income-split">
        <span><i class="dot dot-earned"></i> Earned (completed): <b>{{ $peso($incomeAnalytics['summary']['earned']) }}</b></span>
        <span><i class="dot dot-expected"></i> Expected (pending / confirmed): <b>{{ $peso($incomeAnalytics['summary']['expected']) }}</b></span>
        <span>All time: <b>{{ $peso($incomeAnalytics['summary']['all']) }}</b></span>
    </div>


    {{-- Weekly / Monthly / Quarterly / Annual chart --}}
    <div class="income-chart-head">

        <h3 id="income-chart-title">Income per month</h3>

        <div class="income-tabs" role="tablist">
            <button type="button" data-period="weekly">Weekly</button>
            <button type="button" data-period="monthly" class="active">Monthly</button>
            <button type="button" data-period="quarterly">Quarterly</button>
            <button type="button" data-period="annual">Annually</button>
        </div>

    </div>

    <div class="income-chart" id="income-chart"></div>

    <div class="income-legend">
        <span><i class="dot dot-earned"></i> Earned</span>
        <span><i class="dot dot-expected"></i> Expected</span>
        <span class="income-note">Hover or tap a bar for details</span>
    </div>

</div>


{{-- Print preview pop-up: look at the sheet first, then download --}}
<div class="pdf-overlay" id="pdf-modal" aria-hidden="true">
    <div class="pdf-box" role="dialog" aria-modal="true" aria-label="Income summary print preview">

        <div class="pdf-bar">
            <h3>Print preview</h3>
            <div class="pdf-actions">
                <button type="button" id="pdf-save">⬇ Download PDF</button>
                <button type="button" id="pdf-print">🖨 Print</button>
                <button type="button" id="pdf-close" class="ghost">Close</button>
            </div>
        </div>

        <p class="pdf-note" id="pdf-note">Check the sheet below, then choose Download PDF or Print.</p>

        <div class="pdf-scroll">
            <div class="pdf-paper" id="pdf-paper"></div>
        </div>

    </div>
</div>

<style>
    .income-panel { margin-top: 24px; }

    .income-print-btn {
        border: 0;
        cursor: pointer;
        font: inherit;
        font-weight: 700;
        padding: 12px 22px;
        border-radius: 999px;
        color: #62444D;
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
        white-space: nowrap;
        align-self: flex-start;
        flex: none;
        height: auto;
        line-height: 1.2;
    }
    .income-print-btn:hover { filter: brightness(1.05); }

    .income-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin: 22px 0 14px;
    }
    .income-card {
        padding: 18px 20px;
        border-radius: 18px;
        background: var(--surface-soft);
        border: 1px solid var(--line);
    }
    .income-card span {
        display: block;
        font-size: .85rem;
        color: var(--text-muted);
        margin-bottom: 6px;
    }
    .income-card strong {
        font-size: 1.45rem;
        color: var(--heading);
    }

    .income-split {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 26px;
        font-size: .92rem;
        color: var(--text);
        margin-bottom: 28px;
    }
    .income-split b { color: var(--heading); }

    .dot {
        display: inline-block;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .dot-earned   { background: #E8AECF; }
    .dot-expected { background: #D6B36A; }

    .income-chart-head {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }
    .income-chart-head h3 { margin: 0; color: var(--heading); }

    .income-tabs {
        display: flex;
        gap: 6px;
        padding: 4px;
        border-radius: 999px;
        background: var(--surface-soft);
        border: 1px solid var(--line);
    }
    .income-tabs button {
        border: 0;
        cursor: pointer;
        font: inherit;
        font-weight: 600;
        font-size: .9rem;
        padding: 8px 16px;
        border-radius: 999px;
        background: transparent;
        color: var(--text);
    }
    .income-tabs button.active {
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
        color: #62444D;
    }

    .income-chart {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        height: 280px;
        padding: 10px 4px 0;
        border-bottom: 1px solid var(--line);
        overflow-x: auto;
    }
    .income-col {
        flex: 1 1 0;
        min-width: 44px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
        position: relative;
    }
    .income-col .amount {
        font-size: .72rem;
        color: var(--text-muted);
        margin-bottom: 4px;
        white-space: nowrap;
    }
    .income-bar {
        width: 100%;
        max-width: 64px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        border-radius: 10px 10px 0 0;
        overflow: hidden;
        transition: height .5s ease;
        cursor: pointer;
    }
    .income-bar .earned   { background: linear-gradient(180deg, #F3BCD2, #E8AECF); }
    .income-bar .expected { background: #D6B36A; }
    .income-col.current .income-bar { outline: 2px solid var(--pink); }

    .income-labels {
        display: flex;
        gap: 10px;
        padding: 8px 4px 0;
        overflow-x: auto;
    }
    .income-labels span {
        flex: 1 1 0;
        min-width: 44px;
        text-align: center;
        font-size: .78rem;
        color: var(--text-muted);
    }

    .income-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 22px;
        margin-top: 14px;
        font-size: .85rem;
        color: var(--text);
    }
    .income-note { color: var(--text-muted); }

    .income-empty {
        width: 100%;
        align-self: center;
        text-align: center;
        color: var(--text-muted);
    }


    @media (max-width: 760px) {
        .income-cards { grid-template-columns: repeat(2, 1fr); }
    }

    /* ---------- PDF PREVIEW WINDOW ---------- */
    .pdf-overlay {
        position: fixed;
        inset: 0;
        z-index: 100001;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(10, 6, 9, .72);
        backdrop-filter: blur(4px);
    }
    .pdf-overlay.open { display: flex; }

    .pdf-box {
        width: min(980px, 100%);
        height: min(92vh, 900px);
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 20px 22px;
        border-radius: 24px;
        background: var(--surface, #fff);
        color: var(--text, #222);
        border: 1px solid var(--line, #ddd);
        box-shadow: 0 30px 90px rgba(0, 0, 0, .45);
    }
    .pdf-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .pdf-bar h3 { margin: 0; color: var(--heading, #62444D); }
    .pdf-actions { display: flex; flex-wrap: wrap; gap: 10px; }
    .pdf-actions button {
        border: 0;
        cursor: pointer;
        font: inherit;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 999px;
        color: #62444D;
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
    }
    .pdf-actions button.ghost {
        background: transparent;
        color: var(--heading, #62444D);
        border: 1px solid var(--line, #ccc);
    }
    .pdf-actions button:disabled { opacity: .45; cursor: not-allowed; }
    .pdf-note { margin: 0; font-size: .9rem; color: var(--text-muted, #7d7074); }
    .pdf-note.ok { color: #2f8a57; font-weight: 600; }
    .pdf-scroll {
        flex: 1;
        overflow: auto;
        padding: 18px;
        border-radius: 14px;
        background: #cfc6ca;
    }
    html.dark-mode .pdf-scroll { background: #2a2326; }

    .pdf-paper {
        width: 100%;
        max-width: 794px;
        margin: 0 auto;
        padding: 40px 44px;
        background: #fff;
        color: #28201f;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        box-shadow: 0 6px 24px rgba(0, 0, 0, .25);
    }
    .pdf-paper .sheet-head { display: flex; align-items: center; gap: 16px; padding-bottom: 14px; border-bottom: 2px solid #F3BCD2; }
    .pdf-paper .sheet-head img { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; }
    .pdf-paper .sheet-head h1 { margin: 0; font-size: 20px; color: #62444D; }
    .pdf-paper .sheet-head p { margin: 3px 0 0; font-size: 12px; color: #62444D; }
    .pdf-paper .sheet-meta { margin-left: auto; text-align: right; font-size: 10px; color: #786e72; line-height: 1.6; }
    .pdf-paper .sheet-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 18px 0 12px; }
    .pdf-paper .sheet-card { padding: 10px 12px; border: 1px solid #F3BCD2; border-radius: 8px; background: #fff1f7; }
    .pdf-paper .sheet-card span { display: block; font-size: 10px; color: #786e72; }
    .pdf-paper .sheet-card b { display: block; margin-top: 4px; font-size: 12.5px; color: #62444D; }
    .pdf-paper .sheet-line { margin: 4px 0; font-size: 11px; }
    .pdf-paper .sheet-small { font-size: 10px; color: #786e72; }
    .pdf-paper h2 { margin: 22px 0 8px; font-size: 14px; color: #62444D; }
    .pdf-paper table { width: 100%; border-collapse: collapse; font-size: 11px; }
    .pdf-paper th, .pdf-paper td { border: 1px solid #e1d2da; padding: 6px 8px; text-align: left; color: #28201f; background: #fff; }
    .pdf-paper th { background: #F3BCD2; color: #62444D; }
    .pdf-paper td.num, .pdf-paper th.num { text-align: right; }
    .pdf-paper tfoot td { background: #fff1f7; color: #62444D; font-weight: 700; }
    .pdf-paper .sheet-foot { margin-top: 26px; text-align: center; font-size: 9px; color: #968c90; }

    @media (max-width: 760px) {
        .pdf-paper { padding: 22px 16px; }
        .pdf-paper .sheet-cards { grid-template-columns: repeat(2, 1fr); }
        .pdf-paper .sheet-head { flex-wrap: wrap; }
        .pdf-paper .sheet-meta { margin-left: 0; text-align: left; }
    }


    /* keep the sheet black-on-white in light AND dark mode */
    .pdf-paper h1, .pdf-paper h2, .pdf-paper p, .pdf-paper span, .pdf-paper b, .pdf-paper div { font-family: Arial, Helvetica, sans-serif !important; letter-spacing: normal !important; text-transform: none !important; }
    .pdf-paper h1, .pdf-paper h2 { color: #62444D !important; font-weight: 700 !important; }
    .pdf-paper h2 { font-size: 14px !important; margin: 22px 0 8px !important; }
    .pdf-paper .sheet-head h1 { font-size: 20px !important; margin: 0 !important; }
    .pdf-paper th, .pdf-paper td { text-transform: none !important; letter-spacing: normal !important; font-size: 11px !important; }
    .pdf-paper td { color: #28201f !important; background: #fff !important; }
    .pdf-paper th { color: #62444D !important; background: #F3BCD2 !important; }
    .pdf-paper tfoot td { color: #62444D !important; background: #fff1f7 !important; }
    .pdf-paper p, .pdf-paper .sheet-line { color: #28201f !important; }
    .pdf-paper .sheet-small, .pdf-paper .sheet-meta { color: #786e72 !important; }
    .pdf-paper .sheet-head p { color: #62444D !important; }
    .pdf-paper .sheet-card span { color: #786e72 !important; }
    .pdf-paper .sheet-card b { color: #62444D !important; }

    /* ---------- PRINT: only the sheet is printed ---------- */
    @media print {

        @page { size: A4; margin: 12mm; }

        html, body {
            background: #fff !important;
            height: auto !important;
            overflow: visible !important;
        }

        /* everything on the page except the pop-up is removed from the print */
        body > *:not(#pdf-modal) { display: none !important; }

        #pdf-modal,
        #pdf-modal .pdf-box,
        #pdf-modal .pdf-scroll {
            position: static !important;
            display: block !important;
            width: 100% !important;
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
            padding: 0 !important;
            margin: 0 !important;
            background: none !important;
            border: 0 !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
        }

        #pdf-modal .pdf-bar,
        #pdf-modal .pdf-note { display: none !important; }

        .pdf-paper {
            max-width: none !important;
            width: 100% !important;
            padding: 0 !important;
            box-shadow: none !important;
        }

        .pdf-paper tr { break-inside: avoid; }
        .pdf-paper h2 { break-after: avoid; }
    }
</style>


<script>
(function () {

    const DATA = @json($incomeAnalytics['periods']);
    const SUMMARY = @json($incomeAnalytics['summary']);
    const GENERATED = @json($incomeAnalytics['generated_at']);
    const LOGO = @json(asset('images/round.png'));

    const NAMES = {
        weekly: 'Weekly',
        monthly: 'Monthly',
        quarterly: 'Quarterly',
        annual: 'Annual'
    };

    const TITLES = {
        weekly: 'Income per week',
        monthly: 'Income per month',
        quarterly: 'Income per quarter',
        annual: 'Income per year'
    };

    const chart = document.getElementById('income-chart');
    const title = document.getElementById('income-chart-title');
    const tabs  = document.querySelectorAll('.income-tabs button');

    let current = 'monthly';

    function peso(n) {
        return '₱' + Number(n).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // the PDF font cannot draw the ₱ sign, so the PDF uses "PHP"
    function php(n) {
        return 'PHP ' + Number(n).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function short(n) {
        n = Number(n);
        if (n >= 1000000) { return '₱' + (n / 1000000).toFixed(1) + 'M'; }
        if (n >= 1000)    { return '₱' + (n / 1000).toFixed(1) + 'k'; }
        return '₱' + n.toFixed(0);
    }

    function render(period) {

        current = period;

        const data = DATA[period];
        const max  = Math.max.apply(null, data.buckets.map(function (b) { return b.income; }));

        title.textContent = TITLES[period] + ' — ' + data.title;

        tabs.forEach(function (t) {
            t.classList.toggle('active', t.dataset.period === period);
        });

        chart.innerHTML = '';

        const old = document.getElementById('income-labels');
        if (old) { old.remove(); }

        if (max <= 0) {
            chart.innerHTML =
                '<div class="income-empty">No income recorded for this period yet.</div>';
            return;
        }

        data.buckets.forEach(function (b) {

            const col = document.createElement('div');
            col.className = 'income-col' + (b.current ? ' current' : '');

            const height = (b.income / max) * 100;
            const earnedPct = b.income > 0 ? (b.earned / b.income) * 100 : 0;

            col.innerHTML =
                '<span class="amount">' + (b.income > 0 ? short(b.income) : '') + '</span>' +
                '<div class="income-bar" style="height:' + (b.income > 0 ? Math.max(height * 0.82, 2) : 0) + '%"' +
                ' title="' + b.label + '&#10;Total: ' + peso(b.income) +
                '&#10;Earned: ' + peso(b.earned) +
                '&#10;Expected: ' + peso(b.expected) +
                '&#10;Bookings: ' + b.bookings + '">' +
                    '<div class="expected" style="height:' + (100 - earnedPct) + '%"></div>' +
                    '<div class="earned" style="height:' + earnedPct + '%"></div>' +
                '</div>';

            chart.appendChild(col);
        });

        const labels = document.createElement('div');
        labels.className = 'income-labels';
        labels.id = 'income-labels';

        data.buckets.forEach(function (b) {
            const s = document.createElement('span');
            s.textContent = b.label;
            labels.appendChild(s);
        });

        chart.parentNode.insertBefore(labels, chart.nextSibling);
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            render(tab.dataset.period);
        });
    });

    render(current);


    // =====================================================
    // PDF: build → preview → SAVE → then PRINT
    // =====================================================

    const modal    = document.getElementById('pdf-modal');
    const paper    = document.getElementById('pdf-paper');
    const btnSave  = document.getElementById('pdf-save');
    const btnPrint = document.getElementById('pdf-print');
    const note     = document.getElementById('pdf-note');
    const openBtn  = document.getElementById('income-print');

    const NOTE_LOCKED = 'Check the sheet below, then choose Download PDF or Print.';
    const NOTE_SAVED  = '✔ Downloaded to your laptop. You can still print it too.';

    let saved = false;

    function esc(text) {
        return String(text).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    // ---------- the on-screen print sheet (same content as the PDF) ----------
    function buildPaper(period) {

        const data = DATA[period];

        const periodRows = data.buckets.map(function (b) {
            return '<tr><td>' + esc(b.label) + '</td>' +
                '<td class="num">' + b.bookings + '</td>' +
                '<td class="num">' + php(b.earned) + '</td>' +
                '<td class="num">' + php(b.expected) + '</td>' +
                '<td class="num">' + php(b.income) + '</td></tr>';
        }).join('');

        const serviceRows = data.services.length
            ? data.services.map(function (s) {
                return '<tr><td>' + esc(s.name) + '</td>' +
                    '<td class="num">' + s.bookings + '</td>' +
                    '<td class="num">' + php(s.income) + '</td></tr>';
            }).join('')
            : '<tr><td colspan="3">No income recorded.</td></tr>';

        paper.innerHTML =
            '<div class="sheet-head">' +
                '<img src="' + esc(LOGO) + '" alt="">' +
                '<div><h1>M. Cares Beauty Services</h1><p>Income Summary Report</p></div>' +
                '<div class="sheet-meta">Generated: ' + esc(GENERATED) + '<br>Report: ' +
                    NAMES[period] + ' (' + esc(data.title) + ')</div>' +
            '</div>' +

            '<div class="sheet-cards">' +
                '<div class="sheet-card"><span>This week</span><b>' + php(SUMMARY.week) + '</b></div>' +
                '<div class="sheet-card"><span>This month</span><b>' + php(SUMMARY.month) + '</b></div>' +
                '<div class="sheet-card"><span>This quarter</span><b>' + php(SUMMARY.quarter) + '</b></div>' +
                '<div class="sheet-card"><span>This year</span><b>' + php(SUMMARY.year) + '</b></div>' +
            '</div>' +

            '<p class="sheet-line">Earned (completed): <b>' + php(SUMMARY.earned) + '</b> &nbsp;·&nbsp; ' +
                'Expected (pending/confirmed): <b>' + php(SUMMARY.expected) + '</b> &nbsp;·&nbsp; ' +
                'All time: <b>' + php(SUMMARY.all) + '</b></p>' +
            '<p class="sheet-small">Cancelled and archived appointments are not counted.</p>' +

            '<h2>' + TITLES[period] + '</h2>' +
            '<table><thead><tr><th>Period</th><th class="num">Bookings</th><th class="num">Earned</th>' +
                '<th class="num">Expected</th><th class="num">Total income</th></tr></thead>' +
                '<tbody>' + periodRows + '</tbody>' +
                '<tfoot><tr><td>Total</td><td class="num">' + data.bookings + '</td>' +
                    '<td class="num">' + php(data.earned) + '</td>' +
                    '<td class="num">' + php(data.total - data.earned) + '</td>' +
                    '<td class="num">' + php(data.total) + '</td></tr></tfoot></table>' +

            '<h2>Income by service</h2>' +
            '<table><thead><tr><th>Service</th><th class="num">Bookings</th><th class="num">Income</th></tr></thead>' +
                '<tbody>' + serviceRows + '</tbody></table>' +

            '<div class="sheet-foot">M. Cares Beauty Services</div>';
    }

    // ---------- the real PDF file (library loads only when you download) ----------
    function loadScript(src) {
        return new Promise(function (resolve, reject) {
            const el = document.createElement('script');
            el.src = src;
            el.onload = resolve;
            el.onerror = function () { reject(new Error('Could not load ' + src)); };
            document.head.appendChild(el);
        });
    }

    async function loadFirst(urls) {
        let lastError;
        for (const url of urls) {
            try { await loadScript(url); return; } catch (e) { lastError = e; }
        }
        throw lastError;
    }

    async function ensureLibs() {

        if (!(window.jspdf && window.jspdf.jsPDF)) {
            await loadFirst([
                'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
                'https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js'
            ]);
        }

        const probe = new window.jspdf.jsPDF();

        if (typeof probe.autoTable !== 'function') {
            await loadFirst([
                'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js',
                'https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js'
            ]);
        }
    }

    function loadLogo() {
        return new Promise(function (resolve) {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = function () {
                try {
                    const c = document.createElement('canvas');
                    c.width = img.naturalWidth;
                    c.height = img.naturalHeight;
                    c.getContext('2d').drawImage(img, 0, 0);
                    resolve(c.toDataURL('image/png'));
                } catch (e) { resolve(null); }
            };
            img.onerror = function () { resolve(null); };
            img.src = LOGO;
        });
    }

    function buildPdf(period, logo) {

        const data = DATA[period];
        const doc = new window.jspdf.jsPDF({ unit: 'pt', format: 'a4' });
        const W = doc.internal.pageSize.getWidth();
        const M = 40;

        const ROSE = [98, 68, 77];
        const PINK = [243, 188, 210];

        // header
        let textX = M;

        if (logo) {
            doc.addImage(logo, 'PNG', M, 34, 54, 54);
            textX = M + 70;
        }

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(20);
        doc.setTextColor.apply(doc, ROSE);
        doc.text('M. Cares Beauty Services', textX, 58);

        doc.setFont('helvetica', 'normal');
        doc.setFontSize(12);
        doc.text('Income Summary Report', textX, 78);

        doc.setFontSize(9);
        doc.setTextColor(120, 110, 114);
        doc.text('Generated: ' + GENERATED, W - M, 52, { align: 'right' });
        doc.text('Report: ' + NAMES[period] + ' (' + data.title + ')', W - M, 66, { align: 'right' });

        doc.setDrawColor.apply(doc, PINK);
        doc.setLineWidth(1.5);
        doc.line(M, 100, W - M, 100);

        // summary boxes
        const boxes = [
            ['This week', SUMMARY.week],
            ['This month', SUMMARY.month],
            ['This quarter', SUMMARY.quarter],
            ['This year', SUMMARY.year]
        ];

        const gap = 10;
        const bw = (W - M * 2 - gap * 3) / 4;

        boxes.forEach(function (b, i) {
            const x = M + i * (bw + gap);
            doc.setFillColor(255, 241, 247);
            doc.setDrawColor.apply(doc, PINK);
            doc.roundedRect(x, 116, bw, 50, 8, 8, 'FD');
            doc.setFontSize(8.5);
            doc.setTextColor(120, 110, 114);
            doc.text(b[0], x + 10, 133);
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(11);
            doc.setTextColor.apply(doc, ROSE);
            doc.text(php(b[1]), x + 10, 153);
            doc.setFont('helvetica', 'normal');
        });

        doc.setFontSize(9.5);
        doc.setTextColor(60, 50, 54);
        doc.text(
            'Earned (completed): ' + php(SUMMARY.earned) +
            '    Expected (pending/confirmed): ' + php(SUMMARY.expected) +
            '    All time: ' + php(SUMMARY.all),
            M, 188
        );

        doc.setFontSize(8.5);
        doc.setTextColor(120, 110, 114);
        doc.text('Cancelled and archived appointments are not counted.', M, 202);

        // income per period
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.setTextColor.apply(doc, ROSE);
        doc.text(TITLES[period], M, 228);
        doc.setFont('helvetica', 'normal');

        doc.autoTable({
            startY: 238,
            margin: { left: M, right: M },
            head: [['Period', 'Bookings', 'Earned', 'Expected', 'Total income']],
            body: data.buckets.map(function (b) {
                return [b.label, String(b.bookings), php(b.earned), php(b.expected), php(b.income)];
            }),
            foot: [[
                'Total',
                String(data.bookings),
                php(data.earned),
                php(data.total - data.earned),
                php(data.total)
            ]],
            theme: 'grid',
            styles: { fontSize: 9, cellPadding: 5, textColor: [40, 30, 34], lineColor: [225, 210, 218] },
            headStyles: { fillColor: PINK, textColor: ROSE, fontStyle: 'bold' },
            footStyles: { fillColor: [255, 241, 247], textColor: ROSE, fontStyle: 'bold' },
            columnStyles: {
                1: { halign: 'right' },
                2: { halign: 'right' },
                3: { halign: 'right' },
                4: { halign: 'right' }
            }
        });

        // income by service
        let y = doc.lastAutoTable.finalY + 28;

        if (y > doc.internal.pageSize.getHeight() - 140) {
            doc.addPage();
            y = 50;
        }

        doc.setFont('helvetica', 'bold');
        doc.setFontSize(12);
        doc.setTextColor.apply(doc, ROSE);
        doc.text('Income by service', M, y);
        doc.setFont('helvetica', 'normal');

        doc.autoTable({
            startY: y + 10,
            margin: { left: M, right: M },
            head: [['Service', 'Bookings', 'Income']],
            body: data.services.length
                ? data.services.map(function (s) { return [s.name, String(s.bookings), php(s.income)]; })
                : [['No income recorded.', '', '']],
            theme: 'grid',
            styles: { fontSize: 9, cellPadding: 5, textColor: [40, 30, 34], lineColor: [225, 210, 218] },
            headStyles: { fillColor: PINK, textColor: ROSE, fontStyle: 'bold' },
            columnStyles: { 1: { halign: 'right' }, 2: { halign: 'right' } }
        });

        // page numbers
        const pages = doc.internal.getNumberOfPages();

        for (let i = 1; i <= pages; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(150, 140, 144);
            doc.text(
                'M. Cares Beauty Services — Page ' + i + ' of ' + pages,
                W / 2,
                doc.internal.pageSize.getHeight() - 24,
                { align: 'center' }
            );
        }

        return doc;
    }

    function setSaved(value) {
        saved = value;
        note.textContent = value ? NOTE_SAVED : NOTE_LOCKED;
        note.classList.toggle('ok', value);
    }

    function closeModal() {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    // CLICK "Print summary" → the preview pops up straight away
    openBtn.addEventListener('click', function () {

        // the pop-up must be a direct child of <body> so printing can ignore the rest of the page
        if (modal.parentNode !== document.body) { document.body.appendChild(modal); }

        buildPaper(current);
        setSaved(false);

        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        modal.querySelector('.pdf-scroll').scrollTop = 0;
    });

    // DOWNLOAD PDF
    btnSave.addEventListener('click', async function () {

        const original = btnSave.textContent;
        btnSave.disabled = true;
        btnSave.textContent = 'Creating PDF…';

        try {

            await ensureLibs();

            const logo = await loadLogo();
            const doc = buildPdf(current, logo);

            const stamp = new Date().toISOString().slice(0, 10);
            doc.save('MCares-Income-Summary-' + NAMES[current] + '-' + stamp + '.pdf');

            setSaved(true);

        } catch (error) {

            console.error(error);
            alert('The PDF could not be created. Please check your internet connection and try again.');

        } finally {

            btnSave.disabled = false;
            btnSave.textContent = original;
        }
    });

    // PRINT the sheet (unlocked after downloading)
    btnPrint.addEventListener('click', function () {
        window.print();
    });

    document.getElementById('pdf-close').addEventListener('click', closeModal);

    modal.addEventListener('mousedown', function (e) {
        if (e.target === modal) { closeModal(); }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) { closeModal(); }
    });

})();
</script>
