

<div class="raffle-overlay" id="raffle-modal" aria-hidden="true">

    <div class="raffle-box" role="dialog" aria-modal="true" aria-label="Raffle spin the wheel">

        <button type="button" class="raffle-close" id="raffle-close" aria-label="Close">×</button>

        <div class="raffle-head">
            <span class="eyebrow">RAFFLE</span>
            <h2>Spin the Wheel</h2>
        </div>

        <div class="raffle-body">

            
            <div class="raffle-stage">

                <div class="raffle-pointer"></div>

                <canvas id="raffle-canvas" width="520" height="520"></canvas>

                <button type="button" class="raffle-spin-btn" id="raffle-spin">SPIN</button>

            </div>

            
            <div class="raffle-side">

                <label for="raffle-prize">Prize (optional)</label>
                <input type="text" id="raffle-prize" placeholder="e.g. Free Hydra Facial" maxlength="60">

                <label for="raffle-names">
                    Participants <small>(one name per line)</small>
                </label>
                <textarea id="raffle-names" rows="8" placeholder="Maria Santos&#10;Juan Dela Cruz&#10;Ana Reyes"></textarea>

                <div class="raffle-row">
                    <button type="button" class="raffle-small" id="raffle-load">Load registered clients</button>
                    <button type="button" class="raffle-small ghost" id="raffle-clear">Clear</button>
                </div>

                <label class="raffle-check">
                    <input type="checkbox" id="raffle-remove" checked>
                    Remove the winner from the wheel after each spin
                </label>

                <p class="raffle-count" id="raffle-count">0 participants</p>

                <div class="raffle-history">
                    <h3>Winners</h3>
                    <ol id="raffle-winners"><li class="empty">No winners yet</li></ol>
                </div>

            </div>

        </div>

        
        <div class="raffle-winner" id="raffle-winner">
            <div class="raffle-winner-card">
                <span class="trophy">🎉</span>
                <p class="label">Congratulations!</p>
                <h3 id="raffle-winner-name"></h3>
                <p class="prize" id="raffle-winner-prize"></p>
                <button type="button" class="raffle-small" id="raffle-winner-ok">OK</button>
            </div>
            <canvas id="raffle-confetti"></canvas>
        </div>

    </div>

</div>


