

<style>

/* ---------- hamburger button (hidden on desktop) ---------- */

.nav-toggle {
    display: none;
    flex: none;
    width: 46px;
    height: 46px;
    padding: 0;
    border: 1px solid var(--line);
    border-radius: 14px;
    background: var(--surface);
    cursor: pointer;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 5px;
}

.nav-toggle span {
    display: block;
    width: 22px;
    height: 2px;
    border-radius: 2px;
    background: var(--heading);
    transition: transform .2s ease, opacity .2s ease;
}

.nav-toggle[aria-expanded="true"] span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.nav-toggle[aria-expanded="true"] span:nth-child(2) { opacity: 0; }
.nav-toggle[aria-expanded="true"] span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }


/* ---------- tablet and phone: menu becomes a drop-down panel ---------- */

@media (max-width: 900px) {

    .site-header { position: sticky; top: 0; }

    .nav-wrap {
        min-height: 66px;
        padding: 8px 0;
        align-items: center;
        gap: 14px;
    }

    .nav-toggle { display: inline-flex; }

    .main-nav {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 70;

        flex-direction: column;
        flex-wrap: nowrap;
        align-items: stretch;
        justify-content: flex-start;
        gap: 4px;

        max-height: calc(100vh - 70px);
        overflow-y: auto;
        padding: 12px 16px 18px;

        font-size: 16px;

        background: var(--surface);
        border-bottom: 1px solid var(--line);
        box-shadow: 0 18px 30px rgba(0, 0, 0, .18);
    }

    .main-nav.is-open { display: flex; }

    .main-nav > a,
    .main-nav .nav-dropdown > a,
    .main-nav .link-button {
        display: flex;
        align-items: center;
        width: 100%;
        min-height: 46px;
        padding: 10px 16px !important;
        border-radius: 14px !important;
        text-align: left;
    }

    .main-nav .nav-profile {
        width: auto;
        align-self: flex-start;
        min-height: 0;
        padding: 0 !important;
        margin: 6px 0;
    }

    .main-nav .inline-form { display: block; width: 100%; }

    .main-nav .theme-toggle { align-self: flex-start; margin: 4px 0; }

    /* the Services / Admin Dashboard lists show open, indented */
    .nav-dropdown {
        display: block;
        width: 100%;
    }

    .nav-dropdown-menu {
        display: block !important;
        position: static;
        min-width: 0;
        margin: 2px 0 6px 14px;
        padding: 0 0 0 10px;
        border: 0;
        border-left: 2px solid var(--line);
        border-radius: 0;
        box-shadow: none;
        background: transparent;
        opacity: 1;
        visibility: visible;
        transform: none;
    }

    .nav-dropdown-menu::before { display: none; }

    .main-nav .nav-dropdown-menu a {
        min-height: 42px;
        display: flex;
        align-items: center;
        font-size: 15px;
        white-space: normal;
    }

    body.nav-open { overflow: hidden; }
}


/* ---------- phones ---------- */

@media (max-width: 650px) {

    html, body { overflow-x: hidden; }

    img, video, svg { max-width: 100%; }

    .container { width: calc(100% - 28px); }

    /* the announcement bar must wrap instead of running off the screen */
    .promo-bar { font-size: 12px; padding-left: 10px; padding-right: 38px; }
    .promo-bar-item { text-align: center; line-height: 1.35; }

    .page-hero,
    .dashboard-header { padding-top: 38px; padding-bottom: 34px; }

    .page-hero h1,
    .dashboard-header h1,
    .hero-section h1 { font-size: clamp(28px, 8.4vw, 40px); line-height: 1.12; }

    .page-hero p { font-size: 15px; }

    .section { padding-top: 34px; padding-bottom: 34px; }

    .panel,
    .auth-card { padding: 20px 16px; border-radius: 20px; }

    /* 16px stops iPhones zooming into a field; 46px is a comfortable tap size */
    input, select, textarea { font-size: 16px; }

    input:not([type="checkbox"]):not([type="radio"]):not([type="file"]),
    select,
    .primary-button,
    .secondary-button,
    .small-button { min-height: 46px; }

    .primary-button,
    .secondary-button { width: 100%; text-align: center; justify-content: center; }

    .dashboard-actions { display: flex; flex-direction: column; gap: 10px; }

    .form-grid.two,
    .booking-fields,
    .service-select-grid { grid-template-columns: 1fr !important; }

    .stat-grid { grid-template-columns: 1fr 1fr; gap: 10px; }

    .admin-item { flex-wrap: wrap; gap: 10px; }

    .provider-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }

    .service-list-grid { grid-template-columns: 1fr !important; }

    .pagination nav { overflow-x: auto; }
}


/* ---------- phones: tables become stacked cards ---------- */

@media (max-width: 700px) {

    .table-wrap {
        overflow: visible;
        background: transparent;
        border: 0;
    }

    .table-wrap table,
    .table-wrap tbody,
    .table-wrap tr,
    .table-wrap td {
        display: block;
        width: 100%;
        min-width: 0;
    }

    .table-wrap thead { display: none; }

    .table-wrap tr {
        margin-bottom: 14px;
        padding: 6px 0;
        border: 1px solid var(--line);
        border-radius: 18px;
        background: var(--surface);
        overflow: hidden;
    }

    .table-wrap td {
        padding: 10px 16px;
        border-bottom: 1px solid var(--line);
        text-align: left;
        font-size: 15px;
    }

    .table-wrap td:last-child { border-bottom: 0; }

    /* the column name sits above its value */
    .table-wrap td[data-label]::before {
        content: attr(data-label);
        display: block;
        margin-bottom: 3px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--heading);
    }

    .table-wrap td > form { margin: 0; }
    .table-wrap td select { width: 100%; max-width: 100%; }
    .table-wrap td .save-actions { justify-content: flex-start; }
}

</style>

<script>
(function () {

    // ----- hamburger menu -----
    var toggle = document.getElementById('nav-toggle');
    var nav    = document.getElementById('main-nav');

    function setOpen(open) {
        if (!toggle || !nav) { return; }
        nav.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        document.body.classList.toggle('nav-open', open);
    }

    if (toggle && nav) {

        toggle.addEventListener('click', function () {
            setOpen(!nav.classList.contains('is-open'));
        });

        // a tapped link (or the theme button) closes the menu
        nav.addEventListener('click', function (event) {
            if (event.target.closest('a, #theme-toggle')) { setOpen(false); }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') { setOpen(false); }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 900) { setOpen(false); }
        });
    }

    // ----- table cells get their column name (used by the phone card layout) -----
    document.querySelectorAll('.table-wrap table').forEach(function (table) {

        var heads = Array.prototype.map.call(
            table.querySelectorAll('thead th'),
            function (th) { return th.textContent.trim(); }
        );

        if (!heads.length) { return; }

        table.querySelectorAll('tbody tr').forEach(function (row) {
            Array.prototype.forEach.call(row.children, function (cell, index) {
                if (cell.tagName === 'TD' && !cell.hasAttribute('colspan') && heads[index]) {
                    cell.setAttribute('data-label', heads[index]);
                }
            });
        });
    });

})();
</script>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/mobile-responsive.blade.php ENDPATH**/ ?>