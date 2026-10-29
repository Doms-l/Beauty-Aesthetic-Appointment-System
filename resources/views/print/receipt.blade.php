{{-- Receipt for ONE completed appointment.
     Paper: half of a short bond (8.5 x 5.5 in).
     $appointment, $package (or null), $staffName, $issuedAt --}}
@php
    $service  = $appointment->service;
    $client   = $appointment->user;
    $isPack   = $package !== null;
    $peso     = fn ($n) => '₱' . number_format((float) $n, 2);
    $amount   = (float) $service->price;
    $timeText = \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt {{ $appointment->receipt_number }} | M. Care</title>

    <style>
        /* half of a short bond: 8.5in wide, 5.5in tall */
        @page { size: 8.5in 5.5in; margin: 0; }

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

        .toolbar {
            display: flex;
            gap: 10px;
            justify-content: center;
            padding: 14px;
        }
        .toolbar button {
            border: 0;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            padding: 10px 22px;
            border-radius: 999px;
            color: #62444D;
            background: linear-gradient(135deg, #F3BCD2, #FFAFF1);
        }
        .toolbar button.ghost { background: #fff; border: 1px solid #d9c7cf; }

        .paper {
            width: 8.5in;
            height: 5.5in;
            margin: 0 auto 24px;
            padding: 0.35in 0.5in 0.3in;
            background: #fff;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .paper::before {
            content: "";
            position: absolute;
            inset: 0.14in;
            border: 1.5px solid #D6B36A;
            border-radius: 10px;
            pointer-events: none;
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #E8AECF;
        }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand img { width: 0.95in; height: 0.95in; object-fit: contain; }
        .brand h1 { margin: 0; font-size: 20px; letter-spacing: 1px; color: #62444D; }
        .brand p  { margin: 2px 0 0; font-size: 11px; color: #8a6f78; letter-spacing: .5px; }

        .meta { text-align: right; font-size: 12px; line-height: 1.6; }
        .meta h2 {
            margin: 0 0 4px;
            font-size: 15px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #B8325F;
        }
        .meta b { color: #62444D; }

        .info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 28px;
            margin: 12px 0 10px;
            font-size: 12.5px;
        }
        .info div { display: flex; gap: 6px; border-bottom: 1px dotted #cdb8c1; padding: 3px 0; }
        .info span { width: 96px; flex: none; color: #8a6f78; text-transform: uppercase; font-size: 10.5px; letter-spacing: .6px; padding-top: 1px; }
        .info b { font-weight: 600; color: #222; }

        table.items { width: 100%; border-collapse: collapse; font-size: 13px; }
        table.items th {
            background: #B8325F;
            color: #fff;
            text-align: left;
            padding: 6px 10px;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        table.items th.num, table.items td.num { text-align: right; }
        table.items td { padding: 8px 10px; border-bottom: 1px solid #efe3e8; vertical-align: top; }
        table.items td small { display: block; color: #8a6f78; margin-top: 2px; font-size: 11px; }

        .total {
            margin-top: 8px;
            display: flex;
            justify-content: flex-end;
        }
        .total div {
            min-width: 2.6in;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 8px 12px;
            background: #FDEEBB;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #62444D;
        }
        .total strong { font-size: 20px; color: #222; }

        .foot {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            font-size: 10.5px;
            color: #8a6f78;
        }
        .foot .thanks { font-size: 15px; font-style: italic; color: #B8325F; margin-bottom: 3px; }
        .sign { width: 2.3in; text-align: center; }
        .sign .line { border-top: 1px solid #62444D; padding-top: 3px; color: #62444D; font-size: 11px; }

        @media print {
            html, body { background: #fff; }
            .toolbar { display: none; }
            .paper { margin: 0; }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <button type="button" onclick="window.print()">🖨 Print receipt</button>
    <button type="button" class="ghost" onclick="window.close()">Close</button>
</div>

<div class="paper">

    <div class="head">
        <div class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="M. Care Beauty Services">
            <div>
                <h1>M. CARE BEAUTY SERVICES</h1>
                <p>Glow with Confidence, Naturally</p>
            </div>
        </div>

        <div class="meta">
            <h2>Acknowledgement Receipt</h2>
            Receipt No.: <b>{{ $appointment->receipt_number }}</b><br>
            Date issued: <b>{{ $issuedAt->format('M d, Y') }}</b>
        </div>
    </div>

    <div class="info">
        <div><span>Client</span><b>{{ $client->full_name }}</b></div>
        <div><span>Service date</span><b>{{ $appointment->appointment_date->format('M d, Y') }} · {{ $timeText }}</b></div>
        <div><span>Contact no.</span><b>{{ $client->phone ?: '-' }}</b></div>
        <div><span>Served by</span><b>{{ $staffName ?: '-' }}</b></div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Service</th>
                <th class="num" style="width:1.6in;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ $service->name }}
                    @if($isPack)
                        <small>
                            Session {{ $package['session_no'] }} of {{ $package['sessions'] }}
                            · Package price for {{ $package['sessions'] }} sessions
                        </small>
                    @endif
                </td>
                <td class="num">{{ $peso($amount) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <div>
            <span>{{ $isPack ? 'PACKAGE PRICE' : 'TOTAL' }}</span>
            <strong>{{ $peso($amount) }}</strong>
        </div>
    </div>

    <div class="foot">
        <div>
            <div class="thanks">Thank you for choosing M. Care!</div>
            This is an acknowledgement receipt, not an official receipt.
        </div>

        <div class="sign">
            <div style="height:0.3in;"></div>
            <div class="line">Authorized signature</div>
        </div>
    </div>

</div>

</body>
</html>