<style>
    .raffle-open-btn { cursor: pointer; }

    .raffle-overlay {
        position: fixed;
        inset: 0;
        z-index: 100000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(10, 6, 9, .72);
        backdrop-filter: blur(4px);
    }
    .raffle-overlay.open { display: flex; }

    .raffle-box {
        position: relative;
        width: min(1040px, 100%);
        max-height: 94vh;
        overflow: auto;
        padding: 28px 30px 30px;
        border-radius: 28px;
        background: var(--surface, #fff);
        color: var(--text, #222);
        border: 1px solid var(--line, #ddd);
        box-shadow: 0 30px 90px rgba(0, 0, 0, .45);
    }
    .raffle-head h2 { margin: 4px 0 0; color: var(--heading, #62444D); }

    .raffle-close {
        position: absolute;
        top: 14px;
        right: 18px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid var(--line, #ddd);
        background: var(--surface-soft, #fff1f7);
        color: var(--heading, #62444D);
        font-size: 1.6rem;
        line-height: 1;
        cursor: pointer;
    }

    .raffle-body {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
        gap: 30px;
        margin-top: 18px;
        align-items: start;
    }

    /* ----- wheel ----- */
    .raffle-stage {
        position: relative;
        width: 100%;
        max-width: 520px;
        margin: 0 auto;
        aspect-ratio: 1;
    }
    #raffle-canvas {
        width: 100%;
        height: 100%;
        display: block;
        border-radius: 50%;
        box-shadow: 0 10px 40px rgba(98, 68, 77, .35);
    }
    .raffle-pointer {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 3;
        width: 0;
        height: 0;
        border-left: 16px solid transparent;
        border-right: 16px solid transparent;
        border-top: 34px solid #62444D;
        filter: drop-shadow(0 3px 3px rgba(0, 0, 0, .35));
    }
    .raffle-spin-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        width: 22%;
        height: 22%;
        border-radius: 50%;
        border: 4px solid #fff;
        cursor: pointer;
        font: inherit;
        font-weight: 800;
        letter-spacing: .06em;
        color: #fff;
        background: #62444D;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .35);
    }
    .raffle-spin-btn:disabled { opacity: .6; cursor: not-allowed; }

    /* ----- side panel ----- */
    .raffle-side label {
        display: block;
        margin: 14px 0 6px;
        font-weight: 700;
        color: var(--heading, #62444D);
    }
    .raffle-side label small { font-weight: 400; color: var(--text-muted, #7d7074); }
    .raffle-side input[type=text],
    .raffle-side textarea {
        width: 100%;
        padding: 12px 14px;
        border-radius: 14px;
        border: 1px solid var(--input-border, #ded5d9);
        background: var(--input-bg, #fff);
        color: var(--text, #222);
        font: inherit;
        resize: vertical;
    }
    .raffle-row { display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap; }

    .raffle-small {
        border: 0;
        cursor: pointer;
        font: inherit;
        font-weight: 700;
        padding: 10px 18px;
        border-radius: 999px;
        color: #62444D;
        background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
    }
    .raffle-small.ghost {
        background: transparent;
        color: var(--heading, #62444D);
        border: 1px solid var(--line, #ccc);
    }

    .raffle-side .raffle-check {
        display: flex;
        gap: 10px;
        align-items: center;
        font-weight: 500;
        color: var(--text, #222);
        margin-top: 16px;
    }
    .raffle-count { margin: 10px 0 0; color: var(--text-muted, #7d7074); }

    .raffle-history { margin-top: 14px; }
    .raffle-history h3 { margin: 0 0 6px; color: var(--heading, #62444D); font-size: 1.05rem; }
    .raffle-history ol { margin: 0; padding-left: 22px; max-height: 130px; overflow: auto; }
    .raffle-history li { padding: 3px 0; }
    .raffle-history li.empty { list-style: none; margin-left: -22px; color: var(--text-muted, #7d7074); }

    /* ----- winner pop-up ----- */
    .raffle-winner {
        position: absolute;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        border-radius: 28px;
        background: rgba(10, 6, 9, .6);
        z-index: 10;
        overflow: hidden;
    }
    .raffle-winner.show { display: flex; }
    #raffle-confetti {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }
    .raffle-winner-card {
        position: relative;
        z-index: 2;
        text-align: center;
        padding: 34px 44px;
        border-radius: 26px;
        background: var(--surface, #fff);
        border: 2px solid #F3BCD2;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .4);
        max-width: 90%;
        animation: raffle-pop .45s cubic-bezier(.2, 1.4, .4, 1);
    }
    @keyframes raffle-pop { from { transform: scale(.4); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .raffle-winner-card .trophy { font-size: 3rem; }
    .raffle-winner-card .label { margin: 4px 0; color: var(--text-muted, #7d7074); text-transform: uppercase; letter-spacing: .14em; font-size: .8rem; }
    .raffle-winner-card h3 { margin: 6px 0; font-size: 2.1rem; color: var(--heading, #62444D); word-break: break-word; }
    .raffle-winner-card .prize { margin: 0 0 18px; color: var(--text, #222); font-weight: 600; }

    @media (max-width: 860px) {
        .raffle-body { grid-template-columns: 1fr; }
        .raffle-box { padding: 22px 18px 24px; }
    }
</style>


<script>
(function () {

    const CLIENTS = <?php echo json_encode($raffleClients ?? [], 15, 512) ?>;

    const modal   = document.getElementById('raffle-modal');
    const canvas  = document.getElementById('raffle-canvas');
    const ctx     = canvas.getContext('2d');
    const spinBtn = document.getElementById('raffle-spin');
    const names   = document.getElementById('raffle-names');
    const prize   = document.getElementById('raffle-prize');
    const remove  = document.getElementById('raffle-remove');
    const count   = document.getElementById('raffle-count');
    const winners = document.getElementById('raffle-winners');
    const popup   = document.getElementById('raffle-winner');

    const COLORS = ['#F3BCD2', '#FDEEBB', '#E8AECF', '#FFD6E8', '#D6B36A', '#FFAFF1'];

    let rotation = 0;          // current wheel angle (radians)
    let spinning = false;
    let pending  = null;       // winner waiting to be removed


    // ---------- helpers ----------
    function getNames() {
        return names.value
            .split('\n')
            .map(function (n) { return n.trim(); })
            .filter(Boolean);
    }

    // truly random whole number 0 … max-1
    function randomInt(max) {
        const buf = new Uint32Array(1);
        const limit = Math.floor(0xFFFFFFFF / max) * max;
        do { crypto.getRandomValues(buf); } while (buf[0] >= limit);
        return buf[0] % max;
    }

    function open() {
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        draw();
    }

    function close() {
        if (spinning) { return; }
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }


    // ---------- drawing ----------
    function draw() {

        const list = getNames();
        const w = canvas.width, h = canvas.height;
        const cx = w / 2, cy = h / 2, r = w / 2 - 6;

        count.textContent = list.length + (list.length === 1 ? ' participant' : ' participants');

        ctx.clearRect(0, 0, w, h);

        if (list.length === 0) {
            ctx.fillStyle = '#F3BCD2';
            ctx.beginPath(); ctx.arc(cx, cy, r, 0, Math.PI * 2); ctx.fill();
            ctx.fillStyle = '#62444D';
            ctx.font = 'bold 26px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('Add participants', cx, cy - 6);
            ctx.font = '18px sans-serif';
            ctx.fillText('to start the raffle', cx, cy + 24);
            return;
        }

        const seg = (Math.PI * 2) / list.length;

        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate(rotation);

        list.forEach(function (name, i) {

            const a0 = i * seg, a1 = a0 + seg;

            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.arc(0, 0, r, a0, a1);
            ctx.closePath();
            ctx.fillStyle = COLORS[i % COLORS.length];
            ctx.fill();
            ctx.strokeStyle = '#fff';
            ctx.lineWidth = 2;
            ctx.stroke();

            // label
            ctx.save();
            ctx.rotate(a0 + seg / 2);
            ctx.textAlign = 'right';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = '#4a2f38';
            const size = Math.max(11, Math.min(24, 300 / Math.max(list.length, 6) + 6));
            ctx.font = 'bold ' + size + 'px sans-serif';
            let label = name;
            while (label.length > 3 && ctx.measureText(label).width > r * 0.62) {
                label = label.slice(0, -2);
            }
            if (label !== name) { label = label.trim() + '…'; }
            ctx.fillText(label, r - 18, 0);
            ctx.restore();
        });

        ctx.restore();

        // rim
        ctx.beginPath();
        ctx.arc(cx, cy, r, 0, Math.PI * 2);
        ctx.strokeStyle = '#62444D';
        ctx.lineWidth = 6;
        ctx.stroke();
    }


    // ---------- spin ----------
    function spin() {

        if (spinning) { return; }

        const list = getNames();

        if (list.length < 2) {
            alert('Please add at least 2 participants.');
            return;
        }

        // the winner is chosen FIRST with a secure random number,
        // then the wheel is animated so it lands on that person
        const winnerIndex = randomInt(list.length);
        const seg = (Math.PI * 2) / list.length;

        // the pointer is at the top (-90°). Put the middle of the
        // winner's slice there (a little random offset inside the slice)
        const jitter = (Math.random() - 0.5) * seg * 0.7;
        const landing = -Math.PI / 2 - (winnerIndex + 0.5) * seg + jitter;

        const turns = (5 + randomInt(3)) * Math.PI * 2;
        const current = ((rotation % (Math.PI * 2)) + Math.PI * 2) % (Math.PI * 2);
        let delta = ((landing - current) % (Math.PI * 2) + Math.PI * 2) % (Math.PI * 2);

        const start = rotation;
        const total = turns + delta;
        const duration = 5200;
        const t0 = performance.now();

        spinning = true;
        spinBtn.disabled = true;

        function frame(now) {

            const t = Math.min((now - t0) / duration, 1);
            const eased = 1 - Math.pow(1 - t, 4);          // slows down at the end

            rotation = start + total * eased;
            draw();

            if (t < 1) {
                requestAnimationFrame(frame);
            } else {
                spinning = false;
                spinBtn.disabled = false;
                announce(list[winnerIndex]);
            }
        }

        requestAnimationFrame(frame);
    }


    // ---------- winner ----------
    function announce(name) {

        pending = name;

        document.getElementById('raffle-winner-name').textContent = name;

        const p = prize.value.trim();
        document.getElementById('raffle-winner-prize').textContent =
            p ? 'wins ' + p + '!' : '';

        const empty = winners.querySelector('.empty');
        if (empty) { empty.remove(); }

        const li = document.createElement('li');
        li.textContent = p ? name + ' — ' + p : name;
        winners.appendChild(li);

        popup.classList.add('show');
        confetti();
    }

    function closeWinner() {

        popup.classList.remove('show');
        stopConfetti();

        // optionally take the winner off the wheel
        if (remove.checked && pending) {

            const list = getNames();
            const i = list.indexOf(pending);

            if (i > -1) {
                list.splice(i, 1);
                names.value = list.join('\n');
            }
        }

        pending = null;
        draw();
    }


    // ---------- confetti ----------
    let confettiRun = false;

    function confetti() {

        const c = document.getElementById('raffle-confetti');
        const g = c.getContext('2d');

        c.width = c.offsetWidth;
        c.height = c.offsetHeight;

        const pieces = [];
        for (let i = 0; i < 140; i++) {
            pieces.push({
                x: Math.random() * c.width,
                y: -20 - Math.random() * c.height * 0.6,
                s: 6 + Math.random() * 8,
                vy: 2 + Math.random() * 4,
                vx: -1.5 + Math.random() * 3,
                rot: Math.random() * 6,
                vr: -0.2 + Math.random() * 0.4,
                color: COLORS[i % COLORS.length]
            });
        }

        confettiRun = true;

        (function tick() {

            if (!confettiRun) { g.clearRect(0, 0, c.width, c.height); return; }

            g.clearRect(0, 0, c.width, c.height);

            pieces.forEach(function (p) {
                p.x += p.vx; p.y += p.vy; p.rot += p.vr;
                if (p.y > c.height + 20) { p.y = -20; }
                g.save();
                g.translate(p.x, p.y);
                g.rotate(p.rot);
                g.fillStyle = p.color;
                g.fillRect(-p.s / 2, -p.s / 2, p.s, p.s * 0.6);
                g.restore();
            });

            requestAnimationFrame(tick);
        })();
    }

    function stopConfetti() { confettiRun = false; }


    // ---------- events ----------
    document.querySelectorAll('.raffle-open-btn').forEach(function (b) {
        b.addEventListener('click', function (e) { e.preventDefault(); open(); });
    });

    document.getElementById('raffle-close').addEventListener('click', close);
    modal.addEventListener('mousedown', function (e) { if (e.target === modal) { close(); } });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) {
            popup.classList.contains('show') ? closeWinner() : close();
        }
    });

    spinBtn.addEventListener('click', spin);
    document.getElementById('raffle-winner-ok').addEventListener('click', closeWinner);

    names.addEventListener('input', function () { if (!spinning) { draw(); } });

    document.getElementById('raffle-load').addEventListener('click', function () {
        if (spinning) { return; }
        names.value = CLIENTS.join('\n');
        draw();
    });

    document.getElementById('raffle-clear').addEventListener('click', function () {
        if (spinning) { return; }
        names.value = '';
        draw();
    });

    draw();

})();
</script>
<?php /**PATH C:\Users\Regis D\Desktop\Beauty-Aesthetic-Appointment-System\resources\views/partials/raffle-wheel.blade.php ENDPATH**/ ?>