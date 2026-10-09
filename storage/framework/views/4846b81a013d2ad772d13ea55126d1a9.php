

<?php
    $peso = fn ($n) => '₱' . number_format((float) $n, 2);
?>

<div class="panel income-panel" id="income-analytics">

    <div class="section-heading inline-heading">

        <div>
            <span class="eyebrow">ANALYTICS</span>
            <h2>Income Summary</h2>
            <p>
                Income from all non-cancelled appointments.
                Cancelled appointments are not counted.
            </p>
        </div>

        <button type="button" class="income-print-btn" id="income-print">
            🖨 Print summary
        </button>

    </div>


    
    <div class="income-cards">

        <div class="income-card">
            <span>This week</span>
            <strong><?php echo e($peso($incomeAnalytics['summary']['week'])); ?></strong>
        </div>

        <div class="income-card">
            <span>This month</span>
            <strong><?php echo e($peso($incomeAnalytics['summary']['month'])); ?></strong>
        </div>

        <div class="income-card">
            <span>This quarter</span>
            <strong><?php echo e($peso($incomeAnalytics['summary']['quarter'])); ?></strong>
        </div>

        <div class="income-card">
            <span>This year</span>
            <strong><?php echo e($peso($incomeAnalytics['summary']['year'])); ?></strong>
        </div>

    </div>

    <div class="income-split">
        <span><i class="dot dot-earned"></i> Earned (completed): <b><?php echo e($peso($incomeAnalytics['summary']['earned'])); ?></b></span>
        <span><i class="dot dot-expected"></i> Expected (pending / confirmed): <b><?php echo e($peso($incomeAnalytics['summary']['expected'])); ?></b></span>
        <span>All time: <b><?php echo e($peso($incomeAnalytics['summary']['all'])); ?></b></span>
    </div>


    
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


    
    <div class="income-report" id="income-report">

        <h1>M. Cares Beauty Services</h1>
        <h2>Income Summary — <span id="report-period-name"></span></h2>
        <p class="report-meta">
            Generated <?php echo e($incomeAnalytics['generated_at']); ?> ·
            Cancelled appointments are excluded.
        </p>

        <table>
            <thead>
                <tr><th>Period</th><th>Bookings</th><th>Earned</th><th>Expected</th><th>Total income</th></tr>
            </thead>
            <tbody id="report-periods"></tbody>
            <tfoot id="report-totals"></tfoot>
        </table>

        <h3>Income by service</h3>

        <table>
            <thead>
                <tr><th>Service</th><th>Bookings</th><th>Income</th></tr>
            </thead>
            <tbody id="report-services"></tbody>
        </table>

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

    .income-report { display: none; }

    @media (max-width: 760px) {
        .income-cards { grid-template-columns: repeat(2, 1fr); }
    }

    /* ---------- PRINT: only the report is printed ---------- */
    @media print {
        body * { visibility: hidden !important; }

        #income-report, #income-report * { visibility: visible !important; }

        #income-report {
            display: block !important;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 24px;
            color: #000;
            background: #fff;
            font-family: Arial, sans-serif;
        }
        #income-report h1 { font-size: 22px; margin: 0 0 4px; color: #62444D; }
        #income-report h2 { font-size: 16px; margin: 0 0 6px; color: #000; }
        #income-report h3 { font-size: 14px; margin: 24px 0 8px; color: #000; }
        #income-report .report-meta { font-size: 12px; color: #555; margin-bottom: 14px; }
        #income-report table { width: 100%; border-collapse: collapse; font-size: 12px; }
        #income-report th, #income-report td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
            color: #000;
            background: #fff;
        }
        #income-report th { background: #f3e1ea; }
        #income-report td.num, #income-report th.num { text-align: right; }
        #income-report tfoot td { font-weight: 700; }
    }
</style>


<script>
(function () {

    const DATA = <?php echo json_encode($incomeAnalytics['periods'], 15, 512) ?>;

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

        // labels row (rebuilt each time)
        const old = document.getElementById('income-labels');
        if (old) { old.remove(); }

        if (max <= 0) {
            chart.innerHTML =
                '<div class="income-empty">No income recorded for this period yet.</div>';
        } else {

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

        buildReport(period);
    }

    // fills the hidden printable report with the selected period
    function buildReport(period) {

        const data = DATA[period];

        document.getElementById('report-period-name').textContent =
            NAMES[period] + ' (' + data.title + ')';

        const rows = data.buckets.map(function (b) {
            return '<tr><td>' + b.label + '</td>' +
                '<td class="num">' + b.bookings + '</td>' +
                '<td class="num">' + peso(b.earned) + '</td>' +
                '<td class="num">' + peso(b.expected) + '</td>' +
                '<td class="num">' + peso(b.income) + '</td></tr>';
        }).join('');

        document.getElementById('report-periods').innerHTML = rows;

        document.getElementById('report-totals').innerHTML =
            '<tr><td>Total</td>' +
            '<td class="num">' + data.bookings + '</td>' +
            '<td class="num">' + peso(data.earned) + '</td>' +
            '<td class="num">' + peso(data.total - data.earned) + '</td>' +
            '<td class="num">' + peso(data.total) + '</td></tr>';

        document.getElementById('report-services').innerHTML =
            data.services.length
                ? data.services.map(function (s) {
                    return '<tr><td>' + s.name + '</td>' +
                        '<td class="num">' + s.bookings + '</td>' +
                        '<td class="num">' + peso(s.income) + '</td></tr>';
                }).join('')
                : '<tr><td colspan="3">No income recorded.</td></tr>';
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            render(tab.dataset.period);
        });
    });

    document.getElementById('income-print').addEventListener('click', function () {
        buildReport(current);
        window.print();
    });

    render(current);

})();
</script>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/income-analytics.blade.php ENDPATH**/ ?>