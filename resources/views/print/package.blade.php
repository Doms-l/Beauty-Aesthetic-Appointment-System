{{-- Clients Package Sessions sheet. Paper: short bond (8.5 x 11 in), also fits A4.
     $appointment, $total (sessions in the package), $sessions (finished visits),
     $staffName, $startDate --}}
@php
    $service  = $appointment->service;
    $client   = $appointment->user;
    $peso     = fn ($n) => '₱' . number_format((float) $n, 2);

    // many sessions (e.g. 10) need tighter rows so the sheet still fits one page
    $rowHeight = $total > 6 ? '0.34in' : '0.43in';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package sheet | {{ $client->full_name }} | M. Care</title>

    <style>
        @page { size: 8.5in 11in; margin: 0; }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", Arial, sans-serif;
            color: #222;
            background: #ece7ea;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .toolbar { display: flex; gap: 10px; justify-content: center; padding: 14px; }
        .toolbar button {
            border: 0; cursor: pointer; font: inherit; font-weight: 700;
            padding: 10px 22px; border-radius: 999px; color: #62444D;
            background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
        }
        .toolbar button.ghost { background: #fff; border: 1px solid #d9c7cf; }

        /* the page (a little inside the paper so it also fits A4) */
        .paper {
            width: 8.5in;
            min-height: 11in;
            margin: 0 auto 24px;
            padding: 0.4in 0.55in 0.35in;
            background: linear-gradient(180deg, #fff 0%, #fff5f9 100%);
            position: relative;
        }

        .top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .top img { width: 1.75in; height: 1.75in; object-fit: contain; }
        .title { text-align: right; }
        .title .c { margin: 0; font-size: 36px; font-weight: 800; letter-spacing: 2px; color: #B8325F; line-height: 1; }
        .title .p { margin: 4px 0 0; font-family: Georgia, "Times New Roman", serif; font-size: 36px; font-weight: 700; letter-spacing: 1px; color: #3b2a30; line-height: 1.05; }
        .title .tag { margin: 12px 0 0; font-style: italic; font-size: 13px; color: #62444D; }
        .title .tag::after { content: " ♥"; color: #B8325F; }

        .boxes { display: grid; grid-template-columns: 1.05fr 1fr; gap: 18px; margin-top: 14px; }
        .box {
            position: relative;
            border: 1.5px solid #E8AECF;
            border-radius: 12px;
            padding: 22px 16px 12px;
            background: rgba(255,255,255,.7);
        }
        .pill {
            position: absolute;
            top: -11px; left: 12px;
            background: #B8325F;
            color: #fff;
            border-radius: 999px;
            padding: 3px 14px;
            font-size: 10.5px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }
        .field { display: flex; align-items: flex-end; gap: 6px; margin: 9px 0; font-size: 11px; }
        .field label { width: 92px; flex: none; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: #3b2a30; }
        .field i { font-style: normal; flex: none; }
        .field .v { flex: 1; min-height: 16px; border-bottom: 1px solid #b99aa7; font-size: 12.5px; padding: 0 4px 1px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }

        .bar {
            margin-top: 20px;
            position: relative;
            border-radius: 6px 6px 0 0;
            background: #B8325F;
            color: #fff;
            padding: 7px 14px 7px 14px;
            font-size: 10.5px;
            letter-spacing: 1.3px;
            text-transform: uppercase;
            font-weight: 700;
        }

        table.sessions { width: 100%; border-collapse: collapse; }
        table.sessions th {
            background: #B8325F; color: #fff; text-align: left;
            font-size: 9.5px; letter-spacing: 1px; text-transform: uppercase;
            padding: 4px 8px;
        }
        table.sessions td { padding: 0 8px; height: var(--row-h); border-bottom: 1px solid #ecd2dd; font-size: 11.5px; vertical-align: middle; }
        table.sessions td.n { width: 0.42in; }
        .no {
            display: inline-flex; align-items: center; justify-content: center;
            width: 22px; height: 22px; border-radius: 50%;
            background: #B8325F; color: #fff; font-size: 11px; font-weight: 700;
        }
        .status { white-space: nowrap; font-size: 9px; letter-spacing: .6px; text-transform: uppercase; font-weight: 700; color: #3b2a30; }
        .status .cb {
            display: inline-block; width: 11px; height: 11px;
            border: 1.3px solid #62444D; border-radius: 2px;
            margin-right: 5px; vertical-align: -2px; text-align: center;
            font-size: 10px; line-height: 9px; color: #B8325F;
        }
        .blank { display: block; border-bottom: 1px solid #c9aab8; height: 14px; }

        .lower { display: grid; grid-template-columns: 1fr 1.9in; gap: 18px; margin-top: 18px; align-items: end; }
        .notes { position: relative; border: 1.5px solid #E8AECF; border-radius: 12px; padding: 24px 14px 10px; background: rgba(255,255,255,.7); }
        .notes .line { border-bottom: 1px solid #d9b8c7; height: 0.27in; }

        .stamp {
            width: 1.5in; height: 1.5in; border-radius: 50%;
            margin: 0 auto;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            background: radial-gradient(circle, #fde9f2 0%, #f6c3d9 100%);
            border: 2px solid #E8AECF;
            font-family: "Brush Script MT", "Segoe Script", cursive;
            font-size: 25px; line-height: 1.05; color: #B8325F; text-align: center;
        }
        .stamp span { font-size: 26px; }

        @media print {
            html, body { background: #fff; }
            .toolbar { display: none; }
            .paper { margin: 0; height: 11in; overflow: hidden; }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <button type="button" onclick="window.print()">🖨 Print package sheet</button>
    <button type="button" class="ghost" onclick="window.close()">Close</button>
</div>

<div class="paper" style="--row-h: {{ $rowHeight }};">

    <div class="top">
        <img src="{{ asset('images/logo.png') }}" alt="M. Care Beauty Services">

        <div class="title">
            <p class="c">CLIENTS</p>
            <p class="p">PACKAGE<br>SESSIONS</p>
            <p class="tag">Glow with Confidence, Naturally</p>
        </div>
    </div>

    <div class="boxes">

        <div class="box">
            <span class="pill">Client information</span>
            <div class="field"><label>Client name</label><i>:</i><span class="v">{{ $client->full_name }}</span></div>
            <div class="field"><label>Contact no.</label><i>:</i><span class="v">{{ $client->phone }}</span></div>
            <div class="field"><label>Email</label><i>:</i><span class="v">{{ $client->email }}</span></div>
            <div class="field"><label>Date</label><i>:</i><span class="v">{{ $startDate ? $startDate->format('M d, Y') : '' }}</span></div>
        </div>

        <div class="box">
            <span class="pill">Staff in charge</span>
            <div class="field"><label>Staff name</label><i>:</i><span class="v">{{ $staffName }}</span></div>
            <div class="field"><label>Signature</label><i>:</i><span class="v"></span></div>
            <div class="field" style="margin-top:20px;"><label>Service</label><i>:</i><span class="v">{{ $service->name }}</span></div>
            <div class="field"><label>Package price</label><i>:</i><span class="v">{{ $peso($service->price) }}</span></div>
        </div>

    </div>

    <div class="bar">Clients package · {{ $total }} sessions</div>

    <table class="sessions">
        <thead>
            <tr>
                <th>Session</th>
                <th>Schedule date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Staff in charge</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
        @for($i = 0; $i < $total; $i++)
            @php $b = $sessions->get($i); @endphp
            <tr>
                <td class="n"><span class="no">{{ $i + 1 }}</span></td>
                <td>
                    @if($b) {{ $b->session_date->format('M d, Y') }}
                    @elseif($i === $sessions->count() && $appointment->next_session_date) {{ $appointment->next_session_date->format('M d, Y') }}
                    @else <span class="blank"></span>
                    @endif
                </td>
                <td>{{ $b ? \Carbon\Carbon::parse($b->session_time)->format('h:i A') : '' }}@unless($b)<span class="blank"></span>@endunless</td>
                <td class="status">
                    <span class="cb">{{ $b ? '✓' : '' }}</span>{{ $b ? 'Completed' : 'Pending' }}
                </td>
                <td>{{ $b ? $b->staff_label : '' }}@unless($b)<span class="blank"></span>@endunless</td>
                <td><span class="blank"></span></td>
            </tr>
        @endfor
        </tbody>
    </table>

    <div class="lower">
        <div class="notes">
            <span class="pill">Notes / special instructions</span>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>

        <div class="stamp">
            Thank<br>You!
            <span>♥</span>
        </div>
    </div>

</div>

</body>
</html>
