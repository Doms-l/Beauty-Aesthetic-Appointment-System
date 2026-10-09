{{-- Free perks shown to clients on the booking page --}}
<div class="perks-banner">

    <div class="perks-title">
        <span class="eyebrow">FREE FOR EVERY CLIENT</span>
        <h3>Relax while you glow ✨</h3>
    </div>

    <div class="perks-grid">

        <div class="perk">
            <span class="perk-icon">📶</span>
            <div>
                <strong>Free Wi-Fi</strong>
                <small>Stay connected during your visit</small>
            </div>
        </div>

        <div class="perk">
            <span class="perk-icon">💧</span>
            <div>
                <strong>Free Drinking Water</strong>
                <small>Stay refreshed anytime</small>
            </div>
        </div>

        <div class="perk">
            <span class="perk-icon">☕</span>
            <div>
                <strong>Free Coffee</strong>
                <small>Enjoy a warm cup while you wait</small>
            </div>
        </div>

    </div>

</div>

<style>
    .perks-banner {
        margin-bottom: 34px;
        padding: 24px 26px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(243, 188, 210, .28), rgba(253, 238, 187, .22));
        border: 1px solid var(--line, #ddd);
    }
    .perks-title h3 { margin: 4px 0 16px; color: var(--heading, #62444D); }
    .perks-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }
    .perk {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 18px;
        background: var(--surface, #fff);
        border: 1px solid var(--line, #ddd);
    }
    .perk-icon {
        flex: none;
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        font-size: 1.5rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
    }
    .perk strong { display: block; color: var(--heading, #62444D); }
    .perk small { color: var(--text-muted, #7d7074); }

    @media (max-width: 760px) {
        .perks-grid { grid-template-columns: 1fr; }
    }
</style>
